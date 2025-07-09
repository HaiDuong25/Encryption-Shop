<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;

$columns = Schema::getColumnListing('orders');

echo "Các cột trong bảng orders:\n";
echo "========================\n";

foreach ($columns as $column) {
    echo "- $column\n";
}
