<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Thêm 'withdraw' vào ENUM
        DB::statement("ALTER TABLE wallet_transactions MODIFY COLUMN type ENUM('deposit','payment','refund','withdraw') DEFAULT 'deposit'");
    }

    public function down(): void
    {
        // Trước khi rollback, đổi các giá trị 'withdraw' sang 'deposit'
        DB::statement("UPDATE wallet_transactions SET type = 'deposit' WHERE type = 'withdraw'");

        // Loại bỏ 'withdraw' khỏi ENUM
        DB::statement("ALTER TABLE wallet_transactions MODIFY COLUMN type ENUM('deposit','payment','refund') DEFAULT 'deposit'");
    }
};
