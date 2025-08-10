<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class WalletPaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kiểm tra xem đã có payment method wallet chưa
        $existingWallet = PaymentMethod::where('payment_type', 'Số dư ví')->first();
        
        if (!$existingWallet) {
            PaymentMethod::create([
                'payment_type' => 'Số dư ví',
                'description' => 'Thanh toán bằng số dư trong ví điện tử',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
