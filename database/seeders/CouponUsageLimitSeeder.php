<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coupon;
use Carbon\Carbon;

class CouponUsageLimitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coupons = [
            [
                'code' => 'WELCOME10',
                'discount' => 10,
                'discount_type' => 'percentage',
                'min_order_amount' => 100000,
                'max_discount_amount' => 50000,
                'usage_limit' => 100, // Giới hạn 100 lần
                'used_count' => 0,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonth(),
                'expires_at' => Carbon::now()->addMonth(),
                'is_active' => true,
            ],
            [
                'code' => 'SAVE20',
                'discount' => 20,
                'discount_type' => 'percentage',
                'min_order_amount' => 500000,
                'max_discount_amount' => 100000,
                'usage_limit' => 50, // Giới hạn 50 lần
                'used_count' => 0,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addWeeks(2),
                'expires_at' => Carbon::now()->addWeeks(2),
                'is_active' => true,
            ],
            [
                'code' => 'FREESHIP',
                'discount' => 30000,
                'discount_type' => 'fixed',
                'min_order_amount' => 200000,
                'max_discount_amount' => 30000,
                'usage_limit' => 25, // Giới hạn 25 lần
                'used_count' => 0,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDays(10),
                'expires_at' => Carbon::now()->addDays(10),
                'is_active' => true,
            ],
            [
                'code' => 'VIP15',
                'discount' => 15,
                'discount_type' => 'percentage',
                'min_order_amount' => 1000000,
                'max_discount_amount' => 200000,
                'usage_limit' => 10, // Giới hạn 10 lần
                'used_count' => 0,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDays(7),
                'expires_at' => Carbon::now()->addDays(7),
                'is_active' => true,
            ],
            [
                'code' => 'FLASH5',
                'discount' => 50000,
                'discount_type' => 'fixed',
                'min_order_amount' => 300000,
                'max_discount_amount' => 50000,
                'usage_limit' => 5, // Giới hạn 5 lần - thấp nhất
                'used_count' => 0,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDays(3),
                'expires_at' => Carbon::now()->addDays(3),
                'is_active' => true,
            ],
            [
                'code' => 'SINGLE1',
                'discount' => 100000,
                'discount_type' => 'fixed',
                'min_order_amount' => 500000,
                'max_discount_amount' => 100000,
                'usage_limit' => 1, // Chỉ sử dụng được 1 lần
                'used_count' => 0,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDays(5),
                'expires_at' => Carbon::now()->addDays(5),
                'is_active' => true,
            ],
        ];

        foreach ($coupons as $couponData) {
            Coupon::updateOrCreate(
                ['code' => $couponData['code']],
                $couponData
            );
        }
    }
}
