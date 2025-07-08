<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
 public function up()
{
    Schema::table('product_variants', function (Blueprint $table) {
        $table->unsignedBigInteger('size_id')->nullable()->after('color_id');

        // Nếu có bảng sizes:
        $table->foreign('size_id')->references('id')->on('sizes')->onDelete('set null');
    });
}

public function down()
{
    Schema::table('product_variants', function (Blueprint $table) {
        $table->dropForeign(['size_id']);
        $table->dropColumn('size_id');
    });
}

};
