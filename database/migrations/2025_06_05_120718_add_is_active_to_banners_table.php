<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
{
    Schema::table('banners', function (Blueprint $table) {
        $table->boolean('is_active')->default(true);
    });
}

public function down()
{
    Schema::table('banners', function (Blueprint $table) {
        $table->dropColumn('is_active');
    });
}
};