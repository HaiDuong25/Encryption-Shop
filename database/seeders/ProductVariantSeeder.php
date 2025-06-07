<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductVariantSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('product_variants')->insert([
            [
                'product_id' => 1,
                'color_id' => 1,
                'size_id' => 1,
                'price' => 100000.00,
                'quantity' => 10,
                'image' => 'images/product1_red_s.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => 1,
                'color_id' => 2,
                'size_id' => 1,
                'price' => 105000.00,
                'quantity' => 15,
                'image' => 'images/product1_blue_s.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => 2,
                'color_id' => 1,
                'size_id' => 2,
                'price' => 120000.00,
                'quantity' => 5,
                'image' => 'images/product2_red_m.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => 2,
                'color_id' => 3,
                'size_id' => 3,
                'price' => 130000.00,
                'quantity' => 8,
                'image' => 'images/product2_green_l.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Thêm nhiều biến thể khác nếu cần
        ]);
    }
}
