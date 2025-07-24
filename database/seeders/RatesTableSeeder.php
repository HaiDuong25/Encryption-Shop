<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

class RatesTableSeeder extends Seeder
{
    public function run()
    {
        // Lấy 5 sản phẩm mới nhất
        $products = Product::orderBy('id', 'desc')->take(5)->get();

        $demoUsers = [1, 2, 3]; // Giả sử đã có user_id 1, 2, 3
        $contents = [
            'Sản phẩm tuyệt vời!',
            'Chất lượng ổn trong tầm giá.',
            'Mẫu mã đẹp, sẽ ủng hộ tiếp.',
            'Rất hài lòng với dịch vụ.',
            'Đóng gói chắc chắn, giao hàng nhanh.',
        ];

        $rates = [];

        foreach ($products as $index => $product) {
            $rates[] = [
                'user_id' => $demoUsers[$index % count($demoUsers)],
                'product_id' => $product->id,
                'score' => rand(4, 5),
                'content' => $contents[$index],
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('rates')->insert($rates);

        $this->command->info("✅ Đã thêm 5 đánh giá demo cho 5 sản phẩm.");
    }
}
