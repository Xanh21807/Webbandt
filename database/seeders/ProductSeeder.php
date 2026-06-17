<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // ============ IPHONE (category_id = 1) ============
            [
                'category_id' => 1,
                'name' => 'iPhone 16 Pro',
                'brand' => 'Apple',
                'price' => 28990000,
                'ram' => '8GB',
                'storage' => '256GB',
                'battery' => '3582mAh',
                'description' => 'iPhone 16 Pro với chip A18 Pro, camera 48MP, màn hình ProMotion 120Hz',
                'quantity' => 45,
                'status' => 'active',
                'image' => 'storage/product-images/product-image-024.jpg'
            ],
            [
                'category_id' => 1,
                'name' => 'iPhone 16',
                'brand' => 'Apple',
                'price' => 22990000,
                'ram' => '8GB',
                'storage' => '128GB',
                'battery' => '3561mAh',
                'description' => 'iPhone 16 với chip A18, Dynamic Island, camera 48MP cải tiến',
                'quantity' => 60,
                'status' => 'active',
                'image' => 'storage/product-images/product-image-001.jpg'
            ],
            [
                'category_id' => 1,
                'name' => 'iPhone 15',
                'brand' => 'Apple',
                'price' => 19990000,
                'ram' => '6GB',
                'storage' => '128GB',
                'battery' => '3349mAh',
                'description' => 'iPhone 15 với Dynamic Island, camera 48MP, cổng USB-C',
                'quantity' => 80,
                'status' => 'active',
                'image' => 'storage/product-images/product-image-049.jpg'
            ],
            [
                'category_id' => 1,
                'name' => 'iPhone 15 Plus',
                'brand' => 'Apple',
                'price' => 23990000,
                'ram' => '6GB',
                'storage' => '128GB',
                'battery' => '4383mAh',
                'description' => 'iPhone 15 Plus màn hình 6.7 inch, pin lớn, Dynamic Island',
                'quantity' => 40,
                'status' => 'active',
                'image' => 'storage/product-images/product-image-023.jpg'
            ],
            [
                'category_id' => 1,
                'name' => 'iPhone 15 Pro',
                'brand' => 'Apple',
                'price' => 26990000,
                'ram' => '8GB',
                'storage' => '256GB',
                'battery' => '3274mAh',
                'description' => 'iPhone 15 Pro với chip A17 Pro, khung Titan, Action Button',
                'quantity' => 55,
                'status' => 'active',
                'image' => 'storage/product-images/product-image-046.jpg'
            ],
            [
                'category_id' => 1,
                'name' => 'iPhone 14',
                'brand' => 'Apple',
                'price' => 16990000,
                'ram' => '6GB',
                'storage' => '128GB',
                'battery' => '3279mAh',
                'description' => 'iPhone 14 với chip A15 Bionic, camera kép 12MP',
                'quantity' => 100,
                'status' => 'active',
                'image' => 'storage/product-images/product-image-045.jpg'
            ],
            [
                'category_id' => 1,
                'name' => 'iPhone 14 Plus',
                'brand' => 'Apple',
                'price' => 19990000,
                'ram' => '6GB',
                'storage' => '128GB',
                'battery' => '4325mAh',
                'description' => 'iPhone 14 Plus màn hình lớn 6.7 inch, pin cả ngày',
                'quantity' => 70,
                'status' => 'active',
                'image' => 'storage/product-images/product-image-048.jpg'
            ],
            [
                'category_id' => 1,
                'name' => 'iPhone SE 2024',
                'brand' => 'Apple',
                'price' => 12990000,
                'ram' => '4GB',
                'storage' => '64GB',
                'battery' => '2018mAh',
                'description' => 'iPhone SE giá rẻ với chip A15 Bionic, Touch ID',
                'quantity' => 120,
                'status' => 'active',
                'image' => 'storage/product-images/product-image-049.jpg'
            ],

            // ============ SAMSUNG (category_id = 2) ============
            [
                'category_id' => 2,
                'name' => 'Samsung Galaxy S24',
                'brand' => 'Samsung',
                'price' => 22990000,
                'ram' => '8GB',
                'storage' => '256GB',
                'battery' => '4000mAh',
                'description' => 'Galaxy S24 với Galaxy AI, màn hình Dynamic AMOLED 2X',
                'quantity' => 65,
                'status' => 'active',
                'image' => 'storage/product-images/product-image-040.jpg'
            ],
            [
                'category_id' => 2,
                'name' => 'Samsung Galaxy S24+',
                'brand' => 'Samsung',
                'price' => 26990000,
                'ram' => '12GB',
                'storage' => '256GB',
                'battery' => '4900mAh',
                'description' => 'Galaxy S24+ màn hình 6.7 inch QHD+, Galaxy AI',
                'quantity' => 50,
                'status' => 'active',
                'image' => 'storage/product-images/product-image-018.jpg'
            ],
            [
                'category_id' => 2,
                'name' => 'Samsung Galaxy Z Fold6',
                'brand' => 'Samsung',
                'price' => 45990000,
                'ram' => '12GB',
                'storage' => '512GB',
                'battery' => '4400mAh',
                'description' => 'Galaxy Z Fold6 gập được, màn hình 7.6 inch, S Pen',
                'quantity' => 25,
                'status' => 'active',
                'image' => 'storage/product-images/product-image-043.jpg'
            ],
            [
                'category_id' => 2,
                'name' => 'Samsung Galaxy Z Flip6',
                'brand' => 'Samsung',
                'price' => 28990000,
                'ram' => '12GB',
                'storage' => '256GB',
                'battery' => '3700mAh',
                'description' => 'Galaxy Z Flip6 gập nhỏ gọn, màn hình phụ 3.4 inch',
                'quantity' => 40,
                'status' => 'active',
                'image' => 'storage/product-images/product-image-032.jpg'
            ],
            [
                'category_id' => 2,
                'name' => 'Samsung Galaxy A55 5G',
                'brand' => 'Samsung',
                'price' => 10990000,
                'ram' => '8GB',
                'storage' => '128GB',
                'battery' => '5000mAh',
                'description' => 'Galaxy A55 5G với chip Exynos 1480, camera 50MP OIS',
                'quantity' => 150,
                'status' => 'active',
                'image' => 'storage/product-images/product-image-013.jpg'
            ],
            [
                'category_id' => 2,
                'name' => 'Samsung Galaxy A35 5G',
                'brand' => 'Samsung',
                'price' => 8490000,
                'ram' => '8GB',
                'storage' => '128GB',
                'battery' => '5000mAh',
                'description' => 'Galaxy A35 5G giá tốt, màn hình Super AMOLED 120Hz',
                'quantity' => 200,
                'status' => 'active',
                'image' => 'storage/product-images/product-image-026.jpg'
            ],
            [
                'category_id' => 2,
                'name' => 'Samsung Galaxy A15',
                'brand' => 'Samsung',
                'price' => 4990000,
                'ram' => '6GB',
                'storage' => '128GB',
                'battery' => '5000mAh',
                'description' => 'Galaxy A15 phân khúc giá rẻ, màn hình 6.5 inch FHD+',
                'quantity' => 300,
                'status' => 'active',
                'image' => 'storage/product-images/product-image-032.jpg'
            ],
            [
                'category_id' => 2,
                'name' => 'Samsung Galaxy M54 5G',
                'brand' => 'Samsung',
                'price' => 9990000,
                'ram' => '8GB',
                'storage' => '256GB',
                'battery' => '6000mAh',
                'description' => 'Galaxy M54 pin khủng 6000mAh, camera 108MP',
                'quantity' => 100,
                'status' => 'active',
                'image' => 'storage/product-images/product-image-008.jpg'
            ],

            // ============ XIAOMI (category_id = 3) ============
            [
                'category_id' => 3,
                'name' => 'Xiaomi 14 Ultra',
                'brand' => 'Xiaomi',
                'price' => 29990000,
                'ram' => '16GB',
                'storage' => '512GB',
                'battery' => '5000mAh',
                'description' => 'Xiaomi 14 Ultra với camera Leica, Snapdragon 8 Gen 3',
                'quantity' => 30,
                'status' => 'active',
                'image' => 'storage/product-images/product-image-028.jpg'
            ],
            [
                'category_id' => 3,
                'name' => 'Xiaomi 14',
                'brand' => 'Xiaomi',
                'price' => 18990000,
                'ram' => '12GB',
                'storage' => '256GB',
                'battery' => '4610mAh',
                'description' => 'Xiaomi 14 màn hình phẳng, camera Leica 50MP',
                'quantity' => 55,
                'status' => 'active',
                'image' => 'storage/product-images/product-image-014.jpg'
            ],
            [
                'category_id' => 3,
                'name' => 'Xiaomi Redmi Note 13 Pro+ 5G',
                'brand' => 'Xiaomi',
                'price' => 9990000,
                'ram' => '8GB',
                'storage' => '256GB',
                'battery' => '5000mAh',
                'description' => 'Redmi Note 13 Pro+ camera 200MP, sạc nhanh 120W',
                'quantity' => 120,
                'status' => 'active',
                'image' => 'storage/product-images/product-image-003.jpg'
            ],
            [
                'category_id' => 3,
                'name' => 'Xiaomi Redmi Note 13 Pro',
                'brand' => 'Xiaomi',
                'price' => 7490000,
                'ram' => '8GB',
                'storage' => '128GB',
                'battery' => '5100mAh',
                'description' => 'Redmi Note 13 Pro camera 200MP, màn hình AMOLED 120Hz',
                'quantity' => 150,
                'status' => 'active',
                'image' => 'storage/product-images/product-image-006.jpg'
            ],
            [
                'category_id' => 3,
                'name' => 'Xiaomi Redmi Note 13',
                'brand' => 'Xiaomi',
                'price' => 4990000,
                'ram' => '6GB',
                'storage' => '128GB',
                'battery' => '5000mAh',
                'description' => 'Redmi Note 13 giá rẻ, màn hình AMOLED 120Hz',
                'quantity' => 200,
                'status' => 'active',
                'image' => 'storage/product-images/product-image-032.jpg'
            ],
            [
                'category_id' => 3,
                'name' => 'Xiaomi POCO X6 Pro 5G',
                'brand' => 'Xiaomi',
                'price' => 8990000,
                'ram' => '8GB',
                'storage' => '256GB',
                'battery' => '5000mAh',
                'description' => 'POCO X6 Pro với Dimensity 8300 Ultra, màn hình 1.5K',
                'quantity' => 100,
                'status' => 'active',
                'image' => 'storage/product-images/product-image-005.jpg'
            ],
            [
                'category_id' => 3,
                'name' => 'Xiaomi POCO F6',
                'brand' => 'Xiaomi',
                'price' => 10990000,
                'ram' => '12GB',
                'storage' => '256GB',
                'battery' => '5000mAh',
                'description' => 'POCO F6 với Snapdragon 8s Gen 3, hiệu năng cao',
                'quantity' => 80,
                'status' => 'active',
                'image' => 'storage/product-images/product-image-003.jpg'
            ],
            [
                'category_id' => 3,
                'name' => 'Xiaomi Redmi 13C',
                'brand' => 'Xiaomi',
                'price' => 2990000,
                'ram' => '4GB',
                'storage' => '128GB',
                'battery' => '5000mAh',
                'description' => 'Redmi 13C giá siêu rẻ, camera 50MP, pin lớn',
                'quantity' => 250,
                'status' => 'active',
                'image' => 'storage/product-images/product-image-013.jpg'
            ],

            // ============ OPPO (category_id = 4) ============
            [
                'category_id' => 4,
                'name' => 'OPPO Find X7 Ultra',
                'brand' => 'Oppo',
                'price' => 27990000,
                'ram' => '16GB',
                'storage' => '512GB',
                'battery' => '5000mAh',
                'description' => 'Find X7 Ultra với camera Hasselblad, Dimensity 9300',
                'quantity' => 25,
                'status' => 'active',
                'image' => 'storage/product-images/product-image-026.jpg'
            ],
            [
                'category_id' => 4,
                'name' => 'OPPO Find N3 Flip',
                'brand' => 'Oppo',
                'price' => 22990000,
                'ram' => '12GB',
                'storage' => '256GB',
                'battery' => '4300mAh',
                'description' => 'Find N3 Flip gập nhỏ gọn, màn hình phụ lớn 3.26 inch',
                'quantity' => 35,
                'status' => 'active',
                'image' => 'storage/product-images/product-image-043.jpg'
            ],
            [
                'category_id' => 4,
                'name' => 'OPPO Reno11 Pro 5G',
                'brand' => 'Oppo',
                'price' => 13990000,
                'ram' => '12GB',
                'storage' => '256GB',
                'battery' => '4600mAh',
                'description' => 'Reno11 Pro camera chân dung Hasselblad, sạc 80W',
                'quantity' => 70,
                'status' => 'active',
                'image' => 'storage/product-images/product-image-008.jpg'
            ],
            [
                'category_id' => 4,
                'name' => 'OPPO Reno11 5G',
                'brand' => 'Oppo',
                'price' => 10990000,
                'ram' => '12GB',
                'storage' => '256GB',
                'battery' => '5000mAh',
                'description' => 'Reno11 5G thiết kế thời trang, camera 50MP OIS',
                'quantity' => 90,
                'status' => 'active',
                'image' => 'storage/product-images/product-image-014.jpg'
            ],
            [
                'category_id' => 4,
                'name' => 'OPPO A98 5G',
                'brand' => 'Oppo',
                'price' => 8490000,
                'ram' => '8GB',
                'storage' => '256GB',
                'battery' => '5000mAh',
                'description' => 'A98 5G giá tốt, sạc nhanh 67W, màn hình 120Hz',
                'quantity' => 120,
                'status' => 'active',
                'image' => 'storage/product-images/product-image-032.jpg'
            ],
            [
                'category_id' => 4,
                'name' => 'OPPO A79 5G',
                'brand' => 'Oppo',
                'price' => 6990000,
                'ram' => '8GB',
                'storage' => '256GB',
                'battery' => '5000mAh',
                'description' => 'A79 5G tầm trung, loa stereo, sạc 33W',
                'quantity' => 150,
                'status' => 'active',
                'image' => 'storage/product-images/product-image-003.jpg'
            ],
            [
                'category_id' => 4,
                'name' => 'OPPO A18',
                'brand' => 'Oppo',
                'price' => 3490000,
                'ram' => '4GB',
                'storage' => '128GB',
                'battery' => '5000mAh',
                'description' => 'A18 giá rẻ, pin khủng 5000mAh, màn hình 90Hz',
                'quantity' => 200,
                'status' => 'active',
                'image' => 'storage/product-images/product-image-013.jpg'
            ],
            [
                'category_id' => 4,
                'name' => 'OPPO A38',
                'brand' => 'Oppo',
                'price' => 4290000,
                'ram' => '6GB',
                'storage' => '128GB',
                'battery' => '5000mAh',
                'description' => 'A38 thiết kế đẹp, camera 50MP, sạc nhanh 33W',
                'quantity' => 180,
                'status' => 'active',
                'image' => 'storage/product-images/product-image-006.jpg'
            ],

            // ============ VIVO (category_id = 5) ============
            [
                'category_id' => 5,
                'name' => 'Vivo X100 Pro',
                'brand' => 'Vivo',
                'price' => 26990000,
                'ram' => '16GB',
                'storage' => '512GB',
                'battery' => '5400mAh',
                'description' => 'X100 Pro với camera ZEISS, Dimensity 9300',
                'quantity' => 30,
                'status' => 'active',
                'image' => 'storage/product-images/product-image-026.jpg'
            ],
            [
                'category_id' => 5,
                'name' => 'Vivo X100',
                'brand' => 'Vivo',
                'price' => 19990000,
                'ram' => '12GB',
                'storage' => '256GB',
                'battery' => '5000mAh',
                'description' => 'X100 camera ZEISS 50MP, màn hình AMOLED 120Hz',
                'quantity' => 45,
                'status' => 'active',
                'image' => 'storage/product-images/product-image-014.jpg'
            ],
            [
                'category_id' => 5,
                'name' => 'Vivo V30 Pro 5G',
                'brand' => 'Vivo',
                'price' => 12990000,
                'ram' => '12GB',
                'storage' => '256GB',
                'battery' => '5000mAh',
                'description' => 'V30 Pro camera ZEISS, Aura Light Portrait',
                'quantity' => 60,
                'status' => 'active',
                'image' => 'storage/product-images/product-image-008.jpg'
            ],
            [
                'category_id' => 5,
                'name' => 'Vivo V30e 5G',
                'brand' => 'Vivo',
                'price' => 9490000,
                'ram' => '8GB',
                'storage' => '256GB',
                'battery' => '5500mAh',
                'description' => 'V30e thiết kế mỏng nhẹ, camera 50MP OIS',
                'quantity' => 80,
                'status' => 'active',
                'image' => 'storage/product-images/product-image-032.jpg'
            ],
            [
                'category_id' => 5,
                'name' => 'Vivo Y36',
                'brand' => 'Vivo',
                'price' => 5490000,
                'ram' => '8GB',
                'storage' => '128GB',
                'battery' => '5000mAh',
                'description' => 'Y36 giá tốt, thiết kế thời trang, sạc 44W',
                'quantity' => 150,
                'status' => 'active',
                'image' => 'storage/product-images/product-image-003.jpg'
            ],
            [
                'category_id' => 5,
                'name' => 'Vivo Y27 5G',
                'brand' => 'Vivo',
                'price' => 6190000,
                'ram' => '8GB',
                'storage' => '128GB',
                'battery' => '5000mAh',
                'description' => 'Y27 5G tầm trung, màn hình 90Hz, camera 50MP',
                'quantity' => 130,
                'status' => 'active',
                'image' => 'storage/product-images/product-image-013.jpg'
            ],
            [
                'category_id' => 5,
                'name' => 'Vivo Y17s',
                'brand' => 'Vivo',
                'price' => 3790000,
                'ram' => '6GB',
                'storage' => '128GB',
                'battery' => '5000mAh',
                'description' => 'Y17s giá rẻ, camera kép, pin cả ngày',
                'quantity' => 200,
                'status' => 'active',
                'image' => 'storage/product-images/product-image-006.jpg'
            ],
            [
                'category_id' => 5,
                'name' => 'Vivo Y02t',
                'brand' => 'Vivo',
                'price' => 2490000,
                'ram' => '4GB',
                'storage' => '64GB',
                'battery' => '5000mAh',
                'description' => 'Y02t giá siêu rẻ, pin lớn, màn hình 6.51 inch',
                'quantity' => 250,
                'status' => 'active',
                'image' => 'storage/product-images/product-image-005.jpg'
            ],

            // ============ ỐP LƯNG (category_id = 6) - Thêm ============
            [
                'category_id' => 6,
                'name' => 'Ốp lưng iPhone 16 Pro Max Clear Case',
                'brand' => 'Apple',
                'price' => 1490000,
                'ram' => null,
                'storage' => null,
                'battery' => null,
                'description' => 'Ốp lưng trong suốt chính hãng Apple, MagSafe, chống ố vàng',
                'quantity' => 100,
                'status' => 'active',
                'image' => 'storage/product-images/product-image-030.jpg'
            ],
            [
                'category_id' => 6,
                'name' => 'Ốp lưng Ringke Fusion X',
                'brand' => 'Ringke',
                'price' => 390000,
                'ram' => null,
                'storage' => null,
                'battery' => null,
                'description' => 'Ốp lưng chống sốc viền cứng, lưng trong suốt',
                'quantity' => 200,
                'status' => 'active',
                'image' => 'storage/product-images/product-image-018.jpg'
            ],
            [
                'category_id' => 6,
                'name' => 'Ốp lưng Nillkin CamShield Pro',
                'brand' => 'Nillkin',
                'price' => 350000,
                'ram' => null,
                'storage' => null,
                'battery' => null,
                'description' => 'Ốp lưng bảo vệ camera với nắp trượt',
                'quantity' => 150,
                'status' => 'active',
                'image' => 'storage/product-images/product-image-037.jpg'
            ],

            // ============ CÁP SẠC (category_id = 7) - Thêm ============
            [
                'category_id' => 7,
                'name' => 'Sạc không dây MagSafe Apple 15W',
                'brand' => 'Apple',
                'price' => 1090000,
                'ram' => null,
                'storage' => null,
                'battery' => null,
                'description' => 'Sạc không dây MagSafe chính hãng Apple 15W',
                'quantity' => 80,
                'status' => 'active',
                'image' => 'storage/product-images/product-image-030.jpg'
            ],
            [
                'category_id' => 7,
                'name' => 'Củ sạc nhanh Anker Nano II 65W',
                'brand' => 'Anker',
                'price' => 990000,
                'ram' => null,
                'storage' => null,
                'battery' => null,
                'description' => 'Củ sạc GaN siêu nhỏ 65W, 2 cổng USB-C',
                'quantity' => 100,
                'status' => 'active',
                'image' => 'storage/product-images/product-image-016.jpg'
            ],
            [
                'category_id' => 7,
                'name' => 'Cáp Baseus Tungsten Gold 100W 2m',
                'brand' => 'Baseus',
                'price' => 290000,
                'ram' => null,
                'storage' => null,
                'battery' => null,
                'description' => 'Cáp USB-C sạc nhanh 100W, dây dù siêu bền',
                'quantity' => 200,
                'status' => 'active',
                'image' => 'storage/product-images/product-image-037.jpg'
            ],

            // ============ TAI NGHE (category_id = 8) - Thêm ============
            [
                'category_id' => 8,
                'name' => 'AirPods 4 Active Noise Cancellation',
                'brand' => 'Apple',
                'price' => 4990000,
                'ram' => null,
                'storage' => null,
                'battery' => null,
                'description' => 'AirPods 4 với chống ồn chủ động, chip H2',
                'quantity' => 60,
                'status' => 'active',
                'image' => 'storage/product-images/product-image-036.jpg'
            ],
            [
                'category_id' => 8,
                'name' => 'AirPods Max',
                'brand' => 'Apple',
                'price' => 12990000,
                'ram' => null,
                'storage' => null,
                'battery' => null,
                'description' => 'Tai nghe over-ear cao cấp Apple, ANC, Spatial Audio',
                'quantity' => 25,
                'status' => 'active',
                'image' => 'storage/product-images/product-image-042.jpg'
            ],
            [
                'category_id' => 8,
                'name' => 'Bose QuietComfort Ultra',
                'brand' => 'Bose',
                'price' => 8990000,
                'ram' => null,
                'storage' => null,
                'battery' => null,
                'description' => 'Tai nghe Bose chống ồn đỉnh cao, CustomTune',
                'quantity' => 35,
                'status' => 'active',
                'image' => 'storage/product-images/product-image-010.jpg'
            ],
            [
                'category_id' => 8,
                'name' => 'Soundpeats Air4 Pro',
                'brand' => 'Soundpeats',
                'price' => 1190000,
                'ram' => null,
                'storage' => null,
                'battery' => null,
                'description' => 'Tai nghe TWS giá rẻ với ANC, aptX Lossless',
                'quantity' => 150,
                'status' => 'active',
                'image' => 'storage/product-images/product-image-021.jpg'
            ],

            // ============ SẠC DỰ PHÒNG (category_id = 9) - Thêm ============
            [
                'category_id' => 9,
                'name' => 'Pin sạc Anker Prime 27650mAh 250W',
                'brand' => 'Anker',
                'price' => 3990000,
                'ram' => null,
                'storage' => null,
                'battery' => '27650mAh',
                'description' => 'Pin sạc cao cấp, sạc được laptop 250W, 3 cổng',
                'quantity' => 30,
                'status' => 'active',
                'image' => 'storage/product-images/product-image-039.jpg'
            ],
            [
                'category_id' => 9,
                'name' => 'Pin sạc Baseus Blade 20000mAh 100W',
                'brand' => 'Baseus',
                'price' => 1490000,
                'ram' => null,
                'storage' => null,
                'battery' => '20000mAh',
                'description' => 'Pin sạc siêu mỏng như dao, sạc nhanh 100W',
                'quantity' => 80,
                'status' => 'active',
                'image' => 'storage/product-images/product-image-020.jpg'
            ],
            [
                'category_id' => 9,
                'name' => 'Pin sạc UGREEN 10000mAh MagSafe',
                'brand' => 'UGREEN',
                'price' => 690000,
                'ram' => null,
                'storage' => null,
                'battery' => '10000mAh',
                'description' => 'Pin sạc MagSafe cho iPhone, nhỏ gọn tiện lợi',
                'quantity' => 120,
                'status' => 'active',
                'image' => 'storage/product-images/product-image-016.jpg'
            ],

            // ============ MIẾNG DÁN (category_id = 10) - Thêm ============
            [
                'category_id' => 10,
                'name' => 'Kính cường lực iPhone 16 Pro Max Belkin',
                'brand' => 'Belkin',
                'price' => 790000,
                'ram' => null,
                'storage' => null,
                'battery' => null,
                'description' => 'Kính cường lực chính hãng Belkin, dễ dán, chống trầy',
                'quantity' => 150,
                'status' => 'active',
                'image' => 'storage/product-images/product-image-030.jpg'
            ],
            [
                'category_id' => 10,
                'name' => 'Kính cường lực Privacy chống nhìn trộm',
                'brand' => 'Mipow',
                'price' => 390000,
                'ram' => null,
                'storage' => null,
                'battery' => null,
                'description' => 'Kính cường lực chống nhìn trộm 180 độ',
                'quantity' => 200,
                'status' => 'active',
                'image' => 'storage/product-images/product-image-018.jpg'
            ],

            // ============ GIÁ ĐỠ (category_id = 11) - Thêm ============
            [
                'category_id' => 11,
                'name' => 'Đế sạc 3 in 1 Apple MagSafe',
                'brand' => 'Apple',
                'price' => 3490000,
                'ram' => null,
                'storage' => null,
                'battery' => null,
                'description' => 'Đế sạc iPhone, Apple Watch, AirPods cùng lúc',
                'quantity' => 40,
                'status' => 'active',
                'image' => 'storage/product-images/product-image-030.jpg'
            ],
            [
                'category_id' => 11,
                'name' => 'Gimbal điện thoại DJI Osmo Mobile 6',
                'brand' => 'DJI',
                'price' => 3590000,
                'ram' => null,
                'storage' => null,
                'battery' => null,
                'description' => 'Gimbal chống rung 3 trục, tự động theo dõi đối tượng',
                'quantity' => 35,
                'status' => 'active',
                'image' => 'storage/product-images/product-image-034.jpg'
            ],
            [
                'category_id' => 11,
                'name' => 'Kẹp điện thoại xe đạp Quad Lock',
                'brand' => 'Quad Lock',
                'price' => 890000,
                'ram' => null,
                'storage' => null,
                'battery' => null,
                'description' => 'Kẹp điện thoại xe đạp/xe máy, khóa chắc chắn',
                'quantity' => 80,
                'status' => 'active',
                'image' => 'storage/product-images/product-image-011.jpg'
            ],
        ];

        $count = 0;
        foreach ($products as $productData) {
            $image = $productData['image'];
            unset($productData['image']);
            
            $product = Product::create($productData);

            foreach ($this->buildImageUrls($productData, $image) as $imageUrl) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_url' => $imageUrl,
                ]);
            }
            
            $count++;
        }

        echo "Đã thêm {$count} sản phẩm mới!\n";
    }

    private function buildImageUrls(array $productData, ?string $primaryImage = null): array
    {
        $name = Str::of($productData['name'])->lower()->ascii()->toString();
        $brand = Str::of($productData['brand'] ?? 'smartphone')->lower()->ascii()->toString();

        $imageSets = [
            'iphone' => [
                'storage/product-images/product-image-025.jpg',
                'storage/product-images/product-image-002.jpg',
                'storage/product-images/product-image-047.jpg',
            ],
            'samsung' => [
                'storage/product-images/product-image-041.jpg',
                'storage/product-images/product-image-019.jpg',
                'storage/product-images/product-image-044.jpg',
            ],
            'xiaomi' => [
                'storage/product-images/product-image-029.jpg',
                'storage/product-images/product-image-015.jpg',
                'storage/product-images/product-image-004.jpg',
            ],
            'oppo' => [
                'storage/product-images/product-image-027.jpg',
                'storage/product-images/product-image-033.jpg',
                'storage/product-images/product-image-009.jpg',
            ],
            'vivo' => [
                'storage/product-images/product-image-027.jpg',
                'storage/product-images/product-image-033.jpg',
                'storage/product-images/product-image-007.jpg',
            ],
        ];

        $urls = [];
        if ($primaryImage) {
            $urls[] = $primaryImage;
        }

        $selectedImages = [
            'storage/product-images/product-image-033.jpg',
            'storage/product-images/product-image-027.jpg',
            'storage/product-images/product-image-007.jpg',
        ];

        foreach ($imageSets as $needle => $set) {
            if (Str::contains($name, $needle)) {
                $selectedImages = $set;
                break;
            }
        }

        foreach ($selectedImages as $imageUrl) {
            $urls[] = $imageUrl;
        }

        return array_values(array_unique($urls));
    }
}


