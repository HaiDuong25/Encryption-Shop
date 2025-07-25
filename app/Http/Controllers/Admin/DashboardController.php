<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
class DashboardController extends \App\Http\Controllers\Controller
{
     public function index()
    {
        // Tổng doanh thu: chỉ tính đơn hoàn thành
        $totalRevenue = Order::where('status', Order::STATUS_COMPLETED)
            ->sum('total_price');

        // Tổng số đơn hàng
        $totalOrders = Order::count();

        // Tổng sản phẩm
        $totalProducts = Product::count();

        // Tổng khách hàng (role = user)
        $totalCustomers = User::where('role', 'user')->count();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalOrders',
            'totalProducts',
            'totalCustomers'
        ));
    }
}
