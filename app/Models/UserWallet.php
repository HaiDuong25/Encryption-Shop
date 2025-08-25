<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserWallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'balance'
    ];

    protected $casts = [
        'balance' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(WalletTransaction::class, 'user_id', 'user_id');
    }

    /**
     * Thêm tiền vào ví
     */
    public function addBalance($amount, $description = null, $transactionCode = null, $paymentMethodType = null, $paymentData = null)
    {
        $balanceBefore = $this->balance;
        $this->balance += $amount;
        $this->save();

        // Tạo transaction log
        WalletTransaction::create([
            'user_id' => $this->user_id,
            'type' => 'deposit',
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $this->balance,
            'transaction_code' => $transactionCode ?? 'DEP_' . time() . '_' . $this->user_id,
            'description' => $description ?? 'Nạp tiền vào ví',
            'status' => 'completed',
            'payment_method_type' => $paymentMethodType,
            'payment_data' => $paymentData
        ]);

        return $this;
    }

    /**
     * Trừ tiền từ ví
     */
    public function subtractBalance($amount, $description = null, $transactionCode = null, $paymentMethodType = 'WALLET')
    {
        if ($this->balance < $amount) {
            throw new \Exception('Số dư không đủ');
        }

    $balanceBefore = $this->balance;
    $newBalance = (float)$this->balance - (float)$amount;
    $this->setAttribute('balance', $newBalance); // cast sẽ xử lý
        $this->save();

        \Log::info('Wallet subtract', [
            'user_id' => $this->user_id,
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $this->balance,
            'transaction_code' => $transactionCode,
            'description' => $description,
        ]);

        // Tạo transaction log
        WalletTransaction::create([
            'user_id' => $this->user_id,
            'type' => 'payment',
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $this->balance,
            'transaction_code' => $transactionCode ?? 'PAY_' . time() . '_' . $this->user_id,
            'description' => $description ?? 'Thanh toán bằng ví',
            'status' => 'completed',
            'payment_method_type' => $paymentMethodType
        ]);

        return $this;
    }

    /**
     * Hoàn tiền vào ví (refund)
     */
    public function refundBalance($amount, $description = null, $transactionCode = null)
    {
    $balanceBefore = $this->balance;
    $newBalance = (float)$this->balance + (float)$amount;
    $this->setAttribute('balance', $newBalance);
        $this->save();

        \Log::info('Wallet refund', [
            'user_id' => $this->user_id,
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $this->balance,
            'transaction_code' => $transactionCode,
            'description' => $description,
        ]);

        WalletTransaction::create([
            'user_id' => $this->user_id,
            'type' => 'refund',
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $this->balance,
            'transaction_code' => $transactionCode ?? 'REF_' . time() . '_' . $this->user_id,
            'description' => $description ?? 'Hoàn tiền về ví',
            'status' => 'completed'
        ]);

        return $this;
    }

    /**
     * Kiểm tra số dư có đủ hay không
     */
    public function hasEnoughBalance($amount)
    {
        return $this->balance >= $amount;
    }
}