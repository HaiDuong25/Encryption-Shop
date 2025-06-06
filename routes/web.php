<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\PaymentMethodController;
use App\Http\Controllers\Admin\PaymentController;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ProductVariantController;

use App\Http\Controllers\Admin\RateController as AdminRateController;
use App\Http\Controllers\Admin\RateReplyController as AdminRateReplyController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;

use Illuminate\Http\Request;
use App\Http\Controllers\CouponController;
use App\Models\Coupon;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\BannerController;

Route::prefix('admin')->group(function () {
    // Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // CRUD phương thức thanh toán
    Route::resource('payment-methods', PaymentMethodController::class);
    Route::get('payment-methods', [PaymentMethodController::class, 'index'])->name('admin.payment-methods.index');

    // Quản lý thanh toán
    Route::get('payments', [PaymentController::class, 'index'])->name('admin.payments.index');
    Route::post('payments/{id}/confirm', [PaymentController::class, 'confirm'])->name('payments.confirm');
    Route::get('payments/invoice/{id}', [PaymentController::class, 'invoice'])->name('admin.payments.invoice');

    // Products
    Route::resource('products', ProductController::class);

    // Categories
    Route::resource('categories', CategoryController::class)->except(['show']);

    // Brands
    Route::resource('brands', BrandController::class);

    // Product Variants
    Route::resource('product-variants', ProductVariantController::class)->except(['show']);

    // Rates
    Route::resource('rates', AdminRateController::class)->except(['create', 'store']);
    Route::post('rates/{rate}/replies', [AdminRateReplyController::class, 'store'])->name('rates.replies.store');

    // Contacts
    Route::resource('contacts', AdminContactController::class)->only(['index', 'show', 'destroy']);

    // Coupons
    Route::resource('coupons', CouponController::class)->except(['show']);
    Route::post('apply-coupon', function (Request $request) {
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

    // News
    Route::resource('news', NewsController::class);

    // Banners
    Route::resource('banners', BannerController::class)->except(['show']);
});

