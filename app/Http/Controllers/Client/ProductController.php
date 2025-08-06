<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\Brand;

class ProductController extends Controller
{
public function index(Request $request)
{
    $query = Product::select('*', DB::raw('COALESCE(sale_price, price) as final_price'))
        ->where('status', 1);

    // Lọc theo danh mục
    if ($request->has('categories')) {
    $categoryIds = [];

    foreach ($request->categories as $categoryId) {
        $category = Category::with('children')->find($categoryId);
        if ($category) {
            $categoryIds = array_merge($categoryIds, $category->getAllChildrenIds());
        }
    }

    $query->whereIn('category_id', $categoryIds);
}


    // Lọc theo khoảng giá dựa trên final_price
    if ($request->filled('min_price')) {
        $query->whereRaw('COALESCE(sale_price, price) >= ?', [$request->min_price]);
    }

    if ($request->filled('max_price')) {
        $query->whereRaw('COALESCE(sale_price, price) <= ?', [$request->max_price]);
    }

    // Lọc theo thương hiệu
    if ($request->filled('brands')) {
        $query->whereIn('brand_id', $request->brands);
    }

    // Tìm kiếm theo keyword
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
        // Load rates có status = 1 và kèm user
        'rates' => function ($q) {
            $q->where('status', 1)->with('user');
        }
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

public function searchProducts(Request $request)
{
    $query = $request->get('query', '');
    
    if (strlen($query) < 2) {
        return response()->json([]);
    }

    $products = Product::where('status', 1)
        ->where(function($q) use ($query) {
            $q->where('name', 'like', '%' . $query . '%')
              ->orWhere('description', 'like', '%' . $query . '%')
              ->orWhereHas('category', function($categoryQuery) use ($query) {
                  $categoryQuery->where('name', 'like', '%' . $query . '%');
              });
        })
        ->with('category')
        ->select('id', 'name', 'image', 'price', 'sale_price', 'description', 'category_id')
        ->limit(8)
        ->get()
        ->map(function ($product) {
            $images = json_decode($product->image, true);
            $mainImage = is_array($images) && !empty($images) ? $images[0] : $product->image;
            
            return [
                'id' => $product->id,
                'name' => $product->name,
                'image' => $mainImage ? asset('storage/' . $mainImage) : null,
                'price' => $product->price,
                'sale_price' => $product->sale_price,
                'formatted_price' => $product->sale_price ? format_vnd($product->sale_price) : format_vnd($product->price),
                'category_name' => $product->category ? $product->category->name : '',
                'url' => route('client.products.show', $product->id)
            ];
        });

    return response()->json($products);
}

}
