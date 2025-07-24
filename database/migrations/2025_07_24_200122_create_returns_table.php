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
       Schema::create('returns', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->foreignId('order_id')->constrained()->onDelete('cascade');
    $table->foreignId('order_detail_id')->nullable()->constrained()->onDelete('cascade');
    $table->string('reason');
    $table->text('description')->nullable();
    $table->string('image')->nullable();
    $table->enum('status', ['pending', 'approved', 'rejected', 'returned', 'refunded'])->default('pending');
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('returns');
    }
};
