<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Thêm phương thức thanh toán bằng số dư ví
        DB::table('payment_methods')->insert([
            'payment_type' => 'Số dư ví',
            'description' => 'Thanh toán bằng số dư trong ví điện tử',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('payment_methods')->where('payment_type', 'Số dư ví')->delete();
    }
};
