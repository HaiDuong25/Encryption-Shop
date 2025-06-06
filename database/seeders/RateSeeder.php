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
        // Lấy một user_id ngẫu nhiên hoặc cố định
        // $userId = Account::inRandomOrder()->first()->id;
        // Hoặc nếu bạn biết ID
        // $userId = 1;

        Rate::create([ /* ... dữ liệu tương tự như Tinker ... */ ]);
        Rate::create([ /* ... dữ liệu tương tự như Tinker ... */ ]);
    }
}
