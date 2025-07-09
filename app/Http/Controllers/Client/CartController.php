<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
class CartController extends Controller
{

    public function index()
    {
        $carts = Cart::where('user_id', Auth::id())->with('product')->get();
        return view('client.cart.index', compact('carts'));
    }
public function add(Request $request, $productId)
{
    $variantId = $request->input('variant_id');
    $quantity = (int) $request->input('quantity', 1);

    if (!Auth::check()) {
        return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để thêm giỏ hàng.');
    }

    $product = Product::with('variants')->findOrFail($productId);

    if ($variantId) {

        $variant = $product->variants()->where('id', $variantId)->firstOrFail();

        $existing = Cart::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->where('variant_id', $variantId)
            ->first();

        $totalQuantity = $existing ? $existing->quantity + $quantity : $quantity;

        if ($totalQuantity > $variant->stock) {
            return redirect()->back()->with('error', 'Số lượng vượt quá tồn kho biến thể sản phẩm.');
        }

        if ($existing) {
            $existing->quantity = $totalQuantity;
            $existing->save();
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $productId,
                'variant_id' => $variantId,
                'quantity' => $quantity,

            ]);
        }
    } else {
        // Nếu không có biến thể, kiểm tra tồn kho product
        $existing = Cart::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->whereNull('variant_id')
            ->first();

        $totalQuantity = $existing ? $existing->quantity + $quantity : $quantity;

        if ($totalQuantity > $product->stock) {
            return redirect()->back()->with('error', 'Số lượng vượt quá tồn kho sản phẩm.');
        }

        if ($existing) {
            $existing->quantity = $totalQuantity;
            $existing->save();
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $productId,
                'quantity' => $quantity,
            ]);
        }
    }

    $this->updateSessionCart();

    return redirect()->back()->with('success', 'Đã thêm sản phẩm vào giỏ hàng!');
}

private function updateSessionCart()
{
    $carts = Cart::where('user_id', Auth::id())->with(['product', 'variant'])->get();
    $sessionCart = [];

    foreach ($carts as $cart) {
        $image = 'default.jpg';

        // Nếu có variant và variant có ảnh
        if ($cart->variant && $cart->variant->image) {
            $image = 'storage/' . $cart->variant->image;
        }
        // Nếu không có variant nhưng product có ảnh
        elseif ($cart->product && $cart->product->image) {
            $image = 'storage/' . $cart->product->image;
        }

        $sessionCart[$cart->id] = [
            'name' => $cart->product->name,
            'quantity' => $cart->quantity,
            'price' => $cart->variant->price ?? $cart->product->sale_price ?? $cart->product->price,
            'image' => $image,
        ];
    }

    session()->put('cart', $sessionCart);
}



public function update(Request $request, $id)
{
    $cart = Cart::where('user_id', Auth::id())
        ->with(['product', 'variant'])
        ->where('id', $id)
        ->firstOrFail();
    $quantity = (int) $request->quantity;
    $stock = $cart->variant ? $cart->variant->stock : $cart->product->stock;
    if ($quantity > $stock) {
        return redirect()->back()->with('error', 'Số lượng vượt quá tồn kho sản phẩm.');
    }
    $cart->quantity = $quantity;
    $cart->save();
    $this->updateSessionCart();
    return redirect()->back()->with('success', 'Đã cập nhật số lượng!');
}
    public function delete($id)
    {
        $cart = Cart::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
        $cart->delete();
        $this->updateSessionCart();

        return redirect()->back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng!');
    }

    public function checkout()
    {
        $carts = Cart::where('user_id', Auth::id())->with('product')->get();

        // Tính tổng tiền
        $total = 0;
        foreach ($carts as $cart) {
            $total += ($cart->product->sale_price ?? $cart->product->price) * $cart->quantity;
        }

        return view('client.cart.checkout', compact('carts', 'total'));
    }

}
