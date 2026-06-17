<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Str;

class AccessorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create accessory categories
        $accessoryCategories = [
            ['name' => 'Ốp lưng', 'description' => 'Ốp lưng điện thoại các loại'],
            ['name' => 'Cáp sạc', 'description' => 'Cáp sạc và phụ kiện sạc'],
            ['name' => 'Tai nghe', 'description' => 'Tai nghe có dây và không dây'],
            ['name' => 'Sạc dự phòng', 'description' => 'Pin sạc dự phòng'],
            ['name' => 'Miếng dán màn hình', 'description' => 'Kính cường lực và miếng dán bảo vệ'],
            ['name' => 'Giá đỡ điện thoại', 'description' => 'Giá đỡ và kẹp điện thoại'],
        ];

        foreach ($accessoryCategories as $category) {
            Category::create($category);
        }

        // Get category IDs
        $opLungCat = Category::where('name', 'Ốp lưng')->first()->id;
        $capSacCat = Category::where('name', 'Cáp sạc')->first()->id;
        $taiNgheCat = Category::where('name', 'Tai nghe')->first()->id;
        $sacDuPhongCat = Category::where('name', 'Sạc dự phòng')->first()->id;
        $miengDanCat = Category::where('name', 'Miếng dán màn hình')->first()->id;
        $giaDoCat = Category::where('name', 'Giá đỡ điện thoại')->first()->id;

        // Create accessory products
        $accessories = [
            // Ốp lưng
            [
                'category_id' => $opLungCat,
                'name' => 'Ốp lưng iPhone 15 Pro Max MagSafe trong suốt',
                'brand' => 'Apple',
                'price' => 1290000,
                'ram' => null,
                'storage' => null,
                'battery' => null,
                'description' => 'Ốp lưng trong suốt chính hãng Apple với MagSafe, chống sốc, chống trầy xước',
                'quantity' => 100,
                'status' => 'active',
            ],
            [
                'category_id' => $opLungCat,
                'name' => 'Ốp lưng Samsung S24 Ultra Silicone',
                'brand' => 'Samsung',
                'price' => 690000,
                'ram' => null,
                'storage' => null,
                'battery' => null,
                'description' => 'Ốp lưng silicone cao cấp Samsung, mềm mại, chống sốc',
                'quantity' => 150,
                'status' => 'active',
            ],
            [
                'category_id' => $opLungCat,
                'name' => 'Ốp lưng chống sốc UAG Monarch',
                'brand' => 'UAG',
                'price' => 1590000,
                'ram' => null,
                'storage' => null,
                'battery' => null,
                'description' => 'Ốp lưng chống sốc chuẩn quân đội, 5 lớp bảo vệ',
                'quantity' => 80,
                'status' => 'active',
            ],
            [
                'category_id' => $opLungCat,
                'name' => 'Ốp lưng Spigen Ultra Hybrid',
                'brand' => 'Spigen',
                'price' => 490000,
                'ram' => null,
                'storage' => null,
                'battery' => null,
                'description' => 'Ốp lưng trong suốt Spigen, chống ố vàng, viền TPU chống sốc',
                'quantity' => 200,
                'status' => 'active',
            ],

            // Cáp sạc
            [
                'category_id' => $capSacCat,
                'name' => 'Cáp USB-C to Lightning Apple 1m',
                'brand' => 'Apple',
                'price' => 590000,
                'ram' => null,
                'storage' => null,
                'battery' => null,
                'description' => 'Cáp sạc chính hãng Apple, hỗ trợ sạc nhanh 20W',
                'quantity' => 200,
                'status' => 'active',
            ],
            [
                'category_id' => $capSacCat,
                'name' => 'Cáp USB-C Samsung 1.8m 60W',
                'brand' => 'Samsung',
                'price' => 390000,
                'ram' => null,
                'storage' => null,
                'battery' => null,
                'description' => 'Cáp sạc nhanh Samsung 60W, dây dù siêu bền',
                'quantity' => 180,
                'status' => 'active',
            ],
            [
                'category_id' => $capSacCat,
                'name' => 'Cáp Anker PowerLine III 2m',
                'brand' => 'Anker',
                'price' => 350000,
                'ram' => null,
                'storage' => null,
                'battery' => null,
                'description' => 'Cáp sạc Anker bền gấp 10 lần, hỗ trợ sạc nhanh 100W',
                'quantity' => 150,
                'status' => 'active',
            ],
            [
                'category_id' => $capSacCat,
                'name' => 'Củ sạc nhanh Apple 20W USB-C',
                'brand' => 'Apple',
                'price' => 590000,
                'ram' => null,
                'storage' => null,
                'battery' => null,
                'description' => 'Củ sạc nhanh 20W chính hãng Apple',
                'quantity' => 100,
                'status' => 'active',
            ],
            [
                'category_id' => $capSacCat,
                'name' => 'Củ sạc nhanh Samsung 45W',
                'brand' => 'Samsung',
                'price' => 890000,
                'ram' => null,
                'storage' => null,
                'battery' => null,
                'description' => 'Củ sạc siêu nhanh Samsung 45W, công nghệ Super Fast Charging 2.0',
                'quantity' => 80,
                'status' => 'active',
            ],

            // Tai nghe
            [
                'category_id' => $taiNgheCat,
                'name' => 'AirPods Pro 2 USB-C',
                'brand' => 'Apple',
                'price' => 5990000,
                'ram' => null,
                'storage' => null,
                'battery' => null,
                'description' => 'Tai nghe không dây Apple AirPods Pro 2 với chip H2, chống ồn chủ động',
                'quantity' => 50,
                'status' => 'active',
            ],
            [
                'category_id' => $taiNgheCat,
                'name' => 'Samsung Galaxy Buds2 Pro',
                'brand' => 'Samsung',
                'price' => 3990000,
                'ram' => null,
                'storage' => null,
                'battery' => null,
                'description' => 'Tai nghe không dây Samsung với ANC thông minh, âm thanh 24bit Hi-Fi',
                'quantity' => 60,
                'status' => 'active',
            ],
            [
                'category_id' => $taiNgheCat,
                'name' => 'Sony WF-1000XM5',
                'brand' => 'Sony',
                'price' => 6990000,
                'ram' => null,
                'storage' => null,
                'battery' => null,
                'description' => 'Tai nghe chống ồn tốt nhất thế giới, âm thanh Hi-Res Audio',
                'quantity' => 30,
                'status' => 'active',
            ],
            [
                'category_id' => $taiNgheCat,
                'name' => 'Xiaomi Buds 4 Pro',
                'brand' => 'Xiaomi',
                'price' => 2490000,
                'ram' => null,
                'storage' => null,
                'battery' => null,
                'description' => 'Tai nghe không dây Xiaomi với ANC 48dB, LDAC codec',
                'quantity' => 70,
                'status' => 'active',
            ],
            [
                'category_id' => $taiNgheCat,
                'name' => 'JBL Tune 230NC TWS',
                'brand' => 'JBL',
                'price' => 1990000,
                'ram' => null,
                'storage' => null,
                'battery' => null,
                'description' => 'Tai nghe JBL với bass mạnh mẽ, chống ồn chủ động, pin 40h',
                'quantity' => 90,
                'status' => 'active',
            ],

            // Sạc dự phòng
            [
                'category_id' => $sacDuPhongCat,
                'name' => 'Pin sạc dự phòng Anker 20000mAh',
                'brand' => 'Anker',
                'price' => 1290000,
                'ram' => null,
                'storage' => null,
                'battery' => '20000mAh',
                'description' => 'Sạc dự phòng Anker PowerCore 20000mAh, sạc nhanh 22.5W',
                'quantity' => 100,
                'status' => 'active',
            ],
            [
                'category_id' => $sacDuPhongCat,
                'name' => 'Pin sạc dự phòng Samsung 25W 10000mAh',
                'brand' => 'Samsung',
                'price' => 890000,
                'ram' => null,
                'storage' => null,
                'battery' => '10000mAh',
                'description' => 'Sạc dự phòng Samsung siêu mỏng, sạc nhanh 25W',
                'quantity' => 120,
                'status' => 'active',
            ],
            [
                'category_id' => $sacDuPhongCat,
                'name' => 'Pin sạc dự phòng Xiaomi 30000mAh',
                'brand' => 'Xiaomi',
                'price' => 990000,
                'ram' => null,
                'storage' => null,
                'battery' => '30000mAh',
                'description' => 'Sạc dự phòng Xiaomi dung lượng lớn, sạc nhanh 33W',
                'quantity' => 80,
                'status' => 'active',
            ],
            [
                'category_id' => $sacDuPhongCat,
                'name' => 'Pin sạc MagSafe Apple Battery Pack',
                'brand' => 'Apple',
                'price' => 2590000,
                'ram' => null,
                'storage' => null,
                'battery' => '5000mAh',
                'description' => 'Pin sạc MagSafe gắn lưng iPhone, sạc không dây tiện lợi',
                'quantity' => 50,
                'status' => 'active',
            ],

            // Miếng dán màn hình
            [
                'category_id' => $miengDanCat,
                'name' => 'Kính cường lực iPhone 15 Pro Max',
                'brand' => 'Zagg',
                'price' => 490000,
                'ram' => null,
                'storage' => null,
                'battery' => null,
                'description' => 'Kính cường lực 9H chống trầy, chống vân tay, full màn hình',
                'quantity' => 200,
                'status' => 'active',
            ],
            [
                'category_id' => $miengDanCat,
                'name' => 'Kính cường lực Samsung S24 Ultra UV',
                'brand' => 'Whitestone',
                'price' => 890000,
                'ram' => null,
                'storage' => null,
                'battery' => null,
                'description' => 'Kính cường lực dán keo UV, bảo vệ toàn diện màn hình cong',
                'quantity' => 100,
                'status' => 'active',
            ],
            [
                'category_id' => $miengDanCat,
                'name' => 'Miếng dán PPF tự phục hồi',
                'brand' => 'Dán Dẻo',
                'price' => 190000,
                'ram' => null,
                'storage' => null,
                'battery' => null,
                'description' => 'Miếng dán PPF tự lành vết xước nhẹ, trong suốt 100%',
                'quantity' => 300,
                'status' => 'active',
            ],

            // Giá đỡ điện thoại
            [
                'category_id' => $giaDoCat,
                'name' => 'Giá đỡ điện thoại MagSafe ô tô',
                'brand' => 'Belkin',
                'price' => 1190000,
                'ram' => null,
                'storage' => null,
                'battery' => null,
                'description' => 'Giá đỡ MagSafe gắn cửa gió ô tô, xoay 360 độ',
                'quantity' => 80,
                'status' => 'active',
            ],
            [
                'category_id' => $giaDoCat,
                'name' => 'Giá đỡ điện thoại để bàn có sạc',
                'brand' => 'Anker',
                'price' => 790000,
                'ram' => null,
                'storage' => null,
                'battery' => null,
                'description' => 'Giá đỡ để bàn tích hợp sạc không dây 15W',
                'quantity' => 100,
                'status' => 'active',
            ],
            [
                'category_id' => $giaDoCat,
                'name' => 'Tripod điện thoại Bluetooth',
                'brand' => 'Ulanzi',
                'price' => 390000,
                'ram' => null,
                'storage' => null,
                'battery' => null,
                'description' => 'Chân máy tripod mini với remote Bluetooth, cao 1.5m',
                'quantity' => 150,
                'status' => 'active',
            ],
            [
                'category_id' => $giaDoCat,
                'name' => 'PopSocket gắn lưng điện thoại',
                'brand' => 'PopSockets',
                'price' => 250000,
                'ram' => null,
                'storage' => null,
                'battery' => null,
                'description' => 'PopSocket gắn lưng, có thể làm giá đỡ, nhiều màu sắc',
                'quantity' => 200,
                'status' => 'active',
            ],
        ];

        foreach ($accessories as $accessory) {
            $product = Product::create($accessory);

            foreach ($this->buildImageUrls($accessory['name']) as $imageUrl) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_url' => $imageUrl,
                ]);
            }
        }

        echo "Đã thêm " . count($accessories) . " sản phẩm phụ kiện!\n";
    }

    private function buildImageUrls(string $name): array
    {
        $normalizedName = Str::of($name)->lower()->ascii()->toString();

        if (Str::contains($normalizedName, 'op lung')) {
            return [
                'storage/product-images/product-image-031.jpg',
                'storage/product-images/product-image-017.jpg',
                'storage/product-images/product-image-038.jpg',
            ];
        }

        if (Str::contains($normalizedName, 'cap')) {
            return [
                'storage/product-images/product-image-017.jpg',
                'storage/product-images/product-image-031.jpg',
                'storage/product-images/product-image-019.jpg',
            ];
        }

        if (Str::contains($normalizedName, 'tai nghe') || Str::contains($normalizedName, 'earbuds') || Str::contains($normalizedName, 'airpods') || Str::contains($normalizedName, 'sony') || Str::contains($normalizedName, 'jbl')) {
            return [
                'storage/product-images/product-image-022.jpg',
                'storage/product-images/product-image-012.jpg',
                'storage/product-images/product-image-035.jpg',
            ];
        }

        if (Str::contains($normalizedName, 'sac du phong') || Str::contains($normalizedName, 'power bank') || Str::contains($normalizedName, 'pin sac')) {
            return [
                'storage/product-images/product-image-038.jpg',
                'storage/product-images/product-image-017.jpg',
                'storage/product-images/product-image-035.jpg',
            ];
        }

        if (Str::contains($normalizedName, 'mieng dan') || Str::contains($normalizedName, 'kinh cuong luc')) {
            return [
                'storage/product-images/product-image-031.jpg',
                'storage/product-images/product-image-027.jpg',
                'storage/product-images/product-image-007.jpg',
            ];
        }

        if (Str::contains($normalizedName, 'gia do') || Str::contains($normalizedName, 'tripod') || Str::contains($normalizedName, 'popsocket')) {
            return [
                'storage/product-images/product-image-012.jpg',
                'storage/product-images/product-image-031.jpg',
                'storage/product-images/product-image-035.jpg',
            ];
        }

        return [
            'storage/product-images/product-image-031.jpg',
            'storage/product-images/product-image-017.jpg',
            'storage/product-images/product-image-031.jpg',
        ];
    }
}


