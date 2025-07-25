<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;

class CategoryClientController extends Controller
{
public function index()
{
    $categories = \App\Models\Category::whereNull('parent_id')
                    ->with('children') // nếu muốn hiển thị cả danh mục con
                    ->get();

    return view('client.categories.index', compact('categories'));
}


public function show($id)
{
    $category = Category::with('children')->findOrFail($id);

    if ($category->children->count()) {
        $childIds = $category->children->pluck('id');
        $products = Product::whereIn('category_id', $childIds)->paginate(12);
    } else {
        $products = Product::where('category_id', $category->id)->paginate(12);
    }

    // ✅ Truyền thêm $categories để xử lý dropdown nếu cần
    $categories = Category::with('children')->whereNull('parent_id')->get();

    return view('client.categories.show', compact('category', 'products', 'categories'));
}

}
