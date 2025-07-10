<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Wishlist::where('user_id', Auth::id())->with('product')->get();
        return view('client.wishlist.index', compact('wishlists'));
    }

    // public function add($productId)
    // {
    //     $exists = Wishlist::where('user_id', Auth::id())->where('product_id', $productId)->exists();
    //     if (!$exists) {
    //         Wishlist::create([
    //             'user_id' => Auth::id(),
    //             'product_id' => $productId,
    //         ]);
    //     }

    //     return back()->with('success', 'Đã thêm vào yêu thích!');
    // }
public function add($productId)
{
    // ⚠️ Chỉ dùng tạm để test khi chưa đăng nhập
    $userId = 1; // Giả lập user ID = 1

    if (!Wishlist::where('user_id', $userId)->where('product_id', $productId)->exists()) {
        Wishlist::create([
            'user_id' => $userId,
            'product_id' => $productId,
        ]);
    }

    return back()->with('success', 'Đã thêm vào yêu thích!');
}

    public function remove($productId)
    {
        Wishlist::where('user_id', Auth::id())->where('product_id', $productId)->delete();
        return back()->with('success', 'Đã xóa khỏi yêu thích!');
    }
}
