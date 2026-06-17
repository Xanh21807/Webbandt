# 📱 Website Mua Bán Điện Thoại - Laravel Backend API

[![Laravel](https://img.shields.io/badge/Laravel-11-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-orange.svg)](https://mysql.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

> Backend API hoàn chỉnh cho hệ thống mua bán điện thoại, xây dựng bằng Laravel 11 với đầy đủ tính năng User & Admin.

---

## 🚀 Tính Năng Chính

### 👤 **User Features**
- ✅ Đăng ký / Đăng nhập với JWT token
- ✅ Quên mật khẩu & Reset bằng OTP
- ✅ Xem, tìm kiếm & lọc sản phẩm điện thoại
- ✅ Xem chi tiết sản phẩm với reviews
- ✅ Quản lý giỏ hàng
- ✅ Thanh toán đơn hàng (COD, Banking, Wallet)
- ✅ Theo dõi trạng thái đơn hàng
- ✅ Quản lý sản phẩm yêu thích
- ✅ Quản lý thông tin cá nhân

### 👨‍💼 **Admin Features**
- ✅ Quản lý sản phẩm (CRUD với upload ảnh)
- ✅ Quản lý danh mục sản phẩm
- ✅ Quản lý đơn hàng & cập nhật trạng thái
- ✅ Quản lý người dùng (khóa/mở khóa tài khoản)
- ✅ Dashboard thống kê tổng quan
- ✅ Báo cáo doanh thu theo thời gian
- ✅ Báo cáo sản phẩm bán chạy
- ✅ Báo cáo theo danh mục

---

## 📋 Yêu Cầu Hệ Thống

- PHP >= 8.2
- Composer
- MySQL >= 8.0
- Laravel 11
- Extension: pdo_mysql, mbstring, openssl, tokenizer, xml, ctype, json

---

## ⚙️ Cài Đặt Nhanh

### 1. Clone & Install Dependencies
```bash
cd e:\webcanhan\webmuabandt
composer install
```

### 2. Setup Environment
```bash
# File .env đã được cấu hình sẵn với MySQL
# Chỉ cần tạo database: webmuabandt
```

### 2.1 Bật chatbot LLM thật
Để chatbot trả lời linh hoạt bằng OpenAI hoặc một endpoint tương thích OpenAI, thêm các biến sau vào `.env`:

```bash
CHATBOT_PROVIDER=openai
CHATBOT_API_KEY=your_api_key_here
CHATBOT_BASE_URL=https://api.openai.com/v1
CHATBOT_MODEL=gpt-4o-mini
CHATBOT_TIMEOUT=20
CHATBOT_HISTORY_LIMIT=8
```

Nếu chưa có `CHATBOT_API_KEY`, bot sẽ tự động dùng câu trả lời dự phòng nội bộ.

### 3. Tạo Database
Tạo database MySQL tên: **webmuabandt**

### 4. Run Migration & Seed
```bash
php artisan migrate:fresh --seed
```

### 5. Create Storage Link
```bash
php artisan storage:link
```

### 6. Start Server
```bash
php artisan serve
```

**Server chạy tại:** `http://localhost:8000`

---

## 🔐 Tài Khoản Test

### Admin
```
Email: admin@example.com
Password: password
```

### User
```
Email: user@example.com
Password: password
```

---

## 📚 Documentation

| Tài liệu | Mô tả |
|----------|-------|
| [SETUP_GUIDE.md](SETUP_GUIDE.md) | Hướng dẫn cài đặt chi tiết |
| [API_DOCUMENTATION.md](API_DOCUMENTATION.md) | Tài liệu API đầy đủ |
| [QUICK_REFERENCE.md](QUICK_REFERENCE.md) | Tham khảo nhanh API endpoints |
| [PROJECT_SUMMARY.md](PROJECT_SUMMARY.md) | Tổng quan dự án |
| [postman_collection.json](postman_collection.json) | Postman test collection |

---

## 🗄️ Database Schema

**14 Tables:**
- users, categories, products, product_images
- carts, cart_items, orders, order_items, payments
- favorites, reviews, discount_codes
- password_reset_otps, social_accounts

---

## 🔌 API Endpoints Summary

**46 API Routes:**
- 8 Public routes (register, login, products, etc.)
- 14 Protected user routes (cart, orders, favorites, etc.)
- 24 Protected admin routes (management & statistics)

📖 Xem chi tiết: [QUICK_REFERENCE.md](QUICK_REFERENCE.md)

---

## 🧪 Test API

### Sử dụng Postman
```bash
# Import file postman_collection.json vào Postman
# Test các endpoint với sample data có sẵn
```

### Sử dụng cURL
```bash
# Login
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com","password":"password"}'

# Get Products
curl http://localhost:8000/api/products
```

---

## 🛠️ Tech Stack

- **Framework:** Laravel 11
- **Language:** PHP 8.2+
- **Database:** MySQL 8.0
- **Authentication:** Laravel Sanctum (API Tokens)
- **ORM:** Eloquent
- **File Storage:** Laravel Storage

---

## 📁 Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Api/              # User Controllers
│   │   └── Admin/            # Admin Controllers
│   └── Middleware/
│       └── AdminMiddleware.php
├── Models/                    # 13 Eloquent Models
database/
├── migrations/                # 14 Migration Files
└── seeders/
    └── DatabaseSeeder.php     # Sample Data
routes/
├── api.php                    # All API Routes
└── web.php
```

---

## 🚀 Deployment

### Production Setup
```bash
# Set environment
APP_ENV=production
APP_DEBUG=false

# Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

---

## 📞 Support

Nếu gặp vấn đề:
1. Đọc [SETUP_GUIDE.md](SETUP_GUIDE.md)
2. Kiểm tra [API_DOCUMENTATION.md](API_DOCUMENTATION.md)
3. Xem [Troubleshooting section](SETUP_GUIDE.md#-troubleshooting)

---

## 📄 License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

## 🎯 Next Steps

1. ✅ Backend API hoàn thành
2. 📱 Phát triển Frontend (React/Vue/Flutter)
3. 🔗 Tích hợp API với Frontend
4. 🚀 Deploy lên Production

---

**Developed with ❤️ using Laravel 11**

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
