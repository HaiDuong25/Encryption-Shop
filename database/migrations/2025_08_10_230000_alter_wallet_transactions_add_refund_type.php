<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Thêm giá trị 'refund' vào ENUM trực tiếp (MySQL/MariaDB)
        // Nếu đã tồn tại thì bỏ qua lỗi bằng try/catch
        try {
            DB::statement("ALTER TABLE `wallet_transactions` MODIFY `type` ENUM('deposit','payment','refund') NOT NULL");
        } catch (\Throwable $e) {
            // Ghi log nhưng không fail migration nếu đã có
            logger()->warning('Alter enum add refund skipped: ' . $e->getMessage());
        }
    }

    public function down(): void
    {
        // Cảnh báo: nếu có dữ liệu type=refund sẽ lỗi. Bọc try/catch.
        try {
            DB::statement("ALTER TABLE `wallet_transactions` MODIFY `type` ENUM('deposit','payment') NOT NULL");
        } catch (\Throwable $e) {
            logger()->warning('Revert enum refund skipped: ' . $e->getMessage());
        }
    }
};
