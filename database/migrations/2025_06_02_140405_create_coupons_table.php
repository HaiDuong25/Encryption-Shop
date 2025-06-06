<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
          $table->id();
$table->string('code')->unique();
$table->integer('discount')->default(0);
$table->dateTime('expires_at')->nullable();
$table->boolean('is_active')->default(true);
$table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('coupons');
    }
}