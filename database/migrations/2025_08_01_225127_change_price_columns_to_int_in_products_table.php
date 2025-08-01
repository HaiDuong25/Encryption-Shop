<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Products table
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedInteger('price')->nullable()->change();
            $table->unsignedInteger('sale_price')->nullable()->change();
        });

        // Product variants table
        Schema::table('product_variants', function (Blueprint $table) {
            $table->unsignedInteger('price')->nullable()->change();
            $table->unsignedInteger('sale_price')->nullable()->change();
        });

        // Coupons table
        Schema::table('coupons', function (Blueprint $table) {
            $table->unsignedInteger('min_order_amount')->nullable()->change();
            $table->unsignedInteger('max_discount_amount')->nullable()->change();
        });

        // Orders table
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedInteger('total_price')->change();
            $table->unsignedInteger('total')->nullable()->change();
            $table->unsignedInteger('discount')->default(0)->change();
            $table->unsignedInteger('coupon_discount')->default(0)->change();
            $table->unsignedInteger('subtotal')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Products table
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('price', 12, 2)->nullable()->change();
            $table->decimal('sale_price', 12, 2)->nullable()->change();
        });

        // Product variants table
        Schema::table('product_variants', function (Blueprint $table) {
            $table->decimal('price', 12, 2)->nullable()->change();
            $table->decimal('sale_price', 12, 2)->nullable()->change();
        });

        // Coupons table
        Schema::table('coupons', function (Blueprint $table) {
            $table->decimal('min_order_amount', 15, 2)->nullable()->change();
            $table->decimal('max_discount_amount', 15, 2)->nullable()->change();
        });

        // Orders table
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('total_price', 15, 2)->change();
            $table->decimal('total', 15, 2)->nullable()->change();
            $table->decimal('discount', 15, 2)->default(0)->change();
            $table->decimal('coupon_discount', 15, 2)->default(0)->change();
            $table->decimal('subtotal', 15, 2)->default(0)->change();
        });
    }
};
