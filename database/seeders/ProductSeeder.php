<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Attribute;
use App\Models\Category;
use App\Models\Brand;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $category = Category::first();
        $brand = Brand::first();
        $sizeAttr = Attribute::where('name', 'Size')->first();
        $colorAttr = Attribute::where('name', 'Màu')->first();

        $sizes = $sizeAttr->values()->pluck('id')->toArray();
        $colors = $colorAttr->values()->pluck('id')->toArray();

        // Thêm 3 sản phẩm mẫu
        for ($i = 1; $i <= 3; $i++) {
            $product = Product::create([
                'name' => "Sản phẩm demo $i",
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'sku' => "SKU00$i",
                'price' => rand(100000, 500000),
                'sale_price' => rand(600000, 900000),
                'description' => "Đây là sản phẩm demo số $i.",
                'status' => 'active',
                'is_featured' => rand(0,1),
                'stock' => 0,
            ]);

            // Sinh biến thể (cartesian product)
            foreach ($sizes as $size_id) {
                foreach ($colors as $color_id) {
                    $variant = $product->variants()->create([
                        'sku' => "SKU00$i-{$size_id}-{$color_id}",
                        'price' => rand(100000, 500000),
                        'stock' => rand(10,50),
                    ]);
                    $variant->attributeValues()->attach([$size_id, $color_id]);
                }
            }
        }
    }
}
