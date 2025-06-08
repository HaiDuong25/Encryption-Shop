<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ProductVariantController;

use App\Http\Controllers\Admin\RateController;
use App\Http\Controllers\Admin\RateReplyController;
use App\Http\Controllers\Admin\ContactController;

use Illuminate\Http\Request;
use App\Http\Controllers\CouponController;
use App\Models\Coupon;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\OrderController;

use App\Http\Controllers\Admin\PaymentMethodController;
use App\Http\Controllers\Admin\PaymentController;

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\UserController;

Route::view('/auth', 'auth.auth')->middleware('admin')->name('auth'); // Giao diện login/register
Route::get('/login', [AuthController::class, 'index'])->name('login.form'); // dùng để hiển thị form

Route::post('/register', [AuthController::class, 'register'])->name('register'); // Đăng ký
Route::post('/login', [AuthController::class, 'login'])->name('login');     // xử lý submit form
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout'); // Đăng xuất

Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    //products
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::get('/admin/inventory', [ProductController::class, 'inventory'])->name('inventory.index');
    // orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');
    Route::put('/orders/{order}', [OrderController::class, 'update'])->name('orders.update');
    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');

    //categories
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    //brands
    Route::resource('brands', BrandController::class);
    Route::get('/brands', [BrandController::class, 'index'])->name('brands.index');
    Route::get('/brands/create', [BrandController::class, 'create'])->name('brands.create');
    Route::post('/brands', [BrandController::class, 'store'])->name('brands.store');
    Route::get('/brands/{brand}/edit', [BrandController::class, 'edit'])->name('brands.edit');
    Route::put('/brands/{brand}', [BrandController::class, 'update'])->name('brands.update');
    Route::delete('/brands/{brand}', [BrandController::class, 'destroy'])->name('brands.destroy');

    //product variants
    Route::get('/product-variants', [ProductVariantController::class, 'index'])->name('product-variants.index');
    Route::get('/product-variants/create', [ProductVariantController::class, 'create'])->name('product-variants.create');
    Route::post('/product-variants', [ProductVariantController::class, 'store'])->name('product-variants.store');
    Route::get('/product-variants/{productVariant}/edit', [ProductVariantController::class, 'edit'])->name('product-variants.edit');
    Route::put('/product-variants/{productVariant}', [ProductVariantController::class, 'update'])->name('product-variants.update');
    Route::delete('/product-variants/{productVariant}', [ProductVariantController::class, 'destroy'])->name('product-variants.destroy');

    //phương thức thanh toán
    Route::resource('payment-methods', PaymentMethodController::class);
    Route::get('payment-methods', [PaymentMethodController::class, 'index'])->name('payment-methods.index');

    // Quản lý thanh toán
    Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::post('payments/{id}/confirm', [PaymentController::class, 'confirm'])->name('payments.confirm');
    Route::get('payments/invoice/{id}', [PaymentController::class, 'invoice'])->name('admin.payments.invoice');
    Route::post('admin/payments/{id}/reject', [PaymentController::class, 'reject'])->name('payments.reject');

    //rate
    Route::get('/rates', [RateController::class, 'index'])->name('rates.index');
    Route::get('/rates/{rate}', [RateController::class, 'show'])->name('rates.show');
    Route::get('/rates/{rate}/edit', [RateController::class, 'edit'])->name('rates.edit');
    Route::put('/rates/{rate}', [RateController::class, 'update'])->name('rates.update');
    Route::delete('/rates/{rate}', [RateController::class, 'destroy'])->name('rates.destroy');

    // Route cho việc lưu phản hồi của Admin cho một Rate
    Route::post('/rates/{rate}/replies', [RateReplyController::class, 'store'])->name('rates.replies.store');
    // Routes cho Quản lý Liên hệ Khách hàng
    Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');
    Route::get('/contacts/{contact}', [ContactController::class, 'show'])->name('contacts.show');
    Route::delete('/contacts/{contact}', [ContactController::class, 'destroy'])->name('contacts.destroy');

    //coupons
    Route::get('/coupons/{id}/edit', [CouponController::class, 'edit'])->name('admin.coupons.edit');

    // Route tạo và lưu mã giảm giá
    Route::get('/coupons/create', [CouponController::class, 'create'])->name('coupons.create');
    Route::post('/coupons', [CouponController::class, 'store'])->name('coupons.store');

    // Route áp dụng mã giảm giá cho đơn hàng`
    Route::post('/apply-coupon', function (Request $request) {
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
    Route::delete('/banners/{id}', [BannerController::class, 'destroy'])->name('banners.destroy');
    Route::get('orders/{id}/tracking', [OrderController::class, 'tracking'])->name('admin.orders.tracking');
Route::post('/orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');

    //user
    Route::resource('users', UserController::class);
});
