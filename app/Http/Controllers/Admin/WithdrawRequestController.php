<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WithdrawRequest;
use App\Models\Wallet;
use Illuminate\Http\Request;
use App\Models\WalletTransaction;
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

    // Cập nhật trạng thái yêu cầu
    $request->status = 'approved';
    $request->save();

    // Tìm transaction cũ
    $transaction = \App\Models\WalletTransaction::where('withdraw_request_id', $request->id)->first();

    if ($transaction) {
        $transaction->update([
            'status' => 'completed',
            'description' => 'Rút tiền về tài khoản ngân hàng',
            'balance_before' => $wallet->balance + $request->amount,
            'balance_after' => $wallet->balance,
        ]);
    } else {
        // fallback phòng trường hợp dữ liệu cũ bị thiếu
        \App\Models\WalletTransaction::create([
            'user_id' => $request->user->id,
            'withdraw_request_id' => $request->id, // nhớ thêm cột này
            'amount' => $request->amount,
            'type' => 'withdraw',
            'status' => 'completed',
            'payment_method_type' => 'wallet',
            'description' => 'Rút tiền về tài khoản ngân hàng',
            'balance_before' => $wallet->balance + $request->amount,
            'balance_after' => $wallet->balance,
            'transaction_code' => 'WT' . now()->format('YmdHis') . rand(1000,9999),
        ]);
    }



    return redirect()->route('admin.withdraw.index')->with('success', 'Đã duyệt yêu cầu rút tiền và tạo giao dịch ví!');
}


    // Từ chối yêu cầu
 public function reject($id, Request $req)
{
    $withdraw = WithdrawRequest::findOrFail($id);

    if ($withdraw->status !== 'pending') {
        return back()->with('error', 'Yêu cầu này đã được xử lý');
    }

    $wallet = $withdraw->user->wallet;

    // Cộng lại số tiền vào ví
    $balanceBefore = $wallet->balance;
    $wallet->increment('balance', $withdraw->amount);
    $balanceAfter = $wallet->balance;

    // Lý do hủy
    $note = $req->note ?? 'Không có lý do';

    // Cập nhật trạng thái rút tiền
    $withdraw->update([
        'status' => 'rejected',
        'note' => $note
    ]);

    // Tìm transaction cũ theo withdraw_request_id hoặc description
    $transaction = \App\Models\WalletTransaction::where('withdraw_request_id', $withdraw->id)->first();

    if ($transaction) {
        // Cập nhật thay vì tạo mới
        $transaction->update([
            'status' => 'failed',
            'description' => 'Yêu cầu rút tiền #' . $withdraw->id . ' bị từ chối: ' . $note,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
        ]);
    } else {
        // Trường hợp không tìm thấy transaction cũ thì mới tạo mới (đề phòng)
WalletTransaction::create([
            'user_id' => $withdraw->user->id,
            'withdraw_request_id' => $withdraw->id,
            'amount' => $withdraw->amount,
            'type' => 'withdraw',
            'status' => 'pending',
            'payment_method_type' => 'wallet',
            'description' => 'Yêu cầu rút tiền #' . $withdraw->id . ' bị từ chối: ' . $note,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'transaction_code' => 'WT' . now()->format('YmdHis') . rand(1000,9999),
        ]);
    }

    return back()->with('success', 'Đã từ chối yêu cầu rút tiền và hoàn tiền cho user');
}


}
