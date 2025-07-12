<?php

// Test MoMo API với các credentials test khác nhau
echo "=== Testing different MoMo credentials ===\n\n";

$test_configs = [
    [
        'name' => 'Config 1 - Commonly used',
        'partnerCode' => 'MOMO_TEST_2021',
        'accessKey' => 'F8BBA842ECF85',
        'secretKey' => 'K951B6PE1waDMi640xX08PD3vg6EkVlz'
    ],
    [
        'name' => 'Config 2 - Alternative',
        'partnerCode' => 'MOMOC2C220000000',
        'accessKey' => 'F8BBA842ECF85',
        'secretKey' => 'K951B6PE1waDMi640xX08PD3vg6EkVlz'
    ]
];

foreach ($test_configs as $config) {
    echo "Testing " . $config['name'] . "\n";
    echo "Partner Code: " . $config['partnerCode'] . "\n";
    
    $partnerCode = $config['partnerCode'];
    $accessKey = $config['accessKey'];
    $secretKey = $config['secretKey'];
    $endpoint = 'https://test-payment.momo.vn/v2/gateway/api/create';

    // Test data
    $orderId = time() . '_test';
    $amount = 10000; // 10,000 VND
    $orderInfo = 'Test payment for MoMo integration';
    $redirectUrl = 'http://localhost:8000/momo/return';
    $ipnUrl = 'http://localhost:8000/momo/notify';
    $extraData = "";
    $requestId = time() . "";
    $requestType = "captureWallet";

    // Tạo signature
    $rawHash = "accessKey=" . $accessKey . 
              "&amount=" . $amount . 
              "&extraData=" . $extraData . 
              "&ipnUrl=" . $ipnUrl . 
              "&orderId=" . $orderId . 
              "&orderInfo=" . $orderInfo . 
              "&partnerCode=" . $partnerCode . 
              "&redirectUrl=" . $redirectUrl . 
              "&requestId=" . $requestId . 
              "&requestType=" . $requestType;

    $signature = hash_hmac("sha256", $rawHash, $secretKey);

    $data = array(
        'partnerCode' => $partnerCode,
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

    // Gửi request
    $ch = curl_init($endpoint);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json; charset=UTF-8',
        'Content-Length: ' . strlen(json_encode($data)))
    );
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);

    echo "HTTP Code: " . $httpCode . "\n";
    if ($curlError) {
        echo "CURL Error: " . $curlError . "\n";
    }

    if ($result) {
        $jsonResult = json_decode($result, true);
        echo "Response: " . json_encode($jsonResult, JSON_PRETTY_PRINT) . "\n";
        
        // Nếu thành công, dừng lại
        if (isset($jsonResult['payUrl'])) {
            echo "✓ SUCCESS! This config works!\n";
            echo "Pay URL: " . $jsonResult['payUrl'] . "\n";
            break;
        }
    } else {
        echo "No response received\n";
    }

    curl_close($ch);
    echo "\n" . str_repeat("-", 50) . "\n\n";
}
