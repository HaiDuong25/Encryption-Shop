<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\ProductVariant;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    // Hủy đơn hàng (chỉ được hủy khi status = pending hoặc confirmed)
    public function cancel(Order $order)
    {
        // Kiểm tra quyền sở hữu đơn hàng
        if ($order->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền hủy đơn hàng này.'
            ], 403);
        }

        // Kiểm tra trạng thái đơn hàng
        $statusValue = $order->status;
        
        // Convert numeric status to string for compatibility
        if (is_numeric($statusValue)) {
            $statusMap = [
                '0' => 'pending',
                '1' => 'confirmed', 
                '2' => 'shipping',
                '3' => 'delivering',
                '4' => 'received',
                '5' => 'completed'
            ];
            $statusValue = $statusMap[$statusValue] ?? $statusValue;
        }

        // Chỉ cho phép hủy khi đơn hàng ở trạng thái pending hoặc confirmed
        if (!in_array($statusValue, ['pending', 'confirmed'])) {
            return response()->json([
                'success' => false,
                'message' => 'Chỉ có thể hủy đơn hàng khi đang chờ xử lý hoặc đã xác nhận.'
            ], 400);
        }

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

    // Trả lại số lượng sản phẩm khi hủy đơn hàng
    private function restoreStock(Order $order)
    {
        foreach ($order->orderDetails as $detail) {
            if ($detail->variant_id) {
                // Nếu có variant, cập nhật stock của variant
                $variant = ProductVariant::find($detail->variant_id);
                if ($variant) {
                    $variant->increment('stock', $detail->quantity);
                }
            } else {
                // Nếu không có variant, cập nhật stock của product
                $product = Product::find($detail->product_id);
                if ($product) {
                    $product->increment('stock', $detail->quantity);
                }
            }
        }
    }

    // (Tùy chọn) Thêm các phương thức tạo mới, xem chi tiết...
}
