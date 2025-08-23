<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Traits\ClearsCheckoutSession;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CouponUse;
use App\Models\UserSavedCoupon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
class CartController extends Controller
{
    use ClearsCheckoutSession;

    public function index(Request $request)
    {
        $voucherCleared = false;

        // Clear voucher nếu có flag từ checkout (người dùng abandon checkout)
        if ($request->has('clear_voucher') || $request->header('referer') && str_contains($request->header('referer'), 'checkout')) {
            if (session('applied_coupon')) {
                session()->forget(['applied_coupon', 'coupon_discount', 'coupon_info']);
                $voucherCleared = true;
            }
        }

        $carts = Cart::where('user_id', Auth::id())
            ->with([
                'product.brand',
                'variant.attributeValues.attribute',
                'product.variants.attributeValues.attribute'
            ])
            ->get();

        // Hiển thị thông báo nếu voucher đã được clear
        if ($voucherCleared) {
            session()->flash('info', 'Mã giảm giá đã được xóa do không hoàn tất đơn hàng. Vui lòng nhập lại mã giảm giá nếu cần.');
        }

        return view('client.cart.index', compact('carts'));
    }
    public function add(Request $request, $productId)
    {
        $variantId = $request->input('variant_id');
        $quantity = (int) $request->input('quantity', 1);

        if (!Auth::check()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Bạn cần đăng nhập để thêm giỏ hàng.'], 401);
            }
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để thêm giỏ hàng.');
        }

        $product = Product::with('variants')->findOrFail($productId);
        $message = '';
        $success = true;

