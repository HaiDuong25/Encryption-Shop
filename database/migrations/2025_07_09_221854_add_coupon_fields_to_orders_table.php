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
            $table->decimal('subtotal', 12, 2)->nullable()->after('total_price')->comment('Tổng tiền trước khi giảm giá');
            $table->string('coupon_code', 50)->nullable()->after('subtotal')->comment('Mã giảm giá đã sử dụng');
            $table->decimal('discount_amount', 12, 2)->default(0)->after('coupon_code')->comment('Số tiền được giảm');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['subtotal', 'coupon_code', 'discount_amount']);
        });
    }
};
