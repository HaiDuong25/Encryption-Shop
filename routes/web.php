<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CouponController;
use App\Models\Coupon;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\BannerController;


Route::prefix('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
});
Route::get('/admin/coupons/{id}/edit', [CouponController::class, 'edit'])->name('admin.coupons.edit');

// Route tạo và lưu mã giảm giá
Route::get('/coupons/create', [CouponController::class, 'create'])->name('coupons.create');
Route::post ('/coupons', [CouponController::class, 'store'])->name('coupons.store');

// Route áp dụng mã giảm giá cho đơn hàng
Route::post('/apply-coupon', function(Request $request) {
    $coupon = Coupon::where('code', $request->code)->first();
    if (!$coupon || !$coupon->isValid()) {
        return back()->with('error', 'Mã giảm giá không hợp lệ hoặc đã hết hạn!');
    }
    session(['coupon' => [
        'code' => $coupon->code,
        'discount' => $coupon->discount
    ]]);
    return back()->with('success', 'Áp dụng mã thành công!');
})->name('apply.coupon');

Route::get('/coupons', [CouponController::class, 'index'])->name('coupons.index');
Route::get('/coupons/{id}/edit', [CouponController::class, 'edit'])->name('coupons.edit');
Route::put('/coupons/{id}', [CouponController::class, 'update'])->name('coupons.update');
Route::delete('/coupons/{id}', [CouponController::class, 'destroy'])->name('coupons.destroy');

Route::resource('news', NewsController::class);
Route::resource('banners', BannerController::class);