<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Coupon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class CouponController extends \App\Http\Controllers\Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Coupon::with('couponUses');
        if ($request->filled('discount')) {
            $query->where('discount', $request->discount);
        }
        $coupons = $query->paginate(15);
        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'discount' => 'required|numeric|min:1',
            'discount_type' => 'required|in:percentage,fixed',
            'usage_limit' => 'nullable|integer|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // Validation cho discount dựa trên type
        if ($request->discount_type === 'percentage' && $request->discount > 100) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Giảm giá theo phần trăm không thể vượt quá 100%'
                ], 422);
            }
            return back()->withErrors(['discount' => 'Giảm giá theo phần trăm không thể vượt quá 100%']);
        }

        if ($request->discount_type === 'fixed' && $request->discount > 10000000) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Số tiền giảm giá không thể vượt quá 10.000.000₫'
                ], 422);
            }
            return back()->withErrors(['discount' => 'Số tiền giảm giá không thể vượt quá 10.000.000₫']);
        }

        $coupon = Coupon::create([
            'code' => strtoupper(Str::random(10)),
            'discount' => $request->discount,
            'discount_type' => $request->discount_type ?? 'percentage',
            'usage_limit' => $request->usage_limit ?? 0,
            'used_count' => 0,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'expires_at' => $request->expires_at,
            'is_active' => true
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã tạo mã: ' . $coupon->code,
                'coupon' => $coupon
            ]);
        }
        return redirect()->route('coupons.index')->with('success', 'Đã tạo mã: ' . $coupon->code);
    }

    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);

        $request->validate([
            'discount' => 'required|numeric|min:1',
            'discount_type' => 'required|in:percentage,fixed',
            'usage_limit' => 'nullable|integer|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // Validation cho discount dựa trên type
        if ($request->discount_type === 'percentage' && $request->discount > 100) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Giảm giá theo phần trăm không thể vượt quá 100%'
                ], 422);
            }
            return back()->withErrors(['discount' => 'Giảm giá theo phần trăm không thể vượt quá 100%']);
        }

        if ($request->discount_type === 'fixed' && $request->discount > 10000000) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Số tiền giảm giá không thể vượt quá 10.000.000₫'
                ], 422);
            }
            return back()->withErrors(['discount' => 'Số tiền giảm giá không thể vượt quá 10.000.000₫']);
        }

        $coupon->discount = $request->discount;
        $coupon->discount_type = $request->discount_type;
        $coupon->usage_limit = $request->usage_limit ?? 0;
        $coupon->start_date = $request->start_date;
        $coupon->end_date = $request->end_date;
        $coupon->expires_at = $request->end_date; // Set expires_at same as end_date
        $coupon->save();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật thành công!',
                'coupon' => $coupon
            ]);
        }
        return redirect()->route('coupons.index')->with('success', 'Cập nhật thành công!');
    }

    public function destroy($id)
    {
        try {
            $coupon = Coupon::findOrFail($id);

            // Kiểm tra xem coupon có đang được sử dụng không
            $isUsed = \App\Models\CouponUse::where('coupon_id', $id)->exists();

            if ($isUsed) {
                if (request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Không thể xóa mã giảm giá đã được sử dụng!'
                    ], 422);
                }
                return redirect()->route('coupons.index')->with('error', 'Không thể xóa mã giảm giá đã được sử dụng!');
            }

            $couponCode = $coupon->code;
            $coupon->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Xóa mã '{$couponCode}' thành công!"
                ]);
            }
            return redirect()->route('coupons.index')->with('success', "Xóa mã '{$couponCode}' thành công!");

        } catch (\Exception $e) {
            Log::error('Error deleting coupon: ' . $e->getMessage());

            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Có lỗi xảy ra khi xóa mã giảm giá: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->route('coupons.index')->with('error', 'Có lỗi xảy ra khi xóa mã giảm giá!');
        }
    }
}
