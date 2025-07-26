<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Support\Facades\Session;

trait ClearsCheckoutSession
{
    /**
     * Clear all checkout-related session data
     */
    protected function clearCheckoutSession()
    {
        session()->forget([
            'cart',
            'selected_cart_items',
            'voucher_discount',
            'voucher_code',
            'voucher_message',
            'voucher_error',
            'applied_coupon',
            'coupon_discount',
            'coupon_info',
            'order_data',
            'momo_order_id',
            'momo_request_id',
            'zalopay_order_data'
        ]);
    }
}
