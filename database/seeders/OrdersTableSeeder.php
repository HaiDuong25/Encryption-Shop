<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('orders')->insert([
            [
                'id' => 1,
                'user_id' => 1,
                'name' => 'John Doe',
                'phone' => '1234567890',
                'address' => '123 Main St, Anytown, USA',
                'total_price' => 99.99,
                'status' => 1,
                'discount_id' => null,
                'payment_method_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'user_id' => 2,
                'name' => 'Jane Smith',
                'phone' => '0987654321',
                'address' => '456 Elm St, Othertown, USA',
                'total_price' => 49.99,
                'status' => 0,
                'discount_id' => 1,
                'payment_method_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more orders as needed
        ]);
    }
}
