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
            // Chỉ thêm các column chưa có
            if (!Schema::hasColumn('orders', 'payment_status')) {
                $table->string('payment_status')->default('pending')->after('status');
            }
            if (!Schema::hasColumn('orders', 'transaction_id')) {
                $table->string('transaction_id')->nullable()->after('payment_status');
            }
            if (!Schema::hasColumn('orders', 'total')) {
                $table->decimal('total', 15, 2)->nullable()->after('transaction_id');
            }
            if (!Schema::hasColumn('orders', 'discount')) {
                $table->decimal('discount', 15, 2)->default(0)->after('total');
            }
            if (!Schema::hasColumn('orders', 'shipping_address_id')) {
                $table->unsignedBigInteger('shipping_address_id')->nullable()->after('payment_method_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'shipping_address_id')) {
                $table->dropColumn('shipping_address_id');
            }
            if (Schema::hasColumn('orders', 'payment_status')) {
                $table->dropColumn('payment_status');
            }
            if (Schema::hasColumn('orders', 'transaction_id')) {
                $table->dropColumn('transaction_id');
            }
            if (Schema::hasColumn('orders', 'total')) {
                $table->dropColumn('total');
            }
            if (Schema::hasColumn('orders', 'discount')) {
                $table->dropColumn('discount');
            }
        });
    }
};
