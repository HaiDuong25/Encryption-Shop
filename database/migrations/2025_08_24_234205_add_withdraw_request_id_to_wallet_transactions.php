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
    Schema::table('wallet_transactions', function (Blueprint $table) {
        $table->unsignedBigInteger('withdraw_request_id')->nullable()->after('user_id');
        $table->index('withdraw_request_id');
    });
}

public function down()
{
    Schema::table('wallet_transactions', function (Blueprint $table) {
        $table->dropColumn('withdraw_request_id');
    });
}

};
