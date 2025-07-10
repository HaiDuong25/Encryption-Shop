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
            // Remove old fields that are no longer needed
            if (Schema::hasColumn('orders', 'name')) {
                $table->dropColumn('name');
            }
            if (Schema::hasColumn('orders', 'phone')) {
                $table->dropColumn('phone');
            }
            if (Schema::hasColumn('orders', 'address')) {
                $table->dropColumn('address');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Re-add the removed fields
            $table->string('name')->nullable()->after('user_id');
            $table->string('phone')->nullable()->after('name');
            $table->string('address')->nullable()->after('phone');
        });
    }
};
