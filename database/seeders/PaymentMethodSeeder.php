<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       DB::table('payment_methods')->insert([
    ['payment_type' => 'COD'],
    ['payment_type' => 'Chuyển khoản'],
    ['payment_type' => 'Ví Momo'],
    ['payment_type' => 'ZaloPay'],
    ['payment_type' => 'Thẻ tín dụng'],
]);

    }
}
