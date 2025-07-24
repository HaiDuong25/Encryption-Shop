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
        // Lấy tất cả danh mục con và brand
        $categories = Category::whereNotNull('parent_id')->get();
        $brands = Brand::all();

        // Lấy thuộc tính và giá trị size, màu
        $sizeAttr = Attribute::where('name', 'Size')->first();
        $colorAttr = Attribute::where('name', 'Màu')->first();

        $sizes = $sizeAttr ? $sizeAttr->values()->pluck('id')->toArray() : [];
        $colors = $colorAttr ? $colorAttr->values()->pluck('id')->toArray() : [];

        if ($categories->isEmpty() || $brands->isEmpty() || empty($sizes) || empty($colors)) {
            $this->command->warn("⚠️ Vui lòng chắc chắn đã có danh mục con, thương hiệu và giá trị thuộc tính Size, Màu.");
            return;
        }

        $materials = ['Cotton', 'Polyester', 'Da', 'Jean', 'Thun lạnh'];

        // Thêm 10 sản phẩm mẫu
        for ($i = 1; $i <= 5; $i++) {
            $category = $categories->random(); // ✅ random danh mục con
            $brand = $brands->random(); // ✅ random brand

            $product = Product::create([
                'name' => "Sản phẩm demo $i",
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'sku' => "SKU00$i",
                'price' => rand(510000, 800000),
                'sale_price' => rand(100000, 500000),
                'description' => "Đây là sản phẩm demo số $i.",
                'status' => 'active',
                'is_featured' => rand(0, 1),
                'stock' => 0,
                'material' => $materials[array_rand($materials)],
            ]);

            // Sinh biến thể từ Size × Màu
            foreach ($sizes as $size_id) {
                foreach ($colors as $color_id) {
                    $variant = $product->variants()->create([
                        'sku' => "SKU00$i-{$size_id}-{$color_id}",
                        'price' => rand(600000, 900000),
                        'sale_price' => rand(400000, 550000),
                        'stock' => rand(10, 50),
                    ]);
                    $variant->attributeValues()->attach([$size_id, $color_id]);
                }
            }
        }

        $this->command->info("✅ Đã tạo 5 sản phẩm demo với biến thể Size × Màu.");
    }
}
