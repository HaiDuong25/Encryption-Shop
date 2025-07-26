<?php

// Test debug ZaloPay API
echo "Testing ZaloPay API...\n";

// Cấu hình như trong controller
$config = [
    "app_id" => 2553,
    "key1" => "PcY4iZIKFCIdgZvA6ueMcMHHUbRLYjPL",
    "key2" => "kLtgPl8HHhfvMuDHPwKfgfsY4Ydm9eIz",
    "endpoint" => "https://sb-openapi.zalopay.vn/v2/create"
];

// Tạo dữ liệu test
$app_trans_id = date("ymd") . "_" . time();
$order = [
    "app_id" => $config["app_id"],
    "app_trans_id" => $app_trans_id,
    "app_user" => "test_user_123",
    "app_time" => round(microtime(true) * 1000),
    "embed_data" => json_encode(['order_id' => 12345]),
    "item" => json_encode([
        ['itemid' => 'test_product_1', 'itemname' => 'Test Product', 'itemprice' => 100000, 'itemquantity' => 1]
    ]),
    "amount" => 100000,
    "description" => "Test Payment #" . $app_trans_id,
    "bank_code" => "",
    "callback_url" => "http://localhost/callback",
    "phone" => "0123456789"
];

// Tạo mac
$data = $order["app_id"] . "|" . $order["app_trans_id"] . "|" . $order["app_user"] . "|" . 
        $order["amount"] . "|" . $order["app_time"] . "|" . $order["embed_data"] . "|" . $order["item"];
$order["mac"] = hash_hmac("sha256", $data, $config["key1"]);

echo "Request data:\n";
print_r($order);

// Gửi request
$context = stream_context_create([
    "http" => [
        "header" => "Content-type: application/x-www-form-urlencoded\r\n",
        "method" => "POST",
        "content" => http_build_query($order)
    ]
]);

echo "\nSending request to: " . $config["endpoint"] . "\n";

$result = file_get_contents($config["endpoint"], false, $context);

echo "Response:\n";
echo $result . "\n";

$response = json_decode($result, true);
if ($response) {
    echo "\nParsed response:\n";
    print_r($response);
    
    if (isset($response['order_url'])) {
        echo "\nPayment URL found: " . $response['order_url'] . "\n";
    } else {
        echo "\nNo payment URL in response!\n";
    }
} else {
    echo "\nFailed to parse JSON response!\n";
}

?>
