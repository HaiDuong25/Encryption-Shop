<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->string('status')->default('pending')->after('quantity'); // trạng thái sản phẩm: pending, cancelled
            $table->string('cancel_reason')->nullable()->after('status');    // lý do hủy
            $table->text('cancel_note')->nullable()->after('cancel_reason'); // ghi chú hủy
        });
    }

    public function down()
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->dropColumn(['status', 'cancel_reason', 'cancel_note']);
        });
    }
};
