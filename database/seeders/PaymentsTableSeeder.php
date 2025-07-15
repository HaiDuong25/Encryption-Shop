<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('payments')->insert([
            [
                'order_id' => 1,
                'payment_method_id' => 1,
                'status' => 'completed',
                'transaction_code' => 'TXN123456',
                'amount' => 250000, // Thêm dòng này
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'order_id' => 2,
                'payment_method_id' => 2,
                'status' => 'pending',
                'transaction_code' => 'TXN654321',
                'amount' => 100000, // Thêm dòng này
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}