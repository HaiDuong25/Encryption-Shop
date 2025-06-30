<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        // Thêm danh mục cha
        $ao = Category::firstOrCreate(['name' => 'Áo'], ['name' => 'Áo']);
        $quan = Category::firstOrCreate(['name' => 'Quần'], ['name' => 'Quần']);
        $giay = Category::firstOrCreate(['name' => 'Giày'], ['name' => 'Giày']);

        // Thêm danh mục con cho "Áo"
        $categories_ao = [
            ['name' => 'Áo thun', 'parent_id' => $ao->id],
            ['name' => 'Áo sơ mi', 'parent_id' => $ao->id],
            ['name' => 'Áo khoác', 'parent_id' => $ao->id],
        ];

        foreach ($categories_ao as $cat) {
            Category::firstOrCreate(['name' => $cat['name'], 'parent_id' => $cat['parent_id']], $cat);
        }

        // Thêm danh mục con cho "Quần"
        $categories_quan = [
            ['name' => 'Quần jean', 'parent_id' => $quan->id],
            ['name' => 'Quần short', 'parent_id' => $quan->id],
        ];

        foreach ($categories_quan as $cat) {
            Category::firstOrCreate(['name' => $cat['name'], 'parent_id' => $cat['parent_id']], $cat);
        }

        // Thêm danh mục con cho "Giày"
        $categories_giay = [
            ['name' => 'Giày thể thao', 'parent_id' => $giay->id],
            ['name' => 'Giày lười', 'parent_id' => $giay->id],
        ];

        foreach ($categories_giay as $cat) {
            Category::firstOrCreate(['name' => $cat['name'], 'parent_id' => $cat['parent_id']], $cat);
        }
    }
}
