<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UnsetCouponOnCheckoutExit
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
        // Nếu không phải route checkout thì unset mã giảm giá
        if (!$request->is('checkout') && !$request->is('cart*')) {
            session()->forget(['applied_coupon', 'coupon_discount', 'coupon_info']);
        }
        return $next($request);
    }
}
