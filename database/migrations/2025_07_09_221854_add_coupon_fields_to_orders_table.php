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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('coupon_code')->nullable()->after('discount_id');
            $table->decimal('coupon_discount', 15, 2)->default(0)->after('coupon_code');
            $table->enum('coupon_type', ['percentage', 'fixed'])->nullable()->after('coupon_discount');
            $table->decimal('subtotal', 15, 2)->default(0)->after('coupon_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['coupon_code', 'coupon_discount', 'coupon_type', 'subtotal']);
        });
    }
};
