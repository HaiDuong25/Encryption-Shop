<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Traits\ClearsCheckoutSession;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Cart;
use App\Models\CouponUse;
use App\Models\UserSavedCoupon;

class MoMoController extends Controller
{
    use ClearsCheckoutSession;
    // Thử credentials khác có thể hoạt động
    private $partnerCode = 'MOMOBKUN20180529';
    private $accessKey = 'klm05TvNBzhg7h7j';
    private $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
    private $endpoint = 'https://test-payment.momo.vn/v2/gateway/api/create';

    public function createPayment(Request $request)
    {
        try {
            // Lấy thông tin đơn hàng từ session
            $orderData = Session::get('order_data');
            if (!$orderData) {
                return redirect()->route('cart.checkout')->with('error', 'Không tìm thấy thông tin đơn hàng');
            }

            $orderId = time() . '_' . $orderData['user_id']; // Tạo orderId unique với format đúng
            $amount = (int) $orderData['total']; // Đảm bảo amount là integer
            $orderInfo = 'Thanh toán đơn hàng Encryption Shop #' . $orderId;
            $redirectUrl = route('momo.return');
            $ipnUrl = route('momo.notify');
            $extraData = ""; // Để trống theo hướng dẫn

            $requestId = time() . "";
            $requestType = "captureWallet"; // Đúng theo tài liệu MoMo

            // Tạo signature theo đúng format MoMo yêu cầu
            // Format: accessKey=$accessKey&amount=$amount&extraData=$extraData&ipnUrl=$ipnUrl&orderId=$orderId&orderInfo=$orderInfo&partnerCode=$partnerCode&redirectUrl=$redirectUrl&requestId=$requestId&requestType=$requestType
            $rawHash = "accessKey=" . $this->accessKey .
                "&amount=" . $amount .
                "&extraData=" . $extraData .
                "&ipnUrl=" . $ipnUrl .
                "&orderId=" . $orderId .
                "&orderInfo=" . $orderInfo .
                "&partnerCode=" . $this->partnerCode .
                "&redirectUrl=" . $redirectUrl .
                "&requestId=" . $requestId .
                "&requestType=" . $requestType;
            $signature = hash_hmac("sha256", $rawHash, $this->secretKey);

            $data = array(
                'partnerCode' => $this->partnerCode,
                'partnerName' => "EncryptionShop",
                'storeId' => "EncryptionShopStore",
                'requestId' => $requestId,
                'amount' => $amount,
                'orderId' => $orderId,
                'orderInfo' => $orderInfo,
                'redirectUrl' => $redirectUrl,
                'ipnUrl' => $ipnUrl,
                'lang' => 'vi',
                'extraData' => $extraData,
                'requestType' => $requestType,
                'signature' => $signature
            );

            // Log request data để debug
            Log::info('MoMo Payment Request:', [
                'data' => $data,
                'rawHash' => $rawHash
            ]);

            $result = $this->execPostRequest($this->endpoint, json_encode($data));
            $jsonResult = json_decode($result, true);

            // Log response để debug
            Log::info('MoMo Payment Response:', $jsonResult);

            // Lưu orderId vào session để sử dụng sau
            Session::put('momo_order_id', $orderId);
            Session::put('momo_request_id', $requestId);

            if (isset($jsonResult['payUrl'])) {
                return redirect($jsonResult['payUrl']);
            } else {
                Log::error('MoMo Payment Error: ', $jsonResult);

                // Xóa order_data khỏi session để tránh vòng lặp
                Session::forget('order_data');

                $errorMessage = 'Có lỗi xảy ra khi tạo thanh toán MoMo';
                if (isset($jsonResult['message'])) {
                    $errorMessage .= ': ' . $jsonResult['message'];
                }

                return redirect()->route('cart.checkout')->with('error', $errorMessage);
            }

        } catch (\Exception $e) {
            Log::error('MoMo Payment Exception: ' . $e->getMessage());

            // Xóa order_data khỏi session để tránh vòng lặp
            Session::forget('order_data');

            return redirect()->route('cart.checkout')->with('error', 'Có lỗi xảy ra khi xử lý thanh toán: ' . $e->getMessage());
        }
    }

