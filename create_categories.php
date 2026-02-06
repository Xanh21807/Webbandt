<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Category;

$categories = [
    ['name' => 'iPhone', 'description' => 'Điện thoại iPhone các loại'],
    ['name' => 'Samsung', 'description' => 'Điện thoại Samsung các loại'],
    ['name' => 'Xiaomi', 'description' => 'Điện thoại Xiaomi các loại'],
    ['name' => 'OPPO', 'description' => 'Điện thoại OPPO các loại'],
    ['name' => 'Vivo', 'description' => 'Điện thoại Vivo các loại'],
];

foreach ($categories as $cat) {
    $existing = Category::where('name', $cat['name'])->first();
    if (!$existing) {
        Category::create($cat);
        echo "✓ Created category: {$cat['name']}\n";
    } else {
        echo "- Category already exists: {$cat['name']}\n";
    }
}

echo "\nTotal categories: " . Category::count() . "\n";
