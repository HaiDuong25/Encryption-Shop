<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentMethodsTable extends Migration
{
    public function up()
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('payment_type');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Thêm 2 phương thức thanh toán mặc định
        DB::table('payment_methods')->insert([
            [
                'payment_type' => 'Chuyển Khoản',
                'description' => 'Thanh toán qua chuyển khoản ngân hàng',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'payment_type' => 'COD',
                'description' => 'Thanh toán khi nhận hàng (COD)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('payment_methods');
    }
}
