<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('return_requests', function (Blueprint $table) {
            $table->string('bank_account_name')->nullable()->after('payment_method');
            $table->string('bank_account_number')->nullable()->after('bank_account_name');
        });
    }

    public function down(): void
    {
        Schema::table('return_requests', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'bank_account_name', 'bank_account_number']);
        });
    }
};

