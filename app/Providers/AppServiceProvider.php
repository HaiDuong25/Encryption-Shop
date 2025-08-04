<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Observers\OrderObserver;
use App\Observers\OrderDetailObserver;
use App\Http\View\Composers\HeaderComposer;

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
        Order::observe(OrderObserver::class);
        
        // Đăng ký OrderDetailObserver để tự động cập nhật trạng thái đơn hàng
        OrderDetail::observe(OrderDetailObserver::class);
        
        // Đăng ký View Composer cho header để truyền savedCouponsCount
        View::composer('client.layout.partials.header', HeaderComposer::class);
    }
}
