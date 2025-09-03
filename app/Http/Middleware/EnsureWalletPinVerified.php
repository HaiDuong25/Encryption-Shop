<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class EnsureWalletPinVerified
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }
        if (!$user->hasWalletPin()) {
            return redirect()->route('wallet.pin.setup')->with('warning','Vui lòng thiết lập PIN ví trước.');
        }
        $verifiedAt = Session::get('wallet_pin_verified_at');
        $ttlMinutes = 10; // thời gian hợp lệ của phiên xác thực PIN
        if (!$verifiedAt || now()->diffInMinutes($verifiedAt) >= $ttlMinutes) {
            // Với request AJAX có thể trả JSON
            if ($request->expectsJson()) {
                return response()->json(['require_pin'=>true,'message'=>'Yêu cầu xác thực PIN'], 401);
            }
            return redirect()->route('wallet.index')->with('require_pin', true);
        }
        return $next($request);
    }
}
