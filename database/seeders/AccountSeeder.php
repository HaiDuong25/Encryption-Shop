<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */public function run(): void
{
    // Xóa sạch dữ liệu an toàn (không gây lỗi khóa ngoại)
    DB::table('accounts')->delete();

    // Nếu muốn reset AUTO_INCREMENT
    DB::statement('ALTER TABLE accounts AUTO_INCREMENT = 1');

    DB::table('accounts')->insert([
        [
            'name' => 'Nguyễn Văn A',
            'email' => 'a@example.com',
            'role' => 'user',
            'address' => 'Hà Nội',
            'password' => Hash::make('password123'),
            'status' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ],
            [
                'name' => 'Trần Thị B',
                'email' => 'b@example.com',
                'role' => 'admin',
                'address' => 'Hồ Chí Minh',
                'password' => Hash::make('password123'),
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Lê Văn C',
                'email' => 'c@example.com',
                'role' => 'user',
                'address' => 'Đà Nẵng',
                'password' => Hash::make('password123'),
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Phạm Thị D',
                'email' => 'd@example.com',
                'role' => 'user',
                'address' => 'Cần Thơ',
                'password' => Hash::make('password123'),
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Hoàng Văn E',
                'email' => 'e@example.com',
                'role' => 'user',
                'address' => 'Hải Phòng',
                'password' => Hash::make('password123'),
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
