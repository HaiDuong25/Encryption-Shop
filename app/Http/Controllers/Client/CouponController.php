<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;
use App\Models\CouponUse;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    /**
     * Kiểm tra tính hợp lệ của coupon
     */
    public function validateCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'order_amount' => 'required|numeric|min:0'
        ]);

        $coupon = Coupon::where('code', $request->code)->first();

        if (!$coupon) {
            return response()->json([
                'valid' => false,
                'message' => 'Mã giảm giá không tồn tại!'
            ]);
        }

        if (!$coupon->canBeUsed()) {
            $message = 'Mã giảm giá không thể sử dụng!';

            if ($coupon->usage_limit > 0 && $coupon->used_count >= $coupon->usage_limit) {
                $message = 'Mã giảm giá đã hết lượt sử dụng!';
            } elseif ($coupon->expires_at && $coupon->expires_at->isPast()) {
                $message = 'Mã giảm giá đã hết hạn!';
            } elseif (!$coupon->is_active) {
                $message = 'Mã giảm giá đã bị vô hiệu hóa!';
            }

            return response()->json([
                'valid' => false,
                'message' => $message
            ]);
        }

        // Kiểm tra user đã đăng nhập chưa
        if (!Auth::check()) {
            return response()->json([
                'valid' => false,
                'message' => 'Bạn cần đăng nhập để sử dụng mã giảm giá!'
            ]);
        }

        $userId = Auth::id();

        // Kiểm tra user đã sử dụng coupon này chưa
        if ($coupon->hasBeenUsedByUser($userId)) {
            return response()->json([
                'valid' => false,
                'message' => 'Bạn đã sử dụng mã giảm giá này rồi!'
            ]);
        }

        // Kiểm tra điều kiện đơn hàng tối thiểu
        if ($coupon->min_order_amount && $request->order_amount < $coupon->min_order_amount) {
            return response()->json([
                'valid' => false,
                'message' => 'Đơn hàng cần tối thiểu ' . number_format($coupon->min_order_amount) . '₫ để sử dụng mã này!'
            ]);
        }

        // Tính toán giá trị giảm giá
        $discountAmount = 0;
        if ($coupon->discount_type === 'percentage') {
            $discountAmount = ($request->order_amount * $coupon->discount) / 100;
            if ($coupon->max_discount_amount && $discountAmount > $coupon->max_discount_amount) {
                $discountAmount = $coupon->max_discount_amount;
            }
        } else {
            $discountAmount = $coupon->discount;
        }

        return response()->json([
            'valid' => true,
            'message' => 'Mã giảm giá hợp lệ!',
            'coupon' => $coupon,
            'discount_amount' => $discountAmount,
            'remaining_usage' => $coupon->remainingUsage(),
            'discount_text' => $coupon->discount_type === 'percentage'
                ? "Giảm {$coupon->discount}%"
                : "Giảm " . number_format($coupon->discount) . "₫"
        ]);
    }

    /**
     * Sử dụng coupon (gọi khi đơn hàng được xác nhận)
     */
    public function useCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'order_id' => 'required|integer',
            'discount_amount' => 'required|numeric|min:0'
        ]);

        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để sử dụng mã giảm giá!'
            ]);
        }

        $userId = Auth::id();
        $coupon = Coupon::where('code', $request->code)->first();

        if (!$coupon || !$coupon->canBeUsed()) {
            return response()->json([
                'success' => false,
                'message' => 'Mã giảm giá không thể sử dụng!'
            ]);
        }

        // Kiểm tra user đã sử dụng coupon này chưa
        if ($coupon->hasBeenUsedByUser($userId)) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn đã sử dụng mã giảm giá này rồi!'
            ]);
        }

        try {
            // Lưu lại việc sử dụng coupon
            CouponUse::create([
                'user_id' => $userId,
                'coupon_id' => $coupon->id,
                'order_id' => $request->order_id,
                'discount_amount' => $request->discount_amount
            ]);

            // Tăng số lần sử dụng tổng thể của coupon
            $coupon->incrementUsage();

            return response()->json([
                'success' => true,
                'message' => 'Đã sử dụng mã giảm giá thành công!',
                'remaining_usage' => $coupon->remainingUsage()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi sử dụng mã giảm giá!'
            ]);
        }
    }

    /**
     * Lấy danh sách coupon khả dụng
     */
    public function getAvailableCoupons()
    {
        // Nếu user đã đăng nhập, chỉ hiển thị coupon chưa sử dụng
        if (Auth::check()) {
            $coupons = Coupon::availableForUser(Auth::id())
                ->latest()
                ->get();
        } else {
            // Nếu chưa đăng nhập, hiển thị tất cả coupon khả dụng
            $coupons = Coupon::available()
                ->latest()
                ->get();
        }

        $coupons = $coupons->map(function ($coupon) {
            return [
                'id' => $coupon->id,
                'code' => $coupon->code,
                'discount_text' => $coupon->discount_type === 'percentage'
                    ? "Giảm {$coupon->discount}%"
                    : "Giảm " . number_format($coupon->discount) . "₫",
                'min_order_amount' => $coupon->min_order_amount,
                'max_discount_amount' => $coupon->max_discount_amount,
                'usage_limit' => $coupon->usage_limit,
                'used_count' => $coupon->used_count,
                'remaining_usage' => $coupon->remainingUsage(),
                'expires_at' => $coupon->expires_at ? $coupon->expires_at->format('d/m/Y') : null,
                'start_date' => $coupon->start_date ? $coupon->start_date->format('d/m/Y') : null,
                'end_date' => $coupon->end_date ? $coupon->end_date->format('d/m/Y') : null,
                'can_use' => Auth::check() ? !$coupon->hasBeenUsedByUser(Auth::id()) : true
            ];
        });

        return response()->json([
            'success' => true,
            'coupons' => $coupons
        ]);
    }
}
