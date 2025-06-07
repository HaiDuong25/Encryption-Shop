<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('payments')->insert([
            ['order_id' => 1, 'status' => 'Chờ thanh toán', 'payment_method_id' => 1, 'confirmed_at' => now()],
            ['order_id' => 2, 'status' => 'Chờ thanh toán',   'payment_method_id' => 2, 'confirmed_at' => null],
            ['order_id' => 3, 'status' => 'Chờ thanh toán', 'payment_method_id' => 3, 'confirmed_at' => now()],
            ['order_id' => 4, 'status' => 'Chờ thanh toán',   'payment_method_id' => 4, 'confirmed_at' => null],
            ['order_id' => 5, 'status' => 'Chờ thanh toán', 'payment_method_id' => 5, 'confirmed_at' => now()],
        ]);
    }
}

