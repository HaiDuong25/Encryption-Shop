<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('statuses')->insert([
    ['content' => 'pending'],
    ['content' => 'processing'],
    ['content' => 'shipped'],
    ['content' => 'delivered'],
    ['content' => 'cancelled'],
]);

    }
}
