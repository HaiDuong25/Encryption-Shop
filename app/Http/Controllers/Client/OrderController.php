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

    // ✅ Phương thức chi tiết đơn hàng nằm trong class
    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403); // Không cho truy cập nếu không phải chủ đơn hàng
        }

        $order->load(['orderDetails.variant.product', 'paymentMethod']); // load đầy đủ quan hệ

        return view('client.orders.show', compact('order'));
    }
 public function cancel(Request $request, Order $order)
{
    // dd('vao cancel', $order->status, $request->all());

    // Ép kiểu về số nguyên nếu cần
    $status = is_numeric($order->status) ? (int)$order->status : ($order->status === 'pending' ? 0 : $order->status);

    if ($status !== 0) {
        return back()->with('error', 'Không thể hủy đơn hàng này.');
    }

    $order->status = 6; // Đã hủy
    $order->cancel_reason = $request->cancel_reason;
    $order->cancel_note = $request->note;
    $order->save();

    return redirect()->route('client.orders.index')->with('success', 'Đơn hàng đã được hủy thành công.');
}

    /**
     * Xác nhận đã nhận hàng → cập nhật trạng thái thành hoàn thành.
     */
    public function confirm($id)
    {
        $order = Order::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if ($order->status == 4) { // 4 = Đã nhận
            $order->status = 5;    // 5 = Hoàn thành
            $order->save();
            return back()->with('success', 'Đơn hàng đã được xác nhận hoàn thành.');
        }

        return back()->with('error', 'Chỉ xác nhận được đơn hàng ở trạng thái Đã nhận.');
    }
}
