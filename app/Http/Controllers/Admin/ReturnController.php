<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReturnRequest;
use Illuminate\Http\Request;

class ReturnController extends Controller
{
 public function index(Request $request)
{
    $query = ReturnRequest::with(['user', 'orderDetail.product']);

    // Xử lý tìm kiếm
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->whereHas('user', function($userQuery) use ($search) {
                $userQuery->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
            })
            ->orWhereHas('orderDetail.product', function($productQuery) use ($search) {
                $productQuery->where('name', 'like', "%{$search}%");
            })
            ->orWhere('reason', 'like', "%{$search}%");
        });
    }

    $returns = $query->orderByDesc('created_at')->paginate(10);
    $returns->appends($request->query());

    return view('admin.returns.index', compact('returns'));
}


    public function show($id)
    {
        $return = ReturnRequest::with(['user', 'order', 'orderDetail'])->findOrFail($id);
        return view('admin.returns.show', compact('return'));
    }

public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:returning,approved,rejected'
    ]);

    // Gọi thêm quan hệ 'orderDetail.variant' thay vì 'productVariant'
    $return = ReturnRequest::with(['order', 'orderDetail.variant'])->findOrFail($id);

    // ✅ Chỉ xử lý nếu trạng thái hiện tại là 'pending'
    if ($return->status !== 'pending') {
        return redirect()->back()->with('error', 'Chỉ được cập nhật khi trạng thái là "Chờ duyệt".');
    }

    $return->status = $request->status;
    $return->save();

    // ✅ Cập nhật trạng thái trả hàng cho OrderDetail cụ thể
    $orderDetail = $return->orderDetail;
    if ($orderDetail) {
        if ($request->status === 'approved') {
            $orderDetail->return_status = 'approved';
            
            // ✅ Cập nhật lại tồn kho của biến thể sản phẩm
            $variant = $orderDetail->variant;
            if ($variant) {
                $variant->stock += $orderDetail->quantity;
                $variant->save();
            }
            // Hoàn tiền phần giá trị của item nếu thanh toán online / ví (không COD)
            try {
                $order = $orderDetail->order;
                $paymentMethod = $order?->paymentMethod;
                if ($order && $paymentMethod && $paymentMethod->payment_type !== 'COD') {
                    $lineAmount = (float) ($orderDetail->total_price ?? ($orderDetail->price * $orderDetail->quantity));
                    // Trừ tỷ lệ giảm giá đơn hàng nếu có discount (phân bổ theo tỷ lệ)
                    $orderSubtotal = (float) ($order->subtotal ?? 0);
                    $orderDiscount = (float) ($order->discount ?? $order->coupon_discount ?? 0);
                    if ($orderSubtotal > 0 && $orderDiscount > 0) {
                        $discountRatio = $orderDiscount / $orderSubtotal;
                        $lineAmountAfterDiscount = $lineAmount * (1 - $discountRatio);
                    } else {
                        $lineAmountAfterDiscount = $lineAmount;
                    }
                    // Không vượt quá còn lại
                    $refundAmount = min($lineAmountAfterDiscount, $order->refundableRemaining());
                    if ($refundAmount > 0) {
                        $order->refundToWallet($refundAmount, 'Hoàn tiền sản phẩm trả hàng (#' . $orderDetail->id . ')');
                        \Log::info("Refund line {$orderDetail->id} amount {$refundAmount} for order {$order->id}");
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Refund error for return request ' . $return->id . ': ' . $e->getMessage());
            }
        } elseif ($request->status === 'rejected') {
            $orderDetail->return_status = 'rejected';
        }
        $orderDetail->save();

        // ✅ Cập nhật trạng thái trả hàng của đơn hàng (không ảnh hưởng đến trạng thái giao hàng)
        $orderDetail->order->updateReturnStatus();
    }

    return redirect()->route('admin.returns.index')->with('success', 'Cập nhật trạng thái thành công.');
}




}
