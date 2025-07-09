<?php

use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

// ADMIN CONTROLLERS
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\AttributeValueController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\RateController;
use App\Http\Controllers\Admin\RateReplyController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PaymentMethodController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\UserController;

// CLIENT CONTROLLERS
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\ProductController as ClientProductController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\OrderController as ClientOrderController;

use Illuminate\Http\Request;
use App\Models\Coupon;


Route::view('/auth', 'auth.auth')->name('auth'); // Giao diện login/register
Route::get('/login', [AuthController::class, 'index'])->name('login.form'); // dùng để hiển thị form

Route::post('/register', [AuthController::class, 'register'])->name('register'); // Đăng ký
Route::post('/login', [AuthController::class, 'login'])->name('login');     // xử lý submit form
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout'); // Đăng xuất

// client
Route::get('/', [HomeController::class, 'index'])->name('home');
//sản phẩm
Route::get('/products', [ClientProductController::class, 'index'])->name('client.products.index');
Route::get('/products/category/{id}', [ClientProductController::class, 'category'])->name('client.products.category');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::get('/products/{id}', [ClientProductController::class, 'show'])->name('client.products.show');
Route::get('/get-stock', [App\Http\Controllers\Client\ProductController::class, 'getStock'])->name('client.products.getStock');


// Route chỉ user (và admin được truy cập luôn)
Route::middleware(['auth'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('cart/delete/{id}', [CartController::class, 'delete'])->name('cart.delete');
    Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::post('/checkout', [CartController::class, 'processCheckout'])->name('cart.processCheckout');
    // Đơn hàng cho user
    Route::get('/orders', [ClientOrderController::class, 'index'])->name('client.orders.index');
    Route::get('/checkout/success', function(Request $request) {
        $order_id = request('order_id');
        return view('client.cart.success', compact('order_id'));
    })->name('cart.success');
});


Route::prefix('admin')->middleware(['auth', RoleMiddleware::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    //products
    Route::resource('products', ProductController::class);
    //variant
    Route::post('/attributes/{attribute}/values', [AttributeValueController::class, 'storeAjax']);
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
    Route::get('categories/create-parent', [CategoryController::class, 'createParent'])->name('categories.create-parent');
    Route::post('categories/store-parent', [CategoryController::class, 'storeParent'])->name('categories.store-parent');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    //brands
    Route::resource('brands', BrandController::class);
    Route::get('/brands', [BrandController::class, 'index'])->name('brands.index');
    Route::get('/brands/create', [BrandController::class, 'create'])->name('brands.create');
    Route::post('/brands', [BrandController::class, 'store'])->name('brands.store');
    Route::get('/brands/{brand}/edit', [BrandController::class, 'edit'])->name('brands.edit');
    Route::put('/brands/{brand}', [BrandController::class, 'update'])->name('brands.update');
    Route::delete('/brands/{brand}', [BrandController::class, 'destroy'])->name('brands.destroy');
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
    Route::post('/admin/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    //user
    Route::resource('users', UserController::class);
    Route::post('users/{user}/toggle', [UserController::class, 'toggle'])->name('users.toggle');
});
