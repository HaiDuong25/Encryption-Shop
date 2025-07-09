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
                // Thông tin người đặt hàng
                'orderer_name' => 'John Doe',
                'orderer_phone' => '1234567890',
                'orderer_email' => 'john@example.com',
                // Thông tin người nhận hàng
                'recipient_name' => 'John Doe',
                'recipient_phone' => '1234567890',
                'recipient_address' => '123 Main St, Anytown, USA',
                'recipient_email' => 'john@example.com',
                'order_notes' => 'Giao hàng giờ hành chính',
                // Thông tin đơn hàng
                'subtotal' => 120000,
                'discount_amount' => 20000,
                'coupon_code' => 'DISCOUNT20',
                'total_price' => 100000,
                'status' => 'confirmed',
                'payment_method_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'user_id' => 2,
                // Thông tin người đặt hàng
                'orderer_name' => 'Jane Smith',
                'orderer_phone' => '0987654321',
                'orderer_email' => 'jane@example.com',
                // Thông tin người nhận hàng
                'recipient_name' => 'Jane Smith',
                'recipient_phone' => '0987654321',
                'recipient_address' => '456 Elm St, Othertown, USA',
                'recipient_email' => 'jane@example.com',
                'order_notes' => null,
                // Thông tin đơn hàng
                'subtotal' => 200000,
                'discount_amount' => 0,
                'coupon_code' => null,
                'total_price' => 200000,
                'status' => 'pending',
                'payment_method_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more orders as needed
        ]);
    }
}
