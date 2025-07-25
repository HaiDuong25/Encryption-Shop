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

    if ($request->has('keyword') && $request->keyword) {
        $keyword = $request->keyword;
        $query->whereHas('user', fn($q) => $q->where('name', 'like', "%$keyword%"))
              ->orWhereHas('orderDetail.product', fn($q) => $q->where('name', 'like', "%$keyword%"));
    }

    $returns = $query->orderByDesc('created_at')->paginate(10);

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

    $return = ReturnRequest::with('order')->findOrFail($id);

    // ✅ Chỉ cho phép cập nhật khi đang là 'pending'
    if ($return->status !== 'pending') {
        return redirect()->back()->with('error', 'Chỉ được cập nhật khi trạng thái là "Chờ duyệt".');
    }

    $return->status = $request->status;
    $return->save();

    // Cập nhật trạng thái đơn hàng tương ứng
    if ($return->order) {
        if ($request->status === 'returning') {
            $return->order->status = 'returning';
        } elseif ($request->status === 'approved') {
            $return->order->status = 'approved';
        } elseif ($request->status === 'rejected') {
            $return->order->status = 'received'; // Hoặc để nguyên trạng thái trước đó
        }
        $return->order->save();
    }

    return redirect()->route('admin.returns.index')->with('success', 'Cập nhật trạng thái thành công.');
}



}
