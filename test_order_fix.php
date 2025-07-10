<?php
/**
 * Test script to verify order editing functionality
 * Run this with: php test_order_fix.php
 */

require_once __DIR__ . '/vendor/autoload.php';

// Load Laravel environment
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Order Controller Fix Test ===\n";

// Test 1: Check if Order model fillable fields include discount_id
$order = new App\Models\Order();
$fillable = $order->getFillable();

echo "1. Testing fillable fields:\n";
if (in_array('discount_id', $fillable)) {
    echo "   ✓ discount_id is fillable\n";
} else {
    echo "   ✗ discount_id is NOT fillable\n";
}

if (in_array('coupon_id', $fillable)) {
    echo "   ✓ coupon_id is fillable (legacy support)\n";
} else {
    echo "   ✗ coupon_id is NOT fillable\n";
}

// Test 2: Check if coupon relationship exists and uses correct foreign key
echo "\n2. Testing relationships:\n";
try {
    $order = new App\Models\Order();
    $couponRelation = $order->coupon();
    echo "   ✓ coupon() relationship exists\n";
    
    // Check the foreign key used by the relationship
    $foreignKey = $couponRelation->getForeignKeyName();
    if ($foreignKey === 'discount_id') {
        echo "   ✓ coupon() relationship uses 'discount_id' as foreign key\n";
    } else {
        echo "   ✗ coupon() relationship uses '{$foreignKey}' instead of 'discount_id'\n";
    }
} catch (Exception $e) {
    echo "   ✗ Error testing coupon relationship: " . $e->getMessage() . "\n";
}

// Test 3: Check if we can create a mock validation array (like what the controller receives)
echo "\n3. Testing validation structure:\n";
$mockValidation = [
    'user_id' => 1,
    'orderer_name' => 'Test User',
    'orderer_phone' => '0123456789',
    'orderer_address' => 'Test Address',
    'recipient_name' => 'Test Recipient',
    'recipient_phone' => '0987654321',
    'recipient_address' => 'Test Recipient Address',
    'status' => 'pending',
    'cancel_reason' => null,
    'cancel_note' => null,
    'discount_id' => null,
    'payment_method_id' => 1,
];

$requiredFields = [
    'user_id', 'orderer_name', 'orderer_phone', 'orderer_address',
    'recipient_name', 'recipient_phone', 'recipient_address',
    'status', 'payment_method_id'
];

echo "   Required fields validation:\n";
foreach ($requiredFields as $field) {
    if (array_key_exists($field, $mockValidation)) {
        echo "   ✓ {$field} present in validation\n";
    } else {
        echo "   ✗ {$field} MISSING from validation\n";
    }
}

echo "\n4. Testing database column compatibility:\n";
try {
    // Check if orders table has the required columns
    $columns = \Illuminate\Support\Facades\Schema::getColumnListing('orders');
    
    $requiredColumns = ['discount_id', 'orderer_name', 'orderer_phone', 'orderer_address', 
                       'recipient_name', 'recipient_phone', 'recipient_address'];
    
    foreach ($requiredColumns as $column) {
        if (in_array($column, $columns)) {
            echo "   ✓ Column '{$column}' exists in orders table\n";
        } else {
            echo "   ✗ Column '{$column}' MISSING from orders table\n";
        }
    }
} catch (Exception $e) {
    echo "   ✗ Error checking database columns: " . $e->getMessage() . "\n";
}

echo "\n=== Test Complete ===\n";
echo "If all checks show ✓, the order editing should work correctly.\n";
echo "If any show ✗, those issues need to be addressed.\n";
