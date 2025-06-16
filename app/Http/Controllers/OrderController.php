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
        $users = \App\Models\User::all();
        $coupons = Coupon::all();
        $paymentMethods = PaymentMethod::all();
        $products = \App\Models\Product::all(); // Lấy danh sách sản phẩm

        return view('orders.create', compact('users', 'coupons', 'paymentMethods', 'products'));

        return view('orders.create', compact('users', 'coupons', 'paymentMethods'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'status' => 'required|integer',
            'discount_id' => 'nullable|integer|exists:coupons,id',
            'payment_method_id' => 'required|integer|exists:payment_methods,id',
            // KHÔNG validate total_price nữa
        ]);
        $total = 0;
        foreach ($request->product_ids as $idx => $productId) {
            $product = \App\Models\Product::find($productId);
            $qty = $request->quantities[$idx];
            if ($product) {
                $total += $product->price * $qty;
            }
        }

        // Tạo đơn hàng
        $order = Order::create([
            'user_id' => $request->user()->id,
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'status' => $request->status,
            'payment_method_id' => $request->payment_method_id,
            'total_price' => $total,
        ]);

        // Lưu chi tiết đơn hàng
        foreach ($request->product_ids as $idx => $productId) {
            $product = \App\Models\Product::find($productId);
            $qty = $request->quantities[$idx];
            if ($product) {
                // Tạo chi tiết đơn hàng
                \App\Models\OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'quantity' => $qty,
                    'price' => $product->price,
                    'total_price' => $product->price * $qty, // Laravel tự tính và truyền vào đây
                    'image' => $product->image, // Đảm bảo dòng này có và $product->image có giá trị
                ]);
            }
        }

        return redirect()->route('orders.index')->with('success', 'Tạo đơn hàng thành công!');
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
        'user_id' => 'required|integer|exists:users,id',
        'name' => 'required|string|max:255',
        'phone' => 'required|string|max:20',
        'address' => 'required|string|max:255',
        'status' => 'required|integer',
        'discount_id' => 'nullable|integer|exists:coupons,id',
        'payment_method_id' => 'required|integer|exists:payment_methods,id',
    ]);
    $order->update($validated);
    return redirect()->route('orders.index')->with('success', 'Cập nhật đơn hàng thành công!');
}
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
        ]);

        $order->update([
            'user_id' => $validated['user_id'],
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'total_price' => $validated['total_price'],
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
 $order->history = [
        [
            'date' => '2025-06-10 08:30:00',
            'desc' => 'Đơn hàng đã được xác nhận',
            'location' => 'Kho Hải Dương'
        ],
        [
            'date' => '2025-06-11 14:15:00',
            'desc' => 'Đang giao hàng bởi shipper',
            'location' => 'TP. Hà Nội'
        ]
    ];
        return view('orders.tracking', compact('order', 'locations'));
    }
 public function updateStatus(Request $request, Order $order)
    {
        try {
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
            return response()->json([
                'message' => 'Cập nhật trạng thái thành công!',
                'status' => $order->status,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage(),
            ], 500);
        }
    }

}
