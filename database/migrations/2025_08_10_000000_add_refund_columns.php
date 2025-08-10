<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (!Schema::hasColumn('orders', 'refunded_amount')) {
                    $table->decimal('refunded_amount', 15, 2)->default(0)->after('total_price');
                }
            });
        }
        if (Schema::hasTable('order_details')) {
            Schema::table('order_details', function (Blueprint $table) {
                if (!Schema::hasColumn('order_details', 'refunded_amount')) {
                    $table->decimal('refunded_amount', 15, 2)->default(0)->after('total_price');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (Schema::hasColumn('orders', 'refunded_amount')) {
                    $table->dropColumn('refunded_amount');
                }
            });
        }
        if (Schema::hasTable('order_details')) {
            Schema::table('order_details', function (Blueprint $table) {
                if (Schema::hasColumn('order_details', 'refunded_amount')) {
                    $table->dropColumn('refunded_amount');
                }
            });
        }
    }
};
