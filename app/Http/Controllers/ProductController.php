<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Hiển thị danh sách sản phẩm
    public function index()
    {
        $products = Product::with(['category', 'brand'])->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    // Hiển thị form tạo sản phẩm mới (dùng chung form)
    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        return view('admin.products.form', [
            'product' => null,
            'categories' => $categories,
            'brands' => $brands,
        ]);
    }

    // Lưu sản phẩm mới
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048', // chỉ cho phép file ảnh max 2MB
            'quantity' => 'required|integer|min:0',
            'material' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'required|boolean',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public'); // lưu vào storage/app/public/products
            $validated['image'] = $path;
        }

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Sản phẩm đã được tạo thành công.');
    }

    // Hiển thị form sửa sản phẩm (dùng chung form)
    public function edit(Product $product)
    {
        $categories = Category::all();
        $brands = Brand::all();
        return view('admin.products.form', [
            'product' => $product,
            'categories' => $categories,
            'brands' => $brands,
        ]);
    }

    // Cập nhật sản phẩm
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'quantity' => 'required|integer|min:0',
            'material' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'required|boolean',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
            // Bạn có thể thêm xóa file cũ nếu muốn
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
        }

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Sản phẩm đã được cập nhật thành công.');
    }

    public function show(Product $product)
    {
        // Load quan hệ nếu muốn
        $product->load(['category', 'brand']);
        return view('admin.products.show', compact('product'));
    }

    // Xóa sản phẩm
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Sản phẩm đã được xóa thành công.');
    }
}
