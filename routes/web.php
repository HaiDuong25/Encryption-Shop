<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardControler;
use App\Http\Controllers\Admin\RateController as AdminRateController;

route::prefix('admin') ->name('admin.') ->group(function () {
    route::get('/', [DashboardControler::class, 'index'])->name('dashboard');
    Route::get('/rates', [AdminRateController::class, 'index'])->name('rates.index');
    Route::get('/rates/{rate}', [AdminRateController::class, 'show'])->name('rates.show');
    Route::get('/rates/{rate}/edit', [AdminRateController::class, 'edit'])->name('rates.edit');
    Route::put('/rates/{rate}', [AdminRateController::class, 'update'])->name('rates.update');
    Route::delete('/rates/{rate}', [AdminRateController::class, 'destroy'])->name('rates.destroy');
});
