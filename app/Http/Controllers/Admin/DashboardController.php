<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
 use App\Models\OrderDetail;
 use App\Models\Payment;
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

    $bestSellingProducts = Product::select('products.*')
        ->withCount(['orderDetails as total_orders' => function ($query) {
            $query->select(\DB::raw("SUM(quantity)"));
        }])
        ->orderByDesc('total_orders')
        ->take(4)
        ->get();

    $recentOrders = Order::with('user','payments')
    ->orderByDesc('created_at')
    ->take(4)
    ->get();

   $transactions = Payment::select('payment_method_id', \DB::raw('SUM(amount) as total_amount'))
    ->with('paymentMethod')
    ->groupBy('payment_method_id')
    ->get();


    return view('admin.dashboard', compact(
        'totalRevenue',
        'totalOrders',
        'totalProducts',
        'totalCustomers',
        'months',
        'revenues',
        'bestSellingProducts',
        'recentOrders',
        'transactions'

    ));
}
}
