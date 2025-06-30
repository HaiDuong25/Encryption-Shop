<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RatesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('rates')->insert([
            [
                'id' => 1,
                'user_id' => 1,
                'product_id' => 1,
                'score' => 5, // Đúng tên trường
                'content' => 'Great product!',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'user_id' => 2,
                'product_id' => 2,
                'score' => 4,
                'content' => 'Good value.',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'user_id' => 1,
                'product_id' => 3,
                'score' => 5,
                'content' => 'Highly recommended!',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
