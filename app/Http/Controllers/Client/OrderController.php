<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // Hiển thị danh sách đơn hàng của user hiện tại
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->with('orderDetails.product')
            ->get();
        return view('client.orders.index', compact('orders'));
    }

    /**
     * Hủy đơn hàng (chỉ cho phép user hủy đơn hàng của chính mình)
     */
    public function cancel($id)
    {
        try {
            $order = Order::where('user_id', Auth::id())->findOrFail($id);
            
            if (!$order->canBeCancelled()) {
                $statusLabels = Order::getStatusLabels();
                $currentStatusLabel = $statusLabels[$order->status] ?? $order->status;
                return redirect()->back()->with('error', "Không thể hủy đơn hàng ở trạng thái '{$currentStatusLabel}'. Chỉ có thể hủy khi đơn hàng đang 'Chờ xử lý' hoặc 'Đã xác nhận'.");
            }

            $order->cancelOrder();
            
            return redirect()->back()->with('success', 'Đã hủy đơn hàng thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Xem chi tiết đơn hàng
     */
    public function show($id)
    {
        $order = Order::where('user_id', Auth::id())
            ->with(['orderDetails.product', 'orderDetails.variant', 'paymentMethod'])
            ->findOrFail($id);
        
        return view('client.orders.show', compact('order'));
    }

    // ...existing code...
}
