<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UnsetCouponSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Chỉ giữ session coupon ở các route cart/checkout
        $keepCoupon = $request->is('cart*') || $request->is('checkout*');
        if (!$keepCoupon) {
            session()->forget(['applied_coupon', 'coupon_discount', 'coupon_info']);
        }
        return $next($request);
    }
}
