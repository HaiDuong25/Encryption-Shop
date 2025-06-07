<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          // Colors
        DB::table('colors')->insert([
            ['name' => 'Đỏ'],
            ['name' => 'Xanh lá'],
            ['name' => 'Vàng'],
            ['name' => 'Nâu'],
            ['name' => 'Hồng'],
        ]);
    }
}
