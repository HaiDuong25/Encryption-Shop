<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'name' => 'Bánh gato dâu',
                'image' => 'cake1.jpg',
                'quantity' => 50,
                'material' => 'Bột mì, dâu tây, kem',
                'price' => 100000,
                'sale_price' => 90000,
                'description' => 'Bánh gato dâu thơm ngon',
                'status' => 1,
                'category_id' => 1,
                'brand_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Bánh mì que',
                'image' => 'bread1.jpg',
                'quantity' => 100,
                'material' => 'Bột mì, pate, ớt',
                'price' => 15000,
                'sale_price' => 12000,
                'description' => 'Bánh mì que giòn rụm',
                'status' => 1,
                'category_id' => 2,
                'brand_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Bánh kem chocolate',
                'image' => 'cake2.jpg',
                'quantity' => 40,
                'material' => 'Socola, bột mì, trứng',
                'price' => 120000,
                'sale_price' => 100000,
                'description' => 'Bánh kem chocolate ngọt ngào',
                'status' => 1,
                'category_id' => 3,
                'brand_id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Bánh quy bơ',
                'image' => 'cookie.jpg',
                'quantity' => 200,
                'material' => 'Bơ, đường, trứng',
                'price' => 50000,
                'sale_price' => 45000,
                'description' => 'Bánh quy bơ giòn tan',
                'status' => 1,
                'category_id' => 4,
                'brand_id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Bánh trung thu đậu xanh',
                'image' => 'mooncake.jpg',
                'quantity' => 80,
                'material' => 'Đậu xanh, bột mì',
                'price' => 70000,
                'sale_price' => 65000,
                'description' => 'Bánh trung thu truyền thống',
                'status' => 1,
                'category_id' => 5,
                'brand_id' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
