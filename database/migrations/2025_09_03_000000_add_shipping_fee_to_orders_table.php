<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('orders', 'shipping_fee')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->unsignedInteger('shipping_fee')->default(0)->after('subtotal');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('orders', 'shipping_fee')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('shipping_fee');
            });
        }
    }
};
