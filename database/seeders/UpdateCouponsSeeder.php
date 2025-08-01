<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Coupon;
use Carbon\Carbon;

class UpdateCouponsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Xóa tất cả coupon cũ (tránh truncate vì có foreign key)
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Coupon::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Tạo mã giảm giá mẫu với logic mới
        $coupons = [
            [
                'code' => 'WELCOME10',
                'description' => 'Chào mừng khách hàng mới - Giảm 10% cho đơn hàng đầu tiên',
                'discount' => 10,
                'discount_type' => 'percentage',
                'min_order_amount' => 100000,
                'max_discount_amount' => 50000, // Tối đa giảm 50k
                'usage_limit' => 100,
                'used_count' => 0,
                'is_one_time_per_user' => true,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonths(3),
                'expires_at' => Carbon::now()->addMonths(3),
                'is_active' => true,
            ],
            [
                'code' => 'SUMMER20',
                'description' => 'Khuyến mãi mùa hè - Giảm 20% tối đa 100k cho đơn từ 300k',
                'discount' => 20,
                'discount_type' => 'percentage',
                'min_order_amount' => 300000,
                'max_discount_amount' => 100000, // Tối đa giảm 100k
                'usage_limit' => 50,
                'used_count' => 0,
                'is_one_time_per_user' => true,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonths(2),
                'expires_at' => Carbon::now()->addMonths(2),
                'is_active' => true,
            ],
            [
                'code' => 'FREESHIP',
                'description' => 'Miễn phí vận chuyển - Giảm 30k cho đơn từ 200k',
                'discount' => 30000,
                'discount_type' => 'fixed',
                'min_order_amount' => 200000,
                'max_discount_amount' => null, // Không cần max vì đã là số tiền cố định
                'usage_limit' => 200,
                'used_count' => 0,
                'is_one_time_per_user' => true,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonth(),
                'expires_at' => Carbon::now()->addMonth(),
                'is_active' => true,
            ],
            [
                'code' => 'VIP50',
                'description' => 'Ưu đãi VIP - Giảm 50k cho đơn từ 500k',
                'discount' => 50000,
                'discount_type' => 'fixed',
                'min_order_amount' => 500000,
                'max_discount_amount' => null,
                'usage_limit' => 30,
                'used_count' => 0,
                'is_one_time_per_user' => true,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addWeeks(2),
                'expires_at' => Carbon::now()->addWeeks(2),
                'is_active' => true,
            ],
            [
                'code' => 'MEGA15',
                'description' => 'Siêu khuyến mãi - Giảm 15% tối đa 200k cho đơn từ 1 triệu',
                'discount' => 15,
                'discount_type' => 'percentage',
                'min_order_amount' => 1000000,
                'max_discount_amount' => 200000, // Tối đa giảm 200k
                'usage_limit' => 20,
                'used_count' => 0,
                'is_one_time_per_user' => true,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDays(10),
                'expires_at' => Carbon::now()->addDays(10),
                'is_active' => true,
            ],
            [
                'code' => 'FLASHSALE',
                'description' => 'Flash Sale - Giảm 25% tối đa 150k trong thời gian có hạn',
                'discount' => 25,
                'discount_type' => 'percentage',
                'min_order_amount' => 400000,
                'max_discount_amount' => 150000, // Tối đa giảm 150k
                'usage_limit' => 100,
                'used_count' => 0,
                'is_one_time_per_user' => true,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDays(7),
                'expires_at' => Carbon::now()->addDays(7),
                'is_active' => true,
            ],
        ];

        foreach ($coupons as $couponData) {
            Coupon::create($couponData);
        }

        $this->command->info('✅ Đã tạo ' . count($coupons) . ' mã giảm giá mẫu với logic mới!');
    }
}
