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
    Schema::create('withdraw_requests', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->foreignId('bank_account_id')->constrained()->onDelete('cascade'); // STK user chọn để rút
    $table->decimal('amount', 15, 2);  // số tiền rút
    $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
    $table->text('note')->nullable(); // admin ghi chú khi duyệt
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdraw_requests');
    }
};
