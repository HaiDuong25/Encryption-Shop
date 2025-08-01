<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;

class PaymentMethodController extends Controller
{
    public function index(Request $request)
    {
        $query = PaymentMethod::query();
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('payment_type', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }
        
        $methods = $query->paginate(10)->withQueryString();
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
        $paymentMethod = PaymentMethod::create($request->only('payment_type', 'description'));
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Thêm phương thức thành công',
                'payment_method' => $paymentMethod
            ]);
        }
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
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật thành công',
                'payment_method' => $payment_method
            ]);
        }
        return redirect()->route('payment-methods.index')->with('success', 'Cập nhật thành công');
    }

    public function destroy(PaymentMethod $payment_method)
    {
        $payment_method->delete();
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Xóa thành công'
            ]);
        }
        return back()->with('success', 'Xóa thành công');
    }
}
