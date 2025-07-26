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
            'payment_method_id' => 'required|exists:payment_methods,id',
        ]);

        $paymentMethod = PaymentMethod::find($request->payment_method_id);

        // Nếu không phải COD thì bắt buộc phải có thông tin ngân hàng
if ($paymentMethod && !str_contains(strtolower($paymentMethod->payment_type), 'cod')) {
            $request->validate([
                'bank_account_name' => 'required|string|max:255',
                'bank_account_number' => 'required|string|max:255',
            ]);
        }

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
            'payment_method_id' => $request->payment_method_id,
            'bank_account_name' => $paymentMethod && $paymentMethod->code !== 'cod' ? $request->bank_account_name : null,
            'bank_account_number' => $paymentMethod && $paymentMethod->code !== 'cod' ? $request->bank_account_number : null,
            'status' => 'pending',
        ]);

        // Cập nhật trạng thái đơn hàng là 'returning'
        $order = $orderDetail->order;
        if ($order) {
            $order->status = 'returning';
            $order->save();
        }

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
