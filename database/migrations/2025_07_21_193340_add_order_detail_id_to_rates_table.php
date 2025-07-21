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
       Schema::table('rates', function (Blueprint $table) {
    $table->unsignedBigInteger('order_detail_id')->nullable()->after('product_id');

    $table->foreign('order_detail_id')->references('id')->on('order_details')->onDelete('cascade');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rates', function (Blueprint $table) {
            //
        });
    }
};
