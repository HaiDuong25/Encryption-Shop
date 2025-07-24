<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('return_requests', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('order_detail_id');
        $table->text('reason');
        $table->string('image')->nullable(); // nếu khách đính kèm ảnh sản phẩm lỗi
        $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
        $table->timestamps();

        $table->foreign('order_detail_id')->references('id')->on('order_details')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_requests');
    }
};
