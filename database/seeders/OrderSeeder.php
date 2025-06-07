<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('orders')->insert([
            [
                'user_id' => 1,
                'name' => 'Nguyen Van A',
                'phone' => '0123456789',
                'address' => '123 Đường ABC, Quận 1',
                'total_price' => 120000.00,
                'status' => 1,
                'discount_id' => null,
                'payment_method_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'name' => 'Tran Thi B',
                'phone' => '0987654321',
                'address' => '456 Đường XYZ, Quận 3',
                'total_price' => 150000.00,
                'status' => 1,
                'discount_id' => null,
                'payment_method_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'name' => 'Le Van C',
                'phone' => '0912345678',
                'address' => '789 Đường LMN, Quận 5',
                'total_price' => 180000.00,
                'status' => 1,
                'discount_id' => null,
                'payment_method_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
             [
                'user_id' => 4,
                'name' => 'Le Van C',
                'phone' => '0912345678',
                'address' => '789 Đường LMN, Quận 5',
                'total_price' => 180000.00,
                'status' => 1,
                'discount_id' => null,
                'payment_method_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
             [
                'user_id' => 5,
                'name' => 'Le Van C',
                'phone' => '0912345678',
                'address' => '789 Đường LMN, Quận 5',
                'total_price' => 180000.00,
                'status' => 1,
                'discount_id' => null,
                'payment_method_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
