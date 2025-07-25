<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Coupon;
use App\Http\Controllers\Api\LocationController;

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
use App\Http\Controllers\Client\AccountController;
use App\Http\Controllers\Client\ContactController as ClientContactController;
use App\Http\Controllers\Client\ShippingAddressController as ClientShippingAddressController;
use App\Http\Controllers\Client\NewsController as ClientNewsController;
use App\Http\Controllers\Client\RateController as ClientRateController;
use App\Http\Controllers\Client\ReturnRequestController;
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

// --- Tin tức ---
Route::get('/news', [ClientNewsController::class, 'index'])->name('client.news.index');
Route::get('/news/{id}', [ClientNewsController::class, 'show'])->name('client.news.show');

// --- Liên hệ ---
Route::get('/lien-he', [ClientContactController::class, 'create'])->name('client.contact.create');
Route::post('/lien-he', [ClientContactController::class, 'store'])->name('client.contact.store');

// --- Các chức năng cần đăng nhập ---
Route::middleware(['auth'])->group(function () {
    //Tài khoản người dùng
    Route::get('/account', [AccountController::class, 'index'])->name('account.index');
    Route::get('/account/edit', [AccountController::class, 'editProfile'])->name('account.editProfile');
    Route::post('/account/update', [AccountController::class, 'updateProfile'])->name('account.updateProfile');
    Route::get('/account/change-password', [AccountController::class, 'changePassword'])->name('account.changePassword');
    Route::post('/account/update-password', [AccountController::class, 'updatePassword'])->name('account.updatePassword');
    // Yêu thích
    Route::get('/yeu-thich', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/yeu-thich/add/{id}', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::delete('/yeu-thich/remove/{id}', [WishlistController::class, 'remove'])->name('wishlist.remove');
    // Đánh giá
  Route::post('/rates/{product}/{orderDetail}', [ClientRateController::class, 'store'])->name('client.rates.store');



    // Giỏ hàng
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/update-quantity/{id}', [CartController::class, 'updateQuantityAjax'])->name('cart.updateQuantityAjax');
    Route::post('/cart/update-variant/{id}', [CartController::class, 'updateVariant'])->name('cart.updateVariant');
    Route::post('/cart/switch-variant/{id}', [CartController::class, 'switchVariant'])->name('cart.switchVariant');
    Route::delete('/cart/delete/{id}', [CartController::class, 'delete'])->name('cart.delete');
    Route::post('/cart/delete-selected', [CartController::class, 'deleteSelected'])->name('cart.deleteSelected');

    // Mua ngay
    Route::post('/buy-now/{id}', [CartController::class, 'buyNow'])->name('cart.buyNow');

    // Thanh toán (yêu cầu đăng nhập)
    Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::post('/checkout', [CartController::class, 'processCheckout'])->name('cart.processCheckout');
    Route::post('/clear-checkout-voucher', [CartController::class, 'clearCheckoutVoucher'])->name('cart.clearCheckoutVoucher');
    Route::get('/cart/success/{order_id}', [CartController::class, 'success'])->name('cart.success');

    // Đơn hàng (client)
    Route::get('/orders', [ClientOrderController::class, 'index'])->name('client.orders.index');
    Route::get('/orders/{order}', [ClientOrderController::class, 'show'])->name('client.orders.show');
    Route::post('/orders/{order}/cancel', [ClientOrderController::class, 'cancel'])->name('client.orders.cancel');
    Route::post('/lich-su-don-hang/{id}/confirm', [ClientOrderController::class, 'confirm'])->name('orders.confirm');

    // Địa chỉ giao hàng (client)
    Route::resource('addresses', ClientShippingAddressController::class, [
        'as' => 'client',
        'except' => []
    ]);
});

// --- Admin ---
Route::prefix('admin')->middleware(['auth', RoleMiddleware::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Product
    Route::resource('products', ProductController::class);

    // Variant attributes
    Route::post('/attributes/{attribute}/values', [AttributeValueController::class, 'storeAjax']);

    // Orders
    Route::resource('orders', OrderController::class)->except(['create', 'store']);
    Route::get('orders/{id}/tracking', [OrderController::class, 'tracking'])->name('admin.orders.tracking');
    Route::post('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::post('orders/{order}/cancel', [OrderController::class, 'cancelOrderByAdmin'])->name('admin.orders.cancel');

    // Shipping Addresses
    Route::resource('shipping-addresses', \App\Http\Controllers\Admin\ShippingAddressController::class);
    Route::get('shipping-addresses/user/{user}/addresses', [\App\Http\Controllers\Admin\ShippingAddressController::class, 'userAddresses'])->name('shipping-addresses.user-addresses');
    Route::patch('shipping-addresses/{shippingAddress}/set-default', [\App\Http\Controllers\Admin\ShippingAddressController::class, 'setDefault'])->name('shipping-addresses.set-default');

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
    Route::get('payments/download-invoice/{id}', [PaymentController::class, 'downloadInvoice'])->name('admin.payments.download-invoice');
    Route::get('payments/export-pdf/{id}', [PaymentController::class, 'exportPdf'])->name('admin.payments.export-pdf');
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

    // News & banners
    Route::resource('news', NewsController::class);
    Route::resource('banners', BannerController::class);

    // Users
    Route::resource('users', UserController::class);
    Route::post('users/{user}/toggle', [UserController::class, 'toggle'])->name('users.toggle');

    // Trả hàng (Admin)
Route::get('/returns', [\App\Http\Controllers\Admin\ReturnController::class, 'index'])->name('admin.returns.index');
Route::get('/returns/{id}', [\App\Http\Controllers\Admin\ReturnController::class, 'show'])->name('admin.returns.show');
Route::post('/returns/{id}/update-status', [\App\Http\Controllers\Admin\ReturnController::class, 'updateStatus'])->name('admin.returns.updateStatus');

});


// --- API Routes for Location ---
Route::get('/api/districts', [LocationController::class, 'getDistricts'])->name('api.districts');
Route::get('/api/wards', [LocationController::class, 'getWards'])->name('api.wards');

// --- Client Routes ---
Route::get('/', [HomeController::class, 'index'])->name('home');

// --- Sản phẩm ---
Route::get('/products', [ClientProductController::class, 'index'])->name('client.products.index');
Route::get('/products/category/{id}', [ClientProductController::class, 'category'])->name('client.products.category');
Route::get('/products/{id}', [ClientProductController::class, 'show'])->name('client.products.show');
Route::get('/get-stock', [ClientProductController::class, 'getStock'])->name('client.products.getStock');

// --- Tin tức ---
Route::get('/news', [ClientNewsController::class, 'index'])->name('client.news.index');
Route::get('/news/{id}', [ClientNewsController::class, 'show'])->name('client.news.show');

Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');

// --- Routes cần auth ---
Route::middleware(['auth'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('cart/delete/{id}', [CartController::class, 'delete'])->name('cart.delete');
    Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::post('/checkout', [CartController::class, 'processCheckout'])->name('cart.processCheckout');

    // Mua ngay
    Route::post('/buy-now/{id}', [CartController::class, 'buyNow'])->name('cart.buyNow');

    // Mã giảm giá AJAX
    Route::get('/api/cart/coupons/available', [CartController::class, 'getAvailableCoupons'])->name('cart.coupons.available');
    Route::post('/cart/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.apply-coupon');
    Route::post('/cart/validate-coupon', [CartController::class, 'validateCoupon'])->name('cart.validate-coupon');
    Route::post('/cart/remove-coupon', [CartController::class, 'removeCoupon'])->name('cart.remove-coupon');

    // MoMo Payment routes
    Route::get('/momo/payment', [\App\Http\Controllers\MoMoController::class, 'createPayment'])->name('momo.create');
    Route::get('/momo/return', [\App\Http\Controllers\MoMoController::class, 'returnPayment'])->name('momo.return');
    Route::post('/momo/notify', [\App\Http\Controllers\MoMoController::class, 'notifyPayment'])->name('momo.notify');

    // Đơn hàng (client)
    Route::get('/orders', [ClientOrderController::class, 'index'])->name('client.orders.index');
    Route::get('/orders/{order}', [ClientOrderController::class, 'show'])->name('client.orders.show');
    Route::post('/orders/{order}/cancel', [ClientOrderController::class, 'cancel'])->name('client.orders.cancel');
    Route::post('/lich-su-don-hang/{id}/confirm', [ClientOrderController::class, 'confirm'])->name('orders.confirm');

    // Địa chỉ giao hàng (client)
    Route::resource('addresses', ClientShippingAddressController::class, [
        'as' => 'client',
        'except' => []
    ]);
    Route::patch('addresses/{address}/set-default', [ClientShippingAddressController::class, 'setDefault'])->name('client.addresses.set-default');


        // Trả hàng (client)
    Route::prefix('returns')->name('client.returns.')->group(function () {
        Route::get('/', [ReturnRequestController::class, 'index'])->name('index');
        Route::get('/create', [ReturnRequestController::class, 'create'])->name('create');
        Route::post('/', [ReturnRequestController::class, 'store'])->name('store');
        Route::get('/{id}', [ReturnRequestController::class, 'show'])->name('show');
    });


});
