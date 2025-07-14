<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        $brands = Brand::all();
        return view('admin.brands.index', compact('brands'));
    }
    public function create()
    {
        return view('admin.brands.form');
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('brands', 'public');
            $validated['image'] = $path;
        }
        $brand = Brand::create($validated);
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Thương hiệu được thêm thành công.',
                'brand' => $brand
            ]);
        }
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
            if ($brand->image && Storage::disk('public')->exists($brand->image)) {
                Storage::disk('public')->delete($brand->image);
            }
            $path = $request->file('image')->store('brands', 'public');
            $validated['image'] = $path;
        }
        $brand->update($validated);
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Thương hiệu được sửa thành công.',
                'brand' => $brand
            ]);
        }
        return redirect()->route('brands.index')->with('success', 'Thương hiệu được sửa thành công.');
    }
    public function destroy(Brand $brand)
    {
        if ($brand->image && Storage::disk('public')->exists($brand->image)) {
            Storage::disk('public')->delete($brand->image);
        }
        $brand->delete();
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Thương hiệu được xóa thành công.'
            ]);
        }
        return redirect()->route('brands.index')->with('success', 'Thương hiệu được xóa thành công.');
    }
}
