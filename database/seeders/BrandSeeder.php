<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          DB::table('brands')->insert([
            ['name' => 'Bánh Ngon'],
            ['name' => 'Sweet House'],
            ['name' => 'Cake Zone'],
            ['name' => 'Fresh Bakery'],
            ['name' => 'Delight Treats'],
        ]);
    }
}
