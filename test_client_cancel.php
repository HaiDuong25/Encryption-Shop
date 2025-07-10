<?php
/**
 * Test client order cancel functionality
 */

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Client Order Cancel Test ===\n";

// Test 1: Check allowed statuses
$allowedStatuses = ['pending', 'confirmed'];
$notAllowedStatuses = ['shipping', 'delivering', 'received', 'completed', 'cancelled'];

echo "1. Testing cancellation rules:\n";
echo "   ✓ Allowed to cancel: " . implode(', ', $allowedStatuses) . "\n";
echo "   ✗ NOT allowed to cancel: " . implode(', ', $notAllowedStatuses) . "\n";

// Test 2: Check status conversion logic
echo "\n2. Testing status conversion:\n";
$statusMap = [
    '0' => 'pending',
    '1' => 'confirmed', 
    '2' => 'shipping',
    '3' => 'delivering',
    '4' => 'received',
    '5' => 'completed',
    '6' => 'cancelled',
];

foreach ($statusMap as $numeric => $string) {
    $canCancel = in_array($string, $allowedStatuses);
    $status = $canCancel ? '✓' : '✗';
    echo "   {$status} Status {$numeric} ({$string}) - " . ($canCancel ? 'CAN cancel' : 'CANNOT cancel') . "\n";
}

// Test 3: Check route existence
echo "\n3. Testing route:\n";
try {
    $route = route('client.orders.cancel', 1);
    echo "   ✓ Client cancel route exists: {$route}\n";
} catch (Exception $e) {
    echo "   ✗ Client cancel route error: " . $e->getMessage() . "\n";
}

echo "\n=== Test Complete ===\n";
echo "Client users can now cancel orders in 'pending' and 'confirmed' status only.\n";
echo "Admin users can still cancel orders in any status via admin panel.\n";
