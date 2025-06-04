<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

route::prefix('admin')->group(function () {
    route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
});
