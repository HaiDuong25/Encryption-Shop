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
        // Check if orders table exists before updating
        if (!Schema::hasTable('orders')) {
            throw new \Exception('Orders table does not exist. Please run create_orders_table migration first.');
        }

        Schema::table('orders', function (Blueprint $table) {
            // First add new column
            $table->string('status_str')->default('pending')->after('status');
            
            // Convert existing numeric status to string
            DB::table('orders')->orderBy('id')->each(function ($order) {
                $statusMap = [
                    0 => 'pending',
                    1 => 'confirmed',
                    2 => 'shipping',
                    3 => 'delivering',
                    4 => 'received',
                    5 => 'completed'
                ];
                DB::table('orders')
                    ->where('id', $order->id)
                    ->update(['status_str' => $statusMap[$order->status] ?? 'pending']);
            });

            // Drop old status column
            $table->dropColumn('status');
            
            // Rename new column to status
            $table->renameColumn('status_str', 'status');
            
            // Then modify the column type
            $table->string('status')->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Check if orders table exists before reverting
        if (!Schema::hasTable('orders')) {
            return; // Table doesn't exist, nothing to revert
        }

        Schema::table('orders', function (Blueprint $table) {
            // First add new column for old numeric status
            $table->integer('status_int')->default(0)->after('status');
            
            // Convert string status back to numeric
            DB::table('orders')->orderBy('id')->each(function ($order) {
                $statusMap = [
                    'pending' => 0,
                    'confirmed' => 1,
                    'shipping' => 2,
                    'delivering' => 3,
                    'received' => 4,
                    'completed' => 5
                ];
                DB::table('orders')
                    ->where('id', $order->id)
                    ->update(['status_int' => $statusMap[$order->status] ?? 0]);
            });

            // Drop string status column
            $table->dropColumn('status');
            
            // Rename numeric column to status and set type
            $table->renameColumn('status_int', 'status');
            $table->integer('status')->default(0)->change();
        });
    }
};
