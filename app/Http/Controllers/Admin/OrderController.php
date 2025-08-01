<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\User;
use App\Models\Coupon;
use App\Models\Payment;
use App\Models\OrderDetail;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends \App\Http\Controllers\Controller
{
    public function index(Request $request)
    {
        $query = Order::with([
            'orderDetails.variant.product.productImages',
            'orderDetails.product.productImages',
            'paymentMethod',
            'user'
        ]);
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_code', 'like', '%' . $search . '%')
                  ->orWhere('total_amount', 'like', '%' . $search . '%')
                  ->orWhere('status', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', '%' . $search . '%')
                                ->orWhere('email', 'like', '%' . $search . '%');
                  });
            });
        }
        
        $orders = $query->latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load([
            'orderDetails.variant.product.productImages',
    'orderDetails.variant.attributeValues.attribute', // <== THÊM DÒNG NÀY
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

        // Thêm danh sách các trạng thái để hiển thị đúng label trong view
        $statuses = [
            'pending' => 'Chờ xử lý',
            'confirmed' => 'Đã xác nhận',
            'shipping' => 'Đã giao cho ĐVVC',
            'delivering' => 'Đang giao',
            'received' => 'Đã nhận',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy',
            'returning' => 'Đang trả hàng',
            'approved' => 'Đã trả hàng',
            'rejected' => 'Từ chối trả',
        ];

        return view('admin.orders.edit', compact('order', 'users', 'coupons', 'paymentMethods', 'statuses'));
    }


    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:pending,confirmed,shipping,delivering,received,completed,cancelled,returning,approved,rejected',
        ]);


        try {
            DB::beginTransaction();

            $oldStatus = $order->getOriginal('status');

            // Load quan hệ cần thiết
            $order->load('orderDetails.variant', 'orderDetails.product');

            // Nếu cần thì cộng tồn trước
            if ($oldStatus !== $validated['status'] && $validated['status'] === 'approved') {
                $this->restoreStock($order);
            }

            // Sau đó mới update trạng thái
            $order->update([
                'status' => $validated['status'],
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

    // Hủy đơn hàng (chỉ cho phép hủy đơn hàng chờ xử lý và đã xác nhận)
    public function cancel(Order $order)
    {
        try {
            // Kiểm tra điều kiện cho phép hủy
            $cancellableStatuses = ['pending', 'confirmed']; // Chỉ cho phép hủy đơn chờ xử lý và đã xác nhận

            // Convert numeric status to string for compatibility
            $statusValue = $order->status;
            if (is_numeric($statusValue)) {
                $statusMap = [
                    '0' => 'pending',
                    '1' => 'confirmed',
                    '2' => 'shipping',
                    '3' => 'delivering',
                    '4' => 'received',
                    '5' => 'completed',
                    '6' => 'returning',
                    '7' => 'approved',
                    '8' => 'rejected',
                    '9' => 'cancelled',
                ];
                $statusValue = $statusMap[$statusValue] ?? 'pending';
            }

            if (!in_array($statusValue, $cancellableStatuses)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Chỉ có thể hủy đơn hàng ở trạng thái "Chờ xử lý" hoặc "Đã xác nhận". Đơn hàng hiện tại đã sang trạng thái khác.'
                ], 400);
            }

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

    // Xóa đơn hàng (chỉ cho phép xóa đơn hàng đã hủy thành công)
    public function destroy(Order $order)
    {
        try {
            // Kiểm tra điều kiện cho phép xóa
            $deletableStatuses = ['cancelled']; // Chỉ cho phép xóa đơn hàng đã hủy

            // Convert numeric status to string for compatibility
            $statusValue = $order->status;
            if (is_numeric($statusValue)) {
                $statusMap = [
                    '0' => 'pending',
                    '1' => 'confirmed',
                    '2' => 'shipping',
                    '3' => 'delivering',
                    '4' => 'received',
                    '5' => 'completed',
                    '6' => 'returning',
                    '7' => 'approved',
                    '8' => 'rejected',
                    '9' => 'cancelled',
                ];
                $statusValue = $statusMap[$statusValue] ?? 'pending';
            }

            if (!in_array($statusValue, $deletableStatuses)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Chỉ có thể xóa đơn hàng đã được hủy thành công. Vui lòng hủy đơn hàng trước khi xóa.'
                ], 400);
            }

            DB::beginTransaction();

            // Đơn hàng đã hủy rồi nên stock đã được trả lại, không cần restore lại
            // Chỉ xóa các bản ghi liên quan
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

    public function updateStatus(Request $request, $id)
    {
        $order = Order::with('orderDetails.variant')->findOrFail($id);
        $newStatus = $request->status;

        if ($newStatus === 'approved' && $order->status !== 'approved') {
            foreach ($order->orderDetails as $detail) {
                $variant = $detail->variant;
                if ($variant) {
                    $variant->stock += $detail->quantity;
                    $variant->save();
                }
            }
        }

        $order->status = $newStatus;
        $order->save();

        return back()->with('success', 'Cập nhật trạng thái thành công.');
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

            // TRẢ LẠI MÃ GIẢM GIÁ CHO KHÁCH HÀNG KHI ADMIN HỦY ĐỚN
            if ($order->coupon_code) {
                try {
                    // Tìm bản ghi sử dụng coupon của đơn hàng này
                    $couponUse = \App\Models\CouponUse::where('order_id', $order->id)
                        ->where('user_id', $order->user_id)
                        ->first();

                    if ($couponUse) {
                        // Tìm coupon để trả lại số lần sử dụng
                        $coupon = \App\Models\Coupon::find($couponUse->coupon_id);
                        if ($coupon) {
                            // Giảm số lần sử dụng của coupon
                            $coupon->decrementUsage();
                            
                            \Log::info("Admin returned coupon {$order->coupon_code} usage for cancelled order {$order->id}. Current usage: {$coupon->used_count}");
                        }
                        
                        // Xóa bản ghi sử dụng coupon
                        $couponUse->delete();
                        
                        \Log::info("Admin deleted coupon usage record for order {$order->id} and user {$order->user_id}");
                    }
                } catch (\Exception $e) {
                    \Log::error('Error returning coupon for admin cancelled order: ' . $e->getMessage());
                    // Không throw exception để không ảnh hưởng đến việc hủy đơn hàng
                }
            }

            // Cập nhật trạng thái đơn hàng
            $order->update(['status' => 'cancelled']);

            return response()->json([
                'success' => true,
                'message' => 'Đã hủy đơn hàng thành công, trả lại tồn kho và mã giảm giá cho khách hàng.'
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
