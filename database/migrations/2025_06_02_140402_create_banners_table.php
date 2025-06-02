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
            $table->string('image')->nullable();
            $table->string('link')->nullable();
            $table->string('location')->nullable();
            $table->integer('views')->default(0);
            $table->tinyInteger('status')->default(1);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('banners');
    }
}
