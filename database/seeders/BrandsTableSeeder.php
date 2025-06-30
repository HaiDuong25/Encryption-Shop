<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('brands')->insert([
            ['id' => 1, 'name' => 'Brand 1', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Brand 2', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
