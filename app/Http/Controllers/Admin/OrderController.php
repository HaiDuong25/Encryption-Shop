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
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
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
            'orderDetails.variant.product.productImages',
            'orderDetails.product.productImages',
            'paymentMethod',
            'coupon',
            'payments',
            'user'
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
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'status' => 'required|string',
            'discount_id' => 'nullable|exists:coupons,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
        ]);
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

    // Hủy đơn hàng (admin có thể hủy bất kỳ đơn hàng nào)
    public function cancel(Order $order)
    {
        try {
            DB::beginTransaction();

            // Trả lại số lượng sản phẩm
            $this->restoreStock($order);

            // Cập nhật trạng thái đơn hàng
            $order->update(['status' => 'cancelled']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Đơn hàng đã được hủy thành công.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi hủy đơn hàng: ' . $e->getMessage()
            ], 500);
        }
    }

    // Xóa đơn hàng (admin có thể xóa bất kỳ đơn hàng nào)
    public function destroy(Order $order)
    {
        try {
            DB::beginTransaction();

            // Trả lại số lượng sản phẩm nếu đơn hàng chưa bị hủy
            if ($order->status !== 'cancelled') {
                $this->restoreStock($order);
            }

            // Xóa các bản ghi liên quan
            $order->orderDetails()->delete();
            $order->payments()->delete();
            
            // Xóa đơn hàng
            $order->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Đơn hàng đã được xóa thành công.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa đơn hàng: ' . $e->getMessage()
            ], 500);
        }
    }

    // Trả lại số lượng sản phẩm khi hủy/xóa đơn hàng
    private function restoreStock(Order $order)
    {
        foreach ($order->orderDetails as $detail) {
            if ($detail->variant_id) {
                // Nếu có variant, cập nhật stock của variant
                $variant = \App\Models\ProductVariant::find($detail->variant_id);
                if ($variant) {
                    $variant->increment('stock', $detail->quantity);
                }
            } else {
                // Nếu không có variant, cập nhật stock của product
                $product = \App\Models\Product::find($detail->product_id);
                if ($product) {
                    $product->increment('stock', $detail->quantity);
                }
            }
        }
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
        $order->status = $request->status;
        $order->save();
        if ($order->status == 'completed' && $order->payments()->count() == 0) {
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

    public function cancelOrderByAdmin(Order $order)
    {
        try {
            // Admin có thể hủy đơn hàng ở bất kỳ trạng thái nào (trừ đã hủy)
            if ($order->status === 'cancelled') {
                return response()->json([
                    'success' => false,
                    'message' => 'Đơn hàng đã được hủy trước đó.'
                ]);
            }

            // Trả lại tồn kho nếu đơn hàng chưa hủy
            $this->restoreStock($order);

            // Cập nhật trạng thái đơn hàng
            $order->update(['status' => 'cancelled']);

            return response()->json([
                'success' => true,
                'message' => 'Đã hủy đơn hàng thành công và trả lại tồn kho.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ]);
        }
    }
}
