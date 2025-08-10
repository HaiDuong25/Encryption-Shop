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
use App\Models\ShippingAddress;
use App\Models\Coupon;
use App\Models\Payment;
use App\Models\ProductVariant;

class ZaloPayController extends Controller
{
    use ClearsCheckoutSession;
        // Xử lý callback từ ZaloPay (chuẩn tài liệu ZLP)
        public function callback(Request $request)
        {
            Log::info('ZaloPay Callback Request', $request->all());

            $dataStr = $request->input('data');
            $reqMac = $request->input('mac');
            $result = [
                'return_code' => 1,
                'return_message' => 'success'
            ];

            try {
                // Xác thực MAC
                $mac = hash_hmac('sha256', $dataStr, $this->key2);
                if ($reqMac !== $mac) {
                    Log::error('ZaloPay Callback: MAC not equal', ['reqMac' => $reqMac, 'mac' => $mac]);
                    $result['return_code'] = -1;
                    $result['return_message'] = 'mac not equal';
                    return response()->json($result);
                }

                // Parse data JSON
                $dataJson = json_decode($dataStr, true);
                Log::info('ZaloPay Callback Data', $dataJson);

                // Cập nhật trạng thái đơn hàng
                $appTransId = $dataJson['app_trans_id'] ?? null;
                $orderId = $dataJson['order_id'] ?? null;
                $status = $dataJson['status'] ?? null;

                // Tìm đơn hàng theo transaction_id hoặc order_id
                $order = null;
                if ($appTransId) {
                    $order = Order::where('transaction_id', $appTransId)->first();
                } elseif ($orderId) {
                    $order = Order::find($orderId);
                }

                if ($order) {
                    // Nếu trạng thái là thành công (status == 1), cập nhật trạng thái đơn hàng
                    if ($status == 1) {
                        $order->status = 'completed';
                        $order->payment_status = 'paid';
                        $order->save();
                        Log::info('ZaloPay Callback: Order updated to completed', ['order_id' => $order->id]);
                    } else {
                        Log::info('ZaloPay Callback: Payment failed or cancelled', ['order_id' => $order->id, 'status' => $status]);
                    }
                } else {
                    Log::error('ZaloPay Callback: Order not found', ['app_trans_id' => $appTransId, 'order_id' => $orderId]);
                }
            } catch (\Exception $ex) {
                Log::error('ZaloPay Callback Exception', ['message' => $ex->getMessage()]);
                $result['return_code'] = 0;
                $result['return_message'] = $ex->getMessage();
            }

            return response()->json($result);
        }
    // Thông tin cấu hình ZaloPay Sandbox - sử dụng credentials từ script test thành công
    private $appid = "2553"; // ID ứng dụng do ZaloPay cấp (từ script test thành công)
    private $key1 = "PcY4iZIKFCIdgZvA6ueMcMHHUbRLYjPL"; // Key1 dùng để ký dữ liệu gửi đi
    private $key2 = "kLtgPl8HHhfvMuDHPwKfgfsY4Ydm9eIz"; // Key2 dùng để xác thực callback
    private $endpoint = "https://sb-openapi.zalopay.vn/v2/create"; // URL endpoint tạo thanh toán

