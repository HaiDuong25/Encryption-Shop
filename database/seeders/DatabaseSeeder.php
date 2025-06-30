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
    BrandsTableSeeder::class,
    BannersTableSeeder::class,
    ContactsTableSeeder::class,
    CategoriesTableSeeder::class,
    ProductsTableSeeder::class,
    SizesTableSeeder::class,
    ColorsTableSeeder::class,
    CouponsTableSeeder::class,
    CartsTableSeeder::class,

     OrdersTableSeeder::class,
         ProductVariantsTableSeeder::class, // Thêm dòng này

     OrderDetailsTableSeeder::class,
    PaymentMethodsTableSeeder::class,
    PaymentsTableSeeder::class,         // Thêm dòng này

      // Thêm dòng này
    RatesTableSeeder::class,
    RateRepliesTableSeeder::class,
    ]);
    }
}
