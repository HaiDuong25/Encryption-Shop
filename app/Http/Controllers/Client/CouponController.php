<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;
use App\Models\CouponUse;
use App\Models\UserSavedCoupon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CouponController extends Controller
{
    /**
     * Display all available coupons for clients
     */
    public function index(Request $request)
    {
        $query = Coupon::where('is_active', true);

        // Filter by expiration
        $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>=', Carbon::now());
        });

        // Filter by date range
        $query->where(function ($q) {
            $q->where(function ($subQ) {
                $subQ->whereNull('start_date')
                     ->whereNull('end_date');
            })
            ->orWhere(function ($subQ) {
                $subQ->where('start_date', '<=', Carbon::now())
                     ->where('end_date', '>=', Carbon::now());
            })
            ->orWhere(function ($subQ) {
                $subQ->whereNull('start_date')
                     ->where('end_date', '>=', Carbon::now());
            })
            ->orWhere(function ($subQ) {
                $subQ->where('start_date', '<=', Carbon::now())
                     ->whereNull('end_date');
            });
        });

        // Filter by usage limit
        $query->where(function ($q) {
            $q->where('usage_limit', 0) // unlimited
              ->orWhereRaw('used_count < usage_limit');
        });

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by discount type
        if ($request->has('type') && !empty($request->type)) {
            $query->where('discount_type', $request->type);
        }

        // Filter by minimum order amount
        if ($request->has('min_order') && !empty($request->min_order)) {
            $minOrder = $request->min_order;
            $query->where(function ($q) use ($minOrder) {
                $q->whereNull('min_order_amount')
                  ->orWhere('min_order_amount', '<=', $minOrder);
            });
        }

        // Sort options
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        
        switch ($sortBy) {
            case 'discount':
                $query->orderBy('discount', $sortOrder);
                break;
            case 'expires':
                $query->orderBy('expires_at', $sortOrder);
                break;
            case 'usage':
                $query->orderByRaw('(used_count / NULLIF(usage_limit, 0)) ' . $sortOrder);
                break;
            default:
                $query->orderBy('created_at', $sortOrder);
        }

        $coupons = $query->paginate(12);

        // Get filter counts for statistics
        $totalCoupons = Coupon::where('is_active', true)->count();
        $expiringSoon = Coupon::where('is_active', true)
            ->where('expires_at', '>=', Carbon::now())
            ->where('expires_at', '<=', Carbon::now()->addDays(7))
            ->count();
        $unlimitedCoupons = Coupon::where('is_active', true)
            ->where('usage_limit', 0)
            ->count();

        return view('client.coupons.all', compact(
            'coupons', 
            'totalCoupons', 
            'expiringSoon', 
            'unlimitedCoupons'
        ));
    }

    /**
     * Display saved coupons page
     */
    public function myCoupons()
    {
        if (!Auth::check()) {
            return redirect()->route('login.form')->with('message', 'Vui lòng đăng nhập để xem mã giảm giá đã lưu');
        }

        $user = Auth::user();
        // Get saved coupons through the pivot table
        $savedCoupons = UserSavedCoupon::where('user_id', $user->id)
                                     ->with('coupon')
                                     ->orderBy('saved_at', 'desc')
                                     ->paginate(12);

        return view('client.coupons.my-coupons', compact('savedCoupons'));
    }

    /**
     * Get coupon details for AJAX
     */
    public function show($id)
    {
        $coupon = Coupon::findOrFail($id);
        return view('client.coupons.detail', compact('coupon'));
    }

    public function saveCoupon(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập']);
        }

        $couponId = $request->coupon_id;
        $user = Auth::user();

        // Kiểm tra xem mã giảm giá có tồn tại không
        $coupon = Coupon::find($couponId);
        if (!$coupon) {
            return response()->json(['success' => false, 'message' => 'Mã giảm giá không tồn tại']);
        }

        // Kiểm tra xem đã lưu chưa
        if ($user->hasSavedCoupon($couponId)) {
            return response()->json(['success' => false, 'message' => 'Mã giảm giá đã được lưu trước đó']);
        }

        // Lưu mã giảm giá
        UserSavedCoupon::create([
            'user_id' => $user->id,
            'coupon_id' => $couponId,
            'saved_at' => now()
        ]);

        $savedCount = $user->savedCoupons()->count();

        return response()->json([
            'success' => true, 
            'message' => 'Đã lưu mã giảm giá thành công',
            'saved_count' => $savedCount
        ]);
    }

    public function removeCoupon(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập']);
        }

        $couponId = $request->coupon_id;
        $user = Auth::user();

        // Xóa mã giảm giá khỏi danh sách đã lưu
        $deleted = UserSavedCoupon::where('user_id', $user->id)
            ->where('coupon_id', $couponId)
            ->delete();

        if ($deleted) {
            $savedCount = $user->savedCoupons()->count();
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa mã giảm giá khỏi danh sách',
                'saved_count' => $savedCount
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Không thể xóa mã giảm giá']);
    }

    /**
     * Xóa mã giảm giá đã sử dụng khỏi danh sách saved (sau khi thanh toán thành công)
     */
    public function removeUsedCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string|max:50'
        ]);

        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập']);
        }

        try {
            $couponCode = $request->coupon_code;
            $user = Auth::user();

            // Tìm coupon theo code
            $coupon = Coupon::where('code', $couponCode)->first();
            if (!$coupon) {
                return response()->json(['success' => false, 'message' => 'Không tìm thấy mã giảm giá']);
            }

            // Xóa khỏi danh sách đã lưu nếu user đã lưu mã này
            $deleted = UserSavedCoupon::where('user_id', $user->id)
                ->where('coupon_id', $coupon->id)
                ->delete();

            if ($deleted) {
                Log::info("Removed used coupon {$couponCode} from user {$user->id} saved list after successful payment");
            }

            return response()->json([
                'success' => true,
                'message' => 'Mã giảm giá đã được xóa khỏi danh sách đã lưu sau khi sử dụng thành công',
                'removed' => $deleted > 0
            ]);
        } catch (\Exception $e) {
            Log::error('Error removing used coupon from saved list: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa mã giảm giá'
            ]);
        }
    }

    public function getSavedCoupons()
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập']);
        }

        $user = Auth::user();
        
        // Get saved coupons with valid, non-expired coupons only
        $savedCoupons = $user->savedCoupons()
            ->with(['coupon' => function($query) {
                $query->where('is_active', true)
                      ->where(function ($q) {
                          // Filter by expiration
                          $q->whereNull('expires_at')
                            ->orWhere('expires_at', '>=', now());
                      })
                      ->where(function ($q) {
                          // Filter by date range
                          $q->where(function ($subQ) {
                              $subQ->whereNull('start_date')
                                   ->whereNull('end_date');
                          })
                          ->orWhere(function ($subQ) {
                              $subQ->where('start_date', '<=', now())
                                   ->where('end_date', '>=', now());
                          })
                          ->orWhere(function ($subQ) {
                              $subQ->whereNull('start_date')
                                   ->where('end_date', '>=', now());
                          })
                          ->orWhere(function ($subQ) {
                              $subQ->where('start_date', '<=', now())
                                   ->whereNull('end_date');
                          });
                      });
            }])
            ->get();

        $coupons = $savedCoupons->map(function($savedCoupon) {
            $coupon = $savedCoupon->coupon;
            if (!$coupon) return null; // Skip expired or invalid coupons
            
            return [
                'id' => $coupon->id,
                'code' => $coupon->code,
                'discount' => $coupon->discount,
                'discount_type' => $coupon->discount_type,
                'description' => $coupon->description,
                'min_order_amount' => $coupon->min_order_amount,
                'max_discount_amount' => $coupon->max_discount_amount,
                'expiry_date' => $coupon->expires_at ? $coupon->expires_at->format('Y-m-d') : null,
                'end_date' => $coupon->end_date ? $coupon->end_date->format('Y-m-d') : null,
                'saved_at' => $savedCoupon->saved_at
            ];
        })->filter(); // Remove null values (expired coupons)

        return response()->json([
            'success' => true, 
            'coupons' => $coupons->values(),
            'message' => 'Đã lọc mã hết hạn và không khả dụng'
        ]);
    }

    /**
     * Restore coupon vào danh sách đã lưu (khi user hủy checkout)
     */
    public function restoreSavedCoupon(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập']);
        }

        $request->validate([
            'coupon_code' => 'required|string'
        ]);

        $user = Auth::user();
        $couponCode = $request->coupon_code;

        // Tìm coupon theo code
        $coupon = Coupon::where('code', $couponCode)
                        ->where('is_active', true)
                        ->first();

        if (!$coupon) {
            return response()->json(['success' => false, 'message' => 'Mã giảm giá không tồn tại hoặc đã hết hạn']);
        }

        // Kiểm tra mã có hết hạn không
        if ($coupon->expires_at && $coupon->expires_at < now()) {
            return response()->json(['success' => false, 'message' => 'Mã giảm giá đã hết hạn']);
        }

        if ($coupon->end_date && $coupon->end_date < now()) {
            return response()->json(['success' => false, 'message' => 'Mã giảm giá đã hết thời gian sử dụng']);
        }

        // Kiểm tra xem đã lưu chưa
        $existingSave = UserSavedCoupon::where('user_id', $user->id)
                                      ->where('coupon_id', $coupon->id)
                                      ->first();

        if ($existingSave) {
            return response()->json(['success' => true, 'message' => 'Mã giảm giá đã có trong danh sách đã lưu']);
        }

        // Lưu mã giảm giá trở lại
        UserSavedCoupon::create([
            'user_id' => $user->id,
            'coupon_id' => $coupon->id,
            'saved_at' => now()
        ]);

        $savedCount = $user->savedCoupons()->count();

        Log::info("Restored coupon {$couponCode} to user {$user->id} saved list after checkout cancellation");

        return response()->json([
            'success' => true, 
            'message' => 'Đã trả mã giảm giá về danh sách đã lưu',
            'saved_count' => $savedCount,
            'coupon' => [
                'id' => $coupon->id,
                'code' => $coupon->code,
                'discount' => $coupon->discount,
                'discount_type' => $coupon->discount_type,
                'description' => $coupon->description
            ]
        ]);
    }
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
