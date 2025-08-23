<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ReturnRequest;
use App\Models\OrderDetail;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Auth;

class ReturnRequestController extends Controller
{
   public function create(Request $request)
{
    $orderDetail = OrderDetail::with('order')->findOrFail($request->order_detail_id);
    $paymentMethods = PaymentMethod::all();
    $selectedPaymentMethodId = $orderDetail->order->payment_method_id;

    return view('client.returns.create', compact('orderDetail', 'paymentMethods', 'selectedPaymentMethodId'));
}


    public function store(Request $request)
    {
        $request->validate([
            'order_detail_id' => 'required|exists:order_details,id',
            'reason' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png',
        ]);

        $orderDetail = OrderDetail::with('order')->findOrFail($request->order_detail_id);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('returns', 'public');
        }

        ReturnRequest::create([
            'user_id' => Auth::id(),
            'order_id' => $orderDetail->order_id,
            'order_detail_id' => $orderDetail->id,
            'reason' => $request->reason,
            'description' => $request->description,
            'image' => $imagePath,
            'status' => 'pending',
        ]);

        // Cập nhật trạng thái trả hàng cho OrderDetail này
        $orderDetail->return_status = 'pending';
        $orderDetail->save();

        // Cập nhật trạng thái trả hàng của đơn hàng (không ảnh hưởng đến trạng thái giao hàng)
        $orderDetail->order->updateReturnStatus();

        return redirect()->route('client.orders.index')->with('success', 'Gửi yêu cầu trả hàng thành công.');
    }

    public function index()
    {
        $returns = ReturnRequest::where('user_id', Auth::id())->latest()->paginate(10);
        return view('client.returns.index', compact('returns'));
    }

    public function show($id)
    {
        $return = ReturnRequest::where('user_id', Auth::id())->findOrFail($id);
        return view('client.returns.show', compact('return'));
    }
}
