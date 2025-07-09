<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Thêm thông tin người đặt hàng (từ tài khoản đăng nhập)
            $table->string('orderer_name')->after('user_id')->comment('Tên người đặt hàng');
            $table->string('orderer_phone')->nullable()->after('orderer_name')->comment('SĐT người đặt hàng');
            $table->string('orderer_email')->nullable()->after('orderer_phone')->comment('Email người đặt hàng');
            
            // Thêm thông tin người nhận hàng
            $table->string('recipient_name')->after('orderer_email')->comment('Tên người nhận hàng');
            $table->string('recipient_phone')->after('recipient_name')->comment('SĐT người nhận hàng');
            $table->text('recipient_address')->after('recipient_phone')->comment('Địa chỉ người nhận hàng');
            $table->string('recipient_email')->nullable()->after('recipient_address')->comment('Email người nhận hàng');
            $table->text('order_notes')->nullable()->after('recipient_email')->comment('Ghi chú đặc biệt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'orderer_name',
                'orderer_phone', 
                'orderer_email',
                'recipient_email',
                'order_notes'
            ]);
        });
    }
};
