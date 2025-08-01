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

    // ✅ Cập nhật trạng thái đơn hàng liên quan
    if ($return->order) {
        if ($request->status === 'returning') {
            $return->order->status = 'returning';
        } elseif ($request->status === 'approved') {
            $return->order->status = 'approved';

            // ✅ Cập nhật lại tồn kho của biến thể sản phẩm
            $variant = $return->orderDetail->variant;
            if ($variant) {
                $variant->stock += $return->orderDetail->quantity;
                $variant->save();
            }
        } elseif ($request->status === 'rejected') {
            $return->order->status = 'received'; // hoặc giữ nguyên nếu bạn muốn
        }
        $return->order->save();
    }

    return redirect()->route('admin.returns.index')->with('success', 'Cập nhật trạng thái thành công.');
}




}
