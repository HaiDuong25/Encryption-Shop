<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\ProductVariant;
use App\Models\Product;

echo "=== Kiểm tra tồn kho ===\n";

// Kiểm tra 5 variant đầu tiên
$variants = ProductVariant::with('product')->take(5)->get();

foreach ($variants as $variant) {
    echo "Product: {$variant->product->name}\n";
    echo "Variant ID: {$variant->id}\n";
    echo "Stock: {$variant->stock}\n";
    echo "---\n";
}

// Kiểm tra sản phẩm nào có variants
$productsWithVariants = Product::whereHas('variants')->with('variants')->take(3)->get();

echo "\n=== Sản phẩm có variants ===\n";
foreach ($productsWithVariants as $product) {
    echo "Product: {$product->name}\n";
    echo "Variants count: " . $product->variants->count() . "\n";
    foreach ($product->variants as $variant) {
        echo "  - Variant {$variant->id}: Stock = {$variant->stock}\n";
    }
    echo "---\n";
}
