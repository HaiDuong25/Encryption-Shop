<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;

class BrandSeeder extends Seeder
{
    public function run()
    {
        $brands = [
            ['name' => 'Nike'],
            ['name' => 'Adidas'],
            ['name' => 'Puma'],
        ];

        foreach ($brands as $brand) {
            Brand::firstOrCreate(['name' => $brand['name']]);
        }
    }
}
