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
            $query->when($request->filled('search') || $request->filled('keyword'), function ($q) use ($request) {
                $searchTerm = $request->search ?? $request->keyword;
                $q->where('name', 'like', '%' . $searchTerm . '%');
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

    public function show(Category $category)
    {
        $category->load('children', 'parent');

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'category' => $category
            ]);
        }

        return view('admin.categories.index', compact('category'));
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
        $category = Category::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Danh mục được tạo thành công.',
                'category' => $category
            ]);
        }
        return redirect()->route('admin.categories.index')->with('success', 'Danh mục được tạo thành công.');
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

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Danh mục được cập nhật thành công.',
                'category' => $category
            ]);
        }
        return redirect()->route('admin.categories.index')->with('success', 'Danh mục được cập nhật thành công.');
    }
    public function destroy(Category $category)
{
    // Kiểm tra nếu danh mục có danh mục con
    if ($category->children()->exists()) {
        if (request()->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa danh mục vì có danh mục con.'
            ]);
        }

        return redirect()->route('admin.categories.index')->with('error', 'Không thể xóa danh mục vì có danh mục con.');
    }

    // Xóa ảnh nếu có
    if ($category->image && Storage::disk('public')->exists($category->image)) {
        Storage::disk('public')->delete($category->image);
    }

    $category->delete();

    if (request()->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'Danh mục được xóa thành công.'
        ]);
    }

    return redirect()->route('admin.categories.index')->with('success', 'Danh mục được xóa thành công.');
}

    public function createParent()
    {
        return view('admin.categories.create-parent');
    }

    public function storeParent(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|boolean',
            'image'  => 'nullable|image|max:2048',
        ]);

        // Parent category has no parent_id
        $validated['parent_id'] = null;

        if ($request->hasFile('image')) {
        $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        $category = Category::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Danh mục cha được tạo thành công.',
                'category' => $category
            ]);
        }

        return redirect()->route('admin.categories.index')->with('success', 'Danh mục cha được tạo thành công.');
    }
}
