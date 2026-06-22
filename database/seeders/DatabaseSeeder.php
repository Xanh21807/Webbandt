<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user (idempotent)
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'status' => 'active',
            ]
        );

        // Create test user (idempotent)
        User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
                'role' => 'user',
                'status' => 'active',
                'phone' => '0123456789',
                'address' => '123 Test Street',
            ]
        );

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
            $createdProduct = Product::create($product);
            $this->attachSampleProductImages($createdProduct);
        }

        // Restore the full catalog after the base sample data
        $this->call([
            AccessorySeeder::class,
            ProductSeeder::class,
            \Database\Seeders\ComboSeeder::class,
        ]);

        $this->syncProductImagePathsToLocalFiles();

        // Create sample orders for statistics
        $testUser = User::where('email', 'user@example.com')->first();

        $orderFixtures = [
            [
                'date' => '2026-01-06 10:15:00',
                'receiver_name' => 'Nguyễn Văn A',
                'receiver_phone' => '0987654321',
                'receiver_address' => '123 Lê Lợi, Quận 1, TP.HCM',
                'payment_method' => 'banking',
                'status' => 'completed',
                'items' => [
                    ['product' => 'iPhone 16 Pro', 'quantity' => 1],
                    ['product' => 'AirPods Pro 2 USB-C', 'quantity' => 1],
                ],
            ],
            [
                'date' => '2026-01-18 14:20:00',
                'receiver_name' => 'Trần Thị B',
                'receiver_phone' => '0976543210',
                'receiver_address' => '456 Nguyễn Huệ, Quận 1, TP.HCM',
                'payment_method' => 'cod',
                'status' => 'paid',
                'items' => [
                    ['product' => 'Samsung Galaxy S24 Ultra', 'quantity' => 1],
                    ['product' => 'Củ sạc nhanh Samsung 45W', 'quantity' => 1],
                ],
            ],
            [
                'date' => '2026-02-03 09:05:00',
                'receiver_name' => 'Lê Quốc C',
                'receiver_phone' => '0911222333',
                'receiver_address' => '789 Hai Bà Trưng, Quận 3, TP.HCM',
                'payment_method' => 'wallet',
                'status' => 'shipping',
                'items' => [
                    ['product' => 'iPhone 15 Pro', 'quantity' => 1],
                    ['product' => 'Ốp lưng iPhone 15 Pro Max MagSafe trong suốt', 'quantity' => 1],
                ],
            ],
            [
                'date' => '2026-02-21 16:45:00',
                'receiver_name' => 'Phạm Thị D',
                'receiver_phone' => '0909888777',
                'receiver_address' => '12 Võ Văn Tần, Quận 3, TP.HCM',
                'payment_method' => 'banking',
                'status' => 'completed',
                'items' => [
                    ['product' => 'Xiaomi 14 Ultra', 'quantity' => 1],
                    ['product' => 'Pin sạc dự phòng Xiaomi 30000mAh', 'quantity' => 2],
                ],
            ],
            [
                'date' => '2026-03-04 11:30:00',
                'receiver_name' => 'Hoàng Văn E',
                'receiver_phone' => '0933444555',
                'receiver_address' => '91 Nguyễn Trãi, Quận 5, TP.HCM',
                'payment_method' => 'cod',
                'status' => 'paid',
                'items' => [
                    ['product' => 'Samsung Galaxy A55 5G', 'quantity' => 2],
                    ['product' => 'JBL Tune 230NC TWS', 'quantity' => 1],
                ],
            ],
            [
                'date' => '2026-03-19 13:10:00',
                'receiver_name' => 'Đỗ Thị F',
                'receiver_phone' => '0866555444',
                'receiver_address' => '15 Pasteur, Quận 1, TP.HCM',
                'payment_method' => 'wallet',
                'status' => 'completed',
                'items' => [
                    ['product' => 'Vivo X100 Pro', 'quantity' => 1],
                    ['product' => 'Sạc không dây MagSafe Apple 15W', 'quantity' => 1],
                ],
            ],
            [
                'date' => '2026-04-08 08:55:00',
                'receiver_name' => 'Ngô Văn G',
                'receiver_phone' => '0922444666',
                'receiver_address' => '100 Trần Hưng Đạo, Quận 1, TP.HCM',
                'payment_method' => 'banking',
                'status' => 'shipping',
                'items' => [
                    ['product' => 'OPPO Reno11 5G', 'quantity' => 1],
                    ['product' => 'Cáp Baseus Tungsten Gold 100W 2m', 'quantity' => 2],
                ],
            ],
            [
                'date' => '2026-04-27 18:25:00',
                'receiver_name' => 'Võ Thị H',
                'receiver_phone' => '0944333555',
                'receiver_address' => '22 Cách Mạng Tháng 8, Quận 10, TP.HCM',
                'payment_method' => 'wallet',
                'status' => 'completed',
                'items' => [
                    ['product' => 'Samsung Galaxy S24+', 'quantity' => 1],
                    ['product' => 'Samsung Galaxy Buds2 Pro', 'quantity' => 1],
                ],
            ],
            [
                'date' => '2026-05-05 12:00:00',
                'receiver_name' => 'Bùi Văn I',
                'receiver_phone' => '0977000111',
                'receiver_address' => '55 Lý Tự Trọng, Quận 1, TP.HCM',
                'payment_method' => 'cod',
                'status' => 'paid',
                'items' => [
                    ['product' => 'iPhone 15', 'quantity' => 2],
                    ['product' => 'Ốp lưng Spigen Ultra Hybrid', 'quantity' => 2],
                ],
            ],
            [
                'date' => '2026-05-14 15:40:00',
                'receiver_name' => 'Trịnh Thị K',
                'receiver_phone' => '0900111222',
                'receiver_address' => '77 Đồng Khởi, Quận 1, TP.HCM',
                'payment_method' => 'banking',
                'status' => 'completed',
                'items' => [
                    ['product' => 'Vivo V30 Pro 5G', 'quantity' => 1],
                    ['product' => 'Pin sạc dự phòng Anker 20000mAh', 'quantity' => 1],
                ],
            ],
            [
                'date' => '2026-06-02 10:10:00',
                'receiver_name' => 'Mai Văn L',
                'receiver_phone' => '0911000222',
                'receiver_address' => '120 Lê Văn Sỹ, Quận 3, TP.HCM',
                'payment_method' => 'wallet',
                'status' => 'shipping',
                'items' => [
                    ['product' => 'Xiaomi POCO F6', 'quantity' => 2],
                    ['product' => 'Củ sạc nhanh Anker Nano II 65W', 'quantity' => 1],
                ],
            ],
            [
                'date' => '2026-06-20 19:15:00',
                'receiver_name' => 'Đặng Thị M',
                'receiver_phone' => '0966333444',
                'receiver_address' => '33 Phạm Ngũ Lão, Quận 1, TP.HCM',
                'payment_method' => 'cod',
                'status' => 'completed',
                'items' => [
                    ['product' => 'iPhone 16', 'quantity' => 1],
                    ['product' => 'AirPods Pro 2 USB-C', 'quantity' => 1],
                ],
            ],
            [
                'date' => '2026-07-07 09:25:00',
                'receiver_name' => 'Nguyễn Thị N',
                'receiver_phone' => '0908222333',
                'receiver_address' => '88 Nguyễn Đình Chiểu, Quận 3, TP.HCM',
                'payment_method' => 'banking',
                'status' => 'completed',
                'items' => [
                    ['product' => 'Samsung Galaxy A35 5G', 'quantity' => 2],
                    ['product' => 'Cáp USB-C Samsung 1.8m 60W', 'quantity' => 2],
                ],
            ],
            [
                'date' => '2026-07-22 17:50:00',
                'receiver_name' => 'Phan Văn P',
                'receiver_phone' => '0972111222',
                'receiver_address' => '10 Lý Thường Kiệt, Quận 10, TP.HCM',
                'payment_method' => 'wallet',
                'status' => 'shipping',
                'items' => [
                    ['product' => 'Samsung Galaxy Z Flip6', 'quantity' => 1],
                    ['product' => 'Ốp lưng Ringke Fusion X', 'quantity' => 1],
                ],
            ],
            [
                'date' => '2026-08-09 11:05:00',
                'receiver_name' => 'Lý Thị Q',
                'receiver_phone' => '0933888999',
                'receiver_address' => '145 Điện Biên Phủ, Quận Bình Thạnh, TP.HCM',
                'payment_method' => 'cod',
                'status' => 'paid',
                'items' => [
                    ['product' => 'Xiaomi Redmi Note 13 Pro', 'quantity' => 2],
                    ['product' => 'Cáp USB-C to Lightning Apple 1m', 'quantity' => 2],
                ],
            ],
            [
                'date' => '2026-08-26 20:30:00',
                'receiver_name' => 'Trương Văn R',
                'receiver_phone' => '0912777444',
                'receiver_address' => '9 Cống Quỳnh, Quận 1, TP.HCM',
                'payment_method' => 'banking',
                'status' => 'completed',
                'items' => [
                    ['product' => 'iPhone SE 2024', 'quantity' => 1],
                    ['product' => 'Ốp lưng Nillkin CamShield Pro', 'quantity' => 1],
                ],
            ],
            [
                'date' => '2026-09-03 08:40:00',
                'receiver_name' => 'Trần Văn S',
                'receiver_phone' => '0945666777',
                'receiver_address' => '66 Hoàng Văn Thụ, Quận Phú Nhuận, TP.HCM',
                'payment_method' => 'wallet',
                'status' => 'shipping',
                'items' => [
                    ['product' => 'Vivo X100', 'quantity' => 1],
                    ['product' => 'Sạc không dây MagSafe Apple 15W', 'quantity' => 1],
                ],
            ],
            [
                'date' => '2026-09-21 14:05:00',
                'receiver_name' => 'Đinh Thị T',
                'receiver_phone' => '0922999000',
                'receiver_address' => '31 Phan Xích Long, Quận Phú Nhuận, TP.HCM',
                'payment_method' => 'cod',
                'status' => 'completed',
                'items' => [
                    ['product' => 'OPPO Find X7 Ultra', 'quantity' => 1],
                    ['product' => 'Giá đỡ điện thoại MagSafe ô tô', 'quantity' => 1],
                ],
            ],
            [
                'date' => '2026-10-11 12:35:00',
                'receiver_name' => 'Hồ Văn U',
                'receiver_phone' => '0988111333',
                'receiver_address' => '250 Võ Văn Tần, Quận 3, TP.HCM',
                'payment_method' => 'banking',
                'status' => 'paid',
                'items' => [
                    ['product' => 'Xiaomi Redmi Note 13', 'quantity' => 3],
                    ['product' => 'Miếng dán PPF tự phục hồi', 'quantity' => 3],
                ],
            ],
            [
                'date' => '2026-10-28 18:00:00',
                'receiver_name' => 'Lâm Thị V',
                'receiver_phone' => '0909333555',
                'receiver_address' => '41 Võ Thị Sáu, Quận 3, TP.HCM',
                'payment_method' => 'wallet',
                'status' => 'completed',
                'items' => [
                    ['product' => 'Samsung Galaxy A15', 'quantity' => 2],
                    ['product' => 'Samsung Galaxy Buds2 Pro', 'quantity' => 1],
                ],
            ],
            [
                'date' => '2026-11-06 10:45:00',
                'receiver_name' => 'Mai Thị W',
                'receiver_phone' => '0911666999',
                'receiver_address' => '5 Hai Bà Trưng, Quận 1, TP.HCM',
                'payment_method' => 'cod',
                'status' => 'shipping',
                'items' => [
                    ['product' => 'Vivo Y36', 'quantity' => 2],
                    ['product' => 'Pin sạc dự phòng Samsung 25W 10000mAh', 'quantity' => 1],
                ],
            ],
            [
                'date' => '2026-11-24 16:10:00',
                'receiver_name' => 'Vũ Văn X',
                'receiver_phone' => '0977333999',
                'receiver_address' => '72 Trần Quang Khải, Quận 1, TP.HCM',
                'payment_method' => 'banking',
                'status' => 'completed',
                'items' => [
                    ['product' => 'Samsung Galaxy Z Fold6', 'quantity' => 1],
                    ['product' => 'Samsung Galaxy Buds2 Pro', 'quantity' => 1],
                ],
            ],
            [
                'date' => '2026-12-08 09:55:00',
                'receiver_name' => 'Đỗ Thị Y',
                'receiver_phone' => '0933111777',
                'receiver_address' => '109 Nguyễn Văn Trỗi, Quận Phú Nhuận, TP.HCM',
                'payment_method' => 'wallet',
                'status' => 'paid',
                'items' => [
                    ['product' => 'iPhone 15 Pro Max', 'quantity' => 1],
                    ['product' => 'PopSocket gắn lưng điện thoại', 'quantity' => 1],
                ],
            ],
            [
                'date' => '2026-12-27 21:20:00',
                'receiver_name' => 'Ngô Thị Z',
                'receiver_phone' => '0900444555',
                'receiver_address' => '18 Bà Huyện Thanh Quan, Quận 3, TP.HCM',
                'payment_method' => 'cod',
                'status' => 'completed',
                'items' => [
                    ['product' => 'Xiaomi 14 Pro', 'quantity' => 2],
                    ['product' => 'Xiaomi POCO X6 Pro 5G', 'quantity' => 1],
                ],
            ],
        ];

        foreach ($orderFixtures as $fixture) {
            $this->createOrderFromFixture($testUser, $fixture);
        }

        $bulkOrderProducts = [
            'iPhone 16 Pro',
            'iPhone 16',
            'iPhone 15',
            'iPhone 15 Pro',
            'Samsung Galaxy S24',
            'Samsung Galaxy S24+',
            'Samsung Galaxy A55 5G',
            'Samsung Galaxy A35 5G',
            'Xiaomi 14 Ultra',
            'Xiaomi 14',
            'Xiaomi POCO F6',
            'OPPO Reno11 5G',
            'Vivo V30 Pro 5G',
            'AirPods Pro 2 USB-C',
            'JBL Tune 230NC TWS',
            'Cáp USB-C Samsung 1.8m 60W',
            'Ốp lưng Spigen Ultra Hybrid',
            'Pin sạc dự phòng Anker 20000mAh',
            'Miếng dán PPF tự phục hồi',
            'Giá đỡ điện thoại MagSafe ô tô',
        ];

        $bulkStatuses = ['paid', 'shipping', 'completed'];
        $bulkPayments = ['cod', 'banking', 'wallet'];
        $bulkCustomers = [
            ['name' => 'Linh', 'phone' => '0901000001', 'address' => '12 Nguyễn Trãi, Quận 1, TP.HCM'],
            ['name' => 'Huy', 'phone' => '0901000002', 'address' => '45 Võ Văn Tần, Quận 3, TP.HCM'],
            ['name' => 'Trang', 'phone' => '0901000003', 'address' => '77 Nguyễn Đình Chiểu, Quận 3, TP.HCM'],
            ['name' => 'Khoa', 'phone' => '0901000004', 'address' => '88 Lê Văn Sỹ, Quận Phú Nhuận, TP.HCM'],
            ['name' => 'Vy', 'phone' => '0901000005', 'address' => '21 Cách Mạng Tháng 8, Quận 10, TP.HCM'],
            ['name' => 'Minh', 'phone' => '0901000006', 'address' => '9 Điện Biên Phủ, Quận Bình Thạnh, TP.HCM'],
            ['name' => 'Hằng', 'phone' => '0901000007', 'address' => '31 Pasteur, Quận 1, TP.HCM'],
            ['name' => 'Phúc', 'phone' => '0901000008', 'address' => '15 Đồng Khởi, Quận 1, TP.HCM'],
            ['name' => 'Ngọc', 'phone' => '0901000009', 'address' => '100 Hoàng Văn Thụ, Quận Phú Nhuận, TP.HCM'],
            ['name' => 'Tú', 'phone' => '0901000010', 'address' => '250 Trần Hưng Đạo, Quận 5, TP.HCM'],
        ];

        $bulkFixtures = [];
        $startDate = Carbon::parse('2026-01-02 09:00:00');

        for ($index = 0; $index < 60; $index++) {
            $customer = $bulkCustomers[$index % count($bulkCustomers)];
            $primaryProduct = $bulkOrderProducts[$index % count($bulkOrderProducts)];
            $secondaryProduct = $bulkOrderProducts[($index + 5) % count($bulkOrderProducts)];
            $useSecondary = $index % 3 === 0;
            $date = $startDate->copy()->addDays($index * 3)->addHours($index % 8);
            $status = $bulkStatuses[$index % count($bulkStatuses)];
            $paymentMethod = $bulkPayments[$index % count($bulkPayments)];

            $bulkFixtures[] = [
                'date' => $date->format('Y-m-d H:i:s'),
                'receiver_name' => $customer['name'] . ' ' . chr(65 + ($index % 26)),
                'receiver_phone' => $customer['phone'],
                'receiver_address' => $customer['address'],
                'payment_method' => $paymentMethod,
                'status' => $status,
                'items' => $useSecondary
                    ? [
                        ['product' => $primaryProduct, 'quantity' => 1 + ($index % 3)],
                        ['product' => $secondaryProduct, 'quantity' => 1],
                    ]
                    : [
                        ['product' => $primaryProduct, 'quantity' => 1 + ($index % 4)],
                    ],
            ];
        }

        foreach ($bulkFixtures as $fixture) {
            $this->createOrderFromFixture($testUser, $fixture);
        }
    }

    private function createOrderFromFixture(User $user, array $fixture): void
    {
        $items = collect($fixture['items'])->map(function (array $item) {
            $product = Product::where('name', $item['product'])->first();

            if (!$product) {
                throw new \RuntimeException('Seed product not found: '.$item['product']);
            }

            return [
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'price' => $product->price,
            ];
        });

        $order = \App\Models\Order::create([
            'user_id' => $user->id,
            'receiver_name' => $fixture['receiver_name'],
            'receiver_phone' => $fixture['receiver_phone'],
            'receiver_address' => $fixture['receiver_address'],
            'payment_method' => $fixture['payment_method'],
            'status' => $fixture['status'],
            'total_amount' => $items->sum(fn (array $item) => $item['price'] * $item['quantity']),
        ]);

        foreach ($items as $item) {
            \App\Models\OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        if (in_array($fixture['status'], ['paid', 'shipping', 'completed'], true)) {
            \App\Models\Payment::create([
                'order_id' => $order->id,
                'payment_method' => $fixture['payment_method'],
                'amount' => $order->total_amount,
                'status' => 'completed',
                'paid_at' => Carbon::parse($fixture['date'])->copy()->addHour(),
            ]);
        }

        $timestamp = Carbon::parse($fixture['date']);
        $order->created_at = $timestamp;
        $order->updated_at = $timestamp;
        $order->saveQuietly();
    }

    private function attachSampleProductImages(Product $product): void
    {
        $imageMap = [
            'iPhone 15 Pro Max' => [
                'storage/product-images/iphone-15-pro-max.jpg',
                'storage/product-images/iphone-15-pro-max_2.jpg',
                'storage/product-images/iphone-15-pro-max_3.jpg',
            ],
            'Samsung Galaxy S24 Ultra' => [
                'storage/product-images/samsungs24ult.jpg',
                'storage/product-images/samsungs24ult_2.jpg',
                'storage/product-images/samsungs24ult_3.jpg',
            ],
            'Xiaomi 14 Pro' => [
                'storage/product-images/xiaomi-14_1.jpg',
                'storage/product-images/xiaomi-14_2.jpg',
                'storage/product-images/xiaomi-14_3.jpg',
            ],
        ];

        if (!isset($imageMap[$product->name])) {
            return;
        }

        foreach ($imageMap[$product->name] as $imageUrl) {
            ProductImage::create([
                'product_id' => $product->id,
                'image_url' => $imageUrl,
            ]);
        }
    }

    private function syncProductImagePathsToLocalFiles(): void
    {
        $manifestPath = storage_path('app/public/product-images/manifest.json');

        if (!is_file($manifestPath)) {
            return;
        }

        $manifest = json_decode((string) file_get_contents($manifestPath), true);

        if (!is_array($manifest)) {
            return;
        }

        foreach ($manifest as $row) {
            if (!isset($row['source_url'], $row['local_path'])) {
                continue;
            }

            DB::table('product_images')
                ->where('image_url', $row['source_url'])
                ->update(['image_url' => $row['local_path']]);
        }
    }
}


