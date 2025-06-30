<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductVariantsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('product_variants')->insert([
            [
                'id' => 1,
                'product_id' => 1,
                'color_id' => 1,
                'size_id' => 1,
                'quantity' => 10,
                'image' => 'variant1.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'product_id' => 1,
                'color_id' => 2,
                'size_id' => 1,
                'quantity' => 5,
                'image' => 'variant2.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
