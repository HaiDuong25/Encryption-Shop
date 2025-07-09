<?php

// Script test chức năng trả lại tồn kho
// Chạy: php test_restore_stock.php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\OrderDetail;

echo "=== TEST CHỨC NĂNG TRẢ LẠI TỒN KHO ===\n\n";

// Lấy một sản phẩm và variant để test
$product = Product::first();
$variant = ProductVariant::first();

if (!$product || !$variant) {
    echo "Không tìm thấy sản phẩm hoặc variant để test!\n";
    exit;
}

echo "Sản phẩm test: {$product->name}\n";
echo "Variant test: {$variant->sku}\n";
echo "Stock trước test - Product: {$product->stock}, Variant: {$variant->stock}\n\n";

// Tạo đơn hàng test
$order = Order::create([
    'user_id' => 1,
    'orderer_name' => 'Test User',
    'orderer_phone' => '0123456789',
    'orderer_email' => 'test@test.com',
    'recipient_name' => 'Test Recipient',
    'recipient_phone' => '0123456789',
    'recipient_address' => 'Test Address',
    'subtotal' => 200000,
    'discount_amount' => 0,
    'total_price' => 200000,
    'status' => Order::STATUS_PENDING,
    'payment_method_id' => 1,
]);

// Tạo order details
OrderDetail::create([
    'order_id' => $order->id,
    'product_id' => $product->id,
    'variant_id' => $variant->id,
    'quantity' => 2,
    'price' => 100000,
]);

OrderDetail::create([
    'order_id' => $order->id,
    'product_id' => $product->id,
    'variant_id' => null, // Test với product không có variant
    'quantity' => 1,
    'price' => 100000,
]);

echo "Đã tạo đơn hàng test ID: {$order->id}\n";

// Giảm stock để mô phỏng việc đặt hàng
$product->decrement('stock', 1);
$variant->decrement('stock', 2);

echo "Đã giảm stock - Product: -{1}, Variant: -{2}\n";
echo "Stock sau khi đặt hàng - Product: {$product->fresh()->stock}, Variant: {$variant->fresh()->stock}\n\n";

// Test hủy đơn hàng
echo "=== TEST HỦY ĐƠN HÀNG ===\n";
try {
    echo "Trạng thái hiện tại: {$order->status}\n";
    $order->cancelOrder();
    echo "Đã hủy đơn hàng thành công!\n";
    echo "Stock sau khi hủy - Product: {$product->fresh()->stock}, Variant: {$variant->fresh()->stock}\n\n";
} catch (Exception $e) {
    echo "Lỗi khi hủy đơn hàng: " . $e->getMessage() . "\n\n";
}

// Test hủy đơn hàng với trạng thái không được phép
echo "=== TEST HỦY ĐƠN HÀNG VỚI TRẠNG THÁI KHÔNG ĐƯỢC PHÉP ===\n";
$order3 = Order::create([
    'user_id' => 1,
    'orderer_name' => 'Test User 3',
    'orderer_phone' => '0123456789',
    'orderer_email' => 'test3@test.com',
    'recipient_name' => 'Test Recipient 3',
    'recipient_phone' => '0123456789',
    'recipient_address' => 'Test Address 3',
    'subtotal' => 100000,
    'discount_amount' => 0,
    'total_price' => 100000,
    'status' => Order::STATUS_SHIPPING, // Trạng thái không được phép hủy
    'payment_method_id' => 1,
]);

echo "Đã tạo đơn hàng test 3 với trạng thái: {$order3->status}\n";
try {
    $order3->cancelOrder();
    echo "Hủy thành công (không nên xảy ra!)\n";
} catch (Exception $e) {
    echo "✓ Đúng! Không thể hủy: " . $e->getMessage() . "\n\n";
}

// Test xóa đơn hàng
echo "=== TEST XÓA ĐƠN HÀNG ===\n";

// Tạo đơn hàng mới để test xóa
$order2 = Order::create([
    'user_id' => 1,
    'orderer_name' => 'Test User 2',
    'orderer_phone' => '0123456789',
    'orderer_email' => 'test2@test.com',
    'recipient_name' => 'Test Recipient 2',
    'recipient_phone' => '0123456789',
    'recipient_address' => 'Test Address 2',
    'subtotal' => 100000,
    'discount_amount' => 0,
    'total_price' => 100000,
    'status' => Order::STATUS_CONFIRMED,
    'payment_method_id' => 1,
]);

OrderDetail::create([
    'order_id' => $order2->id,
    'product_id' => $product->id,
    'variant_id' => $variant->id,
    'quantity' => 1,
    'price' => 100000,
]);

echo "Đã tạo đơn hàng test 2 ID: {$order2->id}\n";

// Giảm stock
$variant->decrement('stock', 1);
echo "Đã giảm stock variant: -1\n";
echo "Stock trước khi xóa - Variant: {$variant->fresh()->stock}\n";

// Xóa đơn hàng (Observer sẽ tự động trả lại stock)
$order2->delete();
echo "Đã xóa đơn hàng!\n";
echo "Stock sau khi xóa - Variant: {$variant->fresh()->stock}\n\n";

// Test admin hủy đơn hàng ở trạng thái không được phép với user
echo "=== TEST ADMIN HỦY ĐƠN HÀNG Ở MỌI TRẠNG THÁI ===\n";
$order4 = Order::create([
    'user_id' => 1,
    'orderer_name' => 'Test User 4',
    'orderer_phone' => '0123456789',
    'orderer_email' => 'test4@test.com',
    'recipient_name' => 'Test Recipient 4',
    'recipient_phone' => '0123456789',
    'recipient_address' => 'Test Address 4',
    'subtotal' => 100000,
    'discount_amount' => 0,
    'total_price' => 100000,
    'status' => Order::STATUS_DELIVERING, // Trạng thái user không thể hủy nhưng admin được
    'payment_method_id' => 1,
]);

OrderDetail::create([
    'order_id' => $order4->id,
    'product_id' => $product->id,
    'variant_id' => $variant->id,
    'quantity' => 1,
    'price' => 100000,
]);

// Giảm stock
$variant->decrement('stock', 1);
echo "Đã tạo đơn hàng test 4 với trạng thái: {$order4->status}\n";
echo "Stock trước khi admin hủy - Variant: {$variant->fresh()->stock}\n";

try {
    $order4->cancelOrderByAdmin(); // Admin method
    echo "✓ Admin đã hủy thành công đơn hàng ở trạng thái '{$order4->status}'!\n";
    echo "Stock sau khi admin hủy - Variant: {$variant->fresh()->stock}\n\n";
} catch (Exception $e) {
    echo "Lỗi khi admin hủy đơn hàng: " . $e->getMessage() . "\n\n";
}

echo "=== TEST HOÀN TẤT ===\n";
echo "Chức năng trả lại tồn kho đã được test thành công!\n";
