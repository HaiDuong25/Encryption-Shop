<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('rates', function (Blueprint $table) {
            if(!Schema::hasColumn('rates','likes_count')){
                $table->unsignedInteger('likes_count')->default(0)->after('status');
            }
            if(!Schema::hasColumn('rates','dislikes_count')){
                $table->unsignedInteger('dislikes_count')->default(0)->after('likes_count');
            }
            if(!Schema::hasColumn('rates','reports_count')){
                $table->unsignedInteger('reports_count')->default(0)->after('dislikes_count');
            }
        });
    }

    public function down(): void
    {
        Schema::table('rates', function (Blueprint $table) {
            if(Schema::hasColumn('rates','likes_count')) $table->dropColumn('likes_count');
            if(Schema::hasColumn('rates','dislikes_count')) $table->dropColumn('dislikes_count');
            if(Schema::hasColumn('rates','reports_count')) $table->dropColumn('reports_count');
        });
    }
};