    // Hàm tạo yêu cầu thanh toán
    public function createPayment(Request $request)
    {
        $orderData = Session::get('order_data');
        if (!$orderData) {
            return redirect()->route('cart.checkout')->with('error', 'Không tìm thấy thông tin đơn hàng');
        }

        $transId = time(); // Tạo mã giao dịch duy nhất dựa trên timestamp

        // Tạo dữ liệu đơn hàng gửi đến ZaloPay
        $order = [
            "app_id" => $this->appid,
            "app_trans_id" => date("ymd") . "_" . time(),
            "app_user" => "user_" . Auth::id(),
            "app_time" => round(microtime(true) * 1000),
            "amount" => (int) $orderData['total'],
            "description" => "Thanh toán đơn hàng Encryption Shop #" . $orderData['order_id'],
            "bank_code" => "",
            "item" => json_encode([]),
            "embed_data" => json_encode([
                'order_id' => $orderData['order_id'],
                'redirecturl' => url('/zalopay/return') // ZaloPay sẽ redirect về URL này sau khi thanh toán
            ]),
            "callback_url" => url('/zalopay/callback'), // URL callback cho server notification
            "phone" => $orderData['phone'] ?? ""
        ];

        // Tạo chữ ký MAC
        $data = $order["app_id"] . "|" . $order["app_trans_id"] . "|" . $order["app_user"] . "|" .
                $order["amount"] . "|" . $order["app_time"] . "|" . $order["embed_data"] . "|" . $order["item"];
        $order["mac"] = hash_hmac("sha256", $data, $this->key1);

        // Gửi yêu cầu đến ZaloPay (sử dụng form data thay vì JSON)
        $response = $this->execPostRequest($this->endpoint, http_build_query($order));
        $result = json_decode($response, true);

        Log::info('ZaloPay Payment Request', $order);
        Log::info('ZaloPay Payment Response', $result);

        // Debug: Kiểm tra response từ ZaloPay
        if (!$result) {
            Log::error('ZaloPay Response is null or invalid JSON');
            return redirect()->route('cart.checkout')->with('error', 'Lỗi kết nối với ZaloPay');
        }

        if (isset($result['order_url']) && !empty($result['order_url'])) {
            Session::put('zalopay_order_data', $orderData);
            Log::info('Redirecting to ZaloPay URL: ' . $result['order_url']);

            // Redirect trực tiếp đến ZaloPay
            return redirect($result['order_url']);
        } else {
            // Log chi tiết lỗi từ ZaloPay
            $errorMessage = $result['return_message'] ?? $result['sub_return_message'] ?? 'Không thể tạo thanh toán ZaloPay';
            $returnCode = $result['return_code'] ?? 'unknown';

            Log::error('ZaloPay Error: ' . $errorMessage . ' (Code: ' . $returnCode . ')');
            Log::error('Full ZaloPay Response: ', $result);

            return redirect()->route('cart.checkout')->with('error', 'Lỗi ZaloPay: ' . $errorMessage);
        }
    }

    // Hàm xử lý khi người dùng thanh toán xong
    public function returnPayment(Request $request)
    {
        Log::info('ZaloPay Return Request: ', $request->all());

        $orderData = Session::get('zalopay_order_data');
        if (!$orderData) {
            Log::error('ZaloPay Return: No order data in session');
            return redirect()->route('cart.checkout')->with('error', 'Không tìm thấy dữ liệu thanh toán');
        }

        // Kiểm tra trạng thái thanh toán từ ZaloPay
        $status = $request->input('status');
        $apptransid = $request->input('apptransid');
        $checksum = $request->input('checksum');

        Log::info('ZaloPay Return Status: ' . $status . ', TransID: ' . $apptransid);

        if ($status == 1) {
            // Thanh toán thành công
            $order = $this->createOrder($apptransid, $request);

            if ($order) {
                // Clear tất cả session liên quan sau khi tạo đơn hàng thành công
                Log::info("ZaloPay payment successful, created order {$order->id}, redirecting to success page");
                $this->clearCheckoutSession();
                return redirect()->route('cart.success', $order->id)->with('success', 'Thanh toán ZaloPay thành công!');
            } else {
                Log::error('ZaloPay payment successful but failed to create order');
                return redirect()->route('cart.checkout')->with('error', 'Có lỗi khi tạo đơn hàng sau thanh toán');
            }
        } else {
            // Thanh toán thất bại hoặc bị hủy
            Log::info('ZaloPay payment failed or cancelled, status: ' . $status);
            return redirect()->route('cart.checkout')->with('error', 'Thanh toán ZaloPay thất bại hoặc đã bị hủy');
        }
    }

    // Hàm gửi POST request bằng CURL
    private function execPostRequest($url, $data)
    {
        Log::info('ZaloPay CURL Request', [
            'url' => $url,
            'data' => $data
        ]);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);

        if ($curlError) {
            Log::error('CURL Error connecting to ZaloPay: ' . $curlError);
            curl_close($ch);
            return json_encode(['error' => 'CURL Error: ' . $curlError]);
        }

        curl_close($ch);

        Log::info('ZaloPay CURL Response', [
            'http_code' => $httpCode,
            'response' => $result
        ]);

