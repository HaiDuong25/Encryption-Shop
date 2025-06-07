<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      DB::table('categories')->insert([
    ['name' => 'Bánh sinh nhật'],
    ['name' => 'Bánh mì'],
    ['name' => 'Bánh kem'],
    ['name' => 'Bánh quy'],
    ['name' => 'Bánh trung thu'],
]);

    }
}
