<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rate_replies', function (Blueprint $table) {
            $table->id();
            // Khóa ngoại rate_id liên kết với cột id của bảng rates
            $table->foreignId('rate_id')->constrained('rates')->onDelete('cascade');

            // SỬ DỤNG DÒNG NÀY: admin_id liên kết với bảng 'admins'
            $table->foreignId('admin_id')->constrained('admins')->onDelete('cascade');

            $table->text('reply_content');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rate_replies');
    }
};
