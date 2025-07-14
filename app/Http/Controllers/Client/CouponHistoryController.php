<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CouponUse;
use Illuminate\Support\Facades\Auth;

class CouponHistoryController extends Controller
{
    /**
     * Hiển thị lịch sử sử dụng coupon của user
     */
    public function index()
    {
        $couponUses = CouponUse::with(['coupon', 'order'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('client.coupon-history', compact('couponUses'));
    }
}
