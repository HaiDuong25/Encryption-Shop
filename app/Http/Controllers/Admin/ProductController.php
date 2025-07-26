<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Attribute;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

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

        if (is_string($product->gallery)) {
        $product->gallery = json_decode($product->gallery, true);
        }

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
            'variant_sale_price' => 'array',
            'variant_stock' => 'array',
            'variant_sku' => 'array',
            'variant_image' => 'array',
            'material' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $galleryPaths = [];
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $img) {
                $galleryPaths[] = $img->store('products/gallery', 'public');
            }
        }
        $data['gallery'] = json_encode($galleryPaths);

        $data['is_featured'] = $request->has('is_featured') ? 1 : 0;
        $product = Product::create($data);

        $combinations = $this->cartesian([$request->sizes, $request->colors]);
        foreach ($combinations as $index => $combo) {
            $variantSku = $request->input("variant_sku.$index") ?: ($product->sku ? $product->sku . '-' : '') . implode('-', $combo);
            $variantImage = $request->hasFile("variant_image.$index")
                ? $request->file("variant_image.$index")->store('variants', 'public')
                : null;
            $variant = $product->variants()->create([
                'sku' => strtoupper($variantSku),
                'price' => $request->input("variant_price.$index") ?? $data['price'],
                'sale_price' => $request->input("variant_sale_price.$index") ?? $data['sale_price'],
                'stock' => $request->input("variant_stock.$index") ?? 0,
                'image' => $variantImage,
            ]);
            $variant->attributeValues()->attach($combo);
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Tạo sản phẩm thành công!', 'product' => $product]);
        }

        return redirect()->route('products.index')->with('success', 'Tạo sản phẩm thành công!');
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
            'remove_gallery' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        } else {
            $data['image'] = $product->image;
        }

        $existingGallery = is_array($product->gallery)
            ? $product->gallery
            : (is_string($product->gallery) ? json_decode($product->gallery, true) : []);

        $removedGallery = explode(',', $request->input('remove_gallery', ''));

        $updatedGallery = array_filter($existingGallery, function ($img) use ($removedGallery) {
            return !in_array($img, $removedGallery);
        });

        foreach ($removedGallery as $imgPath) {
            if (Storage::disk('public')->exists($imgPath)) {
                Storage::disk('public')->delete($imgPath);
            }
        }

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $img) {
                $updatedGallery[] = $img->store('products/gallery', 'public');
            }
        }

        $data['gallery'] = json_encode(array_values($updatedGallery));
        $data['is_featured'] = $request->has('is_featured') ? 1 : 0;
        $data['slug'] = $request->slug ? $request->slug : Str::slug($request->name);

        $product->update($data);

        // TODO: Cập nhật variant như trong code cũ, nếu cần thiết

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Cập nhật sản phẩm thành công!', 'product' => $product]);
        }

        return redirect()->route('products.index')->with('success', 'Cập nhật sản phẩm thành công!');
    }

    public function destroy(Product $product, Request $request)
    {
        try {
            $hasOrders = \DB::table('order_details')->where('product_id', $product->id)->exists();
            $hasRates = \DB::table('rates')->where('product_id', $product->id)->exists();

            if ($hasOrders || $hasRates) {
                $reasons = [];
                if ($hasOrders) $reasons[] = 'dòn hàng';
                if ($hasRates) $reasons[] = 'đánh giá';

                if ($request->boolean('set_inactive')) {
                    $product->status = 'inactive';
                    $product->save();

                    return response()->json(['success' => true, 'message' => 'Sản phẩm đã được chuyển sang trạng thái ẩn.', 'action' => 'set_inactive']);
                }

                return response()->json(['success' => false, 'requiresConfirmation' => true, 'message' => 'Sản phẩm có ' . implode(' và ', $reasons) . '. Bạn có muốn chuyển sang trạng thái ẩn không?']);
            }

            $product->delete();

            return response()->json(['success' => true, 'message' => 'Đã xóa sản phẩm!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Lỗi khi xử lý: ' . $e->getMessage()]);
        }
    }
}
