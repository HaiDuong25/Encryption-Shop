<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    // ...existing methods...

    public function applyVoucher(Request $request)
    {
        $code = $request->input('voucher');
        // Ví dụ: chỉ chấp nhận mã 'GIAM50K'
        if ($code === 'GIAM50K') {
            session([
                'voucher_discount' => 50000,
                'voucher_code' => $code,
                'voucher_message' => 'Áp dụng mã thành công! Đã giảm 50.000đ.'
            ]);
            session()->forget('voucher_error');
        } else {
            session([
                'voucher_discount' => 0,
                'voucher_code' => $code,
                'voucher_error' => 'Mã không hợp lệ hoặc đã hết hạn.'
            ]);
            session()->forget('voucher_message');
        }
        return redirect()->route('cart.index');
    }

    // ...existing methods...
}