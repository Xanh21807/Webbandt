# 🚀 Hướng Dẫn Setup Backend Laravel - Website Mua Bán Điện Thoại

## ✅ Các Bước Cài Đặt

### 1. Kiểm Tra Yêu Cầu Hệ Thống
- ✅ PHP >= 8.2 đã cài đặt
- ✅ Composer đã cài đặt  
- ✅ MySQL/MariaDB đã cài đặt và đang chạy
- ✅ Extension PHP: pdo_mysql, mbstring, openssl, tokenizer, xml, ctype, json

### 2. Tạo Database MySQL

**Cách 1: Sử dụng phpMyAdmin**
- Mở phpMyAdmin
- Tạo database mới tên: `webmuabandt`
- Collation: `utf8mb4_unicode_ci`

**Cách 2: Sử dụng MySQL Command Line**
```bash
mysql -u root -p
CREATE DATABASE webmuabandt CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

**Cách 3: Sử dụng XAMPP/WAMP**
- Khởi động MySQL từ XAMPP/WAMP Control Panel
- Mở phpMyAdmin (http://localhost/phpmyadmin)
- Tạo database `webmuabandt`

### 3. Cài Đặt Dependencies
```bash
cd e:\webcanhan\webmuabandt
composer install
```

### 4. Cấu Hình Environment (.env đã được setup sẵn)
File `.env` đã được cấu hình với:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=webmuabandt
DB_USERNAME=root
DB_PASSWORD=
```

**Lưu ý:** Nếu MySQL của bạn có mật khẩu, cập nhật `DB_PASSWORD=your_password`

### 5. Chạy Migration và Seed Data
```bash
php artisan migrate:fresh --seed
```

Lệnh này sẽ:
- Tạo tất cả các bảng trong database
- Thêm dữ liệu mẫu (admin, user, categories, products)

### 6. Tạo Storage Link
```bash
php artisan storage:link
```

### 7. Khởi Động Server
```bash
php artisan serve
```

Server sẽ chạy tại: **http://localhost:8000**

---

## 🔐 Tài Khoản Test Đã Tạo Sẵn

### Admin Account
- **Email:** admin@example.com
- **Password:** password

### User Account
- **Email:** user@example.com
- **Password:** password

---

## 📊 Kiểm Tra Database

Sau khi chạy migration, bạn sẽ có các bảng:

✅ users - Quản lý người dùng
✅ categories - Danh mục sản phẩm
✅ products - Sản phẩm điện thoại
✅ product_images - Hình ảnh sản phẩm
✅ carts - Giỏ hàng
✅ cart_items - Sản phẩm trong giỏ
✅ orders - Đơn hàng
✅ order_items - Chi tiết đơn hàng
✅ payments - Thanh toán
✅ favorites - Sản phẩm yêu thích
✅ reviews - Đánh giá
✅ password_reset_otps - OTP reset mật khẩu
✅ discount_codes - Mã giảm giá
✅ social_accounts - Tài khoản mạng xã hội

---

## 🧪 Test API

### Sử dụng Postman
1. Import file `postman_collection.json`
2. Test các endpoint:
   - POST /api/login
   - GET /api/products
   - POST /api/cart/items
   - POST /api/checkout

### Sử dụng cURL

**Login User:**
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d "{\"email\":\"user@example.com\",\"password\":\"password\"}"
```

**Get Products:**
```bash
curl -X GET http://localhost:8000/api/products
```

**Admin Login:**
```bash
curl -X POST http://localhost:8000/api/admin/login \
  -H "Content-Type: application/json" \
  -d "{\"email\":\"admin@example.com\",\"password\":\"password\"}"
```

---

## 🔧 Lệnh Hữu Ích

### Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Reset Database
```bash
php artisan migrate:fresh --seed
```

### Tạo Controller Mới
```bash
php artisan make:controller Api/ControllerName
```

### Tạo Model Mới
```bash
php artisan make:model ModelName -m
```

### Xem Routes
```bash
php artisan route:list
```

---

## ❌ Troubleshooting

### Lỗi: "SQLSTATE[HY000] [1045] Access denied"
**Giải pháp:** Kiểm tra username/password MySQL trong file `.env`

### Lỗi: "SQLSTATE[HY000] [1049] Unknown database 'webmuabandt'"
**Giải pháp:** Tạo database `webmuabandt` trong MySQL trước

### Lỗi: "Class 'Laravel\Sanctum\...' not found"
**Giải pháp:** 
```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

### Lỗi: Storage link không hoạt động
**Giải pháp:**
```bash
php artisan storage:link
```

### Port 8000 đã được sử dụng
**Giải pháp:** Dùng port khác
```bash
php artisan serve --port=8080
```

---

## 📚 Tài Liệu Tham Khảo

- [API Documentation](./API_DOCUMENTATION.md) - Chi tiết tất cả API endpoints
- [Laravel Docs](https://laravel.com/docs) - Tài liệu Laravel
- [Sanctum Docs](https://laravel.com/docs/sanctum) - Authentication

---

## 🎯 Next Steps

1. ✅ Setup backend hoàn tất
2. 📱 Phát triển frontend (React/Vue/Flutter)
3. 🔗 Kết nối frontend với API
4. 🚀 Deploy lên server production

---

## 💡 Tips

- Sử dụng Postman để test API trước khi code frontend
- Kiểm tra file `routes/api.php` để xem tất cả routes
- Đọc file `API_DOCUMENTATION.md` để hiểu cách sử dụng từng endpoint
- Kiểm tra folder `app/Models` để hiểu cấu trúc database

---

**Happy Coding! 🚀**
