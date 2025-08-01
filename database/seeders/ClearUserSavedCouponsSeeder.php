<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UserSavedCoupon;

class ClearUserSavedCouponsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Xóa hết tất cả saved coupons để test từ đầu
        UserSavedCoupon::truncate();
        
        $this->command->info('Đã xóa hết tất cả saved coupons để test từ đầu');
    }
}
