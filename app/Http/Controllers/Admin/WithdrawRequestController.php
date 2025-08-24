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

    $wallet = $request->user->wallet;

    // Ở đây không trừ nữa vì đã trừ khi user gửi yêu cầu

    $request->status = 'approved';
    $request->save();

    \App\Models\WalletTransaction::create([
        'user_id' => $request->user->id,
        'amount' => $request->amount,
        'type' => 'withdraw',
        'status' => 'completed',
        'payment_method_type' => 'wallet',
        'description' => 'Rút tiền về tài khoản ngân hàng',
        'balance_before' => $wallet->balance + $request->amount,
        'balance_after' => $wallet->balance,
        'transaction_code' => 'WT' . now()->format('YmdHis') . rand(1000,9999),
    ]);

    return redirect()->route('admin.wallet-transactions.index')->with('success', 'Đã duyệt yêu cầu rút tiền và tạo giao dịch ví!');
}


    // Từ chối yêu cầu
 public function reject($id, Request $req)
{
    $request = WithdrawRequest::findOrFail($id);

    if ($request->status !== 'pending') {
        return back()->with('error', 'Yêu cầu này đã được xử lý');
    }

    $wallet = $request->user->wallet;

    // Cộng lại số tiền vào ví
    $balanceBefore = $wallet->balance;
    $wallet->increment('balance', $request->amount);
    $balanceAfter = $wallet->balance;

    // Lý do hủy
    $note = $req->note ?? 'Không có lý do';

    // Cập nhật trạng thái rút tiền
    $request->status = 'rejected';
    $request->note = $note;
    $request->save();

    // Tạo transaction failed với lý do
    \App\Models\WalletTransaction::create([
        'user_id' => $request->user->id,
        'withdraw_request_id' => $request->id,
        'amount' => $request->amount,
        'type' => 'withdraw',
        'status' => 'failed',
        'payment_method_type' => 'wallet',
        'description' => 'Yêu cầu rút tiền #' . $request->id . ' bị từ chối: ' . $note,
        'balance_before' => $balanceBefore,
        'balance_after' => $balanceAfter,
        'transaction_code' => 'WT' . now()->format('YmdHis') . rand(1000,9999),
    ]);

    return back()->with('success', 'Đã từ chối yêu cầu rút tiền và hoàn tiền cho user');
}


}
