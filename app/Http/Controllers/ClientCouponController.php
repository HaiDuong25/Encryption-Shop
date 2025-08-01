<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\UserSavedCoupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ClientCouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::where('status', 'active')
                        ->where(function($query) {
                            $query->whereNull('expires_at')
                                  ->orWhere('expires_at', '>', now());
                        })
                        ->orderBy('created_at', 'desc')
                        ->paginate(12);

        $totalCoupons = $coupons->total();
        $expiringSoon = Coupon::where('status', 'active')
                             ->where('expires_at', '>', now())
                             ->where('expires_at', '<=', now()->addDays(7))
                             ->count();
        $unlimitedCoupons = Coupon::where('status', 'active')
                                 ->where(function($query) {
                                     $query->where('usage_limit', 0)
                                           ->orWhereNull('usage_limit');
                                 })
                                 ->count();

        return view('client.coupons.simple', compact('coupons', 'totalCoupons', 'expiringSoon', 'unlimitedCoupons'));
    }

    public function saveCoupon(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để lưu mã giảm giá'
            ], 401);
        }

        $couponId = $request->input('coupon_id');
        
        if (!$couponId || !Coupon::find($couponId)) {
            return response()->json([
                'success' => false,
                'message' => 'Mã giảm giá không hợp lệ'
            ], 422);
        }

        $userId = Auth::id();

        // Kiểm tra xem đã lưu chưa
        $exists = UserSavedCoupon::where('user_id', $userId)
                                ->where('coupon_id', $couponId)
                                ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Mã giảm giá đã được lưu trước đó'
            ]);
        }

        // Lưu mã giảm giá
        $userSavedCoupon = new UserSavedCoupon();
        $userSavedCoupon->user_id = $userId;
        $userSavedCoupon->coupon_id = $couponId;
        $userSavedCoupon->saved_at = now();
        $userSavedCoupon->save();

        // Đếm số mã đã lưu
        $savedCount = UserSavedCoupon::where('user_id', $userId)->count();

        return response()->json([
            'success' => true,
            'message' => 'Đã lưu mã giảm giá thành công',
            'saved_count' => $savedCount
        ]);
    }
}
