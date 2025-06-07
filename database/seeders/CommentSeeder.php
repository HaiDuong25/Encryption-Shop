<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          DB::table('comments')->insert([
            ['product_id' => 1, 'user_id' => 1, 'comment' => 'Bánh rất ngon và mềm.'],
            ['product_id' => 2, 'user_id' => 2, 'comment' => 'Hình ảnh đẹp đúng như mô tả.'],
            ['product_id' => 3, 'user_id' => 3, 'comment' => 'Giá hơi cao nhưng đáng tiền.'],
            ['product_id' => 4, 'user_id' => 4, 'comment' => 'Giao hàng nhanh.'],
            ['product_id' => 5, 'user_id' => 5, 'comment' => 'Sẽ quay lại mua tiếp.'],
        ]);
    }
}
