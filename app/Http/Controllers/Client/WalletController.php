<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Models\UserWallet;
use App\Models\WalletTransaction;

class WalletController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $wallet = $user->getOrCreateWallet();
        
        // Lấy lịch sử giao dịch gần nhất
        $recentTransactions = $user->walletTransactions()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('client.wallet.index', compact('wallet', 'recentTransactions'));
    }

    public function topup()
    {
        return view('client.wallet.topup');
    }

    public function processTopup(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10000|max:50000000', // Tối thiểu 10k, tối đa 50tr
            'payment_method' => 'required|in:momo,zalopay'
        ]);

        $user = Auth::user();
        $amount = $request->amount;
        $paymentMethod = $request->payment_method;

        // Tạo pending transaction
        $transactionCode = 'TOP_' . time() . '_' . $user->id;
        $transaction = WalletTransaction::create([
            'user_id' => $user->id,
            'type' => 'deposit',
            'amount' => $amount,
            'balance_before' => $user->getOrCreateWallet()->balance,
            'balance_after' => $user->getOrCreateWallet()->balance, // Chưa cộng vào
            'transaction_code' => $transactionCode,
            'description' => 'Nạp tiền vào ví qua ' . strtoupper($paymentMethod),
            'status' => 'pending',
            'payment_method_type' => strtoupper($paymentMethod)
        ]);

        // Lưu thông tin nạp tiền vào session
        $topupData = [
            'user_id' => $user->id,
            'amount' => $amount,
            'transaction_code' => $transactionCode,
            'transaction_id' => $transaction->id,
            'description' => 'Nạp tiền vào ví Encryption Shop',
            'redirect_url' => route('wallet.topup.success'),
            'cancel_url' => route('wallet.topup.cancel')
        ];

        Session::put('wallet_topup_data', $topupData);

        // Redirect đến payment gateway tương ứng
        if ($paymentMethod === 'momo') {
            return redirect()->route('wallet.momo.create');
        } else {
            return redirect()->route('wallet.zalopay.create');
        }
    }

    public function topupSuccess(Request $request)
    {
        $transactionCode = $request->get('transaction_code');
        
        if ($transactionCode) {
            $transaction = WalletTransaction::where('transaction_code', $transactionCode)
                ->where('user_id', Auth::id())
                ->first();
            
            if ($transaction && $transaction->status === 'completed') {
                Session::forget('wallet_topup_data');
                return view('client.wallet.topup-success', compact('transaction'));
            }
        }

        return redirect()->route('wallet.index')->with('error', 'Không tìm thấy thông tin giao dịch');
    }

    public function topupCancel()
    {
        Session::forget('wallet_topup_data');
        return redirect()->route('wallet.index')->with('warning', 'Bạn đã hủy giao dịch nạp tiền');
    }

    public function history(Request $request)
    {
        $user = Auth::user();
        
        $query = $user->walletTransactions()->orderBy('created_at', 'desc');
        
        // Filter by type (include refund)
        if ($request->filled('type')) {
            $allowedTypes = ['deposit', 'payment', 'refund'];
            if (in_array($request->type, $allowedTypes, true)) {
                $query->where('type', $request->type);
            }
        }
        
        // Filter by status
        if ($request->filled('status') && in_array($request->status, ['pending', 'completed', 'failed'])) {
            $query->where('status', $request->status);
        }
        
        // Filter by date range
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $transactions = $query->paginate(15)->withQueryString();

        return view('client.wallet.history', compact('transactions'));
    }
}
