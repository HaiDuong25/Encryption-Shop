<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
     $this->call([
      UsersTableSeeder::class,
    BannersTableSeeder::class,
    ContactsTableSeeder::class,
    CouponsTableSeeder::class,
    CartsTableSeeder::class,
     OrdersTableSeeder::class,
     OrderDetailsTableSeeder::class,
    PaymentMethodsTableSeeder::class,
    PaymentsTableSeeder::class,         

    RatesTableSeeder::class,
    RateRepliesTableSeeder::class,
            CategorySeeder::class,
            BrandSeeder::class,
            AttributeSeeder::class,
            ProductSeeder::class,
        ]);
    }
}
