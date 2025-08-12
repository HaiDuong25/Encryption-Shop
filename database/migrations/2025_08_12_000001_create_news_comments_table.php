<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('news_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('news_id');
            $table->string('name');
            $table->string('email');
            $table->string('website')->nullable();
            $table->text('content');
            $table->boolean('save_info')->default(false);
            $table->timestamps();
            $table->foreign('news_id')->references('id')->on('news')->onDelete('cascade');
        });
    }
    public function down()
    {
        Schema::dropIfExists('news_comments');
    }
};
