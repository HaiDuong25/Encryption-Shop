<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            // Thay đổi cột user_id để cho phép giá trị NULL
            // Đảm bảo kiểu dữ liệu khớp (unsignedBigInteger)
            $table->unsignedBigInteger('user_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            // Hoàn tác: Nếu muốn, đặt lại user_id thành NOT NULL
            // Cẩn thận: Nếu đã có dữ liệu user_id là NULL thì việc này sẽ gây lỗi khi rollback
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
        });
    }
};
