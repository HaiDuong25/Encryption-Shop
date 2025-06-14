<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannersTable extends Migration
{
    public function up()
    {
Schema::create('banners', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->string('image');
    $table->string('link')->nullable();
    $table->integer('position')->default(0);
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
    }

    public function down()
    {
        Schema::dropIfExists('banners');
    }
}