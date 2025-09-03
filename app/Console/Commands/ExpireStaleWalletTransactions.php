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
    protected $description = 'Expire wallet DEPOSIT transactions pending > 10 minutes (không đụng tới withdraw)';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Checking for stale wallet transactions...');

        // Chỉ tìm các giao dịch NẠP TIỀN (deposit) pending quá 10 phút
        $expiredTransactions = WalletTransaction::where('status', 'pending')
            ->where('type', 'deposit')
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
                    'description' => $transaction->description . ' (Hết hạn sau 10 phút - auto cancel)'
                ]);
                
                $count++;
                
                Log::info("Expired wallet transaction: {$transaction->transaction_code} for user {$transaction->user_id}");
                
            } catch (\Exception $e) {
                Log::error("Failed to expire wallet transaction {$transaction->transaction_code}: " . $e->getMessage());
            }
        }

    $this->info("Expired {$count} stale wallet DEPOSIT transactions. Withdraw unaffected.");
        
        return 0;
    }
}
