<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CouponsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('coupons')->insert([
            [
                'code' => 'SUMMER2025',
                'discount' => 15,
                'discount_type' => 'percentage',
                'start_date' => '2025-06-01',
                'end_date' => '2025-06-30',
                'expires_at' => '2025-07-01',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'SAVE10',
                'discount' => 20000,
                'discount_type' => 'fixed',
                'start_date' => '2025-07-01',
                'end_date' => '2025-12-31',
                'expires_at' => '2026-01-01',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'FALL2025',
                'discount' => 10,
                'discount_type' => 'percentage',
                'start_date' => '2025-09-01',
                'end_date' => '2025-09-30',
                'expires_at' => '2025-10-01',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
