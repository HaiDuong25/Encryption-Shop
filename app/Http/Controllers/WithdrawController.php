<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\WithdrawRequest;
use App\Models\BankAccount;

use Illuminate\Http\Request;

class WithdrawController extends Controller
{
public function store(Request $request)
{
    $request->validate([
        'bank_account_id' => 'required|exists:bank_accounts,id',
        'amount' => 'required|numeric|min:10000'
    ]);

    $user = auth()->user();

    // Check số dư
    if ($user->wallet_balance < $request->amount) {
        return back()->with('error', 'Số dư không đủ để rút tiền');
    }

    // Trừ số dư tạm thời (nếu muốn)
    $user->wallet_balance -= $request->amount;
    $user->save();

    // Tạo yêu cầu rút
    WithdrawRequest::create([
        'user_id' => $user->id,
        'bank_account_id' => $request->bank_account_id,
        'amount' => $request->amount,
        'status' => 'pending',
    ]);

    return back()->with('success', 'Yêu cầu rút tiền đã được gửi, vui lòng chờ admin xác nhận.');
}
}
