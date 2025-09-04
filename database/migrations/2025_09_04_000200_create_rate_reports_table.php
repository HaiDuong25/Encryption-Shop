<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rate_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rate_id')->constrained('rates')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('reason', 100); // e.g. inappropriate, spam, adult, false_info, abuse
            $table->text('note')->nullable(); // optional user note
            $table->enum('status', ['pending','reviewed','dismissed'])->default('pending');
            $table->timestamps();
            $table->unique(['rate_id','user_id']); // một người chỉ báo cáo 1 lần / đánh giá
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rate_reports');
    }
};