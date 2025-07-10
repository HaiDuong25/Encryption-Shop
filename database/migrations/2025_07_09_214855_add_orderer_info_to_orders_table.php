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
            $table->string('orderer_name')->nullable()->after('user_id');
            $table->string('orderer_email')->nullable()->after('orderer_name');
            $table->string('orderer_phone')->nullable()->after('orderer_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['orderer_name', 'orderer_email', 'orderer_phone']);
        });
    }
};
