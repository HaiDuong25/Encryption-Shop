<?php
$start = microtime(true);
$url = 'http://127.0.0.1:8000/addresses/create';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
$end = microtime(true);
$duration = ($end - $start) * 1000;
echo 'HTTP Code: ' . $httpCode . PHP_EOL;
echo 'Response time: ' . round($duration, 2) . ' ms' . PHP_EOL;
if ($httpCode === 302) {
    echo 'Redirected (likely to login page)' . PHP_EOL;
} elseif ($httpCode === 200) {
    echo 'Page loaded successfully!' . PHP_EOL;
} else {
    echo 'Unexpected response code' . PHP_EOL;
}
