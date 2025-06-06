<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardControler;
use App\Http\Controllers\Admin\PaymentMethodController;
use App\Http\Controllers\Admin\PaymentController;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardControler::class, 'index'])->name('dashboard');

    // CRUD phương thức thanh toán
    Route::resource('payment-methods', PaymentMethodController::class);

    // Quản lý thanh toán (payments)
    Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::post('payments/{id}/confirm', [PaymentController::class, 'confirm'])->name('payments.confirm');

    // ✅ Route xuất hóa đơn PDF sau khi xác nhận
    Route::get('payments/invoice/{id}', [PaymentController::class, 'invoice'])->name('payments.invoice');
});
