<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Coupon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class CouponController extends \App\Http\Controllers\Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Coupon::with('couponUses');
        
        // Tìm kiếm theo mã coupon
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('code', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }
        
        $coupons = $query->latest()->paginate(15);
        $coupons->appends($request->query());
        
        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(Request $request)
    {
        Log::info('CouponController store method called', [
            'request_data' => $request->all(),
            'is_ajax' => $request->ajax(),
            'headers' => $request->headers->all()
        ]);
        
        try {
            $request->validate([
                'code' => 'nullable|string|max:50|unique:coupons,code',
                'description' => 'nullable|string|max:500',
                'discount' => 'required|numeric|min:1',
                'discount_type' => 'required|in:percentage,fixed',
                'min_order_amount' => 'nullable|numeric|min:0',
                'max_discount_amount' => 'nullable|numeric|min:0',
                'usage_limit' => 'nullable|integer|min:0',
                'is_one_time_per_user' => 'boolean',
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

        // Validation cho max_discount_amount đã được loại bỏ vì không hợp lý

        $coupon = Coupon::create([
                'code' => $request->code ?: strtoupper(Str::random(10)),
                'description' => $request->description,
                'discount' => $request->discount,
                'discount_type' => $request->discount_type ?? 'percentage',
                'min_order_amount' => $request->min_order_amount,
                'max_discount_amount' => $request->max_discount_amount,
                'usage_limit' => $request->usage_limit ?? 0,
                'used_count' => 0,
                'is_one_time_per_user' => $request->boolean('is_one_time_per_user', true),
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'expires_at' => $request->end_date, // Set expires_at = end_date
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
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => $e->errors()
                ], 422);
            }
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error creating coupon: ' . $e->getMessage());
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Có lỗi xảy ra khi tạo mã giảm giá. Vui lòng thử lại.'
                ], 500);
            }
            return back()->with('error', 'Có lỗi xảy ra khi tạo mã giảm giá. Vui lòng thử lại.');
        }
    }

    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, $id)
    {
        try {
            $coupon = Coupon::findOrFail($id);

            $request->validate([
                'code' => 'nullable|string|max:50|unique:coupons,code,' . $id,
                'description' => 'nullable|string|max:500',
                'discount' => 'required|numeric|min:1',
                'discount_type' => 'required|in:percentage,fixed',
                'min_order_amount' => 'nullable|numeric|min:0',
                'max_discount_amount' => 'nullable|numeric|min:0',
                'usage_limit' => 'nullable|integer|min:0',
                'is_one_time_per_user' => 'boolean',
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

            // Validation cho max_discount_amount đã được loại bỏ vì không hợp lý

            $coupon->code = $request->code ?: $coupon->code;
            $coupon->description = $request->description;
            $coupon->discount = $request->discount;
            $coupon->discount_type = $request->discount_type;
            $coupon->min_order_amount = $request->min_order_amount;
            $coupon->max_discount_amount = $request->max_discount_amount;
            $coupon->usage_limit = $request->usage_limit ?? 0;
            $coupon->is_one_time_per_user = $request->boolean('is_one_time_per_user');
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
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => $e->errors()
                ], 422);
            }
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating coupon: ' . $e->getMessage());
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Có lỗi xảy ra khi cập nhật mã giảm giá. Vui lòng thử lại.'
                ], 500);
            }
            return back()->with('error', 'Có lỗi xảy ra khi cập nhật mã giảm giá. Vui lòng thử lại.');
        }
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
