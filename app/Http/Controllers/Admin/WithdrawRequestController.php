<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WithdrawRequest;
use App\Models\Wallet;
use Illuminate\Http\Request;

class WithdrawRequestController extends Controller
{
    // Danh sách yêu cầu rút
    public function index()
    {
        $requests = WithdrawRequest::with('user', 'bankAccount')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.withdraw.index', compact('requests'));
    }

    // Duyệt yêu cầu
    public function approve($id)
    {
        $request = WithdrawRequest::findOrFail($id);
        if ($request->status !== 'pending') {
            return back()->with('error', 'Yêu cầu này đã được xử lý');
        }

        // Trừ tiền trong ví
        $wallet = $request->user->wallet;
        if ($wallet->balance < $request->amount) {
            return back()->with('error', 'Số dư ví không đủ');
        }

        $wallet->balance -= $request->amount;
        $wallet->save();

        $request->status = 'approved';
        $request->save();

        // Tạo giao dịch ví (wallet_transactions)
        \App\Models\WalletTransaction::create([
            'user_id' => $request->user->id,
            'amount' => $request->amount,
            'type' => 'withdraw',
            'status' => 'completed',
            'payment_method_type' => 'wallet',
            'description' => 'Rút tiền về tài khoản ngân hàng',
            'balance_before' => $wallet->balance + $request->amount, // số dư trước khi trừ
            'balance_after' => $wallet->balance, // số dư sau khi trừ
            'transaction_code' => 'WT' . now()->format('YmdHis') . rand(1000,9999), // hoặc logic sinh mã của bạn
        ]);

        // Chuyển hướng sang trang giao dịch ví
        return redirect()->route('admin.wallet-transactions.index')->with('success', 'Đã duyệt yêu cầu rút tiền và tạo giao dịch ví!');
    }

    // Từ chối yêu cầu
    public function reject($id, Request $req)
    {
        $request = WithdrawRequest::findOrFail($id);
        if ($request->status !== 'pending') {
            return back()->with('error', 'Yêu cầu này đã được xử lý');
        }
        $request->status = 'rejected';
        $request->note = $req->note ?? 'Không có lý do';
        $request->save();

        return back()->with('success', 'Đã từ chối yêu cầu rút tiền');
    }
}
