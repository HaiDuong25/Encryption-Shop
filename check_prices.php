<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;

$products = Product::select('id', 'name', 'price', 'sale_price')->limit(5)->get();

echo "Kiểm tra dữ liệu giá hiện tại:\n";
echo "=====================================\n";

foreach ($products as $product) {
    echo "ID: {$product->id}\n";
    echo "Name: {$product->name}\n";
    echo "Price (giá gốc): " . number_format($product->price) . " đ\n";
    echo "Sale Price (giá sale): " . ($product->sale_price ? number_format($product->sale_price) . " đ" : "null") . "\n";
    echo "-------------------------------------\n";
}
