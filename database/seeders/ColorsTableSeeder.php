<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ColorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $colors = [
            ['name' => 'Red', 'color_code' => '#FF0000'],
            ['name' => 'Green', 'color_code' => '#00FF00'],
            ['name' => 'Blue', 'color_code' => '#0000FF'],
            ['name' => 'Yellow', 'color_code' => '#FFFF00'],
            ['name' => 'Black', 'color_code' => '#000000'],
            ['name' => 'White', 'color_code' => '#FFFFFF'],
            ['name' => 'Purple', 'color_code' => '#800080'],
            ['name' => 'Orange', 'color_code' => '#FFA500'],
            ['name' => 'Pink', 'color_code' => '#FFC0CB'],
            ['name' => 'Gray', 'color_code' => '#808080'],
        ];

        DB::table('colors')->insert($colors);
    }
}
