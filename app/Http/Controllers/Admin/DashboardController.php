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
    const STATUS_COMPLETED = 'completed';

    public function index()
    {
        // Chỉ tính doanh thu từ đơn hoàn thành
        $totalRevenue = Order::where('status', self::STATUS_COMPLETED)->sum('total_price');
        // Đếm tất cả đơn hàng
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalCustomers = User::where('role', 'user')->count();

        // Dữ liệu doanh thu cho biểu đồ (chỉ từ đơn hoàn thành)
        $now = now();
        $months = [];
        $revenues = [];

        for ($i = 1; $i <= 12; $i++) {
            $months[] = 'Tháng ' . $i;
            $revenues[] = (int)Order::whereMonth('created_at', $i)
                ->whereYear('created_at', $now->year)
                ->where('status', self::STATUS_COMPLETED)
                ->sum('total_price');
        }

        $bestSellingProducts = $this->getBestSellingProducts();
        // Hiện tất cả đơn hàng gần đây
        $recentOrders = Order::with('user', 'payments')
            ->orderByDesc('created_at')
            ->take(4)
            ->get();
        $transactions = $this->getTransactions();

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

    public function filter(Request $request)
    {
        $type = $request->input('type', 'day');
        $now = Carbon::now();

        // Xác định khoảng thời gian và labels
        $start = null;
        $end = null;
        $labels = [];
        $revenues = [];

        switch ($type) {
            case 'day':
                $start = $now->copy()->startOfDay();
                $end = $now->copy()->endOfDay();
                for ($i = 0; $i < 24; $i++) {
                    $hour = $start->copy()->addHours($i);
                    $labels[] = $hour->format('H:i');
                    $revenues[] = (int)Order::where('status', self::STATUS_COMPLETED)
                        ->whereBetween('created_at', [
                            $hour,
                            $hour->copy()->addHour()
                        ])
                        ->sum('total_price');
                }
                break;

            case 'week':
                $start = $now->copy()->startOfWeek();
                $end = $now->copy()->endOfWeek();
                $days = ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'];
                for ($i = 0; $i < 7; $i++) {
                    $day = $start->copy()->addDays($i);
                    $labels[] = $days[$day->dayOfWeek];
                    $revenues[] = (int)Order::where('status', self::STATUS_COMPLETED)
                        ->whereDate('created_at', $day)
                        ->sum('total_price');
                }
                break;

            case 'month':
                $start = $now->copy()->startOfMonth();
                $end = $now->copy()->endOfMonth();
                $daysInMonth = $now->daysInMonth;
                for ($i = 1; $i <= $daysInMonth; $i++) {
                    $labels[] = $i;
                    $revenues[] = (int)Order::where('status', self::STATUS_COMPLETED)
                        ->whereDay('created_at', $i)
                        ->whereMonth('created_at', $now->month)
                        ->whereYear('created_at', $now->year)
                        ->sum('total_price');
                }
                break;

            case 'year':
                $start = $now->copy()->startOfYear();
                $end = $now->copy()->endOfYear();
                for ($i = 1; $i <= 12; $i++) {
                    $labels[] = 'Tháng ' . $i;
                    $revenues[] = (int)Order::where('status', self::STATUS_COMPLETED)
                        ->whereMonth('created_at', $i)
                        ->whereYear('created_at', $now->year)
                        ->sum('total_price');
                }
                break;
        }

        // Lấy thống kê tổng quan trong khoảng thời gian
        // Doanh thu chỉ từ đơn hoàn thành
        $totalRevenue = Order::where('status', self::STATUS_COMPLETED)
            ->whereBetween('created_at', [$start, $end])
            ->sum('total_price');

        // Tổng đơn hàng bao gồm tất cả trạng thái
        $totalOrders = Order::whereBetween('created_at', [$start, $end])->count();
        $totalProducts = Product::count();
        $totalCustomers = User::where('role', 'user')->count();

        // Lấy dữ liệu chi tiết
        $bestSellingProducts = $this->getBestSellingProducts($start, $end);

        // Hiển thị tất cả đơn hàng gần đây trong khoảng thời gian
        $recentOrders = Order::with('user', 'payments')
            ->whereBetween('created_at', [$start, $end])
            ->orderByDesc('created_at')
            ->take(4)
            ->get();

        $transactions = $this->getTransactions($start, $end);

        return response()->json([
            'totalRevenue' => number_format($totalRevenue) . ' đ',
            'totalOrders' => $totalOrders,
            'totalProducts' => $totalProducts,
            'totalCustomers' => $totalCustomers,
            'labels' => $labels,
            'revenues' => $revenues,
            'bestSellingHtml' => view('admin.partials.dashboard_best_selling', ['bestSellingProducts' => $bestSellingProducts])->render(),
            'recentOrdersHtml' => view('admin.partials.dashboard_recent_orders', ['recentOrders' => $recentOrders])->render(),
            'transactionsHtml' => view('admin.partials.dashboard_transactions', ['transactions' => $transactions])->render(),
        ]);
    }

    private function getBestSellingProducts($start = null, $end = null)
    {
        $query = Product::select('products.*')
            ->withCount(['orderDetails as total_orders' => function ($query) use ($start, $end) {
                $query->select(\DB::raw("SUM(quantity)"))
                    ->whereHas('order', function($q) {
                        $q->where('status', self::STATUS_COMPLETED);
                    });
                if ($start && $end) {
                    $query->whereBetween('order_details.created_at', [$start, $end]);
                }
            }])
            ->orderByDesc('total_orders')
            ->take(4);

        return $query->get();
    }

    private function getTransactions($start = null, $end = null)
    {
        $query = Payment::select('payment_method_id', \DB::raw('SUM(amount) as total_amount'))
            ->with('paymentMethod')
            ->where('status', 'completed');

        if ($start && $end) {
            $query->whereBetween('created_at', [$start, $end]);
        }
        return $query->groupBy('payment_method_id')->get();
    }
}
