<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        // Create test user
        User::create([
            'name' => 'Test User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'status' => 'active',
            'phone' => '0123456789',
            'address' => '123 Test Street',
        ]);

        // Create categories
        $categories = [
            ['name' => 'iPhone', 'description' => 'Apple iPhone products'],
            ['name' => 'Samsung', 'description' => 'Samsung Galaxy products'],
            ['name' => 'Xiaomi', 'description' => 'Xiaomi smartphones'],
            ['name' => 'Oppo', 'description' => 'Oppo smartphones'],
            ['name' => 'Vivo', 'description' => 'Vivo smartphones'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Create sample products
        $products = [
            [
                'category_id' => 1,
                'name' => 'iPhone 15 Pro Max',
                'brand' => 'Apple',
                'price' => 29990000,
                'ram' => '8GB',
                'storage' => '256GB',
                'battery' => '4422mAh',
                'description' => 'iPhone 15 Pro Max với chip A17 Pro mạnh mẽ',
                'quantity' => 50,
                'status' => 'active',
            ],
            [
                'category_id' => 2,
                'name' => 'Samsung Galaxy S24 Ultra',
                'brand' => 'Samsung',
                'price' => 33990000,
                'ram' => '12GB',
                'storage' => '512GB',
                'battery' => '5000mAh',
                'description' => 'Samsung Galaxy S24 Ultra với camera 200MP',
                'quantity' => 30,
                'status' => 'active',
            ],
            [
                'category_id' => 3,
                'name' => 'Xiaomi 14 Pro',
                'brand' => 'Xiaomi',
                'price' => 19990000,
                'ram' => '12GB',
                'storage' => '256GB',
                'battery' => '4880mAh',
                'description' => 'Xiaomi 14 Pro với Snapdragon 8 Gen 3',
                'quantity' => 40,
                'status' => 'active',
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        // Create sample orders
        $testUser = User::where('email', 'user@example.com')->first();
        
        // Order 1 - Completed
        $order1 = \App\Models\Order::create([
            'user_id' => $testUser->id,
            'receiver_name' => 'Nguyễn Văn A',
            'receiver_phone' => '0987654321',
            'receiver_address' => '123 Lê Lợi, Quận 1, TP.HCM',
            'payment_method' => 'banking',
            'status' => 'completed',
            'total_amount' => 29990000,
        ]);

        \App\Models\OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => 1,
            'quantity' => 1,
            'price' => 29990000,
        ]);

        \App\Models\Payment::create([
            'order_id' => $order1->id,
            'payment_method' => 'banking',
            'amount' => 29990000,
            'status' => 'completed',
            'paid_at' => now(),
        ]);

        // Order 2 - Paid
        $order2 = \App\Models\Order::create([
            'user_id' => $testUser->id,
            'receiver_name' => 'Trần Thị B',
            'receiver_phone' => '0976543210',
            'receiver_address' => '456 Nguyễn Huệ, Quận 1, TP.HCM',
            'payment_method' => 'cod',
            'status' => 'paid',
            'total_amount' => 53980000,
        ]);

        \App\Models\OrderItem::create([
            'order_id' => $order2->id,
            'product_id' => 3,
            'quantity' => 2,
            'price' => 19990000,
        ]);

        \App\Models\OrderItem::create([
            'order_id' => $order2->id,
            'product_id' => 2,
            'quantity' => 1,
            'price' => 33990000,
        ]);

        \App\Models\Payment::create([
            'order_id' => $order2->id,
            'payment_method' => 'cod',
            'amount' => 53980000,
            'status' => 'completed',
            'paid_at' => now(),
        ]);
    }
}
