<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $methods = PaymentMethod::paginate(10);
        return view('admin.payment-methods.index', compact('methods'));
    }

    public function create()
    {
        return view('admin.payment-methods.create');
    }

    public function store(Request $request)
    {

        $request->validate([
            'payment_type' => 'required|max:255',
            'description' => 'nullable'
        ]);
        PaymentMethod::create($request->only('payment_type', 'description'));
        return redirect()->route('payment-methods.index')->with('success', 'Thêm phương thức thành công');
    }

    public function edit(PaymentMethod $payment_method)
    {
        return view('admin.payment-methods.edit', compact('payment_method'));
    }

    public function update(Request $request, PaymentMethod $payment_method)
    {
        $request->validate([
            'payment_type' => 'required|max:255',
            'description' => 'nullable'
        ]);

        $payment_method->update($request->only('payment_type', 'description'));
        return redirect()->route('payment-methods.index')->with('success', 'Cập nhật thành công');
    }

    public function destroy(PaymentMethod $payment_method)
    {
        $payment_method->delete();
        return back()->with('success', 'Xóa thành công');
    }
}
