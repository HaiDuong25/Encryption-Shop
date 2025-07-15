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
use Illuminate\Support\Facades\Log;

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
        $order->load(['coupon']); // Load relationship với bảng coupons
        $users = User::all();
        $coupons = Coupon::all();
        $paymentMethods = PaymentMethod::all();
        return view('admin.orders.edit', compact('order', 'users', 'coupons', 'paymentMethods'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'orderer_name' => 'required|string|max:255',
            'orderer_phone' => 'required|string|regex:/^[0-9]{10,11}$/',
            'orderer_address' => 'required|string|max:255',
            'recipient_name' => 'required|string|max:255',
            'recipient_phone' => 'required|string|regex:/^[0-9]{10,11}$/',
            'recipient_address' => 'required|string|max:255',
            'status' => 'required|string|in:pending,confirmed,shipping,delivering,received,completed,cancelled',
            'cancel_reason' => 'nullable|string|max:255',
            'cancel_note' => 'nullable|string',
            'discount_id' => 'nullable|exists:coupons,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            // Thêm validation cho order details
            'order_detail_ids' => 'nullable|array',
            'order_detail_ids.*' => 'exists:order_details,id',
            'quantities' => 'nullable|array',
            'quantities.*' => 'integer|min:1',
            'product_ids' => 'nullable|array',
            'variant_ids' => 'nullable|array',
            'prices' => 'nullable|array',
        ]);

        try {
            DB::beginTransaction();

            $oldStatus = $order->getOriginal('status');
            // Cập nhật thông tin đơn hàng
            $order->update([
                'user_id' => $validated['user_id'],
                'orderer_name' => $validated['orderer_name'],
                'orderer_phone' => $validated['orderer_phone'],
                'orderer_address' => $validated['orderer_address'],
                'recipient_name' => $validated['recipient_name'],
                'recipient_phone' => $validated['recipient_phone'],
                'recipient_address' => $validated['recipient_address'],
                'status' => $validated['status'],
                'cancel_reason' => $validated['cancel_reason'] ?? null,
                'cancel_note' => $validated['cancel_note'] ?? null,
                'discount_id' => $validated['discount_id'] ?? null,
                'payment_method_id' => $validated['payment_method_id'],
            ]);

            // Xử lý logic thanh toán và hóa đơn khi chuyển trạng thái
            if ($oldStatus !== $validated['status']) {
                $this->handlePaymentAndInvoiceLogic($order, $oldStatus, $validated['status']);
                
                $order->statusHistories()->create([
                    'old_status' => $oldStatus,
                    'new_status' => $validated['status'],
                    'description' => $request->input('note') ?? null,
                    'changed_by' => auth()?->id(),
                ]);
            }

            // Cập nhật số lượng order details nếu có
            if (isset($validated['order_detail_ids']) && isset($validated['quantities'])) {
                foreach ($validated['order_detail_ids'] as $index => $detailId) {
                    if (isset($validated['quantities'][$index])) {
                        $orderDetail = OrderDetail::find($detailId);
                        if ($orderDetail && $orderDetail->order_id == $order->id) {
                            $orderDetail->update([
                                'quantity' => $validated['quantities'][$index]
                            ]);
                        }
                    }
                }

                // Tính lại tổng tiền
                $totalPrice = 0;
                foreach ($order->orderDetails as $detail) {
                    $totalPrice += $detail->price * $detail->quantity;
                }

                // Áp dụng coupon nếu có
                if ($order->coupon_id) {
                    $coupon = Coupon::find($order->coupon_id);
                    if ($coupon) {
                        $discount = ($totalPrice * $coupon->discount) / 100;
                        $totalPrice -= $discount;
                    }
                }

                $order->update(['total_price' => $totalPrice]);
            }

            DB::commit();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cập nhật đơn hàng thành công!',
                    'order' => $order
                ]);
            }
            return redirect()->route('orders.index')->with('success', 'Cập nhật đơn hàng thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()])->withInput();
        }
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

    /**
     * Xử lý logic thanh toán và hóa đơn khi chuyển trạng thái đơn hàng
     */
    private function handlePaymentAndInvoiceLogic(Order $order, $oldStatus, $newStatus)
    {
        try {
            // Nếu chuyển sang trạng thái "completed" - đơn hàng hoàn thành
            if ($newStatus === 'completed' && $oldStatus !== 'completed') {
                $payment = $order->payments()->first();
                
                if ($payment) {
                    // Kiểm tra phương thức thanh toán
                    $paymentMethod = $order->paymentMethod;
                    
                    if ($paymentMethod && $paymentMethod->payment_type === 'COD') {
                        // Đơn COD: Xác nhận thanh toán và tạo hóa đơn khi hoàn thành
                        $payment->update([
                            'status' => 'completed',
                            'confirmed_at' => now(),
                            'transaction_code' => 'COD-' . $order->id . '-' . time()
                        ]);
                        
                        // Tạo hóa đơn cho đơn COD khi hoàn thành
                        $payment->generateInvoice();
                        
                        Log::info("COD payment confirmed and invoice generated for order {$order->id}");
                    } else {
                        // Các phương thức khác (MoMo, etc.): Chỉ cập nhật trạng thái payment
                        $payment->update(['status' => 'completed']);
                        Log::info("Payment status updated to completed for order {$order->id}");
                    }
                }
            }
            
            // Nếu chuyển từ "completed" về trạng thái khác
            if ($oldStatus === 'completed' && $newStatus !== 'completed') {
                $payment = $order->payments()->first();
                
                if ($payment && $payment->status === 'completed') {
                    // Revert payment status
                    $payment->update(['status' => 'pending']);
                    
                    Log::info("Payment status reverted to pending for order {$order->id}");
                }
            }
            
        } catch (\Exception $e) {
            Log::error("Error handling payment and invoice logic for order {$order->id}: " . $e->getMessage());
            // Không throw exception để không ảnh hưởng đến việc cập nhật đơn hàng
        }
    }
}
