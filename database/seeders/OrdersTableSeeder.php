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
                'total_price' => 250000.00,
                'status' => 'confirmed',
                'discount_id' => null,
                'payment_method_id' => 1,
                // Thông tin người nhận
                'recipient_name' => 'John Doe',
                'recipient_phone' => '1234567890',
                'recipient_address' => '123 Main St, Anytown, USA',
                // Thông tin người đặt hàng
                'orderer_name' => 'John Doe',
                'orderer_phone' => '1234567890',
                'orderer_email' => 'john@example.com',
                // Thông tin khác
                'notes' => 'Giao hàng vào buổi sáng',
                'delivery_date' => now()->addDays(3),
                // Thông tin mã giảm giá
                'coupon_code' => null,
                'coupon_discount' => 0,
                'coupon_type' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'user_id' => 2,
                'total_price' => 180000.00,
                'status' => 'pending',
                'discount_id' => 1,
                'payment_method_id' => 2,
                // Thông tin người nhận
                'recipient_name' => 'Jane Smith',
                'recipient_phone' => '0987654321',
                'recipient_address' => '456 Elm St, Othertown, USA',
                // Thông tin người đặt hàng
                'orderer_name' => 'Jane Smith',
                'orderer_phone' => '0987654321',
                'orderer_email' => 'jane@example.com',
                // Thông tin khác
                'notes' => 'Gọi trước khi giao',
                'delivery_date' => now()->addDays(2),
                // Thông tin mã giảm giá
                'coupon_code' => 'SAVE10',
                'coupon_discount' => 20000,
                'coupon_type' => 'fixed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
