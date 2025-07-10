<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderDetailsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('order_details')->insert([
            [
                'order_id' => 1,
                'product_id' => 1,
                'variant_id' => 1,
                'product_id' => 1, // <-- Add this line
                'price' => 100000,
                'quantity' => 2,
                'total_price' => 200000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'order_id' => 1,
                'product_id' => 1,
                'variant_id' => 2,
                'product_id' => 1, // <-- Add this line (set correct product_id)
                'price' => 50000,
                'quantity' => 1,
                'total_price' => 50000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'order_id' => 2,
                'product_id' => 1,
                'variant_id' => 1,
                'product_id' => 1, // <-- Add this line (set correct product_id)
                'price' => 100000,
                'quantity' => 1,
                'total_price' => 100000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
