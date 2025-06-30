<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\Coupon;
use App\Models\Payment;
use App\Models\OrderDetail;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['orderDetails.variant.product', 'paymentMethod'])
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $users = User::all();
        $coupons = Coupon::all();
        $paymentMethods = PaymentMethod::all();

        return view('orders.create', compact('users', 'coupons', 'paymentMethods'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'total_price' => 'required|numeric|min:0',
            'status' => 'required|integer|min:0|max:5',
            'discount_id' => 'nullable|exists:coupons,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id' => $validated['user_id'],
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'total_price' => $validated['total_price'],
                'status' => $validated['status'],
                'coupon_id' => $validated['discount_id'] ?? null,
                'payment_method_id' => $validated['payment_method_id'],
            ]);

            foreach ($validated['products'] as $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }

            DB::commit();
            return redirect()->route('orders.index')->with('success', 'Đơn hàng đã được tạo!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
        }
    }

    public function show(Order $order)
    {
        $order->load([
            'orderDetails.variant.product',
            'paymentMethod',
            'coupon',
            'payments'
        ]);

        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $users = User::all();
        $coupons = Coupon::all();
        $paymentMethods = PaymentMethod::all();

        return view('orders.edit', compact('order', 'users', 'coupons', 'paymentMethods'));
    }

 public function update(Request $request, Order $order)
{
    $validated = $request->validate([
        'user_id' => 'required|exists:users,id',
        'name' => 'required|string|max:255',
        'phone' => 'required|string|max:20',
        'address' => 'required|string|max:255',
        'status' => 'required|integer|min:0|max:5',
        'discount_id' => 'nullable|exists:coupons,id',
        'payment_method_id' => 'required|exists:payment_methods,id',
    ]);

    // 🛑 Không cho phép cập nhật status lùi lại
    if ($validated['status'] < $order->status) {
        return back()->withErrors(['status' => 'Không thể cập nhật trạng thái lùi về trạng thái trước đó.']);
    }

    $order->update([
        'user_id' => $validated['user_id'],
        'name' => $validated['name'],
        'phone' => $validated['phone'],
        'address' => $validated['address'],
        'status' => $validated['status'],
        'coupon_id' => $validated['discount_id'] ?? null,
        'payment_method_id' => $validated['payment_method_id'],
    ]);

    return redirect()->route('orders.index')->with('success', 'Cập nhật đơn hàng thành công!');
}

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Đã xóa đơn hàng!');
    }

    public function tracking($id)
    {
        $order = Order::with(['payments', 'orderDetails.variant.product'])->findOrFail($id);

        $locations = [
            'Chờ xác nhận',
            'Đang xử lý',
            'Đã giao cho đơn vị vận chuyển',
            'Đang giao hàng',
            'Đã nhận hàng',
            'Đơn hàng hoàn thành',
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

    if ($order->status == 1 && $order->payments()->count() == 0) {
        Payment::create([
            'order_id' => $order->id,
            'amount' => $order->total_price,
            'note' => 'Thanh toán khi xác nhận',
        ]);
    }

    return response()->json([
        'message' => 'Cập nhật trạng thái thành công!',
        'status' => $order->status,
    ]);
}

}
