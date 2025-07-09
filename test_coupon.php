<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Coupon;

$coupon = Coupon::where('code', 'DISCOUNT20')->first();

if ($coupon) {
    echo "Mã giảm giá: " . $coupon->code . PHP_EOL;
    echo "Loại: " . $coupon->discount_type . PHP_EOL;
    echo "Giá trị: " . $coupon->discount . PHP_EOL;

    $total = 100000;
    $discount = $coupon->calculateDiscount($total);
    echo "Tổng tiền: " . number_format($total) . " đ" . PHP_EOL;
    echo "Giảm giá: " . number_format($discount) . " đ" . PHP_EOL;
    echo "Còn lại: " . number_format($total - $discount) . " đ" . PHP_EOL;
} else {
    echo "Không tìm thấy mã giảm giá DISCOUNT20" . PHP_EOL;
}
