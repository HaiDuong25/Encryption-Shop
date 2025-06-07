<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->string('phone');
            $table->string('address');
            $table->decimal('total_price', 15, 2);
            $table->tinyInteger('status')->default(0);

            $table->foreignId('discount_id')->nullable()->constrained('coupons')->onDelete('set null');
            $table->foreignId('payment_method_id')->constrained('payment_methods')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
