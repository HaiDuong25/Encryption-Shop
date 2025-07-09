<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\Brand;

class ProductController extends Controller
{
public function index(Request $request)
{
    $query = Product::where('status', 1);

    // Lọc theo danh mục
    if ($request->has('categories')) {
        $query->whereIn('category_id', $request->categories);
    }

    // Lọc theo khoảng giá
    if ($request->filled('min_price')) {
        $query->where('price', '>=', $request->min_price);
    }

    if ($request->filled('max_price')) {
        $query->where('price', '<=', $request->max_price);
    }

    // Lọc theo thương hiệu
    if ($request->filled('brands')) {
        $query->whereIn('brand_id', $request->brands);
    }
    if ($request->filled('keyword')) {
    $query->where('name', 'like', '%' . $request->keyword . '%');
}

    $products = $query->with('rates')->orderBy('id', 'desc')->paginate(12);
    $categories = Category::where('status', 1)->get();
    $brands = Brand::all();
    return view('client.products.index', [
        'products' => $products,
        'categories' => $categories,
        'brands' => $brands,
        'selectedCategories' => $request->categories ?? [],
        'selectedBrands' => $request->brands ?? [],
        'min_price' => $request->min_price,
        'max_price' => $request->max_price,
        'keyword' => $request->keyword,
    ]);
}




public function category(Request $request, $id)
{
    $categories = Category::where('status',1)->get();
    $brands = Brand::all();

    $query = Product::where('status', 1)->where('category_id', $id);

    if ($request->filled('min_price')) {
        $query->where('price', '>=', $request->min_price);
    }

    if ($request->filled('max_price')) {
        $query->where('price', '<=', $request->max_price);
    }

    if ($request->filled('brands')) {
        $query->whereIn('brand_id', $request->brands);
    }
    if ($request->filled('keyword')) {
    $query->where('name', 'like', '%' . $request->keyword . '%');
}


    $products = $query->orderBy('id', 'desc')->paginate(12);

    return view('client.products.index', [
        'products' => $products,
        'categories' => $categories,
        'brands' => $brands,
        'selectedCategories' => [$id],
        'selectedBrands' => $request->brands ?? [],
        'min_price' => $request->min_price,
        'max_price' => $request->max_price,
        'keyword' => $request->keyword,
    ]);
}

public function show($id)
{
    $product = Product::with([
        'category',
        'brand',
        'variants.attributeValues.attribute',
        'rates' => fn($q) => $q->where('status', 1)
    ])->findOrFail($id);

    $relatedProducts = Product::where('category_id', $product->category_id)
        ->where('id', '!=', $product->id)
        ->latest()
        ->take(4)
        ->get();

    return view('client.products.show', compact('product', 'relatedProducts'));
}
public function getStock(Request $request)
{
    $productId = $request->product_id;
    $sizeId = $request->size_id;
    $colorId = $request->color_id;

    $product = Product::findOrFail($productId);

    // Lấy đúng variant ứng với cả size và color
    $variant = $product->variants()
        ->whereHas('attributeValues', function($q) use ($sizeId) {
            $q->where('attribute_value_id', $sizeId);
        })
        ->whereHas('attributeValues', function($q) use ($colorId) {
            $q->where('attribute_value_id', $colorId);
        })
        ->first();

    return response()->json([
        'stock' => $variant ? $variant->stock : 0,
    ]);
}




}
