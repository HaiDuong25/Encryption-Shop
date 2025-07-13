<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Coupon;
use Illuminate\Support\Str;

class CouponController extends \App\Http\Controllers\Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Coupon::query();
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
            'discount' => 'required|integer|min:1|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $coupon = Coupon::create([
            'code' => strtoupper(Str::random(10)),
            'discount' => $request->discount,
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
            'discount' => 'required|integer|min:1|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $coupon->discount = $request->discount;
        $coupon->start_date = $request->start_date;
        $coupon->end_date = $request->end_date;
        $coupon->expires_at = $request->expires_at;
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
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Xóa mã thành công!'
            ]);
        }
        return redirect()->route('coupons.index')->with('success', 'Xóa mã thành công!');
    }
}
