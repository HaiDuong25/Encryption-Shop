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
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Middleware\RoleMiddleware;

// CLIENT CONTROLLERS
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\ProductController as ClientProductController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\OrderController as ClientOrderController;
use App\Http\Controllers\Client\WishlistController;
use App\Http\Controllers\Client\AccountController;
use App\Http\Controllers\Client\ContactController as ClientContactController;
use App\Http\Controllers\Client\ShippingAddressController as ClientShippingAddressController;
use App\Http\Controllers\Client\NewsController as ClientNewsController;
use App\Http\Controllers\Client\RateController as ClientRateController;
use App\Http\Controllers\Client\ReturnRequestController;
use App\Http\Controllers\Client\CategoryClientController;
use App\Http\Controllers\Client\CouponController as ClientCouponController;
use App\Http\Controllers\ZaloPayController;
// Auth password reset controllers
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
// --- Auth ---
Route::view('/auth', 'auth.auth')->name('auth');
Route::get('/login', [AuthController::class, 'index'])->name('login.form');
Route::get('/register', function () {
    return view('auth.register');
})->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// --- Password reset ---
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

// --- Trang chính ---
Route::get('/', [HomeController::class, 'index'])->middleware('unset.coupon.checkout')->name('home');

// --- Sản phẩm ---
Route::middleware('unset.coupon.checkout')->group(function () {
    Route::get('/products', [ClientProductController::class, 'index'])->name('client.products.index');
    Route::get('/products/category/{id}', [ClientProductController::class, 'category'])->name('client.products.category');
    Route::get('/products/{id}', [ClientProductController::class, 'show'])->name('client.products.show');
    Route::get('/get-stock', [ClientProductController::class, 'getStock'])->name('client.products.getStock');
    Route::get('/api/search-products', [ClientProductController::class, 'searchProducts'])->name('client.products.search');
});

// --- Tin tức ---
Route::middleware('unset.coupon.checkout')->group(function () {
    Route::get('/news', [ClientNewsController::class, 'index'])->name('client.news.index');
    Route::get('/news/{id}', [ClientNewsController::class, 'show'])->name('client.news.show');
});

// --- Liên hệ ---
Route::get('/lien-he', [ClientContactController::class, 'create'])->name('client.contact.create');
Route::post('/lien-he', [ClientContactController::class, 'store'])->name('client.contact.store');
Route::post('news/{id}/comment', [App\Http\Controllers\Client\NewsCommentController::class, 'store'])->name('client.news.comment');

