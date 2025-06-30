<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;

class ProductController extends Controller
{
   public function index(Request $request) {
    $products = Product::where('status', 1)->orderBy('id', 'desc')->paginate(12);
    $categories = Category::where('status', 1)->get();
    $query = Product::where('status', 1);
    if ($request->has('categories')) {
        $query->whereIn('category_id', $request->categories);
    }
    $products = $query->orderBy('id', 'desc')->paginate(12);
    return view('client.products.index', [
        'products' => $products,
        'categories' => $categories,
        'selectedCategories' => $request->categories ?? []
    ]);
   }

   public function category($id)
{
   $categories = Category::where('status',1)->get();

    $products = Product::where('category_id', $id)
        ->where('status', 1)
        ->paginate(12);

    return view('client.products.index', [
        'products' => $products,
        'categories' => $categories,
        'selectedCategories' => [$id]
    ]);
}

}
