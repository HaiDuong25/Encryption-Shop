<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
class RateReplySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          DB::table('rate_replies')->insert([
            ['rate_id' => 1, 'account_id' => 1, 'reply_content' => 'Cảm ơn bạn đã đánh giá.'],
            ['rate_id' => 2, 'account_id' => 2, 'reply_content' => 'Shop ghi nhận góp ý.'],
            ['rate_id' => 3, 'account_id' => 3, 'reply_content' => 'Rất vui khi bạn hài lòng.'],
            ['rate_id' => 4, 'account_id' => 4, 'reply_content' => 'Shop sẽ cố gắng cải thiện.'],
            ['rate_id' => 5, 'account_id' => 1, 'reply_content' => 'Cảm ơn bạn nhiều.'],
        ]);
    }
}
