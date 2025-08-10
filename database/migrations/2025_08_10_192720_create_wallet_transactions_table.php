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
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->enum('type', ['deposit', 'payment']); // deposit = nạp tiền, payment = thanh toán
            $table->decimal('amount', 15, 2);
            $table->decimal('balance_before', 15, 2); // Số dư trước giao dịch
            $table->decimal('balance_after', 15, 2); // Số dư sau giao dịch
            $table->string('transaction_code')->unique(); // Mã giao dịch
            $table->string('description')->nullable(); // Mô tả giao dịch
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->string('payment_method_type')->nullable(); // MoMo, ZaloPay, etc.
            $table->json('payment_data')->nullable(); // Lưu thêm data từ payment gateway
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['user_id', 'type']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
