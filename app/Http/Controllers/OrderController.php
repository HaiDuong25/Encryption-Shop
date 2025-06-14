<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Hiển thị danh sách đơn hàng
    public function index()
    {
        $orders = Order::with(['orderDetails.product', 'paymentMethod'])
            ->orderByDesc('created_at')
            ->paginate(10);
        return view('orders.index', compact('orders'));
    }
    // Hiển thị form tạo đơn hàng
    public function create()
    {
        $users = User::all();
        $coupons = Coupon::all();
        $paymentMethods = PaymentMethod::all();
        return view('orders.create', compact('users', 'coupons', 'paymentMethods'));
    }

    // Lưu đơn hàng mới
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'total_price' => 'required|numeric',
            'status' => 'required|integer',
            'discount_id' => 'nullable|integer|exists:coupons,id',
            'payment_method_id' => 'required|integer|exists:payment_methods,id',
        ]);
        Order::create($validated);
        return redirect()->route('orders.index')->with('success', 'Tạo đơn hàng thành công!');
    }

    // Hiển thị chi tiết đơn hàng
    public function show(Order $order)
    {
        // Nạp các quan hệ cần thiết
        $order->load([
            'orderDetails.product',
            'paymentMethod',
            'payments',
            'coupon'
        ]);
        return view('orders.show', compact('order'));
    }
    // Hiển thị form chỉnh sửa đơn hàng
    public function edit(Order $order)
    {
        $users = User::all();
        $coupons = Coupon::all();
        $paymentMethods = PaymentMethod::all();
        return view('orders.edit', compact('order', 'users', 'coupons', 'paymentMethods'));
    }

    // Cập nhật đơn hàng
    public function update(Request $request, Order $order)
{
    $validated = $request->validate([
        'user_id' => 'required|integer|exists:users,id',
        'name' => 'required|string|max:255',
        'phone' => 'required|string|max:20',
        'address' => 'required|string|max:255',
        'total_price' => 'required|numeric',
        'status' => 'required|integer',
        'discount_id' => 'nullable|integer|exists:coupons,id',
        'payment_method_id' => 'required|integer|exists:payment_methods,id',
    ]);
    $order->update($validated);
    return redirect()->route('orders.index')->with('success', 'Cập nhật đơn hàng thành công!');
}

    // Xóa đơn hàng
    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Xóa đơn hàng thành công!');
    }
    public function tracking($id)
{
    $order = Order::with(['payments', 'orderDetails.product'])->findOrFail($id);

    // Có thể giả lập dữ liệu vị trí (hoặc tích hợp API vận chuyển sau)
    $locations = [
        'Chờ xác nhận',
        'Đang xử lý',
        'Đã giao cho đơn vị vận chuyển',
        'Đang giao hàng',
        'Đã nhận hàng',
        'Đơn hàng hoàn thành'
    ];

    return view('orders.tracking', compact('order', 'locations'));
}
public function updateStatus(Request $request, Order $order)
{
    $request->validate([
        'status' => 'required|integer|min:0|max:5'
    ]);

    $order->status = $request->status;
    $order->save();

    return response()->json([
        'message' => 'Cập nhật trạng thái thành công!',
        'status' => $order->status,
    ]);
}

}
