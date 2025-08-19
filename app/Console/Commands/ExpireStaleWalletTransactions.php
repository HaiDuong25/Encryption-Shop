<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\WalletTransaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ExpireStaleWalletTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wallet:expire-stale-transactions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire wallet transactions that are pending for more than 10 minutes';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Checking for stale wallet transactions...');

        // Tìm các giao dịch ví đang pending quá 10 phút
        $expiredTransactions = WalletTransaction::where('status', 'pending')
            ->where('created_at', '<', Carbon::now()->subMinutes(10))
            ->get();

        if ($expiredTransactions->isEmpty()) {
            $this->info('No stale transactions found.');
            return 0;
        }

        $count = 0;
        foreach ($expiredTransactions as $transaction) {
            try {
                $transaction->update([
                    'status' => 'failed',
                    'description' => $transaction->description . ' (Hết hạn sau 10 phút)'
                ]);
                
                $count++;
                
                Log::info("Expired wallet transaction: {$transaction->transaction_code} for user {$transaction->user_id}");
                
            } catch (\Exception $e) {
                Log::error("Failed to expire wallet transaction {$transaction->transaction_code}: " . $e->getMessage());
            }
        }

        $this->info("Expired {$count} stale wallet transactions.");
        
        return 0;
    }
}
