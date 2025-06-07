<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rate;
use App\Models\Account; // Hoặc User model của bạn
use Illuminate\Support\Facades\DB; // Nếu cần query phức tạp hơn

class RateSeeder extends Seeder
{
    public function run(): void
    {
         DB::table('rates')->insert([
            ['product_id' => 1, 'account_id' => 1, 'score' => 5, 'status'=>1],
            ['product_id' => 2, 'account_id' => 2, 'score' => 4, 'status'=>1 ],
            ['product_id' => 3, 'account_id' => 3, 'score' => 5, 'status'=>1],
            ['product_id' => 4, 'account_id' => 4, 'score' => 3, 'status'=>1],
            ['product_id' => 5, 'account_id' => 5, 'score' => 4, 'status'=>1],
        ]);
    }
}
