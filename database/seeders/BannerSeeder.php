<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         DB::table('banners')->insert([
            ['title' => 'Khuyến mãi hè', 'image' => 'banner1.jpg'],
            ['title' => 'Mừng sinh nhật', 'image' => 'banner2.jpg'],
            ['title' => 'Tết giảm giá', 'image' => 'banner3.jpg'],
            ['title' => 'Flash Sale', 'image' => 'banner4.jpg'],
            ['title' => 'Mua 1 tặng 1', 'image' => 'banner5.jpg'],
        ]);
    }
}
