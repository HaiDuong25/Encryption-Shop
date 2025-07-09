<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Coupon;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{

    public function index()
    {
        $carts = Cart::where('user_id', Auth::id())->with('product')->get();
        $totals = $this->calculateTotalWithCoupon($carts);
        
        return view('client.cart.index', compact('carts', 'totals'));
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
        $currentInCart = $existing ? $existing->quantity : 0;

        if ($totalQuantity > $variant->stock) {
            return redirect()->back()->with('error', "Xin lỗi! Sản phẩm này chỉ còn {$variant->stock} sản phẩm trong kho. Bạn đã có {$currentInCart} trong giỏ hàng.");
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
        $currentInCart = $existing ? $existing->quantity : 0;

        if ($totalQuantity > $product->stock) {
            return redirect()->back()->with('error', "Xin lỗi! Sản phẩm này chỉ còn {$product->stock} sản phẩm trong kho. Bạn đã có {$currentInCart} trong giỏ hàng.");
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
        $totals = $this->calculateTotalWithCoupon($carts);
        $payment_methods = \App\Models\PaymentMethod::all();
        
        return view('client.cart.checkout', compact('carts', 'totals', 'payment_methods'));
    }

    public function processCheckout(Request $request)
    {
        $request->validate([
            'orderer_name' => 'required|string|max:255',
            'orderer_phone' => 'nullable|string|max:20', 
            'orderer_email' => 'nullable|email',
            'recipient_name' => 'required|string|max:255',
            'recipient_phone' => 'required|string|max:20',
            'recipient_address' => 'required|string|max:500',
            'recipient_email' => 'nullable|email',
            'order_notes' => 'nullable|string|max:1000',
            'payment_method_id' => 'required|exists:payment_methods,id',
        ]);
        
        $carts = Cart::where('user_id', Auth::id())->with(['product', 'variant'])->get();
        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống!');
        }
        
        $totals = $this->calculateTotalWithCoupon($carts);
        
        // Sử dụng database transaction để đảm bảo tính toàn vẹn
        DB::beginTransaction();
        try {
            // Kiểm tra tồn kho trước khi đặt hàng
            foreach ($carts as $cart) {
                if ($cart->variant_id) {
                    $variant = $cart->variant;
                    if (!$variant || $variant->stock < $cart->quantity) {
                        throw new \Exception("Sản phẩm {$cart->product->name} (variant) không đủ số lượng trong kho!");
                    }
                } else {
                    $product = $cart->product;
                    if (!$product || $product->stock < $cart->quantity) {
                        throw new \Exception("Sản phẩm {$cart->product->name} không đủ số lượng trong kho!");
                    }
                }
            }
            
            // Lưu đơn hàng với thông tin người đặt và người nhận riêng biệt
            $order = new \App\Models\Order();
            $order->user_id = Auth::id();
            
            // Thông tin người đặt hàng (từ form)
            $order->orderer_name = $request->orderer_name;
            $order->orderer_phone = $request->orderer_phone;
            $order->orderer_email = $request->orderer_email;
            
            // Thông tin người nhận hàng
            $order->recipient_name = $request->recipient_name;
            $order->recipient_phone = $request->recipient_phone;
            $order->recipient_address = $request->recipient_address;
            $order->recipient_email = $request->recipient_email;
            $order->order_notes = $request->order_notes;
            
            // Thông tin đơn hàng với mã giảm giá
            $order->subtotal = $totals['subtotal'];
            $order->discount_amount = $totals['discount'];
            $order->total_price = $totals['total'];
            $order->status = 'pending';
            $order->payment_method_id = $request->payment_method_id;
            
            // Lưu thông tin mã giảm giá nếu có
            $appliedCoupon = session('applied_coupon');
            if ($appliedCoupon) {
                $order->coupon_code = $appliedCoupon['code'];
            }
            
            $order->save();
            
            // Lưu chi tiết đơn hàng và trừ số lượng tồn kho
            foreach ($carts as $cart) {
                // Tạo chi tiết đơn hàng
                $order->orderDetails()->create([
                    'product_id' => $cart->product_id,
                    'variant_id' => $cart->variant_id,
                    'quantity' => $cart->quantity,
                    'price' => $cart->variant->price ?? $cart->product->sale_price ?? $cart->product->price,
                ]);
                
                // Trừ số lượng tồn kho
                if ($cart->variant_id) {
                    // Nếu có variant, trừ stock của variant
                    $variant = $cart->variant;
                    $variant->decrement('stock', $cart->quantity);
                } else {
                    // Nếu không có variant, trừ stock của product
                    $product = $cart->product;
                    $product->decrement('stock', $cart->quantity);
                }
            }
            
            // Xóa giỏ hàng và mã giảm giá sau khi thanh toán
            Cart::where('user_id', Auth::id())->delete();
            session()->forget(['cart', 'applied_coupon']);
            
            DB::commit();
            
            // Chuyển hướng sang trang success, truyền mã đơn hàng
            return redirect()->route('cart.success', ['order_id' => $order->id]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Áp dụng mã giảm giá
     */
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string|max:50'
        ]);

        $couponCode = strtoupper(trim($request->coupon_code));
        
        // Tìm mã giảm giá
        $coupon = Coupon::where('code', $couponCode)->first();
        
        if (!$coupon) {
            return redirect()->back()->with('error', 'Mã giảm giá không tồn tại!');
        }
        
        if (!$coupon->isValid()) {
            return redirect()->back()->with('error', 'Mã giảm giá đã hết hạn hoặc không còn hiệu lực!');
        }
        
        // Lưu mã giảm giá vào session
        session(['applied_coupon' => [
            'id' => $coupon->id,
            'code' => $coupon->code,
            'discount' => $coupon->discount
        ]]);
        
        return redirect()->back()->with('success', "Áp dụng mã giảm giá \"{$coupon->code}\" thành công!");
    }

    /**
     * Hủy mã giảm giá
     */
    public function removeCoupon()
    {
        session()->forget('applied_coupon');
        return redirect()->back()->with('success', 'Đã hủy mã giảm giá!');
    }

    /**
     * Tính toán tổng tiền với mã giảm giá
     */
    private function calculateTotalWithCoupon($carts)
    {
        $subtotal = $carts->sum(function($cart){
            return ($cart->variant->price ?? $cart->product->sale_price ?? $cart->product->price) * $cart->quantity;
        });
        
        $discount = 0;
        $appliedCoupon = session('applied_coupon');
        
        if ($appliedCoupon) {
            $coupon = Coupon::find($appliedCoupon['id']);
            if ($coupon && $coupon->isValid()) {
                $discount = $coupon->calculateDiscount($subtotal);
            } else {
                // Nếu coupon không còn hợp lệ, xóa khỏi session
                session()->forget('applied_coupon');
            }
        }
        
        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => max(0, $subtotal - $discount)
        ];
    }

}
