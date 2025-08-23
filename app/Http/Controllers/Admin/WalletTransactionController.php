<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WalletTransaction;
use App\Models\User;

class WalletTransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = WalletTransaction::with('user');

        // Filter by type
     if ($request->filled('type') && in_array($request->type, ['deposit', 'payment', 'withdraw', 'refund'])) {
    $query->where('type', $request->type);
}

        // Filter by status
        if ($request->filled('status') && in_array($request->status, ['pending', 'completed', 'failed'])) {
            $query->where('status', $request->status);
        }

        // Filter by payment method
        if ($request->filled('payment_method_type')) {
            $query->where('payment_method_type', $request->payment_method_type);
        }

        // Search by user name, email or transaction code
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('transaction_code', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'LIKE', "%{$search}%")
                               ->orWhere('email', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Sort by latest
        $query->orderBy('created_at', 'desc');

        $transactions = $query->paginate(20)->withQueryString();

        // Statistics
    $stats = [
    'total_deposits' => WalletTransaction::where('type', 'deposit')->where('status', 'completed')->sum('amount'),
    'total_payments' => WalletTransaction::where('type', 'payment')->where('status', 'completed')->sum('amount'),
    'total_withdraws' => WalletTransaction::where('type', 'withdraw')->where('status', 'completed')->sum('amount'),
    'total_refunds' => WalletTransaction::where('type', 'refund')->where('status', 'completed')->sum('amount'),
    'pending_deposits' => WalletTransaction::where('type', 'deposit')->where('status', 'pending')->count(),
    'pending_withdraws' => WalletTransaction::where('type', 'withdraw')->where('status', 'pending')->count(),
    'total_transactions_today' => WalletTransaction::whereDate('created_at', today())->count(),
];

        return view('admin.wallet-transactions.index', compact('transactions', 'stats'));
    }

    public function show($id)
    {
        $transaction = WalletTransaction::with('user')->findOrFail($id);
        return view('admin.wallet-transactions.show', compact('transaction'));
    }

    public function destroy($id)
    {
        $transaction = WalletTransaction::findOrFail($id);

        // Only allow deleting failed payment transactions
        if ($transaction->status !== 'failed') {
            return redirect()->back()->with('error', 'Chỉ có thể xóa các giao dịch thất bại');
        }

        if ($transaction->type !== 'payment') {
            return redirect()->back()->with('error', 'Không thể xóa giao dịch nạp tiền. Chỉ cho phép xóa giao dịch thanh toán thất bại');
        }

        $transaction->delete();

        return redirect()->route('admin.wallet-transactions.index')
            ->with('success', 'Đã xóa giao dịch thanh toán thất bại thành công');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,failed'
        ]);

        $transaction = WalletTransaction::with('user')->findOrFail($id);

        // Prevent changing completed transactions
        if ($transaction->status === 'completed') {
            return redirect()->back()->with('error', 'Không thể thay đổi trạng thái giao dịch đã hoàn thành');
        }

        $oldStatus = $transaction->status;
        $newStatus = $request->status;

        // If changing from pending to completed for deposit, update wallet balance
   // If changing from pending to completed for deposit, update wallet balance
if ($oldStatus === 'pending' && $newStatus === 'completed') {
    $wallet = $transaction->user->getOrCreateWallet();

    if ($transaction->type === 'deposit') {
        // Nạp tiền
        $wallet->balance += $transaction->amount;
    } elseif ($transaction->type === 'withdraw') {
        // Rút tiền (chỉ trừ khi số dư đủ)
        if ($wallet->balance < $transaction->amount) {
            return redirect()->back()->with('error', 'Số dư không đủ để duyệt rút tiền');
        }
        $wallet->balance -= $transaction->amount;
    }

    $wallet->save();
    $transaction->balance_after = $wallet->balance;
}



        $transaction->status = $newStatus;
        $transaction->save();

        return redirect()->back()->with('success', 'Đã cập nhật trạng thái giao dịch');
    }
}
