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
            $table->text('notes')->nullable()->after('total_price');
            $table->date('delivery_date')->nullable()->after('notes');
            $table->timestamp('shipped_at')->nullable()->after('delivery_date');
            $table->timestamp('delivered_at')->nullable()->after('shipped_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['notes', 'delivery_date', 'shipped_at', 'delivered_at']);
        });
    }
};
