<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('payment_methods')->insert([
            [
                'payment_type' => 'Chuyển Khoản',
                'description' => 'Thanh toán qua chuyển khoản ngân hàng',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'payment_type' => 'COD',
                'description' => 'Thanh toán khi nhận hàng (COD)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'payment_type' => 'Thẻ Tín Dụng',
                'description' => 'Thanh toán qua thẻ tín dụng',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'payment_type' => 'Ví Điện Tử',
                'description' => 'Thanh toán qua ví điện tử',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
