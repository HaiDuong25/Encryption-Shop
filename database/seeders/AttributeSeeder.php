<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attribute;
use App\Models\AttributeValue;

class AttributeSeeder extends Seeder
{
    public function run()
    {
        // Size
        $size = Attribute::firstOrCreate(['name' => 'Size']);
        foreach (['S', 'M', 'L', 'XL'] as $val) {
            AttributeValue::firstOrCreate([
                'attribute_id' => $size->id,
                'value' => $val,
            ]);
        }

        // Màu
        $color = Attribute::firstOrCreate(['name' => 'Màu']);
        foreach (['Đỏ', 'Xanh', 'Đen', 'Trắng'] as $val) {
            AttributeValue::firstOrCreate([
                'attribute_id' => $color->id,
                'value' => $val,
            ]);
        }
    }
}