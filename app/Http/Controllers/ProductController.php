<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'brand'])->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        $colors = Color::all();
        $sizes = Size::all();

        return view('admin.products.form', compact('categories', 'brands', 'colors', 'sizes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image',
            'description_images.*' => 'required|image',
            'quantity' => 'required|integer|min:1',
            'material' => 'required|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric',
            'description' => 'required|string',
            'status' => 'required|boolean',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'variants.*.color_id' => 'required|exists:colors,id',
            'variants.*.size_id' => 'required|exists:sizes,id',
        ]);

        $product = new Product($validated);

        if ($request->hasFile('image')) {
            $product->image = $request->file('image')->store('products', 'public');
        }

        $product->save();

        if ($request->hasFile('description_images')) {
            foreach ($request->file('description_images') as $image) {
                $product->images()->create([
                    'image_path' => $image->store('product_descriptions', 'public')
                ]);
            }
        }

        foreach ($request->input('variants', []) as $variant) {
            $product->variants()->create($variant);
        }

        return redirect()->route('products.index')->with('success', 'Thêm sản phẩm thành công!');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $brands = Brand::all();
        $colors = Color::all();
        $sizes = Size::all();
        $product->load(['images', 'variants']);

        return view('admin.products.form', compact('product', 'categories', 'brands', 'colors', 'sizes'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => $request->isMethod('post') ? 'required|image' : 'nullable|image',
            'description_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'quantity' => 'required|integer|min:1',
            'material' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0|lt:price',
            'description' => 'required|string',
            'status' => 'required|in:0,1',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'variants' => 'required|array|min:1',
            'variants.*.color_id' => 'required|exists:colors,id',
            'variants.*.size_id' => 'required|exists:sizes,id',
        ]);


        DB::beginTransaction();
        try {
            $product->fill($request->only([
                'name',
                'quantity',
                'material',
                'price',
                'sale_price',
                'description',
                'status',
                'category_id',
                'brand_id'
            ]));

            if ($request->hasFile('image')) {
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                $product->image = $request->file('image')->store('products', 'public');
            }

            $product->save();

            if ($request->hasFile('description_images')) {
                foreach ($request->file('description_images') as $imageFile) {
                    $imagePath = $imageFile->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $imagePath,
                    ]);
                }
            }

            $product->variants()->delete();
            if ($request->has('variants')) {
                foreach ($request->input('variants') as $variant) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'color_id' => $variant['color_id'],
                        'size_id' => $variant['size_id'],
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('products.index')->with('success', 'Cập nhật sản phẩm thành công!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
        }
    }
    public function show(Product $product)
    {
        $product->load(['category', 'brand', 'images']);
        return view('admin.products.show', compact('product'));
    }
    public function destroy(Product $product)
    {
        try {
            foreach ($product->images as $img) {
                Storage::disk('public')->delete($img->image_path);
                $img->delete();
            }

            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $product->variants()->delete();

            $product->delete();
            return redirect()->route('products.index')->with('success', 'Xoá sản phẩm thành công!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Không thể xoá: ' . $e->getMessage()]);
        }
    }
}
