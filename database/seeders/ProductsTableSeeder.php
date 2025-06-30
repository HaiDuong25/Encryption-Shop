<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->insert([
            [
                'id' => 1,
                'name' => 'Product 1',
                'image' => 'product1.jpg',
                'quantity' => 10,
                'material' => 'Cotton',
                'price' => 19.99,
                'sale_price' => 15.99,
                'description' => 'Description for Product 1',
                'status' => 1,
                'category_id' => 1,
                'brand_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Product 2',
                'image' => 'product2.jpg',
                'quantity' => 5,
                'material' => 'Polyester',
                'price' => 29.99,
                'sale_price' => null,
                'description' => 'Description for Product 2',
                'status' => 1,
                'category_id' => 2,
                'brand_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'Product 3',
                'image' => 'product3.jpg',
                'quantity' => 20,
                'material' => 'Wool',
                'price' => 39.99,
                'sale_price' => 34.99,
                'description' => 'Description for Product 3',
                'status' => 1,
                'category_id' => 1,
                'brand_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more products as needed
        ]);
    }
}
