<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Coupon;

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
use App\Http\Middleware\RoleMiddleware;

// CLIENT CONTROLLERS
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\ProductController as ClientProductController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\OrderController as ClientOrderController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\Client\ContactController as ClientContactController;
// --- Auth ---
Route::view('/auth', 'auth.auth')->name('auth');
Route::get('/login', [AuthController::class, 'index'])->name('login.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// --- Trang chính ---
Route::get('/', [HomeController::class, 'index'])->name('home');

// --- Sản phẩm ---
Route::get('/products', [ClientProductController::class, 'index'])->name('client.products.index');
Route::get('/products/category/{id}', [ClientProductController::class, 'category'])->name('client.products.category');
Route::get('/products/{id}', [ClientProductController::class, 'show'])->name('client.products.show');
Route::get('/get-stock', [ClientProductController::class, 'getStock'])->name('client.products.getStock');
// --- Liên hệ ---
Route::get('/lien-he', [ClientContactController::class, 'create'])->name('client.contact.create');
Route::post('/lien-he', [ClientContactController::class, 'store'])->name('client.contact.store');

// --- Các chức năng cần đăng nhập ---
Route::middleware(['auth'])->group(function () {
    // Yêu thích
    Route::get('/yeu-thich', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/yeu-thich/add/{id}', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::delete('/yeu-thich/remove/{id}', [WishlistController::class, 'remove'])->name('wishlist.remove');

    // Giỏ hàng
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/delete/{id}', [CartController::class, 'delete'])->name('cart.delete');

    // Thanh toán
    Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::post('/checkout', [CartController::class, 'processCheckout'])->name('cart.processCheckout');
    Route::get('/checkout/success', function (Request $request) {
        $order_id = request('order_id');
        return view('client.cart.success', compact('order_id'));
    })->name('cart.success');

    // Mua ngay
    Route::post('/buy-now/{id}', [CartController::class, 'buyNow'])->name('cart.buyNow');

    // Mã giảm giá AJAX
    Route::post('/apply-coupon', [CartController::class, 'applyCoupon'])->name('apply.coupon');
    Route::post('/remove-coupon', [CartController::class, 'removeCoupon'])->name('remove.coupon');

    // Đơn hàng (client)
    Route::get('/orders', [ClientOrderController::class, 'index'])->name('client.orders.index');
    Route::get('/orders/{order}', [ClientOrderController::class, 'show'])->name('client.orders.show');
    Route::post('/orders/{order}/cancel', [ClientOrderController::class, 'cancel'])->name('client.orders.cancel');
    Route::post('/lich-su-don-hang/{id}/confirm', [ClientOrderController::class, 'confirm'])->name('orders.confirm');
});

// --- Admin ---
Route::prefix('admin')->middleware(['auth', RoleMiddleware::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Product
    Route::resource('products', ProductController::class);

    // Variant attributes
    Route::post('/attributes/{attribute}/values', [AttributeValueController::class, 'storeAjax']);

    // Orders
    Route::resource('orders', OrderController::class)->except(['tracking']);
    Route::get('orders/{id}/tracking', [OrderController::class, 'tracking'])->name('admin.orders.tracking');
    Route::post('/admin/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::post('/admin/orders/{order}/cancel', [OrderController::class, 'cancelOrderByAdmin'])->name('admin.orders.cancel');

    // Categories
    Route::resource('categories', CategoryController::class);
    Route::get('categories/create-parent', [CategoryController::class, 'createParent'])->name('categories.create-parent');
    Route::post('categories/store-parent', [CategoryController::class, 'storeParent'])->name('categories.store-parent');

    // Brands
    Route::resource('brands', BrandController::class);

    // Payment methods
    Route::resource('payment-methods', PaymentMethodController::class);

    // Payments
    Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::post('payments/{id}/confirm', [PaymentController::class, 'confirm'])->name('payments.confirm');
    Route::get('payments/invoice/{id}', [PaymentController::class, 'invoice'])->name('admin.payments.invoice');
    Route::post('admin/payments/{id}/reject', [PaymentController::class, 'reject'])->name('payments.reject');

    // Rates & replies
    Route::resource('rates', RateController::class)->except(['create', 'store']);
    Route::post('/rates/{rate}/replies', [RateReplyController::class, 'store'])->name('rates.replies.store');

    // Contacts
    Route::resource('contacts', ContactController::class)->only(['index', 'show', 'destroy']);

    // Coupons
    Route::resource('coupons', CouponController::class)->except(['show']);
    Route::get('/coupons/create', [CouponController::class, 'create'])->name('coupons.create');
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

    // News & banners
    Route::resource('news', NewsController::class);
    Route::resource('banners', BannerController::class);
    Route::delete('/banners/{id}', [BannerController::class, 'destroy'])->name('banners.destroy');

    // Users
    Route::resource('users', UserController::class);
    Route::post('users/{user}/toggle', [UserController::class, 'toggle'])->name('users.toggle');
    });


// Routes cho "Mua ngay" - cần auth
Route::middleware(['auth'])->group(function () {
    Route::post('/buy-now/{id}', [CartController::class, 'buyNow'])->name('cart.buyNow');
});

// Routes cho mã giảm giá AJAX - cần auth
Route::middleware(['auth'])->group(function () {
    Route::post('/cart/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.apply-coupon');
    Route::post('/cart/remove-coupon', [CartController::class, 'removeCoupon'])->name('cart.remove-coupon');
});

// Routes cho client orders - cần auth
Route::middleware(['auth'])->group(function () {
    Route::get('/orders/{order}', [ClientOrderController::class, 'show'])->name('client.orders.show');
    Route::post('/orders/{order}/cancel', [ClientOrderController::class, 'cancel'])->name('client.orders.cancel');
});

// client
Route::get('/', [HomeController::class, 'index'])->name('home');
//sản phẩm
Route::get('/products', [ClientProductController::class, 'index'])->name('client.products.index');
Route::get('/products/category/{id}', [ClientProductController::class, 'category'])->name('client.products.category');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::get('/products/{id}', [ClientProductController::class, 'show'])->name('client.products.show');
Route::get('/get-stock', [App\Http\Controllers\Client\ProductController::class, 'getStock'])->name('client.products.getStock');
Route::post('/cart/apply-voucher', [CartController::class, 'applyVoucher'])->name('cart.applyVoucher');


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
