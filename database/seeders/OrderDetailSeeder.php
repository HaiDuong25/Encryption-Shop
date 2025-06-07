<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class OrderDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       DB::table('order_details')->insert([
    ['order_id' => 1, 'variant_id' => 1, 'quantity' => 1, 'price' => 100000 , 'total_price' => 100000],
    ['order_id' => 2, 'variant_id' => 2, 'quantity' => 2, 'price' => 15000 , 'total_price' => 100000],
    ['order_id' => 3, 'variant_id' => 3, 'quantity' => 1, 'price' => 120000 , 'total_price' => 100000],
    ['order_id' => 4, 'variant_id' => 4, 'quantity' => 3, 'price' => 50000 , 'total_price' => 100000],
    ['order_id' => 5, 'variant_id' => 1, 'quantity' => 2, 'price' => 70000 , 'total_price' => 100000],
]);

    }
}
