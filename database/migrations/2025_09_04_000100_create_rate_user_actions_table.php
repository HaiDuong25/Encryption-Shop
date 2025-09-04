<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rate_user_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rate_id')->constrained('rates')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('action', ['like','dislike','report']);
            $table->timestamps();
            $table->unique(['rate_id','user_id','action']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rate_user_actions');
    }
};