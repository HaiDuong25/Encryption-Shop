<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\User;
use App\Models\Coupon;
use App\Models\Payment;
use App\Models\OrderDetail;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        $orders = Order::with([
            'orderDetails.variant.product.productImages', 
            'orderDetails.product.productImages',
            'paymentMethod'
        ])
            ->latest()
            ->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    public function create()
    {
        $users = User::all();
        $coupons = Coupon::all();
        $paymentMethods = PaymentMethod::all();

        return view('admin.orders.create', compact('users', 'coupons', 'paymentMethods'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'orderer_name' => 'nullable|string|max:255',
            'orderer_phone' => 'nullable|string|max:20', 
            'orderer_email' => 'nullable|email|max:255',
            'recipient_name' => 'required|string|max:255',
            'recipient_phone' => 'required|string|max:20',
            'recipient_email' => 'nullable|email|max:255',
            'recipient_address' => 'required|string',
            'order_notes' => 'nullable|string',
            'total_price' => 'required|numeric|min:0',
            'status' => 'required|string|in:pending,confirmed,shipping,delivering,received,completed',
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
                'orderer_name' => $validated['orderer_name'],
                'orderer_phone' => $validated['orderer_phone'],
                'orderer_email' => $validated['orderer_email'],
                'recipient_name' => $validated['recipient_name'],
                'recipient_phone' => $validated['recipient_phone'],
                'recipient_email' => $validated['recipient_email'],
                'recipient_address' => $validated['recipient_address'],
                'order_notes' => $validated['order_notes'],
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
            'orderDetails.variant.product.productImages',
            'orderDetails.product.productImages',
            'paymentMethod',
            'coupon',
            'payments'
        ]);
        return view('admin.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $users = User::all();
        $coupons = Coupon::all();
        $paymentMethods = PaymentMethod::all();
        return view('admin.orders.edit', compact('order', 'users', 'coupons', 'paymentMethods'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'orderer_name' => 'nullable|string|max:255',
            'orderer_phone' => 'nullable|string|max:20', 
            'orderer_email' => 'nullable|email|max:255',
            'recipient_name' => 'required|string|max:255',
            'recipient_phone' => 'required|string|max:20',
            'recipient_email' => 'nullable|email|max:255',
            'recipient_address' => 'required|string',
            'order_notes' => 'nullable|string',
            'status' => 'required|string',
            'discount_id' => 'nullable|exists:coupons,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
        ]);
        
        $order->update([
            'user_id' => $validated['user_id'],
            'orderer_name' => $validated['orderer_name'],
            'orderer_phone' => $validated['orderer_phone'],
            'orderer_email' => $validated['orderer_email'],
            'recipient_name' => $validated['recipient_name'],
            'recipient_phone' => $validated['recipient_phone'],
            'recipient_email' => $validated['recipient_email'],
            'recipient_address' => $validated['recipient_address'],
            'order_notes' => $validated['order_notes'],
            'status' => $validated['status'],
            'coupon_id' => $validated['discount_id'] ?? null,
            'payment_method_id' => $validated['payment_method_id'],
        ]);
        
        return redirect()->route('orders.index')->with('success', 'Cập nhật đơn hàng thành công!');
    }

    public function destroy(Order $order)
    {
        // Observer sẽ tự động trả lại tồn kho khi xóa
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Đã xóa đơn hàng và trả lại tồn kho thành công!');
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
        return view('admin.orders.tracking', compact('order', 'locations'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string'
        ]);
        
        $oldStatus = $order->status;
        $newStatus = $request->status;
        
        // Xử lý đặc biệt khi admin chuyển sang trạng thái cancelled
        if ($newStatus === Order::STATUS_CANCELLED && $oldStatus !== Order::STATUS_CANCELLED) {
            // Admin có thể hủy ở bất kỳ trạng thái nào
            $order->restoreStock();
        }
        
        $order->status = $newStatus;
        $order->save();
        
        if ($order->status == 'completed' && $order->payments()->count() == 0) {
            Payment::create([
                'order_id' => $order->id,
                'amount' => $order->total_price,
                'note' => 'Thanh toán khi xác nhận',
            ]);
        }
        
        $message = 'Cập nhật trạng thái thành công!';
        if ($newStatus === Order::STATUS_CANCELLED && $oldStatus !== Order::STATUS_CANCELLED) {
            $message .= ' Đã trả lại tồn kho.';
        }
        
        return response()->json([
            'message' => $message,
            'status' => $order->status,
        ]);
    }

    /**
     * Hủy đơn hàng và trả lại tồn kho (Admin có thể hủy ở mọi trạng thái)
     */
    public function cancel(Order $order)
    {
        try {
            if (!$order->canBeCancelledByAdmin()) {
                return redirect()->back()->with('error', 'Đơn hàng đã được hủy trước đó.');
            }

            $order->cancelOrderByAdmin();
            
            return redirect()->back()->with('success', 'Đã hủy đơn hàng và trả lại tồn kho thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}
