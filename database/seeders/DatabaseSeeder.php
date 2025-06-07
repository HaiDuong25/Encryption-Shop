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
UserSeeder::class,
    AccountSeeder::class,      // Account thường liên quan User
    BrandSeeder::class,        // Brand trước Product nếu Product có brand_id
    CategorySeeder::class,     // Category trước Product nếu Product có category_id
    ColorSeeder::class,        // Color trước Product nếu Product có color_id
    SizeSeeder::class,         // Size trước Product nếu Product có size_id

    ProductSeeder::class,      // Product có thể tham chiếu Brand, Category, Color, Size
    BannerSeeder::class,       // Banner có thể không phụ thuộc bảng khác, hoặc phụ thuộc Product
    ProductVariantSeeder::class,
    CouponSeeder::class,       // Coupon độc lập hoặc phụ thuộc User
PaymentMethodSeeder::class,// PaymentMethod trước Payment và Order
    OrderSeeder::class,        // Order phụ thuộc User, PaymentMethod, Coupon, Status

    PaymentSeeder::class,      // Payment phụ thuộc Order, PaymentMethod

    OrderDetailSeeder::class,  // OrderDetail phụ thuộc Order, Product

    CommentSeeder::class,      // Comment có thể phụ thuộc User và Product

    RateSeeder::class,         // Rate phụ thuộc User và Product
    RateReplySeeder::class,    // RateReply phụ thuộc Rate và User

    StatusSeeder::class,       // Status thường dùng để trạng thái cho Order hoặc sản phẩm
    ContactSeeder::class       // Contact thường độc lập hoặc phụ thuộc User
]);

    }
}
