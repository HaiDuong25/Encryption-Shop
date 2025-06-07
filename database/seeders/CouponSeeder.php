<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
           DB::table('coupons')->insert([
            ['code' => 'DISCOUNT10', 'discount' => 10 , 'is_active'=>1],
            ['code' => 'SALE15', 'discount' => 15 , 'is_active'=>1],
            ['code' => 'FREESHIP', 'discount' => 5 , 'is_active'=>1],
            ['code' => 'NEWUSER20', 'discount' => 20 , 'is_active'=>1],
            ['code' => 'WELCOME5', 'discount' => 5 , 'is_active'=>1],
        ]);
    }
}