        return $result;
    }

    // Hàm tạo đơn hàng trong hệ thống sau khi thanh toán thành công
    private function createOrder($transactionId, $request = null)
    {
        try {
            $orderData = Session::get('order_data');
            if (!$orderData) return false;

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
                $productVariantsCount = ProductVariant::where('product_id', $cart->product_id)->count();
                if ($productVariantsCount > 0 && !$cart->variant_id) {
                    Log::error("ZaloPay Payment: Product {$cart->product_id} has variants but cart {$cart->id} has no variant_id");
                    return false;
                }
            }

            $shippingAddress = ShippingAddress::find($orderData['shipping_address_id']);

            $order = Order::create([
                'user_id' => $user->id,
                'shipping_address_id' => $orderData['shipping_address_id'],
                'payment_method_id' => $orderData['payment_method_id'],
                'subtotal' => $orderData['subtotal'],
                'discount' => $orderData['discount'],
                'total' => $orderData['total'],
                'total_price' => $orderData['total'],
                'notes' => $orderData['notes'] ?? null,
                'coupon_code' => $orderData['coupon_code'] ?? null,
                'coupon_discount' => $orderData['discount'],
                'status' => 'pending',
                'payment_status' => 'paid',
                'transaction_id' => $transactionId,
                'orderer_name' => $user->name,
                'orderer_email' => $user->email,
                'orderer_phone' => $user->phone,
                'orderer_address' => $user->address ?? '',
                'recipient_name' => $shippingAddress ? $shippingAddress->name : $user->name,
                'recipient_phone' => $shippingAddress ? $shippingAddress->phone : $user->phone,
                'recipient_address' => $shippingAddress ? ($shippingAddress->address_detail . ', ' . $shippingAddress->ward . ', ' . $shippingAddress->district . ', ' . $shippingAddress->province) : ($user->address ?? ''),
            ]);

            // Lưu chi tiết từng sản phẩm vào bảng order_details
            foreach ($carts as $cart) {
                $price = $cart->variant->sale_price ?? $cart->variant->price ?? $cart->product->sale_price ?? $cart->product->price;
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $cart->product_id,
                    'variant_id' => $cart->variant_id,
                    'quantity' => $cart->quantity,
                    'price' => $price,
                    'total_price' => $price * $cart->quantity
                ]);

                if ($cart->variant) {
                    $cart->variant->decrement('stock', $cart->quantity);
                } else {
                    $cart->product->decrement('stock', $cart->quantity);
                }
            }

            // GHI NHẬN VIỆC SỬ DỤNG COUPON VÀ XÓA KHỎI DANH SÁCH ĐÃ LƯU
            if (!empty($orderData['coupon_code']) && $orderData['discount'] > 0) {
                try {
                    $coupon = Coupon::where('code', $orderData['coupon_code'])->first();
                    if ($coupon) {
                        CouponUse::create([
                            'user_id' => $user->id,
                            'coupon_id' => $coupon->id,
                            'order_id' => $order->id,
                            'discount_amount' => $orderData['discount'],
                            'used_at' => now()
                        ]);
                        $coupon->incrementUsage();

                        // XÓA MÃ KHỎI DANH SÁCH ĐÃ LƯU CỦA USER
                        UserSavedCoupon::where('user_id', $user->id)
                            ->where('coupon_id', $coupon->id)
                            ->delete();

                        Log::info("Coupon {$orderData['coupon_code']} used by user {$user->id} for order {$order->id} (ZaloPay payment) and removed from saved list");
                    }
                } catch (\Exception $e) {
                    Log::error('Error recording coupon usage in ZaloPay payment: ' . $e->getMessage());
                }
            }

            // Xóa chỉ những sản phẩm đã thanh toán khỏi giỏ hàng (giống như COD)
            $selectedCartItems = Session::get('selected_cart_items', []);
            if (!empty($selectedCartItems)) {
                Cart::where('user_id', $user->id)->whereIn('id', $selectedCartItems)->delete();
                Log::info("Deleted selected cart items for ZaloPay order {$order->id}: " . implode(',', $selectedCartItems));
            } else {
                // Fallback: xóa tất cả nếu không có selected_items
                Cart::where('user_id', $user->id)->delete();
                Log::info("Deleted all cart items for ZaloPay order {$order->id} (no selection found)");
            }

            // Lưu thanh toán
            Payment::create([
                'order_id' => $order->id,
                'payment_method_id' => $order->payment_method_id,
                'amount' => $order->total_price,
                'status' => 'completed',
                'confirmed_at' => now(),
                'transaction_code' => $transactionId,
                'payment_method_type' => 'ZaloPay', // Loại ví điện tử
                // Lưu dữ liệu giao dịch thay vì thông tin người dùng
                'payer_account' => null, // Không lưu thông tin cá nhân
                'payer_name' => null, // Không lưu thông tin cá nhân
            ]);

            return $order;
        } catch (\Exception $e) {
            Log::error('Lỗi khi tạo đơn hàng sau thanh toán ZaloPay: ' . $e->getMessage());
            return false;
        }
    }
}
