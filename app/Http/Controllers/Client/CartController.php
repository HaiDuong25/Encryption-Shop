<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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
        $subtotal = $carts->sum(function($cart){
            return ($cart->variant->sale_price ?? $cart->variant->price ?? $cart->product->sale_price ?? $cart->product->price) * $cart->quantity;
        });

        // Lấy thông tin coupon từ session
        $appliedCoupon = session('applied_coupon');
        $couponDiscount = session('coupon_discount', 0);
        $couponInfo = session('coupon_info', null);
        $total = $subtotal - $couponDiscount;
        
        $payment_methods = \App\Models\PaymentMethod::all();
        
        // Lấy địa chỉ của user hiện tại
        $addresses = [];
        $defaultAddress = null;
        $provinces = [];
        if (Auth::check()) {
            $addresses = Auth::user()->shippingAddresses()->get();
            $defaultAddress = $addresses->where('is_default', 1)->first();
            
            // Load provinces for quick address form
            try {
                $response = Http::timeout(10)->get('https://provinces.open-api.vn/api/p/');
                if ($response->successful()) {
                    $provinceData = $response->json();
                    $provinces = collect($provinceData)->pluck('name')->sort()->values()->toArray();
                }
            } catch (\Exception $e) {
                Log::error('Error loading provinces: ' . $e->getMessage());
                $provinces = ['Hà Nội', 'Hồ Chí Minh', 'Đà Nẵng']; // Fallback
            }
        }
        
        return view('client.cart.checkout', compact('carts', 'subtotal', 'total', 'payment_methods', 'appliedCoupon', 'couponDiscount', 'couponInfo', 'addresses', 'defaultAddress', 'provinces'));
    }

    public function processCheckout(Request $request)
    {
        $request->validate([
            // Địa chỉ giao hàng
            'shipping_address_id' => 'required|exists:shipping_addresses,id',
            
            // Thông tin khác
            'notes' => 'nullable|string|max:1000',
            'coupon_code' => 'nullable|string|max:50',
            'payment_method_id' => 'required|exists:payment_methods,id',
        ]);
        
        // Kiểm tra địa chỉ thuộc về user hiện tại
        $shippingAddress = \App\Models\ShippingAddress::where('id', $request->shipping_address_id)
            ->where('user_id', Auth::id())
            ->first();
            
        if (!$shippingAddress) {
            return redirect()->back()->with('error', 'Địa chỉ giao hàng không hợp lệ!');
        }
        
        $carts = Cart::where('user_id', Auth::id())->with(['product', 'variant'])->get();
        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống!');
        }
        
        // Tính tổng tiền
        $subtotal = $carts->sum(function($cart){
            return ($cart->variant->sale_price ?? $cart->variant->price ?? $cart->product->sale_price ?? $cart->product->price) * $cart->quantity;
        });
        
        // Xử lý mã giảm giá từ session hoặc request
        $discountAmount = 0;
        $couponCode = null;
        $couponType = null;
        $couponValue = 0; // Giá trị gốc của coupon (% hoặc số tiền)
        
        // Ưu tiên sử dụng coupon từ session (đã được validate qua AJAX)
        $sessionCouponCode = session('applied_coupon');
        $sessionDiscountAmount = session('coupon_discount', 0);
        $sessionCouponInfo = session('coupon_info', []);
        
        if ($sessionCouponCode && $sessionDiscountAmount > 0) {
            // Sử dụng thông tin coupon từ session
            $couponCode = $sessionCouponCode;
            $discountAmount = $sessionDiscountAmount;
            if (isset($sessionCouponInfo['type'])) {
                $couponType = $sessionCouponInfo['type'];
                $couponValue = $sessionCouponInfo['value'];
            }
        } elseif ($request->filled('coupon_code')) {
            // Fallback: validate coupon từ request (trường hợp không dùng AJAX)
            $coupon = \App\Models\Coupon::where('code', $request->coupon_code)
                ->where('is_active', 1)
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->first();
                
            if ($coupon) {
                $couponCode = $coupon->code;
                $couponType = $coupon->discount_type;
                $couponValue = $coupon->discount; // Lưu giá trị gốc
                
                // Kiểm tra đơn hàng tối thiểu
                if (!$coupon->min_order_amount || $subtotal >= $coupon->min_order_amount) {
                    if ($coupon->discount_type === 'percentage') {
                        $discountAmount = ($subtotal * $coupon->discount) / 100;
                        // Giới hạn giảm tối đa nếu có
                        if ($coupon->max_discount_amount && $discountAmount > $coupon->max_discount_amount) {
                            $discountAmount = $coupon->max_discount_amount;
                        }
                    } else {
                        $discountAmount = min($coupon->discount, $subtotal);
                    }
                }
            }
        }
        
        $totalPrice = $subtotal - $discountAmount;
        
        // Kiểm tra phương thức thanh toán
        $paymentMethod = \App\Models\PaymentMethod::find($request->payment_method_id);
        
        if ($paymentMethod && $paymentMethod->payment_type === 'Ví Điện Tử MOMO') {
            // Thanh toán MoMo - lưu thông tin vào session trước khi chuyển hướng
            $orderData = [
                'user_id' => Auth::id(),
                'shipping_address_id' => $request->shipping_address_id,
                'payment_method_id' => $request->payment_method_id,
                'subtotal' => $subtotal,
                'discount' => $discountAmount,
                'total' => $totalPrice,
                'notes' => $request->notes,
                'coupon_code' => $couponCode,
                'shipping_address' => $shippingAddress,
                'carts' => $carts->toArray()
            ];
            
            session(['order_data' => $orderData]);
            
            // Chuyển hướng đến MoMo payment
            return redirect()->route('momo.create');
        }
        
        // Thanh toán COD - xử lý bình thường
        
        // Lưu đơn hàng
        $order = new \App\Models\Order();
        $order->user_id = Auth::id();
        
        // Thông tin người đặt hàng (từ user hiện tại)
        $user = Auth::user();
        $order->orderer_name = $user->name;
        $order->orderer_email = $user->email;
        $order->orderer_phone = $user->phone;
        $order->orderer_address = $user->address ?? '';
        
        // Thông tin người nhận hàng (từ shipping address)
        $order->recipient_name = $shippingAddress->name;
        $order->recipient_phone = $shippingAddress->phone;
        $order->recipient_address = $shippingAddress->address_detail . ', ' . $shippingAddress->ward . ', ' . $shippingAddress->district . ', ' . $shippingAddress->province;
        
        // Thông tin đơn hàng
        $order->subtotal = $subtotal;
        $order->coupon_code = $couponCode;
        $order->coupon_discount = $discountAmount; // Lưu số tiền giảm thực tế
        $order->coupon_type = $couponType;
        $order->total_price = $totalPrice;
        $order->notes = $request->notes;
        $order->status = 'pending';
        $order->payment_method_id = $request->payment_method_id;
        $order->save();
        
        // Lưu chi tiết đơn hàng và giảm tồn kho
        foreach ($carts as $cart) {
            $price = $cart->variant->sale_price ?? $cart->variant->price ?? $cart->product->sale_price ?? $cart->product->price;
            
            $order->orderDetails()->create([
                'product_id' => $cart->product_id,
                'variant_id' => $cart->variant_id,
                'quantity' => $cart->quantity,
                'price' => $price,
                'total_price' => $price * $cart->quantity,
            ]);
            
            // Giảm tồn kho
            if ($cart->variant_id) {
                $cart->variant->decrement('stock', $cart->quantity);
            } else {
                $cart->product->decrement('stock', $cart->quantity);
            }
        }
        
        // Xóa giỏ hàng sau khi thanh toán
        Cart::where('user_id', Auth::id())->delete();
        session()->forget('cart');
        session()->forget('voucher_discount');
        session()->forget('voucher_code');
        session()->forget('voucher_message');
        session()->forget('voucher_error');
        
        // Xóa session coupon sau khi đặt hàng thành công
        session()->forget(['applied_coupon', 'coupon_discount', 'coupon_info']);
        
        // Chuyển hướng sang trang success, truyền mã đơn hàng
        return redirect()->route('cart.success', ['order_id' => $order->id])
            ->with('success', 'Đặt hàng thành công! Mã đơn hàng: #' . $order->id);
    }
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string|max:50'
        ]);
        
        $couponCode = $request->coupon_code;
        
        // Tìm mã giảm giá
        $coupon = \App\Models\Coupon::where('code', $couponCode)
            ->where('is_active', 1)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();
            
        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Mã giảm giá không tồn tại hoặc đã hết hạn'
            ]);
        }
        
        // Tính tổng tiền giỏ hàng
        $carts = Cart::where('user_id', Auth::id())->with(['product', 'variant'])->get();
        $subtotal = $carts->sum(function($cart){
            return ($cart->variant->sale_price ?? $cart->variant->price ?? $cart->product->sale_price ?? $cart->product->price) * $cart->quantity;
        });
        
        // Kiểm tra đơn hàng tối thiểu
        if ($coupon->min_order_amount && $subtotal < $coupon->min_order_amount) {
            return response()->json([
                'success' => false,
                'message' => 'Đơn hàng tối thiểu ' . number_format($coupon->min_order_amount) . 'đ để sử dụng mã này'
            ]);
        }
        
        // Tính giảm giá
        $discountAmount = 0;
        if ($coupon->discount_type === 'percentage') {
            $discountAmount = ($subtotal * $coupon->discount) / 100;
            // Giới hạn giảm tối đa nếu có
            if ($coupon->max_discount_amount && $discountAmount > $coupon->max_discount_amount) {
                $discountAmount = $coupon->max_discount_amount;
            }
        } else {
            $discountAmount = min($coupon->discount, $subtotal);
        }
        
        $total = $subtotal - $discountAmount;
        
        // Lưu mã giảm giá vào session để sử dụng khi checkout
        session([
            'applied_coupon' => $couponCode,
            'coupon_discount' => $discountAmount,
            'coupon_info' => [
                'code' => $coupon->code,
                'type' => $coupon->discount_type,
                'value' => $coupon->discount
            ]
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Áp dụng mã giảm giá thành công!',
            'discount_amount' => $discountAmount,
            'total' => $total,
            'coupon_info' => [
                'code' => $coupon->code,
                'type' => $coupon->discount_type,
                'value' => $coupon->discount
            ]
        ]);
    }
    public function removeCoupon(Request $request)
    {
        // Xóa mã giảm giá khỏi session
        session()->forget(['applied_coupon', 'coupon_discount', 'coupon_info']);
        
        return response()->json([
            'success' => true,
            'message' => 'Đã bỏ mã giảm giá'
        ]);
    }
    public function updateVariant(Request $request, $id)
    {
        $request->validate([
            'variant_id' => 'required|exists:product_variants,id'
        ]);
        
        $cart = Cart::where('user_id', Auth::id())
            ->with(['product', 'variant'])
            ->where('id', $id)
            ->firstOrFail();
        
        $newVariant = \App\Models\ProductVariant::where('id', $request->variant_id)
            ->where('product_id', $cart->product_id)
            ->firstOrFail();
        
        // Kiểm tra tồn kho
        if ($cart->quantity > $newVariant->stock) {
            return response()->json([
                'success' => false,
                'message' => 'Số lượng vượt quá tồn kho của biến thể mới.'
            ]);
        }
        
        // Kiểm tra xem đã có cart item với variant này chưa
        $existingCart = Cart::where('user_id', Auth::id())
            ->where('product_id', $cart->product_id)
            ->where('variant_id', $request->variant_id)
            ->where('id', '!=', $id)
            ->first();
        
        if ($existingCart) {
            // Nếu đã có, gộp số lượng
            $totalQuantity = $existingCart->quantity + $cart->quantity;
            
            if ($totalQuantity > $newVariant->stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tổng số lượng vượt quá tồn kho của biến thể mới.'
                ]);
            }
            
            $existingCart->quantity = $totalQuantity;
            $existingCart->save();
            
            // Xóa cart item cũ
            $cart->delete();
        } else {
            // Cập nhật variant_id
            $cart->variant_id = $request->variant_id;
            $cart->save();
        }
        
        $this->updateSessionCart();
        
        return response()->json([
            'success' => true,
            'message' => 'Đã cập nhật biến thể sản phẩm!',
            'variant' => [
                'sku' => $newVariant->sku,
                'price' => $newVariant->sale_price ?? $newVariant->price,
                'sale_price' => $newVariant->sale_price,
                'original_price' => $newVariant->price
            ]
        ]);
    }
    
    public function success($order_id)
    {
        $order = \App\Models\Order::with(['paymentMethod', 'shippingAddress'])->find($order_id);
        
        if (!$order) {
            return redirect()->route('home')->with('error', 'Không tìm thấy đơn hàng');
        }
        
        // Kiểm tra đơn hàng thuộc về user hiện tại (nếu đã đăng nhập)
        if (Auth::check() && $order->user_id !== Auth::id()) {
            return redirect()->route('home')->with('error', 'Bạn không có quyền xem đơn hàng này');
        }
        
        return view('client.cart.success', compact('order'));
    }
}