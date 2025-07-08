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
    Schema::create('colors', function (Blueprint $table) {
        $table->id();
        $table->string('name')->unique();        // Tên màu (Red, Green, ...)
        $table->string('color_code')->nullable(); // Mã hex màu (#FF0000, ...)
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('colors');
}

};
