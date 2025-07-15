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
        // Cập nhật enum status để thêm 'completed'
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        
        Schema::table('payments', function (Blueprint $table) {
            $table->enum('status', ['pending', 'completed', 'rejected'])->default('pending')->after('payment_method_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        
        Schema::table('payments', function (Blueprint $table) {
            $table->enum('status', ['pending', 'confirmed', 'rejected'])->default('pending')->after('payment_method_id');
        });
    }
};
