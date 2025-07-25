<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
class DashboardController extends \App\Http\Controllers\Controller
{
  public function index()
{
    $totalRevenue = Order::where('status', Order::STATUS_COMPLETED)->sum('total_price');
    $totalOrders = Order::count();
    $totalProducts = Product::count();
    $totalCustomers = User::where('role', 'user')->count();

    // Dữ liệu cho biểu đồ
    $months = [];
    $revenues = [];

    for ($i = 1; $i <= 12; $i++) {
        $months[] = Carbon::create()->month($i)->format('M');
        $revenues[] = Order::whereMonth('created_at', $i)
            ->whereYear('created_at', now()->year)
            ->where('status', Order::STATUS_COMPLETED)
            ->sum('total_price');

    }


    return view('admin.dashboard', compact(
        'totalRevenue',
        'totalOrders',
        'totalProducts',
        'totalCustomers',
        'months',
        'revenues'
    ));
}
}
