<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Attribute;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Brand;
use App\Models\Category;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()->with('category', 'brand');

        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('price_from')) {
            $query->where('price', '>=', $request->price_from);
        }
        if ($request->filled('price_to')) {
            $query->where('price', '<=', $request->price_to);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $products = $query->latest()->paginate(15);
        // ✅ Chỉ lấy danh mục con
        $categories = Category::whereNotNull('parent_id')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        $product->load('variants.attributeValues.attribute', 'category', 'brand');
        return view('admin.products.show', compact('product'));
    }

    public function create()
    {
        $sizeAttr = Attribute::firstOrCreate(['name' => 'Size']);
        $colorAttr = Attribute::firstOrCreate(['name' => 'Màu']);
        $sizes = $sizeAttr->values;
        $colors = $colorAttr->values;

        return view('admin.products.create', [
            'sizes' => $sizes,
            'colors' => $colors,
            'sizeAttributeId' => $sizeAttr->id,
            'colorAttributeId' => $colorAttr->id,
            'categories' => Category::whereNotNull('parent_id')->get(),
            'brands' => Brand::all(),
        ]);
    }

    public function store(Request $request)
    {
         $data = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => [
                'nullable',
                'integer',
                function ($attribute, $value, $fail) {
                    if (!Category::where('id', $value)->whereNotNull('parent_id')->exists()) {
                        $fail('Vui lòng chọn một danh mục con.');
                    }
                },
            ],
            'brand_id' => 'nullable|integer',
            'sku' => 'nullable|string|max:100',
            'price' => 'nullable|numeric',
            'sale_price' => 'nullable|numeric',
            'stock' => 'nullable|integer',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'gallery.*' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'sizes' => 'required|array|min:1',
            'colors' => 'required|array|min:1',
            'variant_price' => 'array',
            'variant_stock' => 'array',
            'variant_sku' => 'array',
            'variant_image' => 'array',
            'material' => 'nullable|string|max:255',

        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }
        if ($request->hasFile('gallery')) {
            $galleryPaths = [];
            foreach ($request->file('gallery') as $img) {
                $galleryPaths[] = $img->store('products/gallery', 'public');
            }
            $data['gallery'] = json_encode($galleryPaths);
        }
        $data['is_featured'] = $request->has('is_featured') ? 1 : 0;
        $product = Product::create($data);

        $combinations = $this->cartesian([$request->sizes, $request->colors]);
        foreach ($combinations as $index => $combo) {
            $variantSku = $request->input("variant_sku.$index") ?: ($product->sku ? $product->sku . '-' : '') . implode('-', $combo);
            $variantPrice = $request->input("variant_price.$index");
            $variantStock = $request->input("variant_stock.$index");
            $variantImage = null;
            if ($request->hasFile("variant_image.$index")) {
                $variantImage = $request->file("variant_image.$index")->store('variants', 'public');
            }
            $variant = $product->variants()->create([
                'sku'   => strtoupper($variantSku),
                'price' => $variantPrice ?: null,
                'stock' => $variantStock ?: 0,
                'image' => $variantImage,
            ]);
            $variant->attributeValues()->attach($combo);
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã tạo sản phẩm và biến thể!',
                'product' => $product
            ]);
        }
        return redirect()->route('products.index')->with('success', 'Đã tạo sản phẩm và biến thể!');
    }

    private function cartesian($arrays)
    {
        $result = [[]];
        foreach ($arrays as $property_values) {
            $tmp = [];
            foreach ($result as $result_item) {
                foreach ($property_values as $property_value) {
                    $tmp[] = array_merge($result_item, [$property_value]);
                }
            }
            $result = $tmp;
        }
        return $result;
    }

    public function edit(Product $product)
    {
        $categories = Category::whereNotNull('parent_id')->get();
        $brands = \App\Models\Brand::all();
        $sizeAttr = Attribute::firstOrCreate(['name' => 'Size']);
        $colorAttr = Attribute::firstOrCreate(['name' => 'Màu']);
        $sizes = $sizeAttr->values;
        $colors = $colorAttr->values;
        $product->load('variants.attributeValues');
        $variantData = $product->variants->map(function ($v) {
            return [
                'id' => $v->id,
                'size_id' => $v->size_id,
                'color_id' => $v->color_id,
                'sku' => $v->sku,
                'price' => $v->price,
                'stock' => $v->stock,
                'image' => $v->image
            ];
        })->values();
        return view('admin.products.edit', [
            'product' => $product,
            'categories' => $categories,
            'brands' => $brands,
            'sizes' => $sizes,
            'colors' => $colors,
            'sizeAttributeId' => $sizeAttr->id,
            'colorAttributeId' => $colorAttr->id,
            'variantData' => $variantData, // thêm dòng này!
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|integer|exists:categories,id',
            'brand_id' => 'nullable|integer|exists:brands,id',
            'sku' => 'nullable|string|max:100',
            'price' => 'nullable|numeric',
            'sale_price' => 'nullable|numeric',
            'stock' => 'nullable|integer',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'gallery.*' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'sizes' => 'array|min:1',
            'colors' => 'array|min:1',
            'variant_price' => 'array',
            'variant_stock' => 'array',
            'variant_sku' => 'array',
            'variant_image' => 'array',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }
        if ($request->hasFile('gallery')) {
            $galleryPaths = [];
            foreach ($request->file('gallery') as $img) {
                $galleryPaths[] = $img->store('products/gallery', 'public');
            }
            $data['gallery'] = json_encode($galleryPaths);
        }
        $data['is_featured'] = $request->has('is_featured') ? 1 : 0;
        $data['slug'] = $request->slug ? $request->slug : Str::slug($request->name);

        $product->update($data);

        if ($request->has('variant_sizes')) {
            $product->variants()->delete();
            $sizes = $request->variant_sizes;
            $colors = $request->variant_colors;
            foreach ($sizes as $idx => $sizeId) {
                $variant = $product->variants()->create([
                    'size_id' => $sizeId,
                    'color_id' => $colors[$idx],
                    'sku' => $request->variant_sku[$idx] ?? null,
                    'price' => $request->variant_price[$idx] ?? null,
                    'stock' => $request->variant_stock[$idx] ?? 0,
                    'image' => $request->hasFile("variant_image.$idx")
                        ? $request->file("variant_image.$idx")->store('variants', 'public')
                        : null,
                ]);
            }
        } else if ($request->has('old_variant_ids')) {
            foreach ($request->old_variant_ids as $idx => $id) {
                $variant = $product->variants()->find($id);
                if ($variant) {
                    $variant->sku = $request->old_variant_sku[$idx] ?? $variant->sku;
                    $variant->price = $request->old_variant_price[$idx] ?? $variant->price;
                    $variant->stock = $request->old_variant_stock[$idx] ?? $variant->stock;
                    if ($request->hasFile("old_variant_image.$idx")) {
                        $variant->image = $request->file("old_variant_image.$idx")->store('variants', 'public');
                    }
                    $variant->save();
                }
            }
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã cập nhật sản phẩm!',
                'product' => $product
            ]);
        }
        return redirect()->route('products.index')->with('success', 'Đã cập nhật sản phẩm!');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa sản phẩm!'
            ]);
        }
        return back()->with('success', 'Đã xóa sản phẩm!');
    }
}
