<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\UserWallet;

use App\Models\WithdrawRequest;
use App\Models\WalletTransaction;

// thêm model rút tiền
use App\Models\BankAccount; // thêm model tài khoản ngân hàng
class WalletController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $wallet = $user->getOrCreateWallet();

        // Lấy lịch sử giao dịch gần nhất
        $recentTransactions = $user->walletTransactions()->orderBy('created_at', 'desc')->paginate(10);

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
            'payment_method' => 'required|in:momo,zalopay',
        ]);

        $user = Auth::user();
        $amount = $request->amount;
        $paymentMethod = $request->payment_method;

        // Kiểm tra và hủy các giao dịch pending cũ của user này (quá 10 phút)
        WalletTransaction::where('user_id', $user->id)
            ->where('status', 'pending')
            ->where('created_at', '<', now()->subMinutes(10))
            ->update([
                'status' => 'failed',
                'description' => DB::raw("CONCAT(description, ' (Hết hạn sau 10 phút)')"),
            ]);

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
            'payment_method_type' => strtoupper($paymentMethod),
        ]);

        // Lưu thông tin nạp tiền vào session
        $topupData = [
            'user_id' => $user->id,
            'amount' => $amount,
            'transaction_code' => $transactionCode,
            'transaction_id' => $transaction->id,
            'description' => 'Nạp tiền vào ví Encryption Shop',
            'redirect_url' => route('wallet.topup.success'),
            'cancel_url' => route('wallet.topup.cancel'),
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
            $transaction = WalletTransaction::where('transaction_code', $transactionCode)->where('user_id', Auth::id())->first();

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
            $allowedTypes = ['deposit', 'withdraw', 'payment', 'refund'];
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

    public function paymentHistory(Request $request)
    {
        $user = Auth::user();

        // Lấy wallet transactions
        $walletTransactions = $user
            ->walletTransactions()
            ->select('id', 'type', 'amount', 'description', 'status', 'payment_method_type', 'created_at', 'balance_after')
            ->selectRaw("'wallet' as source_type")
            ->get()
            ->map(function ($transaction) {
                return [
                    'id' => $transaction->id,
                    'type' => $transaction->type,
                    'amount' => $transaction->amount,
                    'description' => $transaction->description,
                    'status' => $transaction->status,
                    'payment_method_type' => $transaction->payment_method_type,
                    'created_at' => $transaction->created_at,
                    'balance_after' => $transaction->balance_after,
                    'source_type' => 'wallet',
                    'order_id' => $this->extractOrderIdFromDescription($transaction->description),
                ];
            });

        // Lấy payments của user (từ orders) - chỉ lấy payments KHÔNG PHẢI từ ví để tránh duplicate
        $payments = \App\Models\Payment::whereHas('order', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->with(['order', 'paymentMethod'])
            ->where(function ($query) {
                // Chỉ lấy payments không phải từ wallet và có amount > 0
                $query->where('payment_method_type', '!=', 'WALLET')->whereNotNull('payment_method_type')->where('amount', '>', 0);
            })
            ->orWhere(function ($query) use ($user) {
                // Hoặc payments không có payment_method_type nhưng có amount > 0 và không phải payment method 4 (ví)
                $query
                    ->whereHas('order', function ($subQuery) use ($user) {
                        $subQuery->where('user_id', $user->id)->where('payment_method_id', '!=', 4); // 4 là ID của "Số dư ví"
                    })
                    ->whereNull('payment_method_type')
                    ->where('amount', '>', 0);
            })
            ->get()
            ->map(function ($payment) {
                $amount = $payment->amount && $payment->amount > 0 ? $payment->amount : $payment->order->total;

                return [
                    'id' => $payment->id,
                    'type' => 'payment',
                    'amount' => $amount,
                    'description' => 'Thanh toán đơn hàng #' . $payment->order->id . ' qua ' . ($payment->paymentMethod->payment_type ?? 'N/A'),
                    'status' => $payment->status,
                    'payment_method_type' => $payment->payment_method_type ?? ($payment->paymentMethod->payment_type ?? 'N/A'),
                    'created_at' => $payment->created_at,
                    'source_type' => 'payment',
                    'order_id' => $payment->order->id,
                ];
            });

        // Kết hợp và sắp xếp theo thời gian
        $allTransactions = $walletTransactions->merge($payments)->sortByDesc('created_at')->values();

        // Phân trang thủ công
        $perPage = 15;
        $currentPage = $request->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;

        $paginatedTransactions = $allTransactions->slice($offset, $perPage)->values();

        $paginator = new \Illuminate\Pagination\LengthAwarePaginator($paginatedTransactions, $allTransactions->count(), $perPage, $currentPage, ['path' => $request->url(), 'pageName' => 'page']);

        $paginator->withQueryString();

        return view('client.wallet.payment-history', compact('paginator'));
    }

    private function extractOrderIdFromDescription($description)
    {
        if (preg_match('/đơn hàng #(\d+)/', $description, $matches)) {
            return $matches[1];
        }
        return null;
    }

    public function withdrawForm()
    {
        $bankAccounts = auth()->user()->bankAccounts ?? collect();
        return view('client.wallet.withdraw', compact('bankAccounts'));
    }

    public function withdraw(Request $request)
{
    $request->validate([
        'amount' => 'required|numeric|min:10000',
        'bank_account_id' => 'nullable|exists:bank_accounts,id',
        'bank_name' => 'nullable|string|max:100',
        'account_number' => 'nullable|string|max:50',
        'account_holder' => 'nullable|string|max:100',
        'note' => 'nullable|string|max:255',
    ]);

    $user = auth()->user();
    $wallet = $user->wallet;

    if ($request->amount > $wallet->balance) {
        return back()->withErrors([
            'amount' => 'Số dư ví không đủ! Hiện có: '.number_format($wallet->balance).'đ',
        ])->withInput();
    }

    $bankAccountId = $request->bank_account_id;

    if (!$bankAccountId && $request->bank_name && $request->account_number && $request->account_holder) {
        $existing = BankAccount::where('user_id', $user->id)
            ->where('account_number', $request->account_number)
            ->first();

        if ($existing) {
            $bankAccountId = $existing->id;
        } else {
            $bankAccount = BankAccount::create([
                'user_id' => $user->id,
                'bank_name' => $request->bank_name,
                'account_number' => $request->account_number,
                'account_holder' => $request->account_holder,
            ]);
            $bankAccountId = $bankAccount->id;
        }
    }

    if (!$bankAccountId) {
        return back()->withErrors(['bank_account_id' => 'Vui lòng chọn hoặc nhập tài khoản ngân hàng']);
    }

    // Trừ tiền tạm
 // Lưu lại số dư trước khi trừ
$balanceBefore = $wallet->balance;

// Trừ tiền tạm
$wallet->decrement('balance', $request->amount);

// Lưu lại số dư sau khi trừ
$balanceAfter = $wallet->balance;

// Tạo yêu cầu rút tiền
$withdraw = WithdrawRequest::create([
    'user_id' => $user->id,
    'bank_account_id' => $bankAccountId,
    'amount' => $request->amount,
    'status' => 'pending',
    'note' => $request->note,
]);

// Tạo transaction pending
WalletTransaction::create([
    'user_id' => $user->id,
    'type' => 'withdraw', // rút tiền
        'withdraw_request_id' => $withdraw->id, // liên kết trực tiếp
    'amount' => $request->amount,
    'balance_before' => $balanceBefore,
    'balance_after' => $balanceAfter,
    'transaction_code' => 'WD' . time() . rand(1000, 9999), // hoặc biến transactionCode nếu có
    'description' => 'Yêu cầu rút tiền #' . $withdraw->id,
    'status' => 'pending',
    'payment_method_type' => $bankAccountId ? 'BANK' : 'OTHER',
]);



    return redirect()->route('wallet.history')->with('success', 'Yêu cầu rút tiền đã được gửi, vui lòng chờ duyệt.');
}


    public function reject($id, Request $request)
{
    $withdraw = WithdrawRequest::findOrFail($id);

    if ($withdraw->status !== 'pending') {
        return back()->with('error', 'Yêu cầu đã xử lý trước đó.');
    }

    $withdraw->update([
        'status' => 'rejected',
        'note' => $request->note
    ]);

    // Cộng lại tiền cho user
    $wallet = $withdraw->user->wallet;
    $wallet->increment('balance', $withdraw->amount);

    // Update transaction status -> failed
 // Update transaction status -> failed
$transaction = WalletTransaction::where('withdraw_request_id', $withdraw->id)->first();
if ($transaction) {
    $transaction->update(['status' => 'failed']);
}


    return back()->with('success', 'Đã từ chối và hoàn tiền.');
}

}
