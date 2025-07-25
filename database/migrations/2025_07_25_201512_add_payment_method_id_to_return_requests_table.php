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
      // database/migrations/xxxx_add_payment_method_id_to_return_requests_table.php
Schema::table('return_requests', function (Blueprint $table) {
    $table->unsignedBigInteger('payment_method_id')->nullable()->after('order_detail_id');
    $table->foreign('payment_method_id')->references('id')->on('payment_methods')->onDelete('set null');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('return_requests', function (Blueprint $table) {
            //
        });
    }
};
