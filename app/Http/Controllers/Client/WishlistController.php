<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
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

    public function add($productId)
    {
        $exists = Wishlist::where('user_id', Auth::id())->where('product_id', $productId)->exists();
        if (!$exists) {
            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $productId,
            ]);
        }

        if (request()->ajax()) {
        return response()->json(['success' => true]);
        }

        return back()->with('success', 'Đã thêm vào yêu thích!');
    }

    public function remove($productId)
    {
        Wishlist::where('user_id', Auth::id())->where('product_id', $productId)->delete();
        return back()->with('success', 'Đã xóa khỏi yêu thích!');
    }
}
