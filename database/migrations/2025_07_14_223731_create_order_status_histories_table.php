<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('order_status_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->string('old_status')->nullable(); // trạng thái cũ
            $table->string('new_status');             // trạng thái mới
            $table->text('description')->nullable();  // ghi chú lý do thay đổi
            $table->unsignedBigInteger('changed_by')->nullable(); // người thay đổi
            $table->timestamps();

            // Foreign Keys
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('changed_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_status_histories');
    }
};
