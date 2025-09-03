<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'pin_code_hash')) {
                $table->string('pin_code_hash')->nullable()->after('password');
            }
            if (!Schema::hasColumn('users', 'pin_set_at')) {
                $table->timestamp('pin_set_at')->nullable()->after('pin_code_hash');
            }
            if (!Schema::hasColumn('users', 'pin_failed_attempts')) {
                $table->unsignedTinyInteger('pin_failed_attempts')->default(0)->after('pin_set_at');
            }
            if (!Schema::hasColumn('users', 'pin_locked_until')) {
                $table->timestamp('pin_locked_until')->nullable()->after('pin_failed_attempts');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'pin_code_hash')) {
                $table->dropColumn('pin_code_hash');
            }
            if (Schema::hasColumn('users', 'pin_set_at')) {
                $table->dropColumn('pin_set_at');
            }
            if (Schema::hasColumn('users', 'pin_failed_attempts')) {
                $table->dropColumn('pin_failed_attempts');
            }
            if (Schema::hasColumn('users', 'pin_locked_until')) {
                $table->dropColumn('pin_locked_until');
            }
        });
    }
};
