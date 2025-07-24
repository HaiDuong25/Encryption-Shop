<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReturnRequest;
use Illuminate\Http\Request;

class ReturnController extends Controller
{
    public function index()
    {
        $returns = ReturnRequest::with(['user', 'order', 'orderDetail'])->latest()->paginate(10);
        return view('admin.returns.index', compact('returns')); // đúng view
    }

    public function show($id)
    {
        $return = ReturnRequest::with(['user', 'order', 'orderDetail'])->findOrFail($id);
        return view('admin.returns.show', compact('return'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:pending,approved,rejected,returned,refunded']);

        $return = ReturnRequest::findOrFail($id);
        $return->status = $request->status;
        $return->save();

        return redirect()->back()->with('success', 'Cập nhật trạng thái thành công.');
    }
}
