# 📱 Website Mua Bán Điện Thoại - Laravel Backend

Backend API cho hệ thống mua bán điện thoại, được xây dựng bằng Laravel 11 và MySQL.

## 🚀 Tính Năng

### User Features
- ✅ Đăng ký / Đăng nhập
- ✅ Quên mật khẩu (OTP)
- ✅ Xem danh sách sản phẩm
- ✅ Tìm kiếm & lọc sản phẩm
- ✅ Xem chi tiết sản phẩm
- ✅ Thêm vào giỏ hàng
- ✅ Thanh toán
- ✅ Theo dõi đơn hàng
- ✅ Quản lý thông tin cá nhân
- ✅ Sản phẩm yêu thích

### Admin Features
- ✅ Quản lý sản phẩm (CRUD)
- ✅ Quản lý đơn hàng
- ✅ Quản lý người dùng
- ✅ Quản lý danh mục
- ✅ Thống kê & báo cáo

## 📋 Yêu Cầu Hệ Thống

- PHP >= 8.2
- Composer
- MySQL >= 8.0
- Laravel 11

## ⚙️ Cài Đặt

### 1. Clone Repository
```bash
cd e:\webcanhan\webmuabandt
```

### 2. Cài Đặt Dependencies
```bash
composer install
```

### 3. Cấu Hình Environment
```bash
cp .env.example .env
php artisan key:generate
```

Cập nhật thông tin database trong file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=webmuabandt
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Chạy Migration & Seeder
```bash
php artisan migrate
php artisan db:seed
```

### 5. Tạo Symbolic Link cho Storage
```bash
php artisan storage:link
```

### 6. Khởi Động Server
```bash
php artisan serve
```

API sẽ chạy tại: `http://localhost:8000`

## 📚 API Documentation

### Authentication

#### Đăng Ký
```http
POST /api/register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

#### Đăng Nhập
```http
POST /api/login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "password123"
}
```

#### Quên Mật Khẩu
```http
POST /api/forgot-password
Content-Type: application/json

{
  "email": "john@example.com"
}
```

#### Xác Nhận OTP
```http
POST /api/verify-otp
Content-Type: application/json

{
  "email": "john@example.com",
  "otp": "123456"
}
```

#### Reset Mật Khẩu
```http
POST /api/reset-password
Content-Type: application/json

{
  "email": "john@example.com",
  "otp": "123456",
  "password": "newpassword123",
  "password_confirmation": "newpassword123"
}
```

### Products

#### Xem Danh Sách Sản Phẩm
```http
GET /api/products?keyword=iphone&brand=Apple&min_price=10000000&max_price=30000000&sort_by=price_asc
```

#### Xem Chi Tiết Sản Phẩm
```http
GET /api/products/{id}
```

### Cart (Yêu cầu Authentication)

#### Xem Giỏ Hàng
```http
GET /api/cart
Authorization: Bearer {token}
```

#### Thêm Vào Giỏ
```http
POST /api/cart/items
Authorization: Bearer {token}
Content-Type: application/json

{
  "product_id": 1,
  "quantity": 2
}
```

#### Cập Nhật Giỏ Hàng
```http
PUT /api/cart/items/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "quantity": 3
}
```

#### Xóa Khỏi Giỏ
```http
DELETE /api/cart/items/{id}
Authorization: Bearer {token}
```

### Orders (Yêu cầu Authentication)

#### Thanh Toán
```http
POST /api/checkout
Authorization: Bearer {token}
Content-Type: application/json

{
  "receiver_name": "John Doe",
  "receiver_phone": "0123456789",
  "receiver_address": "123 Street, City",
  "payment_method": "cod"
}
```

#### Xem Đơn Hàng
```http
GET /api/orders
Authorization: Bearer {token}
```

#### Chi Tiết Đơn Hàng
```http
GET /api/orders/{id}
Authorization: Bearer {token}
```

### Favorites (Yêu cầu Authentication)

#### Thêm Yêu Thích
```http
POST /api/favorites/{product_id}
Authorization: Bearer {token}
```

#### Xóa Yêu Thích
```http
DELETE /api/favorites/{product_id}
Authorization: Bearer {token}
```

#### Xem Danh Sách Yêu Thích
```http
GET /api/favorites
Authorization: Bearer {token}
```

### Admin APIs (Yêu cầu Admin Authentication)

#### Admin Login
```http
POST /api/admin/login
Content-Type: application/json

{
  "email": "admin@example.com",
  "password": "password"
}
```

#### Quản Lý Sản Phẩm
```http
GET /api/admin/products
POST /api/admin/products
PUT /api/admin/products/{id}
DELETE /api/admin/products/{id}
Authorization: Bearer {admin_token}
```

#### Quản Lý Đơn Hàng
```http
GET /api/admin/orders
GET /api/admin/orders/{id}
PUT /api/admin/orders/{id}
Authorization: Bearer {admin_token}
```

#### Quản Lý Người Dùng
```http
GET /api/admin/users
GET /api/admin/users/{id}
PUT /api/admin/users/{id}/status
Authorization: Bearer {admin_token}
```

#### Thống Kê
```http
GET /api/admin/statistics/dashboard?start_date=2024-01-01&end_date=2024-12-31
GET /api/admin/statistics/revenue?group_by=month
GET /api/admin/statistics/products
GET /api/admin/statistics/categories
Authorization: Bearer {admin_token}
```

## 🗄️ Database Schema

### Tables
- `users` - Thông tin người dùng
- `categories` - Danh mục sản phẩm
- `products` - Sản phẩm
- `product_images` - Hình ảnh sản phẩm
- `carts` - Giỏ hàng
- `cart_items` - Items trong giỏ
- `orders` - Đơn hàng
- `order_items` - Items trong đơn
- `payments` - Thanh toán
- `favorites` - Sản phẩm yêu thích
- `reviews` - Đánh giá
- `password_reset_otps` - OTP reset mật khẩu
- `social_accounts` - Tài khoản mạng xã hội
- `discount_codes` - Mã giảm giá

## 🔐 Tài Khoản Test

### Admin
- Email: `admin@example.com`
- Password: `password`

### User
- Email: `user@example.com`
- Password: `password`

## 🛠️ Công Nghệ Sử Dụng

- **Framework**: Laravel 11
- **Authentication**: Laravel Sanctum
- **Database**: MySQL 8
- **ORM**: Eloquent
- **Validation**: Form Requests

## 📝 Lưu Ý

1. Đảm bảo đã tạo database `webmuabandt` trong MySQL
2. Cấu hình đúng thông tin database trong file `.env`
3. Chạy migration trước khi sử dụng
4. Sử dụng Postman hoặc công cụ tương tự để test API
5. Tất cả API routes đều có prefix `/api`

## 🚀 Deployment

### Production Setup
```bash
# Set environment to production
APP_ENV=production
APP_DEBUG=false

# Run optimizations
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

## 📞 Liên Hệ

Nếu có vấn đề hoặc câu hỏi, vui lòng tạo issue trên GitHub.

---

**Developed with ❤️ using Laravel**
