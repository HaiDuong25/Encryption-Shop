<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardControler;

route::prefix('admin')->group(function () {
    route::get('/', [DashboardControler::class, 'index'])->name('admin.dashboard');
});
