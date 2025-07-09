<?php

use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\AttributeValueController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BrandController;

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

//client
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\ProductController as ClientProductController;
use App\Http\Controllers\Client\OrderHistoryController ;
use App\Http\Controllers\Client\CartController;

Route::view('/auth', 'auth.auth')->name('auth');
Route::get('/login', [AuthController::class, 'index'])->name('login.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ClientProductController::class, 'index'])->name('client.products.index');
Route::get('/products/category/{id}', [ClientProductController::class, 'category'])->name('client.products.category');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');

Route::middleware(['auth'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('cart/delete/{id}', [CartController::class, 'delete'])->name('cart.delete');
    Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
});

Route::middleware('auth')->prefix('lich-su-don-hang')->group(function () {
    Route::get('/', [OrderHistoryController::class, 'index'])->name('orders.history');
    Route::get('/{id}', [OrderHistoryController::class, 'show'])->name('client.orders.show');
    Route::post('/{id}/cancel', [OrderHistoryController::class, 'cancel'])->name('orders.cancel');
Route::post('/lich-su-don-hang/{id}/confirm', [OrderHistoryController::class, 'confirm'])->name('orders.confirm');

});

Route::prefix('admin')->middleware(['auth', RoleMiddleware::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('products', ProductController::class);
    Route::post('/attributes/{attribute}/values', [AttributeValueController::class, 'storeAjax']);
    Route::resource('orders', OrderController::class);
    Route::resource('brands', BrandController::class);
    Route::resource('payment-methods', PaymentMethodController::class);
    Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::post('payments/{id}/confirm', [PaymentController::class, 'confirm'])->name('payments.confirm');
    Route::get('payments/invoice/{id}', [PaymentController::class, 'invoice'])->name('admin.payments.invoice');
    Route::post('admin/payments/{id}/reject', [PaymentController::class, 'reject'])->name('payments.reject');
    Route::resource('rates', RateController::class);
    Route::post('/rates/{rate}/replies', [RateReplyController::class, 'store'])->name('rates.replies.store');
    Route::resource('contacts', ContactController::class)->except(['create', 'store', 'update']);
    Route::resource('coupons', CouponController::class)->except(['show']);
    Route::get('/coupons/{id}/edit', [CouponController::class, 'edit'])->name('admin.coupons.edit');
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
    Route::resource('news', NewsController::class);
    Route::resource('banners', BannerController::class);
    Route::get('orders/{id}/tracking', [OrderController::class, 'tracking'])->name('admin.orders.tracking');
    Route::post('/admin/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::resource('users', UserController::class);
    Route::post('users/{user}/toggle', [UserController::class, 'toggle'])->name('users.toggle');
});

