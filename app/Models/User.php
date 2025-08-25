<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'status',
        'avatar',
        'cover_image',
        'bio',
        'date_of_birth',
        'gender'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // 🔹 Check quyền
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isActive()
    {
        return $this->status === 'active';
    }

    // 🔹 Quan hệ
    public function shippingAddresses()
    {
        return $this->hasMany(ShippingAddress::class);
    }

    public function couponUses()
    {
        return $this->hasMany(CouponUse::class);
    }

    public function savedCoupons()
    {
        return $this->hasMany(UserSavedCoupon::class);
    }

    public function savedCouponsWithDetails()
    {
        return $this->belongsToMany(Coupon::class, 'user_saved_coupons', 'user_id', 'coupon_id')
            ->withTimestamps()
            ->withPivot('saved_at');
    }

    // 🔹 Quan hệ với ví
    public function wallet()
    {
        return $this->hasOne(UserWallet::class, 'user_id');
    }

    public function walletTransactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }

    // 🔹 Lấy hoặc tạo ví cho user
    public function getOrCreateWallet()
    {
        if (!$this->wallet) {
            return UserWallet::create([
                'user_id' => $this->id,
                'balance' => 0,
            ]);
        }
        return $this->wallet;
    }

    // 🔹 Cộng tiền vào ví
    public function addToWallet($amount, $description = null)
    {
        $wallet = $this->getOrCreateWallet();
        $wallet->balance += $amount;
        $wallet->save();

        $this->walletTransactions()->create([
            'amount'      => $amount,
            'type'        => 'refund',
            'description' => $description,
        ]);
    }

    // 🔹 Trừ tiền trong ví
    public function deductFromWallet($amount, $description = null)
    {
        $wallet = $this->getOrCreateWallet();

        if ($wallet->balance >= $amount) {
            $wallet->balance -= $amount;
            $wallet->save();

            $this->walletTransactions()->create([
                'amount'      => -$amount,
                'type'        => 'withdraw',
                'description' => $description,
            ]);

            return true;
        }
        return false;
    }

    // 🔹 Check sản phẩm đã mua
    public function hasPurchasedProduct($productId)
    {
        return OrderDetail::whereHas('order', function ($query) {
            $query->where('user_id', $this->id)
                  ->where('status', 'completed');
        })->where('product_id', $productId)->exists();
    }

    public function bankAccounts()
    {
        return $this->hasMany(BankAccount::class, 'user_id');
    }
}
