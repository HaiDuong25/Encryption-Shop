<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends \App\Http\Controllers\Controller
{
    public function index(Request $request)
    {
        $query = Category::query();
        if ($request->filled('parent_id')) {
            $parent = Category::whereNull('parent_id')
                ->where('id', $request->parent_id)
                ->first();
            $children = Category::where('parent_id', $request->parent_id)->get();
            $categories = collect();
            if ($parent) {
                $categories->push($parent);
            }
            $categories = $categories->merge($children);
        } else {
            $query->when($request->filled('keyword'), function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->keyword . '%');
            });
            $query->when($request->filled('status'), function ($q) use ($request) {
                $q->where('status', $request->status);
            });
            $categories = $query->orderBy('parent_id')->orderBy('created_at', 'desc')->get();
        }
        $parentCategories = Category::whereNull('parent_id')->get();
        return view('admin.categories.index', compact('categories', 'parentCategories'));
    }
    public function create()
    {
        $categories = Category::whereNull('parent_id')->get();
        return view('admin.categories.form', compact('categories'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|boolean',
            'parent_id' => 'nullable|exists:categories,id',
        ]);
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }
        Category::create($validated);
        return redirect()->route('categories.index')->with('success', 'Danh mục được tạo thành công.');
    }
    public function edit(Category $category)
    {
        $categories = Category::where('id', '!=', $category->id)
            ->whereNull('parent_id')
            ->get();
        return view('admin.categories.form', compact('category', 'categories'));
    }
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|boolean',
            'parent_id' => 'nullable|exists:categories,id',
        ]);
        if ($request->hasFile('image')) {
            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }
        $category->update($validated);
        return redirect()->route('categories.index')->with('success', 'Danh mục được cập nhật thành công.');
    }
    public function destroy(Category $category)
    {
        if ($category->image && Storage::disk('public')->exists($category->image)) {
            Storage::disk('public')->delete($category->image);
        }
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Danh mục được xóa thành công.');
    }
}
