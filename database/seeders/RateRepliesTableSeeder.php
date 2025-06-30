<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RateRepliesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rate_replies')->insert([
            [
                'rate_id' => 1,
                'user_id' => 1,
                'reply_content' => 'Thank you for your feedback!',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'rate_id' => 2,
                'user_id' => 2,
                'reply_content' => 'We appreciate your comments!',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'rate_id' => 3,
                'user_id' => 1,
                'reply_content' => 'Your satisfaction is our priority!',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more entries as needed
        ]);
    }
}