    public function returnPayment(Request $request)
    {
        try {
            $partnerCode = $request->partnerCode;
            $orderId = $request->orderId;
            $requestId = $request->requestId;
            $amount = $request->amount;
            $orderInfo = $request->orderInfo;
            $orderType = $request->orderType;
            $transId = $request->transId;
            $resultCode = $request->resultCode;
            $message = $request->message;
            $payType = $request->payType;
            $responseTime = $request->responseTime;
            $extraData = $request->extraData;
            $signature = $request->signature;

            // Verify signature
            $rawHash = "accessKey=" . $this->accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&message=" . $message . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&orderType=" . $orderType . "&partnerCode=" . $partnerCode . "&payType=" . $payType . "&requestId=" . $requestId . "&responseTime=" . $responseTime . "&resultCode=" . $resultCode . "&transId=" . $transId;
            $partnerSignature = hash_hmac("sha256", $rawHash, $this->secretKey);

            if ($signature == $partnerSignature) {
                if ($resultCode == 0) {
                    // Thanh toán thành công - tạo đơn hàng
                    $order = $this->createOrder($transId, $request);
                    if ($order) {
                        Log::info("MoMo payment successful, created order {$order->id}, redirecting to success page");
                        $orderId = $order->id;
                        // Clear session sau khi lưu orderId
                        $this->clearCheckoutSession();
                        return redirect()->route('cart.success', $orderId)->with('success', 'Thanh toán MoMo thành công!');
                    } else {
                        Log::error('MoMo payment successful but failed to create order');
                        return redirect()->route('cart.index')->with('error', 'Có lỗi xảy ra khi tạo đơn hàng');
                    }
                } else {
                    // Thanh toán thất bại
                    Log::info("MoMo payment failed with result code: {$resultCode}, message: {$message}");
                    return redirect()->route('cart.checkout')->with('error', 'Thanh toán không thành công: ' . $message);
                }
            } else {
                Log::error('MoMo Signature Invalid', [
                    'received_signature' => $signature,
                    'calculated_signature' => $partnerSignature,
                    'raw_hash' => $rawHash
                ]);
                return redirect()->route('cart.checkout')->with('error', 'Chữ ký không hợp lệ');
            }

        } catch (\Exception $e) {
            Log::error('MoMo Return Exception: ' . $e->getMessage());
            return redirect()->route('cart.checkout')->with('error', 'Có lỗi xảy ra khi xử lý kết quả thanh toán');
        }
    }

    public function notifyPayment(Request $request)
    {
        // Xử lý IPN từ MoMo (tùy chọn)
        Log::info('MoMo IPN: ', $request->all());
        return response()->json(['status' => 'success']);
    }

    private function createOrder($transactionId, $request = null)
    {
        try {
            $orderData = Session::get('order_data');
            if (!$orderData) {
                return false;
            }

            $user = Auth::user();
            
            // Lấy only selected cart items
            $selectedCartItems = Session::get('selected_cart_items', []);
            if (empty($selectedCartItems)) {
                // Fallback: lấy tất cả cart items nếu không có selection
                $carts = Cart::where('user_id', $user->id)->with(['product', 'variant'])->get();
            } else {
                $carts = Cart::where('user_id', $user->id)
                    ->whereIn('id', $selectedCartItems)
                    ->with(['product', 'variant'])
                    ->get();
            }

            // Validation: Kiểm tra variant selection
            foreach ($carts as $cart) {
                // Kiểm tra nếu product có variants nhưng cart không có variant_id
                $productVariantsCount = \App\Models\ProductVariant::where('product_id', $cart->product_id)->count();
                if ($productVariantsCount > 0 && !$cart->variant_id) {
                    Log::error("MoMo Payment: Product {$cart->product_id} has variants but cart {$cart->id} has no variant_id");
                    return false;
                }
            }

            // Lấy thông tin shipping address
            $shippingAddress = \App\Models\ShippingAddress::find($orderData['shipping_address_id']);

            // Tạo đơn hàng
            $order = Order::create([
                'user_id' => $user->id,
                'shipping_address_id' => $orderData['shipping_address_id'],
                'payment_method_id' => $orderData['payment_method_id'],
                'subtotal' => $orderData['subtotal'],
                'discount' => $orderData['discount'],
                'total' => $orderData['total'],
                'total_price' => $orderData['total'], // Tương thích với field cũ
                'notes' => $orderData['notes'] ?? null,
                'coupon_code' => $orderData['coupon_code'] ?? null,
                'coupon_discount' => $orderData['discount'],
                'status' => 'confirmed', // Đã thanh toán
                'payment_status' => 'paid',
                'transaction_id' => $transactionId,
                // Thông tin người đặt
                'orderer_name' => $user->name,
                'orderer_email' => $user->email,
                'orderer_phone' => $user->phone,
                'orderer_address' => $user->address ?? '',
                // Thông tin người nhận
                'recipient_name' => $shippingAddress ? $shippingAddress->name : $user->name,
                'recipient_phone' => $shippingAddress ? $shippingAddress->phone : $user->phone,
                'recipient_address' => $shippingAddress ?
                    $shippingAddress->address_detail . ', ' . $shippingAddress->ward . ', ' . $shippingAddress->district . ', ' . $shippingAddress->province :
                    ($user->address ?? ''),
            ]);

            // Tạo chi tiết đơn hàng
            foreach ($carts as $cart) {
                $price = $cart->variant->sale_price ?? $cart->variant->price ?? $cart->product->sale_price ?? $cart->product->price;

                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $cart->product_id,
                    'variant_id' => $cart->variant_id, // Sử dụng đúng field variant_id
                    'quantity' => $cart->quantity,
                    'price' => $price,
                    'total_price' => $price * $cart->quantity
                ]);

                // Giảm stock
                if ($cart->variant) {
                    $cart->variant->decrement('stock', $cart->quantity);
                } else {
                    $cart->product->decrement('stock', $cart->quantity);
                }
            }

