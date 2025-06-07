<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->delete();
        DB::statement('ALTER TABLE users AUTO_INCREMENT = 1');

        DB::table('users')->insert([
            [
                'name' => 'Nguyễn Văn A',
                'email' => 'a@example.com',
                'role' => 'user',
                'phone' => '0123456789',
                'address' => 'Hà Nội',
                'status' => 'active',
                'avatar' => null,
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('password123'),
                'remember_token' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Trần Thị B',
                'email' => 'b@example.com',
                'role' => 'admin',
                'phone' => '0987654321',
                'address' => 'TP HCM',
                'status' => 'active',
                'avatar' => null,
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('password123'),
                'remember_token' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Lê Văn C',
                'email' => 'c@example.com',
                'role' => 'staff',
                'phone' => '0912345678',
                'address' => 'Đà Nẵng',
                'status' => 'pending',
                'avatar' => null,
                'email_verified_at' => null,
                'password' => Hash::make('password123'),
                'remember_token' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