// --- Các chức năng cần đăng nhập ---
Route::middleware(['auth', 'unset.coupon.checkout'])->group(function () {
// Đăng ký middleware unset.coupon.checkout
app('router')->aliasMiddleware('unset.coupon.checkout', \App\Http\Middleware\UnsetCouponOnCheckoutExit::class);
    //Tài khoản người dùng
    Route::get('/account', [AccountController::class, 'index'])->name('account.index');
    Route::get('/account/edit', [AccountController::class, 'editProfile'])->name('account.editProfile');
    Route::post('/account/update', [AccountController::class, 'updateProfile'])->name('account.updateProfile');
    Route::get('/account/change-password', [AccountController::class, 'changePassword'])->name('account.changePassword');
    Route::post('/account/update-password', [AccountController::class, 'updatePassword'])->name('account.updatePassword');
    Route::post('/account/upload-avatar', [AccountController::class, 'uploadAvatar'])->name('account.uploadAvatar');
    Route::post('/account/upload-cover-image', [AccountController::class, 'uploadCoverImage'])->name('account.uploadCoverImage');
    // Yêu thích
    Route::get('/yeu-thich', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/yeu-thich/add/{id}', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::delete('/yeu-thich/remove/{id}', [WishlistController::class, 'remove'])->name('wishlist.remove');
    // Đánh giá (tạo mới) – giới hạn tham số phải là số để tránh xung đột với /rates/{rate}/report
    Route::post('/rates/{product}/{orderDetail}', [ClientRateController::class, 'store'])
        ->whereNumber('product')
        ->whereNumber('orderDetail')
        ->name('client.rates.store');
    // Rate actions (like, dislike)
    Route::post('/rates/{rate}/action', [App\Http\Controllers\Client\RateActionController::class, 'act'])
        ->whereNumber('rate')
        ->name('client.rates.action');
    // Báo cáo đánh giá (report chi tiết)
    Route::post('/rates/{rate}/report', [App\Http\Controllers\Client\RateReportController::class, 'store'])
        ->whereNumber('rate')
        ->name('client.rates.report');



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
Route::get('/admin', function () {
    return redirect()->route('admin.dashboard');
});
Route::prefix('admin')->middleware(['auth', RoleMiddleware::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::post('/dashboard/filter', [DashboardController::class, 'filter'])->name('admin.dashboard.filter');

    // Product
    Route::resource('products', ProductController::class);

    // Variant attributes
    Route::post('/attributes/{attribute}/values', [AttributeValueController::class, 'storeAjax']);

    // Orders
    Route::resource('orders', OrderController::class)->except(['create', 'store', 'edit']);
    Route::get('orders/{id}/tracking', [OrderController::class, 'tracking'])->name('admin.orders.tracking');
    Route::post('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::post('orders/{order}/cancel', [OrderController::class, 'cancel'])->name('admin.orders.cancel');
    Route::post('orders/{order}/cancel-by-admin', [OrderController::class, 'cancelOrderByAdmin'])->name('admin.orders.cancel-by-admin');

    // Shipping Addresses (Read-only for admin)
    Route::get('shipping-addresses', [\App\Http\Controllers\Admin\ShippingAddressController::class, 'index'])->name('shipping-addresses.index');
    Route::get('shipping-addresses/{shippingAddress}', [\App\Http\Controllers\Admin\ShippingAddressController::class, 'show'])->name('shipping-addresses.show');
    Route::get('shipping-addresses/user/{user}/addresses', [\App\Http\Controllers\Admin\ShippingAddressController::class, 'userAddresses'])->name('shipping-addresses.user-addresses');

    // Categories
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('categories/parent/create', [CategoryController::class, 'createParent'])->name('categories.create-parent');
        Route::post('categories/parent/store', [CategoryController::class, 'storeParent'])->name('categories.store-parent');

        Route::resource('categories', CategoryController::class);
    });

    // Brands
    Route::resource('brands', BrandController::class);

    // Payment methods
    Route::resource('payment-methods', PaymentMethodController::class);

    // Rates & replies
    Route::resource('rates', RateController::class)->except(['create', 'store']);
    Route::post('/rates/{rate}/replies', [RateReplyController::class, 'store'])->name('rates.replies.store');
    // Rate reports management
    Route::get('/rates/{rate}/reports', [\App\Http\Controllers\Admin\RateReportController::class, 'index']);
    Route::patch('/rate-reports/{rateReport}', [\App\Http\Controllers\Admin\RateReportController::class, 'update']);
    Route::get('/rate-reports', [\App\Http\Controllers\Admin\RateReportController::class, 'manage'])->name('admin.rate-reports.index');

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

    // Wallet Transactions (Admin)
    Route::prefix('wallet-transactions')->name('admin.wallet-transactions.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\WalletTransactionController::class, 'index'])->name('index');
        Route::get('/{id}', [\App\Http\Controllers\Admin\WalletTransactionController::class, 'show'])->name('show');
        Route::delete('/{id}', [\App\Http\Controllers\Admin\WalletTransactionController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/update-status', [\App\Http\Controllers\Admin\WalletTransactionController::class, 'updateStatus'])->name('update-status');

    });
Route::prefix('withdraw-requests')->name('admin.withdraw.')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\WithdrawRequestController::class, 'index'])->name('index');
    Route::post('/{id}/approve', [App\Http\Controllers\Admin\WithdrawRequestController::class, 'approve'])->name('approve');
    Route::post('/{id}/reject', [App\Http\Controllers\Admin\WithdrawRequestController::class, 'reject'])->name('reject');
});


});


// --- API Routes for Location (2-level: Province → Ward) ---
Route::get('/api/provinces', [LocationController::class, 'getProvinces'])->name('api.provinces');
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

