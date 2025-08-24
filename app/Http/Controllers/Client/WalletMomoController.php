<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\UserWallet;
use App\Models\WalletTransaction;

class WalletMomoController extends Controller
{
    private $partnerCode = 'MOMOBKUN20180529';
    private $accessKey = 'klm05TvNBzhg7h7j';
    private $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
    private $endpoint = 'https://test-payment.momo.vn/v2/gateway/api/create';

    public function createPayment(Request $request)
    {
        try {
            $topupData = Session::get('wallet_topup_data');
            if (!$topupData) {
                return redirect()->route('wallet.topup')->with('error', 'Không tìm thấy thông tin nạp tiền');
            }

            // Đảm bảo orderId luôn duy nhất, nếu đã tồn tại thì tạo mới
            $orderId = $topupData['transaction_code'];
            $amount = (int) $topupData['amount'];
            $orderInfo = 'Nạp tiền vào ví Encryption Shop - ' . number_format($amount) . ' VND';
            $redirectUrl = route('wallet.momo.return');
            $ipnUrl = route('wallet.momo.notify');
            $extraData = "";

            // Kiểm tra nếu orderId đã tồn tại ở trạng thái pending hoặc completed thì tạo mới
            $exists = \App\Models\WalletTransaction::where('transaction_code', $orderId)
                ->whereIn('status', ['pending', 'completed'])
                ->exists();
            if ($exists) {
                $orderId = 'WALLET_' . time() . '_' . rand(1000,9999);
                $topupData['transaction_code'] = $orderId;
                Session::put('wallet_topup_data', $topupData);
            }

            $requestId = time() . "";
            $requestType = "captureWallet";

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
                'storeId' => "EncryptionShopWallet",
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

            Log::info('MoMo Wallet Topup Request:', [
                'data' => $data,
                'rawHash' => $rawHash
            ]);

            $result = $this->execPostRequest($this->endpoint, json_encode($data));
            $jsonResult = json_decode($result, true);

            Log::info('MoMo Wallet Topup Response:', $jsonResult);

            Session::put('wallet_momo_order_id', $orderId);
            Session::put('wallet_momo_request_id', $requestId);

            if (isset($jsonResult['payUrl'])) {
                return redirect($jsonResult['payUrl']);
            } else {
                Log::error('MoMo Wallet Topup Error: ', $jsonResult);
                Session::forget('wallet_topup_data');

                $errorMessage = 'Có lỗi xảy ra khi tạo thanh toán MoMo';
                if (isset($jsonResult['message'])) {
                    $errorMessage .= ': ' . $jsonResult['message'];
                }

                return redirect()->route('wallet.topup')->with('error', $errorMessage);
            }

        } catch (\Exception $e) {
            Log::error('MoMo Wallet Topup Exception: ' . $e->getMessage());
            Session::forget('wallet_topup_data');
            return redirect()->route('wallet.topup')->with('error', 'Có lỗi xảy ra khi xử lý thanh toán: ' . $e->getMessage());
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
                    // Thanh toán thành công - nạp tiền vào ví
                    $success = $this->processWalletTopup($transId, $orderId, $amount, $request);
                    if ($success) {
                        Log::info("MoMo wallet topup successful for transaction: {$orderId}");
                        Session::forget('wallet_topup_data');
                        return redirect()->route('wallet.topup.success', ['transaction_code' => $orderId])
                            ->with('success', 'Nạp tiền MoMo thành công!');
                    } else {
                        Log::error('Failed to process wallet topup after successful MoMo payment');
                        return redirect()->route('wallet.topup')->with('error', 'Có lỗi khi nạp tiền vào ví sau thanh toán');
                    }
                } else {
                    Log::info("MoMo wallet topup failed, result code: {$resultCode}, message: {$message}");
                    Session::forget('wallet_topup_data');
                    return redirect()->route('wallet.topup.cancel')
                        ->with('error', 'Thanh toán MoMo thất bại: ' . $message);
                }
            } else {
                Log::error('MoMo wallet topup signature verification failed');
                return redirect()->route('wallet.topup')->with('error', 'Xác thực chữ ký không thành công');
            }
        } catch (\Exception $e) {
            Log::error('MoMo Wallet Topup Return Exception: ' . $e->getMessage());
            return redirect()->route('wallet.topup')->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function notifyPayment(Request $request)
    {
        Log::info('MoMo Wallet Topup IPN:', $request->all());
        
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
                $this->processWalletTopup($transId, $orderId, $amount, $request);
                return response()->json(['status' => 'success']);
            }
        }
        
        return response()->json(['status' => 'error']);
    }

    private function processWalletTopup($transId, $transactionCode, $amount, $request)
    {
        try {
            $transaction = WalletTransaction::where('transaction_code', $transactionCode)
                ->where('status', 'pending')
                ->first();

            if (!$transaction) {
                Log::error("Wallet transaction not found: {$transactionCode}");
                return false;
            }

            $user = $transaction->user;
            $wallet = $user->getOrCreateWallet();

            // Cập nhật transaction
            $transaction->update([
                'status' => 'completed',
                'balance_after' => $wallet->balance + $amount,
                'payment_data' => [
                    'momo_trans_id' => $transId,
                    'payment_time' => now(),
                    'raw_response' => $request->all()
                ]
            ]);

            // Cộng tiền vào ví
            $wallet->balance += $amount;
            $wallet->save();

            Log::info("Wallet topup completed for user {$user->id}, amount: {$amount}");
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
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data))
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}
