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
        $table->unsignedBigInteger('color_id')->nullable()->after('product_id');

        // Nếu bạn muốn tạo khóa ngoại:
        $table->foreign('color_id')->references('id')->on('colors')->onDelete('set null');
    });
}

public function down()
{
    Schema::table('product_variants', function (Blueprint $table) {
        $table->dropForeign(['color_id']);
        $table->dropColumn('color_id');
    });
}

};
