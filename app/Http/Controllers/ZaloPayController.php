<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Cart;
use App\Models\CouponUse;
use App\Models\ShippingAddress;
use App\Models\Coupon;
use App\Models\Payment;

class ZaloPayController extends Controller
{
    // Thông tin cấu hình ZaloPay Sandbox
    private $appid = "2554"; // ID ứng dụng do ZaloPay cấp
    private $key1 = "sdngKKJmqEMzvh5QQcdD2A9XBSKUNaYn"; // Key1 dùng để ký dữ liệu gửi đi
    private $key2 = "trMrHtvjo6myautxDUiAcYsVtaeQ8nhf"; // Key2 dùng để xác thực callback (chưa dùng đến trong ví dụ này)
    private $endpoint = "https://sb-openapi.zalopay.vn/v2/create"; // URL endpoint tạo thanh toán

    // Hàm tạo yêu cầu thanh toán
    public function createPayment(Request $request)
    {
        $orderData = Session::get('order_data');
        if (!$orderData) {
            return redirect()->route('cart.checkout')->with('error', 'Không tìm thấy thông tin đơn hàng');
        }

        $transId = time(); // Tạo mã giao dịch duy nhất dựa trên timestamp

        // Dữ liệu đơn hàng gửi đến ZaloPay
        $order = [
            "appid" => $this->appid,
            "apptransid" => date("ymd") . "_" . $transId, // Mã giao dịch: yymmdd_xxxxx
            "appuser" => "user_test",
            "apptime" => round(microtime(true) * 1000),
            "amount" => (int) $orderData['total'],
            "description" => "Thanh toán đơn hàng Encryption Shop",
            "bankcode" => "zalopayapp",
            "item" => json_encode([]),
            "embeddata" => json_encode([])
        ];

        // Tạo chữ ký MAC
        $data = $order["appid"] . "|" . $order["apptransid"] . "|" . $order["appuser"] . "|" . $order["amount"] . "|" . $order["apptime"] . "|" . $order["embeddata"] . "|" . $order["item"];
        $order["mac"] = hash_hmac("sha256", $data, $this->key1);

        // Gửi yêu cầu đến ZaloPay
        $response = $this->execPostRequest($this->endpoint, json_encode($order));
        $result = json_decode($response, true);

        Log::info('ZaloPay Payment Response', $result);

        if (isset($result['orderurl'])) {
            Session::put('zalopay_order_data', $orderData); // Lưu lại dữ liệu đơn hàng
            return redirect($result['orderurl']); // Chuyển hướng đến trang thanh toán
        } else {
            return redirect()->route('cart.checkout')->with('error', 'Không thể tạo thanh toán ZaloPay');
        }
    }

    // Hàm xử lý khi người dùng thanh toán xong
    public function returnPayment(Request $request)
    {
        $orderData = Session::get('zalopay_order_data');
        if (!$orderData) {
            return redirect()->route('cart.checkout')->with('error', 'Không tìm thấy dữ liệu thanh toán');
        }

        $transId = $request->input('apptransid');
        $order = $this->createOrder($transId);

        if ($order) {
            Session::forget(['order_data', 'zalopay_order_data']);
            return redirect()->route('cart.success', $order->id)->with('success', 'Thanh toán ZaloPay thành công!');
        } else {
            return redirect()->route('cart.checkout')->with('error', 'Có lỗi khi tạo đơn hàng sau thanh toán');
        }
    }

    // Hàm gửi POST request bằng CURL
    private function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);

        if (curl_error($ch)) {
            Log::error('CURL Lỗi khi kết nối ZaloPay: ' . curl_error($ch));
        }

        curl_close($ch);
        return $result;
    }

    // Hàm tạo đơn hàng trong hệ thống sau khi thanh toán thành công
    private function createOrder($transactionId)
    {
        try {
            $orderData = Session::get('order_data');
            if (!$orderData) return false;

            $user = Auth::user();
            $carts = Cart::where('user_id', $user->id)->with(['product', 'variant'])->get();
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
                'status' => 'confirmed',
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

            // Xóa giỏ hàng
            Cart::where('user_id', $user->id)->delete();

            // Cập nhật lượt sử dụng mã giảm giá nếu có
            if (!empty($orderData['coupon_code']) && $orderData['discount'] > 0) {
                $coupon = Coupon::where('code', $orderData['coupon_code'])->first();
                if ($coupon) {
                    CouponUse::create([
                        'user_id' => $user->id,
                        'coupon_id' => $coupon->id,
                        'order_id' => $order->id,
                        'discount_amount' => $orderData['discount'],
                        'used_at' => now()
                    ]);
                    $coupon->increment('used_count');
                }
            }

            // Lưu thanh toán
            Payment::create([
                'order_id' => $order->id,
                'payment_method_id' => $order->payment_method_id,
                'amount' => $order->total_price,
                'status' => 'completed',
                'confirmed_at' => now(),
                'transaction_code' => $transactionId,
            ]);

            return $order;
        } catch (\Exception $e) {
            Log::error('Lỗi khi tạo đơn hàng sau thanh toán ZaloPay: ' . $e->getMessage());
            return false;
        }
    }
}
