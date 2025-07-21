<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Rate;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RateController extends Controller
{
    public function store(Request $request, $productId, $orderDetailId)
    {
        $request->validate([
            'score' => 'required|integer|min:1|max:5',
            'content' => 'nullable|string|max:1000',
        ]);

        $product = Product::findOrFail($productId);
        $orderDetail = OrderDetail::findOrFail($orderDetailId);

        // Kiểm tra trạng thái đơn hàng
        if ($orderDetail->order->status !== \App\Models\Order::STATUS_COMPLETED) {
            return redirect()->route('client.products.show', $product->id)
                ->with('error', 'Bạn chỉ có thể đánh giá sản phẩm sau khi đơn hàng hoàn thành.');
        }

        // Kiểm tra xem người dùng đã đánh giá cho order_detail_id này chưa
        if (Rate::where('user_id', auth()->id())->where('order_detail_id', $orderDetailId)->exists()) {
            return redirect()->route('client.products.show', $product->id)
                ->with('error', 'Bạn đã đánh giá sản phẩm này trong đơn hàng này.');
        }

        // Tạo đánh giá
        Rate::create([
            'product_id' => $productId,
            'user_id' => auth()->id(),
            'order_detail_id' => $orderDetailId,
            'score' => $request->score,
            'content' => $request->content,
        ]);

        return redirect()->route('client.products.show', $product->id)
            ->with('success', 'Đánh giá của bạn đã được gửi thành công.');
    }
}