            // GHI NHẬN VIỆC SỬ DỤNG COUPON VÀ XÓA KHỎI DANH SÁCH ĐÃ LƯU
            if (!empty($orderData['coupon_code']) && $orderData['discount'] > 0) {
                try {
                    // Tìm coupon để ghi nhận việc sử dụng
                    $coupon = \App\Models\Coupon::where('code', $orderData['coupon_code'])->first();
                    if ($coupon) {
                        // Tạo bản ghi sử dụng coupon
                        \App\Models\CouponUse::create([
                            'user_id' => $user->id,
                            'coupon_id' => $coupon->id,
                            'order_id' => $order->id,
                            'discount_amount' => $orderData['discount'],
                            'used_at' => now()
                        ]);

                        // Sử dụng method mới để tăng số lần sử dụng
                        $coupon->incrementUsage();

                        // XÓA MÃ KHỎI DANH SÁCH ĐÃ LƯU CỦA USER
                        \App\Models\UserSavedCoupon::where('user_id', $user->id)
                            ->where('coupon_id', $coupon->id)
                            ->delete();

                        Log::info("Coupon {$orderData['coupon_code']} used by user {$user->id} for order {$order->id} (MoMo payment) and removed from saved list");
                    }
                } catch (\Exception $e) {
                    Log::error('Error recording coupon usage in MoMo payment: ' . $e->getMessage());
                    // Không return error để không ảnh hưởng đến việc đặt hàng
                }
            }

            // Chỉ xóa các sản phẩm đã chọn khỏi giỏ hàng sau khi đã tạo đơn hàng thành công
            try {
                $selectedCartItems = Session::get('selected_cart_items', []);
                if (!empty($selectedCartItems)) {
                    // Xóa chỉ những sản phẩm đã chọn
                    Cart::where('user_id', $user->id)
                        ->whereIn('id', $selectedCartItems)
                        ->delete();
                    Log::info("Deleted selected cart items for MoMo order {$order->id}: " . implode(',', $selectedCartItems));
                }
                // Không xóa tất cả cart items nữa, chỉ xóa những item đã chọn
            } catch (\Exception $e) {
                Log::error("Error deleting cart items: " . $e->getMessage());
                // Không throw exception để không ảnh hưởng đến việc tạo đơn hàng
            }

            // Tạo bản ghi payment cho đơn hàng (hiển thị ở quản lý thanh toán)
            try {
                $payment = \App\Models\Payment::create([
                    'order_id' => $order->id,
                    'payment_method_id' => $order->payment_method_id,
                    'amount' => $order->total_price,
                    'status' => 'completed', // Đã thanh toán luôn vì MoMo đã trả về thành công
                    'confirmed_at' => now(),
                    'transaction_code' => $transactionId,
                    'payment_method_type' => 'MoMo', // Loại ví điện tử
                    // Lưu dữ liệu giao dịch thay vì thông tin người dùng
                    'payer_account' => null, // Không lưu thông tin cá nhân
                    'payer_name' => null, // Không lưu thông tin cá nhân
                ]);

                // Tự động tạo hóa đơn cho đơn MoMo ngay sau khi thanh toán thành công
                $payment->generateInvoice();
                
                Log::info("MoMo payment confirmed and invoice generated automatically for order {$order->id}");
                
            } catch (\Exception $e) {
                Log::error('Create Payment Exception (MoMo): ' . $e->getMessage());
            }

            return $order;

        } catch (\Exception $e) {
            Log::error('Create Order Exception: ' . $e->getMessage());
            return false;
        }
    }

    private function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json; charset=UTF-8', // Đúng theo tài liệu MoMo
                'Content-Length: ' . strlen($data)
            )
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); // Tối thiểu 30s theo tài liệu MoMo
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Tạm thời cho test environment
        $result = curl_exec($ch);

        // Log curl error nếu có
        if (curl_error($ch)) {
            Log::error('CURL Error: ' . curl_error($ch));
        }

        curl_close($ch);
        return $result;
    }
}
