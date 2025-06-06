<?php

namespace App\Http\Controllers;

use App\Models\ProductVariant;
use App\Models\Product;
use App\Models\Color;
use App\Models\Size;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    public function index()
    {
        $variants = ProductVariant::with(['product', 'color', 'size'])->get();
        return view('admin.product-variants.index', compact('variants'));
    }

    public function create()
    {
        $products = Product::all();
        $colors = Color::all();
        $sizes = Size::all();
        return view('admin.product-variants.form', compact('products', 'colors', 'sizes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'color_id' => 'required|exists:colors,id',
            'size_id' => 'required|exists:sizes,id',
            'price' => 'required|numeric',
            'quantity' => 'required|integer|min:0',
            'image' => 'nullable|image',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('product-variants', 'public');
        }

        ProductVariant::create($data);

        return redirect()->route('product-variants.index')->with('success', 'Thêm thành công');
    }

    public function edit(ProductVariant $productVariant)
    {
        $products = Product::all();
        $colors = Color::all();
        $sizes = Size::all();
        return view('admin.product-variants.form', compact('productVariant', 'products', 'colors', 'sizes'));
    }

    public function update(Request $request, ProductVariant $productVariant)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'color_id' => 'required|exists:colors,id',
            'size_id' => 'required|exists:sizes,id',
            'price' => 'required|numeric',
            'quantity' => 'required|integer|min:0',
            'image' => 'nullable|image',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('product-variants', 'public');
        }

        $productVariant->update($data);

        return redirect()->route('product-variants.index')->with('success', 'Cập nhật thành công');
    }

    public function destroy(ProductVariant $productVariant)
    {
        $productVariant->delete();
        return redirect()->route('product-variants.index')->with('success', 'Đã xóa thành công');
    }
}
