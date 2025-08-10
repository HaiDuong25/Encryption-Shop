<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\UserWallet;
use App\Models\WalletTransaction;

class WalletZalopayController extends Controller
{
    private $appid = "2553";
    private $key1 = "PcY4iZIKFCIdgZvA6ueMcMHHUbRLYjPL";
    private $key2 = "kLtgPl8HHhfvMuDHPwKfgfsY4Ydm9eIz";
    private $endpoint = "https://sb-openapi.zalopay.vn/v2/create";

    public function createPayment(Request $request)
    {
        $topupData = Session::get('wallet_topup_data');
        if (!$topupData) {
            return redirect()->route('wallet.topup')->with('error', 'Không tìm thấy thông tin nạp tiền');
        }

        $transId = time();

        $order = [
            "app_id" => $this->appid,
            "app_trans_id" => date("ymd") . "_" . $transId,
            "app_user" => "user_" . Auth::id(),
            "app_time" => round(microtime(true) * 1000),
            "amount" => (int) $topupData['amount'],
            "description" => "Nạp tiền vào ví Encryption Shop - " . number_format($topupData['amount']) . " VND",
            "bank_code" => "",
            "item" => json_encode([]),
            "embed_data" => json_encode([
                'transaction_code' => $topupData['transaction_code'],
                'redirecturl' => url('/wallet/zalopay/return')
            ]),
            "callback_url" => url('/wallet/zalopay/callback'),
            "phone" => Auth::user()->phone ?? ""
        ];

        $data = $order["app_id"] . "|" . $order["app_trans_id"] . "|" . $order["app_user"] . "|" .
                $order["amount"] . "|" . $order["app_time"] . "|" . $order["embed_data"] . "|" . $order["item"];
        $order["mac"] = hash_hmac("sha256", $data, $this->key1);

        $response = $this->execPostRequest($this->endpoint, http_build_query($order));
        $result = json_decode($response, true);

        Log::info('ZaloPay Wallet Topup Request', $order);
        Log::info('ZaloPay Wallet Topup Response', $result);

        if (!$result) {
            Log::error('ZaloPay Wallet Topup Response is null or invalid JSON');
            return redirect()->route('wallet.topup')->with('error', 'Lỗi kết nối với ZaloPay');
        }

        if (isset($result['order_url']) && !empty($result['order_url'])) {
            Session::put('wallet_zalopay_order_data', $topupData);
            Log::info('Redirecting to ZaloPay Wallet URL: ' . $result['order_url']);

            return redirect($result['order_url']);
        } else {
            $errorMessage = $result['return_message'] ?? $result['sub_return_message'] ?? 'Không thể tạo thanh toán ZaloPay';
            $returnCode = $result['return_code'] ?? 'unknown';

            Log::error('ZaloPay Wallet Topup Error: ' . $errorMessage . ' (Code: ' . $returnCode . ')');
            Log::error('Full ZaloPay Wallet Response: ', $result);

            return redirect()->route('wallet.topup')->with('error', 'Lỗi ZaloPay: ' . $errorMessage);
        }
    }

    public function returnPayment(Request $request)
    {
        Log::info('ZaloPay Wallet Return Request: ', $request->all());

        $topupData = Session::get('wallet_zalopay_order_data');
        if (!$topupData) {
            Log::error('ZaloPay Wallet Return: No order data in session');
            return redirect()->route('wallet.topup')->with('error', 'Không tìm thấy dữ liệu thanh toán');
        }

        $status = $request->input('status');
        $apptransid = $request->input('apptransid');
        $checksum = $request->input('checksum');

        Log::info('ZaloPay Wallet Return Status: ' . $status . ', TransID: ' . $apptransid);

        if ($status == 1) {
            // Thanh toán thành công
            $success = $this->processWalletTopup($apptransid, $topupData, $request);

            if ($success) {
                Session::forget(['wallet_topup_data', 'wallet_zalopay_order_data']);
                Log::info("ZaloPay wallet topup successful, transaction: {$topupData['transaction_code']}");
                return redirect()->route('wallet.topup.success', ['transaction_code' => $topupData['transaction_code']])
                    ->with('success', 'Nạp tiền ZaloPay thành công!');
            } else {
                Log::error('ZaloPay payment successful but failed to process wallet topup');
                return redirect()->route('wallet.topup')->with('error', 'Có lỗi khi nạp tiền vào ví sau thanh toán');
            }
        } else {
            Log::info('ZaloPay wallet topup failed or cancelled, status: ' . $status);
            return redirect()->route('wallet.topup.cancel')->with('error', 'Thanh toán ZaloPay thất bại hoặc đã bị hủy');
        }
    }

    public function processManualReturn(Request $request)
    {
        $request->validate([
            'zalopay_url' => 'required|url'
        ]);

        $url = $request->zalopay_url;
        $parsedUrl = parse_url($url);
        
        if (!$parsedUrl || !isset($parsedUrl['query'])) {
            return back()->with('error', 'URL không hợp lệ');
        }

        parse_str($parsedUrl['query'], $params);

        Log::info('ZaloPay Wallet Manual Return Params: ', $params);

        if (!isset($params['status']) || !isset($params['apptransid'])) {
            return back()->with('error', 'URL thiếu thông tin cần thiết');
        }

        // Tạo fake request object với params từ URL
        $fakeRequest = new Request($params);
        return $this->returnPayment($fakeRequest);
    }

    public function notifyPayment(Request $request)
    {
        Log::info('ZaloPay Wallet Topup IPN:', $request->all());

        $key2 = $this->key2;
        $postdata = $request->getContent();
        $postdatajson = json_decode($postdata, true);

        $mac = hash_hmac("sha256", $postdatajson["data"], $key2);

        if (strcmp($mac, $postdatajson["mac"]) == 0) {
            $dataJson = json_decode($postdatajson["data"], true);
            $apptransid = $dataJson["app_trans_id"];
            
            Log::info("ZaloPay wallet topup IPN valid for: " . $apptransid);
            
            // Process topup logic here if needed
            
            return response()->json(["return_code" => 1, "return_message" => "success"]);
        } else {
            return response()->json(["return_code" => -1, "return_message" => "mac not equal"]);
        }
    }

    private function processWalletTopup($apptransid, $topupData, $request)
    {
        try {
            $transaction = WalletTransaction::where('transaction_code', $topupData['transaction_code'])
                ->where('status', 'pending')
                ->first();

            if (!$transaction) {
                Log::error("Wallet transaction not found: {$topupData['transaction_code']}");
                return false;
            }

            $user = $transaction->user;
            $wallet = $user->getOrCreateWallet();

            $transaction->update([
                'status' => 'completed',
                'balance_after' => $wallet->balance + $topupData['amount'],
                'payment_data' => [
                    'zalopay_trans_id' => $apptransid,
                    'payment_time' => now(),
                    'raw_response' => $request->all()
                ]
            ]);

            // Cộng tiền vào ví
            $wallet->balance += $topupData['amount'];
            $wallet->save();

            Log::info("Wallet topup completed for user {$user->id}, amount: {$topupData['amount']}");
            return true;

        } catch (\Exception $e) {
            Log::error('Process wallet topup error: ' . $e->getMessage());
            return false;
        }
    }

    private function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded'
        ));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $result;
    }
}
