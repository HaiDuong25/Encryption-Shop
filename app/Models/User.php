<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
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

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isActive()
    {
        return $this->status === 'active';
    }

    /**
     * Get all shipping addresses for the user.
     */
    public function shippingAddresses()
    {
        return $this->hasMany(ShippingAddress::class);
    }

    /**
     * Get all coupon uses for the user.
     */
    public function couponUses()
    {
        return $this->hasMany(CouponUse::class);
    }

    /**
     * Get all saved coupons for the user.
     */
    public function savedCoupons()
    {
        return $this->hasMany(UserSavedCoupon::class);
    }

    /**
     * Get saved coupons with coupon details
     */
    public function savedCouponsWithDetails()
    {
        return $this->belongsToMany(Coupon::class, 'user_saved_coupons', 'user_id', 'coupon_id')
                    ->withTimestamps()
                    ->withPivot('saved_at');
    }

    /**
     * Kiểm tra user đã sử dụng coupon này chưa
     */
    public function hasUsedCoupon($couponId)
    {
        return $this->couponUses()->where('coupon_id', $couponId)->exists();
    }

    /**
     * Kiểm tra user đã lưu coupon này chưa
     */
    public function hasSavedCoupon($couponId)
    {
        return $this->savedCoupons()->where('coupon_id', $couponId)->exists();
    }

    /**
     * Get the user's wallet
     */
    public function wallet()
    {
        return $this->hasOne(UserWallet::class);
    }

    /**
     * Get wallet transactions
     */
    public function walletTransactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }

    /**
     * Get or create user wallet
     */
    public function getOrCreateWallet()
    {
        if (!$this->wallet) {
            UserWallet::create([
                'user_id' => $this->id,
                'balance' => 0
            ]);
        }
        return $this->wallet;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'pin_code_hash'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }public function hasPurchasedProduct($productId)
{
    return OrderDetail::whereHas('order', function ($query) {
        $query->where('user_id', $this->id)
              ->where('status', 'completed');
    })->where('product_id', $productId)->exists();
}

public function bankAccounts()
{
    return $this->hasMany(\App\Models\BankAccount::class, 'user_id');
}

public function hasWalletPin(): bool
{
    return (bool) $this->pin_code_hash;
}

public function verifyWalletPin(string $pin): bool
{
    if (!$this->pin_code_hash) return false;
    return Hash::check($pin, $this->pin_code_hash);
}


}
