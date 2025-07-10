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
    // Kiểm tra quyền sở hữu đơn hàng
    if ($order->user_id !== Auth::id()) {
        abort(403, 'Bạn không có quyền thực hiện hành động này.');
    }

    // Chuyển đổi trạng thái sang chuẩn string để dễ xử lý
    $statusValue = $order->status;
    if (is_numeric($statusValue)) {
        $statusMap = [
            '0' => 'pending',
            '1' => 'confirmed',
            '2' => 'shipping',
            '3' => 'delivering', 
            '4' => 'received',
            '5' => 'completed',
            '6' => 'cancelled',
        ];
        $statusValue = $statusMap[(string)$statusValue] ?? 'pending';
    }

    // Kiểm tra trạng thái đơn hàng - chỉ cho phép hủy khi pending hoặc confirmed
    if (!in_array($statusValue, ['pending', 'confirmed'])) {
        return response()->json([
            'success' => false,
            'message' => 'Không thể hủy đơn hàng này. Chỉ có thể hủy đơn hàng ở trạng thái "Chờ xử lý" hoặc "Đã xác nhận".'
        ], 400);
    }

    try {
        DB::beginTransaction();

        // Load orderDetails và variant để trả lại số lượng kho
        $order->load('orderDetails.variant');

        // Cộng lại số lượng vào kho
        foreach ($order->orderDetails as $detail) {
            $variant = $detail->variant;
            if ($variant) {
                $variant->stock += $detail->quantity;
                $variant->save();
            }
        }

        // Cập nhật trạng thái và lý do hủy
        $order->update([
            'status' => 'cancelled',
            'cancel_reason' => $request->cancel_reason ?? 'Khách hàng hủy đơn',
            'cancel_note' => $request->note ?? null,
        ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Đơn hàng đã được hủy thành công và số lượng sản phẩm đã được trả lại kho.'
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        
        return response()->json([
            'success' => false,
            'message' => 'Có lỗi xảy ra khi hủy đơn hàng: ' . $e->getMessage()
        ], 500);
    }
}


    /**
     * Xác nhận đã nhận hàng → cập nhật trạng thái thành hoàn thành.
     */
    public function confirm($id)
    {
        $order = Order::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        // Chuyển đổi trạng thái sang chuẩn để xử lý
        $statusValue = $order->status;
        if (is_numeric($statusValue)) {
            $statusMap = [
                '0' => 'pending',
                '1' => 'confirmed',
                '2' => 'shipping',
                '3' => 'delivering',
                '4' => 'received',
                '5' => 'completed',
                '6' => 'cancelled',
            ];
            $statusValue = $statusMap[(string)$statusValue] ?? 'pending';
        }

        // Chỉ cho phép xác nhận khi đơn hàng ở trạng thái "Đã nhận"
        if ($statusValue === 'received') {
            $order->update(['status' => 'completed']);
            return back()->with('success', 'Đơn hàng đã được xác nhận hoàn thành.');
        }

        return back()->with('error', 'Chỉ xác nhận được đơn hàng ở trạng thái "Đã nhận".');
    }
}
