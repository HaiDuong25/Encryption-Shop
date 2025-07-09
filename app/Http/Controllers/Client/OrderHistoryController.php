<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class OrderHistoryController extends Controller
{
    /**
     * Hiển thị danh sách đơn hàng của người dùng hiện tại.
     */
    public function index()
    {
        $orders = Order::with(['orderDetails.variant.product', 'paymentMethod', 'coupon'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('client.orders.history', compact('orders'));
    }

    /**
     * Hiển thị chi tiết một đơn hàng cụ thể.
     */
    public function show($id)
    {
        $order = Order::with(['orderDetails.variant.product', 'paymentMethod', 'coupon'])
            ->findOrFail($id);

        if ($order->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền xem đơn hàng này.');
        }

        return view('client.orders.show', compact('order'));
    }

    /**
     * Huỷ đơn hàng khi đang chờ xác nhận.
     */
    public function cancel(Request $request, $id)
    {
        $request->validate([
            'cancel_reason' => 'required|string|max:255'
        ]);

        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($order->status !== 0) {
            return back()->with('error', 'Chỉ được huỷ đơn hàng khi đang chờ xác nhận.');
        }

        $order->status = 6; // 6: Đã huỷ
        $order->cancel_reason = $request->cancel_reason;
        $order->save();

        return redirect()->route('orders.history')->with('success', 'Đã huỷ đơn hàng thành công.');
    }

    /**
     * Xác nhận đã nhận hàng → cập nhật trạng thái thành hoàn thành.
     */
    public function confirm($id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($order->status !== 4) {
            return back()->with('error', 'Chỉ có thể xác nhận khi đơn hàng ở trạng thái "Đã nhận".');
        }

        $order->status = 5; // 5: Hoàn thành
        $order->save();

        return back()->with('success', 'Đã xác nhận hoàn tất đơn hàng. Cảm ơn bạn!');
    }
}
