<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardControler;
use App\Http\Controllers\Admin\RateController as AdminRateController;
use App\Http\Controllers\Admin\RateReplyController as AdminRateReplyController;

route::prefix('admin') ->name('admin.') ->group(function () {
    route::get('/', [DashboardControler::class, 'index'])->name('dashboard');
    Route::get('/rates', [AdminRateController::class, 'index'])->name('rates.index');
    Route::get('/rates/{rate}', [AdminRateController::class, 'show'])->name('rates.show');
    Route::get('/rates/{rate}/edit', [AdminRateController::class, 'edit'])->name('rates.edit');
    Route::put('/rates/{rate}', [AdminRateController::class, 'update'])->name('rates.update');
    Route::delete('/rates/{rate}', [AdminRateController::class, 'destroy'])->name('rates.destroy');

    // Route cho việc lưu phản hồi của Admin cho một Rate
    Route::post('/rates/{rate}/replies', [AdminRateReplyController::class, 'store'])->name('rates.replies.store');
});