//danh mục client
Route::get('categories', [CategoryClientController::class, 'index'])->name('categories.index');
Route::get('category/{id}', [CategoryClientController::class, 'show'])->name('categories.show');

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
    Route::post('/cart/remove-used-coupon', [CartController::class, 'removeUsedCoupon'])->name('cart.remove-used-coupon');

    // MoMo Payment routes
    Route::get('/momo/payment', [\App\Http\Controllers\MoMoController::class, 'createPayment'])->name('momo.create');
    Route::get('/momo/return', [\App\Http\Controllers\MoMoController::class, 'returnPayment'])->name('momo.return');
    Route::post('/momo/notify', [\App\Http\Controllers\MoMoController::class, 'notifyPayment'])->name('momo.notify');

    //zaro payment routes
    Route::get('/zalopay', [ZaloPayController::class, 'createPayment'])->name('zalopay.pay');
    Route::get('/zalopay/return', [ZaloPayController::class, 'returnPayment'])->name('zalopay.return');
    Route::post('/zalopay/callback', [ZaloPayController::class, 'callback'])->name('zalopay.callback');

    // Wallet routes (Ví điện tử)
    Route::prefix('wallet')->group(function () {
        Route::get('/', [\App\Http\Controllers\Client\WalletController::class, 'index'])->name('wallet.index');
        Route::get('/topup', [\App\Http\Controllers\Client\WalletController::class, 'topup'])->name('wallet.topup');
        Route::post('/topup', [\App\Http\Controllers\Client\WalletController::class, 'processTopup'])->name('wallet.process-topup');
        Route::get('/topup/success', [\App\Http\Controllers\Client\WalletController::class, 'topupSuccess'])->name('wallet.topup.success');
        Route::get('/topup/cancel', [\App\Http\Controllers\Client\WalletController::class, 'topupCancel'])->name('wallet.topup.cancel');
        Route::get('/history', [\App\Http\Controllers\Client\WalletController::class, 'history'])->name('wallet.history');
        Route::get('/payment-history', [\App\Http\Controllers\Client\WalletController::class, 'paymentHistory'])->name('wallet.payment-history');
    Route::middleware('wallet.pin')->group(function(){
        Route::get('/withdraw', [\App\Http\Controllers\Client\WalletController::class, 'withdrawForm'])->name('wallet.withdraw');
        Route::post('/withdraw', [\App\Http\Controllers\Client\WalletController::class, 'withdraw'])->name('wallet.withdraw.store');
    });
Route::delete('/bank/{id}', [\App\Http\Controllers\Client\WalletController::class, 'destroy'])->name('wallet.bank.destroy');

        // MoMo wallet topup
        Route::get('/momo/create', [\App\Http\Controllers\Client\WalletMomoController::class, 'createPayment'])->name('wallet.momo.create');
        Route::get('/momo/return', [\App\Http\Controllers\Client\WalletMomoController::class, 'returnPayment'])->name('wallet.momo.return');
        Route::post('/momo/notify', [\App\Http\Controllers\Client\WalletMomoController::class, 'notifyPayment'])->name('wallet.momo.notify');

        // ZaloPay wallet topup
        Route::get('/zalopay/create', [\App\Http\Controllers\Client\WalletZalopayController::class, 'createPayment'])->name('wallet.zalopay.create');
        Route::get('/zalopay/return', [\App\Http\Controllers\Client\WalletZalopayController::class, 'returnPayment'])->name('wallet.zalopay.return');
        Route::post('/zalopay/callback', [\App\Http\Controllers\Client\WalletZalopayController::class, 'notifyPayment'])->name('wallet.zalopay.notify');
        Route::post('/zalopay/process-manual-return', [\App\Http\Controllers\Client\WalletZalopayController::class, 'processManualReturn'])->name('wallet.zalopay.process-manual-return');

    // Wallet PIN routes
    Route::get('/pin/setup', [\App\Http\Controllers\Client\WalletPinController::class, 'showSetupForm'])->name('wallet.pin.setup');
    Route::post('/pin/setup', [\App\Http\Controllers\Client\WalletPinController::class, 'store'])->name('wallet.pin.store');
    Route::post('/pin/verify', [\App\Http\Controllers\Client\WalletPinController::class, 'verify'])->name('wallet.pin.verify');
    Route::post('/pin/change', [\App\Http\Controllers\Client\WalletPinController::class, 'change'])->name('wallet.pin.change');
    Route::get('/pin/forgot', [\App\Http\Controllers\Client\WalletPinController::class, 'showForgotForm'])->name('wallet.pin.forgot');
    Route::post('/pin/forgot', [\App\Http\Controllers\Client\WalletPinController::class, 'resetForgot'])->name('wallet.pin.reset');
    });

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


// --- Coupon Routes ---
Route::get('/coupons', [ClientCouponController::class, 'index'])->name('client.coupons.index');
Route::get('/my-coupons', [ClientCouponController::class, 'myCoupons'])->name('my-coupons');
Route::get('/coupons/{id}', [ClientCouponController::class, 'show'])->name('client.coupons.show');

// AJAX API for coupon saving/removing (requires authentication)
Route::middleware(['auth'])->group(function () {
    Route::post('/coupons/save', [ClientCouponController::class, 'saveCoupon'])->name('client.coupons.save');
    Route::post('/coupons/remove', [ClientCouponController::class, 'removeCoupon'])->name('client.coupons.remove');
    Route::post('/coupons/remove-used', [ClientCouponController::class, 'removeUsedCoupon'])->name('client.coupons.remove-used');
    Route::post('/coupons/restore', [ClientCouponController::class, 'restoreSavedCoupon'])->name('client.coupons.restore');
    Route::get('/api/saved-coupons', [ClientCouponController::class, 'getSavedCoupons'])->name('client.coupons.api.saved');
});

// routes/web.php

// Add this route for coupons page
Route::get('/api/locations', [LocationController::class, 'index'])->name('api.locations');