        // VALIDATION: Nếu sản phẩm có variants thì PHẢI chọn variant
        if ($product->variants()->count() > 0 && !$variantId) {
            $success = false;
            $message = 'Vui lòng chọn phân loại sản phẩm (size, màu) trước khi thêm vào giỏ hàng!';

            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $message], 400);
            }
            return redirect()->back()->with('error', $message);
        }

        if ($variantId) {
            $variant = $product->variants()->where('id', $variantId)->firstOrFail();
            $existing = Cart::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->where('variant_id', $variantId)
                ->first();
            $totalQuantity = $existing ? $existing->quantity + $quantity : $quantity;
            if ($totalQuantity > $variant->stock) {
                $success = false;
                $message = 'Số lượng vượt quá tồn kho biến thể sản phẩm.';
            } else {
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
                $message = 'Đã thêm sản phẩm vào giỏ hàng!';
            }
        } else {
            // Chỉ cho phép thêm không có variant nếu sản phẩm KHÔNG có variants
            if ($product->variants()->count() > 0) {
                $success = false;
                $message = 'Sản phẩm này yêu cầu chọn phân loại!';
            } else {
                $existing = Cart::where('user_id', Auth::id())
                    ->where('product_id', $productId)
                    ->whereNull('variant_id')
                    ->first();
                $totalQuantity = $existing ? $existing->quantity + $quantity : $quantity;
                if ($totalQuantity > $product->stock) {
                    $success = false;
                    $message = 'Số lượng vượt quá tồn kho sản phẩm.';
                } else {
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
                    $message = 'Đã thêm sản phẩm vào giỏ hàng!';
                }
            }
        }

        $this->updateSessionCart();

        if ($request->ajax()) {
            return response()->json([
                'success' => $success,
                'message' => $message
            ]);
        }
        if ($success) {
            return redirect()->back()->with('success', $message);
        } else {
            return redirect()->back()->with('error', $message);
        }
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
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Số lượng vượt quá tồn kho sản phẩm.']);
            }
            return redirect()->back()->with('error', 'Số lượng vượt quá tồn kho sản phẩm.');
        }
        $cart->quantity = $quantity;
        $cart->save();
        $this->updateSessionCart();
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Đã cập nhật số lượng!', 'quantity' => $cart->quantity]);
        }
        return redirect()->back()->with('success', 'Đã cập nhật số lượng!');
    }
    public function delete($id)
    {
        $cart = Cart::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
        $cart->delete();
        $this->updateSessionCart();
        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Đã xóa sản phẩm khỏi giỏ hàng!']);
        }
        return redirect()->back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng!');
    }

    public function deleteSelected(Request $request)
    {
        $selectedItems = $request->input('selected_items', []);

        if (empty($selectedItems)) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng chọn ít nhất một sản phẩm để xóa!'
            ]);
        }

        try {
            // Xóa các sản phẩm được chọn
            $deletedCount = Cart::where('user_id', Auth::id())
                ->whereIn('id', $selectedItems)
                ->delete();

            $this->updateSessionCart();

            return response()->json([
                'success' => true,
                'message' => "Đã xóa {$deletedCount} sản phẩm khỏi giỏ hàng!",
                'deleted_count' => $deletedCount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa sản phẩm!'
            ]);
        }
    }

    public function checkout(Request $request)
    {
        // Lấy danh sách ID sản phẩm được chọn từ request
        $selectedItems = $request->input('selected_items');

        // Chỉ kiểm tra selected_items nếu đây là request từ form cart
        // Không kiểm tra nếu đây là redirect từ thanh toán hoặc các trường hợp khác
        if ($request->isMethod('post') && empty($selectedItems)) {
            return redirect()->route('cart.index')->with('error', 'Vui lòng chọn ít nhất một sản phẩm để thanh toán!');
        }

        // Nếu không có selected_items từ request nhưng có trong session thì dùng session
        if (empty($selectedItems)) {
            $selectedItems = session('selected_cart_items');
        }

        // Nếu vẫn không có selected_items, lấy tất cả cart items
        if (empty($selectedItems)) {
            $allCarts = Cart::where('user_id', Auth::id())->pluck('id')->toArray();
            if (empty($allCarts)) {
                return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống!');
            }
            $selectedItems = $allCarts;
        }

        // Chuyển đổi string thành array nếu cần
        if (is_string($selectedItems)) {
            $selectedItems = explode(',', $selectedItems);
        }

        // Lưu selected_items vào session để sử dụng trong processCheckout
        session(['selected_cart_items' => $selectedItems]);

        // Lấy chỉ những sản phẩm được chọn
        $carts = Cart::where('user_id', Auth::id())
            ->whereIn('id', $selectedItems)
            ->with(['product.category', 'product.brand', 'variant'])
            ->get();

        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Không tìm thấy sản phẩm được chọn!');
        }

        $subtotal = $carts->sum(function ($cart) {
            return ($cart->variant->sale_price ?? $cart->variant->price ?? $cart->product->sale_price ?? $cart->product->price) * $cart->quantity;
        });

        // Lấy thông tin coupon từ session
        $appliedCoupon = session('applied_coupon');
        $couponDiscount = session('coupon_discount', 0);
        $couponInfo = session('coupon_info', null);

        // Validate voucher nếu có - đảm bảo voucher vẫn hợp lệ
        if ($appliedCoupon) {
            $coupon = \App\Models\Coupon::where('code', $appliedCoupon)->first();

            if (!$coupon || !$coupon->canBeUsed()) {
                // Voucher không còn hợp lệ, clear session và thông báo
                session()->forget(['applied_coupon', 'coupon_discount', 'coupon_info']);
                session()->flash('warning', 'Mã giảm giá không còn hợp lệ và đã được xóa. Vui lòng chọn mã khác.');
                return redirect()->route('cart.index');
            }

            // Kiểm tra user đã sử dụng coupon này chưa
            if ($coupon->hasBeenUsedByUser(Auth::id())) {
                session()->forget(['applied_coupon', 'coupon_discount', 'coupon_info']);
                session()->flash('warning', 'Bạn đã sử dụng mã giảm giá này rồi. Mã đã được xóa.');
                return redirect()->route('cart.index');
            }

            // Kiểm tra điều kiện đơn hàng tối thiểu
            if ($coupon->min_order_amount && $subtotal < $coupon->min_order_amount) {
                session()->forget(['applied_coupon', 'coupon_discount', 'coupon_info']);
                session()->flash('warning', 'Đơn hàng không đủ điều kiện tối thiểu cho mã giảm giá và đã được xóa.');
                return redirect()->route('cart.index');
            }

            // Tính lại discount để đảm bảo chính xác
            if ($coupon->discount_type === 'percentage') {
                $recalculatedDiscount = ($subtotal * $coupon->discount) / 100;
                if ($coupon->max_discount_amount && $recalculatedDiscount > $coupon->max_discount_amount) {
                    $recalculatedDiscount = $coupon->max_discount_amount;
                }
            } else {
                $recalculatedDiscount = min($coupon->discount, $subtotal);
            }

            // Cập nhật lại session nếu có sự thay đổi
            if (abs($recalculatedDiscount - $couponDiscount) > 0.01) {
                session(['coupon_discount' => $recalculatedDiscount]);
                $couponDiscount = $recalculatedDiscount;
            }
        }

        $total = $subtotal - $couponDiscount;

        $payment_methods = \App\Models\PaymentMethod::all();

        // Lấy địa chỉ của user hiện tại
        $addresses = [];
        $defaultAddress = null;
        $provinces = [];
        if (Auth::check()) {
            $addresses = Auth::user()->shippingAddresses()->get();
            $defaultAddress = $addresses->where('is_default', 1)->first();

            // Load provinces for quick address form using direct controller call
            try {
                $locationController = new \App\Http\Controllers\Api\LocationController();
                $response = $locationController->getProvinces();
                $provinces = json_decode($response->getContent(), true) ?: [];
            } catch (\Exception $e) {
                Log::error('Error loading provinces: ' . $e->getMessage());
                $provinces = ['Hà Nội', 'Hồ Chí Minh', 'Đà Nẵng']; // Fallback
            }
        }

        return view('client.cart.checkout', compact('carts', 'subtotal', 'total', 'payment_methods', 'appliedCoupon', 'couponDiscount', 'couponInfo', 'addresses', 'defaultAddress', 'provinces'));
    }

    public function processCheckout(Request $request)
    {
        $user = Auth::user();

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

        // Lấy selected_items từ session (đã lưu khi vào checkout) hoặc tất cả nếu không có
        $selectedItems = session('selected_cart_items');

        if (!empty($selectedItems)) {
            // Lấy chỉ những sản phẩm được chọn
            $carts = Cart::where('user_id', Auth::id())
                ->whereIn('id', $selectedItems)
                ->with(['product.variants', 'variant'])
                ->get();
        } else {
            // Fallback: lấy tất cả sản phẩm trong giỏ hàng
            $carts = Cart::where('user_id', Auth::id())->with(['product.variants', 'variant'])->get();
        }

        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Không tìm thấy sản phẩm để thanh toán!');
        }

        // VALIDATION: Kiểm tra tất cả cart items phải có variant nếu product yêu cầu
        foreach ($carts as $cart) {
            $productHasVariants = $cart->product->variants->count() > 0;

            if ($productHasVariants && !$cart->variant_id) {
                return redirect()->route('cart.index')->with('error',
                    "Sản phẩm '{$cart->product->name}' yêu cầu chọn phân loại. Vui lòng cập nhật giỏ hàng!");
            }
        }

        // Tính tổng tiền
        $subtotal = $carts->sum(function ($cart) {
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

        if ($paymentMethod && $paymentMethod->payment_type === 'Số dư ví') {
            // Thanh toán bằng ví - kiểm tra số dư
            $wallet = $user->getOrCreateWallet();

            if ($wallet->balance < $totalPrice) {
                return redirect()->back()->with('error',
                    'Số dư trong ví không đủ để thanh toán. Số dư hiện tại: ' .
                    number_format($wallet->balance, 0, ',', '.') . ' VND. ' .
                    'Cần thêm: ' . number_format($totalPrice - $wallet->balance, 0, ',', '.') . ' VND.'
                );
            }

            // Tạo đơn hàng ngay lập tức
            try {
                $order = new \App\Models\Order();
                $order->user_id = Auth::id();

                // Thông tin người đặt hàng
                $order->orderer_name = $user->name;
                $order->orderer_email = $user->email;
                $order->orderer_phone = $user->phone;
                $order->orderer_address = $user->address ?? '';

                // Thông tin người nhận hàng (FIX: dùng address_detail thay vì address để tránh lỗi thuộc tính không tồn tại)
                $order->recipient_name = $shippingAddress->name;
                $order->recipient_phone = $shippingAddress->phone;
                $order->recipient_address = $shippingAddress->address_detail . ', ' . $shippingAddress->ward . ', ' . $shippingAddress->district . ', ' . $shippingAddress->province;

                $order->shipping_address_id = $request->shipping_address_id;
                $order->payment_method_id = $request->payment_method_id;
                // Các trường giá trị (giữ cả total_price cũ để tương thích)
                $order->subtotal = $subtotal;
                $order->discount = $discountAmount; // tổng số tiền giảm
                $order->total = $totalPrice; // dùng tạm nếu còn view/code cũ đọc
                $order->total_price = $totalPrice; // giá trị chuẩn
                $order->notes = $request->notes;
                $order->coupon_code = $couponCode;
                $order->coupon_discount = $discountAmount;
                $order->status = 'pending';
                $order->payment_status = 'paid';
                $order->transaction_id = 'WALLET_' . time() . '_' . $user->id;
                $order->save();

                // Lưu chi tiết đơn hàng
                foreach ($carts as $cart) {
                    $orderDetail = new \App\Models\OrderDetail();
                    $orderDetail->order_id = $order->id;
                    $orderDetail->product_id = $cart->product_id;
                    $orderDetail->variant_id = $cart->variant_id;
                    $orderDetail->quantity = $cart->quantity;
                    $orderDetail->price = $cart->variant ? $cart->variant->price : $cart->product->price;
                    // Sử dụng cột total_price (migration không có 'total')
                    $orderDetail->total_price = $orderDetail->price * $cart->quantity;
                    $orderDetail->save();
                }

                // Trừ tiền từ ví
                $wallet->subtractBalance(
                    $totalPrice,
                    'Thanh toán đơn hàng #' . $order->id,
                    'ORDER_' . $order->id . '_' . time()
                );

                // Tạo payment record
                \App\Models\Payment::create([
                    'order_id' => $order->id,
                    'payment_method_id' => $request->payment_method_id,
                    'status' => 'completed',
                    'transaction_code' => $order->transaction_id,
                    'payer_name' => $user->name,
                    'payment_method_type' => 'WALLET'
                ]);

                // Xử lý coupon nếu có
                if ($couponCode && $coupon) {
                    \App\Models\CouponUse::create([
                        'user_id' => $user->id,
                        'coupon_id' => $coupon->id,
                        'order_id' => $order->id,
                        'discount_amount' => $discountAmount
                    ]);

                    if ($coupon->usage_count !== null) {
                        $coupon->increment('usage_count');
                    }

                    \App\Models\UserSavedCoupon::where('user_id', $user->id)
                        ->where('coupon_id', $coupon->id)
                        ->delete();
                }

                // Xóa giỏ hàng
                if ($request->selected_items) {
                    Cart::where('user_id', $user->id)
                        ->whereIn('id', $selectedItems)
                        ->delete();
                } else {
                    Cart::where('user_id', $user->id)->delete();
                }

                return redirect()->route('cart.success', $order->id)
                    ->with('success', 'Đặt hàng và thanh toán bằng ví thành công!');

            } catch (\Exception $e) {
                \Log::error('Wallet payment error', [
                    'message' => $e->getMessage(),
                    'line' => $e->getLine(),
                    'file' => $e->getFile(),
                ]);
                return redirect()->back()->with('error', 'Có lỗi xảy ra khi thanh toán bằng ví. Vui lòng thử lại.');
            }
        }

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

        if ($paymentMethod && $paymentMethod->payment_type === 'Ví Điện Tử ZALOPAY') {
            // Thanh toán ZaloPay - lưu thông tin vào session trước khi chuyển hướng
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
                'carts' => $carts->toArray(),
                'phone' => $shippingAddress->phone ?? $user->phone ?? '',
                'order_id' => 'TEMP_' . time() // Temporary order ID cho ZaloPay
            ];

            session(['order_data' => $orderData]);

            // Chuyển hướng đến ZaloPay payment
            return redirect()->route('zalopay.pay');
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

        // Tạo bản ghi thanh toán cho đơn hàng COD
        try {
            \App\Models\Payment::create([
                'order_id' => $order->id,
                'payment_method_id' => $order->payment_method_id,
                'amount' => $order->total_price,
                'status' => 'pending', // Chờ xác nhận - chỉ được confirm khi đơn hàng hoàn thành
                'transaction_code' => null, // Sẽ được tạo khi confirm
                // Không có 'confirmed_at' - sẽ được set khi đơn hàng completed
            ]);

            Log::info("COD payment record created for order {$order->id} - pending confirmation until order completion");

        } catch (\Exception $e) {
            Log::error('Lỗi tạo bản ghi thanh toán (COD): ' . $e->getMessage());
        }

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

        // GHI NHẬN VIỆC SỬ DỤNG COUPON VÀO DATABASE
        if ($couponCode && $discountAmount > 0) {
            try {
                // Tìm coupon để ghi nhận việc sử dụng
                $coupon = \App\Models\Coupon::where('code', $couponCode)->first();
                if ($coupon) {
                    // Tạo bản ghi sử dụng coupon
                    \App\Models\CouponUse::create([
                        'user_id' => Auth::id(),
                        'coupon_id' => $coupon->id,
                        'order_id' => $order->id,
                        'discount_amount' => $discountAmount,
                        'used_at' => now()
                    ]);

                    // Sử dụng method mới để tăng số lần sử dụng
                    $coupon->incrementUsage();

                    Log::info("Coupon {$couponCode} used by user " . Auth::id() . " for order {$order->id}. Remaining usage: " . $coupon->remainingUsage());
                }
            } catch (\Exception $e) {
                Log::error('Error recording coupon usage: ' . $e->getMessage());
                // Không return error để không ảnh hưởng đến việc đặt hàng
            }
        }

        // Xóa chỉ những sản phẩm đã thanh toán khỏi giỏ hàng
        $selectedItems = session('selected_cart_items', []);
        if (!empty($selectedItems)) {
            Cart::where('user_id', Auth::id())->whereIn('id', $selectedItems)->delete();
        } else {
            // Fallback: xóa tất cả nếu không có selected_items
            Cart::where('user_id', Auth::id())->delete();
        }

        // XÓA MÃ GIẢM GIÁ ĐÃ SỬ DỤNG KHỎI DANH SÁCH ĐÃ LƯU CỦA USER
        if ($couponCode) {
            try {
                // Xóa mã khỏi bảng user_saved_coupons nếu user đã lưu mã này
                $coupon = \App\Models\Coupon::where('code', $couponCode)->first();
                if ($coupon) {
                    \App\Models\UserSavedCoupon::where('user_id', Auth::id())
                        ->where('coupon_id', $coupon->id)
                        ->delete();

                    Log::info("Removed used coupon {$couponCode} from user's saved list after successful order {$order->id}");
                }
            } catch (\Exception $e) {
                Log::error('Error removing used coupon from saved list: ' . $e->getMessage());
            }
        }

        // Clear tất cả session liên quan đến checkout
        $this->clearCheckoutSession();

        // Chuyển hướng sang trang success, truyền mã đơn hàng và mã đã sử dụng
        return redirect()->route('cart.success', ['order_id' => $order->id])
            ->with('success', 'Đặt hàng thành công! Mã đơn hàng: #' . $order->id)
            ->with('used_coupon_code', $couponCode);
    }
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string|max:50',
            'subtotal' => 'numeric|min:0' // Thêm subtotal từ frontend
        ]);

        $couponCode = $request->coupon_code;
        $selectedSubtotal = $request->input('subtotal'); // Subtotal của sản phẩm được chọn

        // Tìm mã giảm giá
        $coupon = \App\Models\Coupon::where('code', $couponCode)->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Mã giảm giá không tồn tại!'
            ]);
        }

        // Sử dụng subtotal từ frontend hoặc tính từ toàn bộ giỏ hàng nếu không có
        $subtotal = $selectedSubtotal;
        if (!$subtotal) {
            $carts = Cart::where('user_id', Auth::id())->with(['product', 'variant'])->get();
            $subtotal = $carts->sum(function ($cart) {
                return ($cart->variant->sale_price ?? $cart->variant->price ?? $cart->product->sale_price ?? $cart->product->price) * $cart->quantity;
            });
        }

        // Sử dụng method canBeUsedByUser() mới của Model
        if (!$coupon->canBeUsedByUser(Auth::id(), $subtotal)) {
            $message = 'Mã giảm giá không thể sử dụng!';

            if (!$coupon->is_active) {
                $message = 'Mã giảm giá đã bị vô hiệu hóa!';
            } elseif ($coupon->usage_limit > 0 && $coupon->used_count >= $coupon->usage_limit) {
                $message = 'Mã giảm giá đã hết lượt sử dụng!';
            } elseif ($coupon->expires_at && $coupon->expires_at < now()) {
                $message = 'Mã giảm giá đã hết hạn!';
            } elseif ($coupon->start_date && $coupon->start_date > now()) {
                $message = 'Mã giảm giá chưa có hiệu lực!';
            } elseif ($coupon->end_date && $coupon->end_date < now()) {
                $message = 'Mã giảm giá đã hết hạn!';
            } elseif ($coupon->min_order_amount > 0 && $subtotal < $coupon->min_order_amount) {
                $message = 'Đơn hàng tối thiểu ' . number_format($coupon->min_order_amount) . ' VNĐ để sử dụng mã này';
            } elseif ($coupon->is_one_time_per_user && $coupon->hasBeenUsedByUser(Auth::id())) {
                $message = 'Bạn đã sử dụng mã giảm giá này rồi!';
            }

            return response()->json([
                'success' => false,
                'message' => $message
            ]);
        }

        // Tính giảm giá bằng method calculateDiscountAmount() mới
        $discountAmount = $coupon->calculateDiscountAmount($subtotal);
        $total = $subtotal - $discountAmount;

        // Lưu mã giảm giá vào session để sử dụng khi checkout
        session([
            'applied_coupon' => $couponCode,
            'coupon_discount' => $discountAmount,
            'coupon_info' => [
                'code' => $coupon->code,
                'type' => $coupon->discount_type,
                'discount_type' => $coupon->discount_type,
                'value' => $coupon->discount,
                'discount' => $coupon->discount,
                'max_discount_amount' => $coupon->max_discount_amount,
                'min_order_amount' => $coupon->min_order_amount,
                'description' => $coupon->description,
                'remaining_usage' => $coupon->remainingUsage(),
                'discount_text' => $coupon->discount_type === 'percentage'
                    ? "Giảm {$coupon->discount}%" . ($coupon->max_discount_amount ? " (tối đa " . number_format($coupon->max_discount_amount) . "₫)" : "")
                    : "Giảm " . number_format($coupon->discount) . "₫"
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
                'value' => $coupon->discount,
                'max_discount_amount' => $coupon->max_discount_amount,
                'min_order_amount' => $coupon->min_order_amount,
                'description' => $coupon->description,
                'remaining_usage' => $coupon->remainingUsage(),
                'discount_text' => $coupon->discount_type === 'percentage'
                    ? "Giảm {$coupon->discount}%" . ($coupon->max_discount_amount ? " (tối đa " . number_format($coupon->max_discount_amount) . "₫)" : "")
                    : "Giảm " . number_format($coupon->discount) . "₫"
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
        // Log để debug
        Log::info("Accessing success page for order: {$order_id}");

        $order = \App\Models\Order::with([
            'paymentMethod',
            'shippingAddress',
            'payments' => function($query) {
                $query->orderBy('created_at', 'desc');
            },
            'orderDetails.product',
            'orderDetails.variant.attributeValues.attribute'
        ])->find($order_id);

        if (!$order) {
            Log::error("Order not found for success page: {$order_id}");
            return redirect()->route('home')->with('error', 'Không tìm thấy đơn hàng');
        }

        // Kiểm tra đơn hàng thuộc về user hiện tại (nếu đã đăng nhập)
        if (Auth::check() && $order->user_id !== Auth::id()) {
            Log::warning("User " . Auth::id() . " tried to access order {$order_id} belonging to user {$order->user_id}");
            return redirect()->route('home')->with('error', 'Bạn không có quyền xem đơn hàng này');
        }

        Log::info("Successfully displaying success page for order {$order_id}");
        return view('client.cart.success', compact('order'));
    }
    public function switchVariant(Request $request, $cartId)
    {
        try {
            $request->validate([
                'variant_id' => 'required|exists:product_variants,id'
            ]);

            $cart = Cart::where('id', $cartId)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $newVariantId = $request->variant_id;

            // Kiểm tra xem variant mới có thuộc cùng product không
            $newVariant = $cart->product->variants()->where('id', $newVariantId)->first();
            if (!$newVariant) {
                return response()->json([
                    'success' => false,
                    'message' => 'Biến thể không thuộc sản phẩm này'
                ]);
            }

            // Kiểm tra tồn kho
            if ($cart->quantity > $newVariant->stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Số lượng vượt quá tồn kho biến thể mới'
                ]);
            }

            // Kiểm tra xem đã có cart item với variant mới chưa
            $existingCart = Cart::where('user_id', Auth::id())
                ->where('product_id', $cart->product_id)
                ->where('variant_id', $newVariantId)
                ->where('id', '!=', $cartId)
                ->first();

            if ($existingCart) {
                // Nếu đã có, merge quantity và xóa cart cũ
                $totalQuantity = $existingCart->quantity + $cart->quantity;

                if ($totalQuantity > $newVariant->stock) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tổng số lượng vượt quá tồn kho'
                    ]);
                }

                $existingCart->quantity = $totalQuantity;
                $existingCart->save();
                $cart->delete();
            } else {
                // Nếu chưa có, chỉ cập nhật variant_id
                $cart->variant_id = $newVariantId;
                $cart->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Đã chuyển biến thể thành công'
            ]);

        } catch (\Exception $e) {
            Log::error('Switch variant error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi chuyển biến thể'
            ]);
        }
    }
    public function updateQuantityAjax(Request $request, $id)
    {
        try {
            $cart = Cart::where('user_id', Auth::id())
                ->with(['product', 'variant'])
                ->where('id', $id)
                ->firstOrFail();

            $quantity = (int) $request->quantity;

            // Validate quantity
            if ($quantity < 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Số lượng phải lớn hơn 0'
                ], 400);
            }

            // Check stock
            $stock = $cart->variant ? $cart->variant->stock : $cart->product->stock;
            if ($quantity > $stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Số lượng vượt quá tồn kho sản phẩm (còn lại: ' . $stock . ')'
                ], 400);
            }

            // Update quantity
            $cart->quantity = $quantity;
            $cart->save();

            // Update session cart
            $this->updateSessionCart();

            // Calculate new item total
            $price = $cart->variant->sale_price ?? $cart->variant->price ?? $cart->product->sale_price ?? $cart->product->price;
            $itemTotal = $price * $quantity;

            return response()->json([
                'success' => true,
                'message' => 'Đã cập nhật số lượng!',
                'data' => [
                    'quantity' => $quantity,
                    'item_total' => number_format($itemTotal),
                    'item_total_raw' => $itemTotal,
                    'price' => $price
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    public function validateCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string|max:50',
            'subtotal' => 'required|numeric|min:0'
        ]);

        $couponCode = $request->coupon_code;
        $subtotal = $request->subtotal;

        // Tìm mã giảm giá
        $coupon = \App\Models\Coupon::where('code', $couponCode)->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Mã giảm giá không tồn tại!'
            ]);
        }

        // Sử dụng method canBeUsed() mới
        if (!$coupon->canBeUsed()) {
            $message = 'Mã giảm giá không thể sử dụng!';

            if ($coupon->usage_limit > 0 && $coupon->used_count >= $coupon->usage_limit) {
                $message = 'Mã giảm giá đã hết lượt sử dụng!';
            } elseif ($coupon->expires_at && $coupon->expires_at < now()) {
                $message = 'Mã giảm giá đã hết hạn!';
            } elseif ($coupon->start_date && $coupon->start_date > now()) {
                $message = 'Mã giảm giá chưa có hiệu lực!';
            } elseif ($coupon->end_date && $coupon->end_date < now()) {
                $message = 'Mã giảm giá đã hết hạn!';
            } elseif (!$coupon->is_active) {
                $message = 'Mã giảm giá đã bị vô hiệu hóa!';
            }

            return response()->json([
                'success' => false,
                'message' => $message
            ]);
        }

        // Kiểm tra user đã sử dụng coupon này chưa
        if ($coupon->hasBeenUsedByUser(Auth::id())) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn đã sử dụng mã giảm giá này rồi!'
            ]);
        }

        // Kiểm tra giá trị đơn hàng tối thiểu
        if ($coupon->min_order_amount && $subtotal < $coupon->min_order_amount) {
            return response()->json([
                'success' => false,
                'message' => 'Đơn hàng tối thiểu ' . number_format($coupon->min_order_amount) . ' VNĐ để sử dụng mã này'
            ]);
        }

        // Tính toán discount
        $discountAmount = 0;
        if ($coupon->discount_type === 'percentage') {
            $discountAmount = ($subtotal * $coupon->discount) / 100;
            if ($coupon->max_discount_amount && $discountAmount > $coupon->max_discount_amount) {
                $discountAmount = $coupon->max_discount_amount;
            }
        } else {
            $discountAmount = min($coupon->discount, $subtotal);
        }

        return response()->json([
            'success' => true,
            'message' => 'Mã giảm giá hợp lệ',
            'discount_amount' => $discountAmount,
            'coupon_info' => [
                'code' => $coupon->code,
                'type' => $coupon->discount_type,
                'discount_type' => $coupon->discount_type,
                'value' => $coupon->discount,
                'discount' => $coupon->discount,
                'max_discount_amount' => $coupon->max_discount_amount,
                'max_discount' => $coupon->max_discount_amount,
                'min_order_value' => $coupon->min_order_amount,
                'original_subtotal' => $subtotal,
                'original_discount' => $discountAmount,
                'remaining_usage' => $coupon->remainingUsage(),
                'discount_text' => $coupon->discount_type === 'percentage'
                    ? "Giảm {$coupon->discount}%"
                    : "Giảm " . number_format($coupon->discount) . "₫"
            ]
        ]);
    }

    /**
     * Lấy danh sách coupon khả dụng cho user hiện tại
     */
    public function getAvailableCoupons()
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để xem mã giảm giá!'
            ]);
        }

        // Sử dụng scope mới availableForUser
        $coupons = \App\Models\Coupon::availableForUser(Auth::id())
            ->latest()
            ->get();

        $coupons = $coupons->map(function ($coupon) {
            return [
                'id' => $coupon->id,
                'code' => $coupon->code,
                'description' => $coupon->description ?: $this->generateCouponDescription($coupon),
                'discount_text' => $coupon->discount_type === 'percentage'
                    ? "Giảm {$coupon->discount}%" . ($coupon->max_discount_amount ? " (tối đa " . number_format($coupon->max_discount_amount) . "₫)" : "")
                    : "Giảm " . number_format($coupon->discount) . "₫",
                'discount_type' => $coupon->discount_type,
                'discount_value' => $coupon->discount,
                'min_order_amount' => $coupon->min_order_amount,
                'max_discount_amount' => $coupon->max_discount_amount,
                'usage_limit' => $coupon->usage_limit,
                'used_count' => $coupon->used_count,
                'remaining_usage' => $coupon->remainingUsage(),
                'is_one_time_per_user' => $coupon->is_one_time_per_user,
                'expires_at' => $coupon->expires_at ? date('d/m/Y', strtotime((string)$coupon->expires_at)) : null,
                'start_date' => $coupon->start_date ? date('d/m/Y', strtotime((string)$coupon->start_date)) : null,
                'end_date' => $coupon->end_date ? date('d/m/Y', strtotime((string)$coupon->end_date)) : null,
                'can_use' => !$coupon->hasBeenUsedByUser(Auth::id()),
                'short_description' => $this->generateCouponDescription($coupon)
            ];
        });

        return response()->json([
            'success' => true,
            'coupons' => $coupons
        ]);
    }

    /**
     * Tạo mô tả cho coupon
     */
    private function generateCouponDescription($coupon)
    {
        $description = '';

        if ($coupon->discount_type === 'percentage') {
            $description = "Giảm {$coupon->discount}%";
            if ($coupon->max_discount_amount) {
                $description .= " (tối đa " . number_format($coupon->max_discount_amount) . "₫)";
            }
        } else {
            $description = "Giảm " . number_format($coupon->discount) . "₫";
        }

        if ($coupon->min_order_amount) {
            $description .= " cho đơn hàng từ " . number_format($coupon->min_order_amount) . "₫";
        }

        if ($coupon->remainingUsage() > 0) {
            $description .= ". Còn lại " . $coupon->remainingUsage() . " lượt sử dụng";
        }

        return $description;
    }

    public function clearCheckoutVoucher(Request $request)
    {
        // Clear voucher session khi người dùng abandon checkout
        $hadCoupon = session('applied_coupon');
        $restoreCoupon = $request->input('restore_coupon');

        session()->forget(['applied_coupon', 'coupon_discount', 'coupon_info']);

        // Log để tracking
        if ($hadCoupon) {
            Log::info('User abandoned checkout, cleared voucher: ' . $hadCoupon, [
                'user_id' => Auth::id(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'restore_coupon' => $restoreCoupon
            ]);
        }

        $message = 'Đã xóa mã giảm giá do không hoàn tất đơn hàng';
        if ($restoreCoupon) {
            $message .= ' và trả mã về danh sách đã lưu';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'cleared_coupon' => $hadCoupon,
            'restored_coupon' => $restoreCoupon
        ]);
    }

    /**
     * Xóa mã giảm giá đã sử dụng khỏi danh sách saved coupons của user
     */
    public function removeUsedCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string|max:50'
        ]);

        try {
            // Chỉ return success để frontend xử lý việc xóa khỏi localStorage
            // Vì saved coupons được lưu ở localStorage, backend chỉ cần confirm
            return response()->json([
                'success' => true,
                'message' => 'Mã giảm giá đã được xóa khỏi danh sách đã lưu'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa mã giảm giá'
            ], 500);
        }
    }
}
