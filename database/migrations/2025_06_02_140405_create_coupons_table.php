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
            $table->decimal('discount', 15, 2);
            $table->string('discount_type'); // kiểu giảm giá: % hoặc số tiền
            $table->integer('usage_limit')->default(0);
            $table->date('start_date');
            $table->date('end_date');
            $table->tinyInteger('status')->default(1);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('coupons');
    }
}
