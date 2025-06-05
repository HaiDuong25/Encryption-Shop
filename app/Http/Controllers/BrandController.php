<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::all();
        return view('admin.brands.index', compact('brands'));
    }

    public function create()
    {
        // Giao diện form chung dùng cho create & edit
        return view('admin.brands.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048', // validate ảnh, tối đa 2MB
        ]);

        // Xử lý upload ảnh nếu có
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('brands', 'public'); // lưu trong storage/app/public/brands
            $validated['image'] = $path;
        }

        Brand::create($validated);

        return redirect()->route('brands.index')->with('success', 'Thương hiệu được thêm thành công.');
    }

    public function edit(Brand $brand)
    {
        return view('admin.brands.form', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Xoá ảnh cũ nếu có
            if ($brand->image && Storage::disk('public')->exists($brand->image)) {
                Storage::disk('public')->delete($brand->image);
            }
            $path = $request->file('image')->store('brands', 'public');
            $validated['image'] = $path;
        }

        $brand->update($validated);

        return redirect()->route('brands.index')->with('success', 'Thương hiệu được sửa thành công.');
    }

    public function destroy(Brand $brand)
    {
        // Xoá ảnh nếu có
        if ($brand->image && Storage::disk('public')->exists($brand->image)) {
            Storage::disk('public')->delete($brand->image);
        }

        $brand->delete();

        return redirect()->route('brands.index')->with('success', 'Thương hiệu được xóa thành công.');
    }
}
