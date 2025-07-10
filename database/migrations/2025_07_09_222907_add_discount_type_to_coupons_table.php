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
        Schema::table('coupons', function (Blueprint $table) {
            $table->enum('discount_type', ['percentage', 'fixed'])->default('percentage')->after('discount');
            $table->decimal('min_order_amount', 15, 2)->nullable()->after('discount_type');
            $table->decimal('max_discount_amount', 15, 2)->nullable()->after('min_order_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn(['discount_type', 'min_order_amount', 'max_discount_amount']);
        });
    }
};
