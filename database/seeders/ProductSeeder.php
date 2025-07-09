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
            $originalPrice = rand(300000, 800000); // Giá gốc
            $salePrice = rand(100000, $originalPrice - 50000); // Giá sale (thấp hơn giá gốc)
            
            $product = Product::create([
                'name' => "Sản phẩm demo $i",
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'sku' => "SKU00$i",
                'price' => $originalPrice,
                'sale_price' => $salePrice,
                'description' => "Đây là sản phẩm demo số $i với giá gốc " . number_format($originalPrice) . " đ và giá sale " . number_format($salePrice) . " đ.",
                'status' => 'active',
                'is_featured' => rand(0,1),
                'stock' => 0,
            ]);

            // Sinh biến thể (cartesian product)
            foreach ($sizes as $size_id) {
                foreach ($colors as $color_id) {
                    $variantOriginalPrice = rand(200000, 600000);
                    $variantSalePrice = rand(100000, $variantOriginalPrice - 30000);
                    
                    $variant = $product->variants()->create([
                        'sku' => "SKU00$i-{$size_id}-{$color_id}",
                        'price' => $variantSalePrice,
                        'stock' => rand(10,50),
                    ]);
                    $variant->attributeValues()->attach([$size_id, $color_id]);
                }
            }
        }
    }
}
