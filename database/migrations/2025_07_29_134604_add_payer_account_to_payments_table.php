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
        Schema::table('payments', function (Blueprint $table) {
            $table->string('payer_account')->nullable()->after('transaction_code'); // Số tài khoản/SĐT người thanh toán
            $table->string('payer_name')->nullable()->after('payer_account'); // Tên người thanh toán
            $table->string('payment_method_type')->nullable()->after('payer_name'); // Loại ví: MoMo, ZaloPay, etc.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['payer_account', 'payer_name', 'payment_method_type']);
        });
    }
};
