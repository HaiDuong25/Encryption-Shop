<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Đăng ký OrderObserver để xử lý logic payment khi order thay đổi trạng thái
        \App\Models\Order::observe(\App\Observers\OrderObserver::class);
    }
}
