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

        // Thêm các phương thức thanh toán mặc định
        DB::table('payment_methods')->insert([
            [
                'payment_type' => 'COD',
                'description' => 'Thanh toán khi nhận hàng (COD)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'payment_type' => 'Ví Điện Tử MOMO',
                'description' => 'Thanh toán qua ví điện tử MOMO',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'payment_type' => 'Ví Điện Tử ZALOPAY',
                'description' => 'Thanh toán qua ví điện tử ZALOPAY',
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
