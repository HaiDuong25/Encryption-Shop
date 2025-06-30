<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'role' => 'admin',
                'phone' => '1234567890',
                'address' => '123 Admin St, Admin City',
                'status' => 'active',
                'avatar' => null,
                'email_verified_at' => now(),
                'password' => bcrypt('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => ' User',
                'email' => 'user@example.com',
                'role' => 'user',
                'phone' => '0987654321',
                'address' => '456 Staff Rd, Staff Town',
                'status' => 'active',
                'avatar' => null,
                'email_verified_at' => now(),
                'password' => bcrypt('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Regular User',
                'email' => 'user@example.com',
                'role' => 'user',
                'phone' => '1122334455',
                'address' => '789 User Ave, User City',
                'status' => 'active',
                'avatar' => null,
                'email_verified_at' => now(),
                'password' => bcrypt('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
