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
        Schema::table('coupons', function (Blueprint $table) {
            // Thêm các cột còn thiếu để kiểm soát giới hạn giảm giá tốt hơn
            $table->text('description')->nullable()->after('code')->comment('Mô tả chi tiết về mã giảm giá');
            $table->boolean('is_one_time_per_user')->default(true)->after('usage_limit')->comment('Mỗi user chỉ được sử dụng 1 lần');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn(['description', 'is_one_time_per_user']);
        });
    }
};
