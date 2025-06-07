<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContactSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('contacts')->insert([
            [
                'user_id' => 1,
                'name' => 'Nguyen Van A',
                'email' => 'a@example.com',
                'phone' => '0123456789',
                'content' => 'Tôi muốn hỏi về đơn hàng.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'name' => 'Tran Thi B',
                'email' => 'b@example.com',
                'phone' => '0987654321',
                'content' => 'Shop có giao hàng ngoại thành không?',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'name' => 'Le Van C',
                'email' => 'c@example.com',
                'phone' => null,
                'content' => 'Mình muốn đổi sản phẩm.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'name' => 'Pham Thi D',
                'email' => 'd@example.com',
                'phone' => null,
                'content' => 'Hỗ trợ mình tạo tài khoản nhé.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'name' => 'Hoang Van E',
                'email' => 'e@example.com',
                'phone' => '0909090909',
                'content' => 'Tôi không nhận được mã giảm giá.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
