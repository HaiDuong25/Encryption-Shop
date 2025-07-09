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
        $carts = Cart::where('user_id', Auth::id())->with(['product.category', 'product.brand', 'variant'])->get();
        $total = $carts->sum(function($cart){
            return ($cart->variant->price ?? $cart->product->sale_price ?? $cart->product->price) * $cart->quantity;
        });
        $voucherDiscount = session('voucher_discount', 0);
        $finalTotal = max(0, $total - $voucherDiscount);
        $payment_methods = \App\Models\PaymentMethod::all();
        return view('client.cart.checkout', compact('carts', 'total', 'voucherDiscount', 'finalTotal', 'payment_methods'));
    }

    public function processCheckout(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'payment_method_id' => 'required|exists:payment_methods,id',
        ]);
        $carts = Cart::where('user_id', Auth::id())->with(['product', 'variant'])->get();
        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống!');
        }
        $total = $carts->sum(function($cart){
            return ($cart->variant->price ?? $cart->product->sale_price ?? $cart->product->price) * $cart->quantity;
        });
        $voucherDiscount = session('voucher_discount', 0);
        $finalTotal = max(0, $total - $voucherDiscount);
        // Lưu đơn hàng
        $order = new \App\Models\Order();
        $order->user_id = Auth::id();
        $order->name = $request->name;
        $order->phone = $request->phone;
        $order->address = $request->address;
        $order->total_price = $finalTotal;
        $order->status = 'pending';
        $order->payment_method_id = $request->payment_method_id;
        $order->save();
        // Lưu chi tiết đơn hàng
        foreach ($carts as $cart) {
            $order->orderDetails()->create([
                'product_id' => $cart->product_id,
                'variant_id' => $cart->variant_id,
                'quantity' => $cart->quantity,
                'price' => $cart->variant->price ?? $cart->product->sale_price ?? $cart->product->price,
            ]);
        }
        // Xóa giỏ hàng sau khi thanh toán
        Cart::where('user_id', Auth::id())->delete();
        session()->forget('cart');
        session()->forget('voucher_discount');
        session()->forget('voucher_code');
        session()->forget('voucher_message');
        session()->forget('voucher_error');
        // Chuyển hướng sang trang success, truyền mã đơn hàng
        return redirect()->route('cart.success', ['order_id' => $order->id]);
    }
public function applyVoucher(\Illuminate\Http\Request $request)
{
    $code = $request->input('voucher');
    // Ví dụ: chỉ chấp nhận mã 'GIAM50K'
    if ($code === 'GIAM50K') {
        session([
            'voucher_discount' => 50000,
            'voucher_code' => $code,
            'voucher_message' => 'Áp dụng mã thành công! Đã giảm 50.000đ.'
        ]);
        session()->forget('voucher_error');
    } else {
        session([
            'voucher_discount' => 0,
            'voucher_code' => $code,
            'voucher_error' => 'Mã không hợp lệ hoặc đã hết hạn.'
        ]);
        session()->forget('voucher_message');
    }
    return redirect()->route('cart.index');
}
}