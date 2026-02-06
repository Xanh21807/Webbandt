# 📱 Website Mua Bán Điện Thoại - Backend API Complete

## 🎉 Tổng Quan Dự Án

Backend API hoàn chỉnh cho hệ thống mua bán điện thoại được xây dựng bằng **Laravel 11** với đầy đủ tính năng cho cả **User** và **Admin**.

---

## ✨ Tính Năng Đã Triển Khai

### 👤 User Features
- [x] **Đăng ký tài khoản** - Tạo tài khoản mới với email/password
- [x] **Đăng nhập** - Xác thực người dùng với JWT token (Laravel Sanctum)
- [x] **Quên mật khẩu** - Gửi OTP qua email để reset password
- [x] **Xác nhận OTP** - Verify mã OTP để đặt lại mật khẩu
- [x] **Quản lý thông tin cá nhân** - Cập nhật name, phone, address
- [x] **Xem danh sách sản phẩm** - Hiển thị tất cả điện thoại
- [x] **Tìm kiếm sản phẩm** - Search theo keyword (tên, brand, mô tả)
- [x] **Lọc sản phẩm** - Filter theo brand, RAM, storage, giá, category
- [x] **Sắp xếp sản phẩm** - Sort theo giá tăng/giảm, mới nhất
- [x] **Xem chi tiết sản phẩm** - Thông tin đầy đủ + hình ảnh + đánh giá
- [x] **Thêm vào giỏ hàng** - Add/Update/Delete items trong cart
- [x] **Thanh toán** - Checkout với nhiều phương thức (COD, Banking, Wallet)
- [x] **Xem đơn hàng** - Danh sách và chi tiết orders
- [x] **Hủy đơn hàng** - Cancel order (với điều kiện)
- [x] **Sản phẩm yêu thích** - Add/Remove/View favorites

### 🔐 Admin Features
- [x] **Đăng nhập Admin** - Riêng biệt với user login
- [x] **Quản lý sản phẩm** - CRUD products với upload ảnh
- [x] **Quản lý danh mục** - CRUD categories
- [x] **Quản lý đơn hàng** - Xem, cập nhật trạng thái, hủy orders
- [x] **Quản lý người dùng** - Xem, khóa/mở khóa tài khoản
- [x] **Dashboard thống kê** - Tổng quan doanh thu, đơn hàng, user
- [x] **Báo cáo doanh thu** - Theo ngày/tuần/tháng
- [x] **Báo cáo sản phẩm** - Sản phẩm bán chạy, tồn kho
- [x] **Báo cáo danh mục** - Thống kê theo category

---

## 📂 Cấu Trúc Project

```
webmuabandt/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/
│   │   │   │   ├── AuthController.php          # User authentication
│   │   │   │   ├── ProductController.php       # Product & Favorites
│   │   │   │   ├── CartController.php          # Shopping cart
│   │   │   │   └── OrderController.php         # Checkout & Orders
│   │   │   └── Admin/
│   │   │       ├── AuthController.php          # Admin login
│   │   │       ├── ProductController.php       # Product management
│   │   │       ├── OrderController.php         # Order management
│   │   │       ├── UserController.php          # User management
│   │   │       ├── CategoryController.php      # Category management
│   │   │       └── StatisticsController.php    # Reports & Statistics
│   │   └── Middleware/
│   │       └── AdminMiddleware.php             # Admin authorization
│   └── Models/
│       ├── User.php                            # User model
│       ├── Product.php                         # Product model
│       ├── Category.php                        # Category model
│       ├── Cart.php & CartItem.php             # Cart models
│       ├── Order.php & OrderItem.php           # Order models
│       ├── Payment.php                         # Payment model
│       ├── Favorite.php                        # Favorite model
│       ├── Review.php                          # Review model
│       ├── ProductImage.php                    # Product images
│       ├── PasswordResetOtp.php                # OTP model
│       ├── SocialAccount.php                   # Social login
│       └── DiscountCode.php                    # Discount codes
├── database/
│   ├── migrations/                             # 14 migration files
│   └── seeders/
│       └── DatabaseSeeder.php                  # Sample data
├── routes/
│   ├── api.php                                 # All API routes
│   └── web.php                                 # Web routes
├── .env                                        # Environment config
├── API_DOCUMENTATION.md                        # Complete API docs
├── SETUP_GUIDE.md                              # Setup instructions
├── postman_collection.json                     # Postman test collection
└── README.md                                   # Project overview
```

---

## 🗄️ Database Schema (14 Tables)

### Core Tables
1. **users** - Người dùng (role: user/admin, status: active/blocked)
2. **categories** - Danh mục sản phẩm
3. **products** - Sản phẩm điện thoại (with specs: RAM, storage, battery, etc.)
4. **product_images** - Hình ảnh sản phẩm (multiple images per product)

### Shopping Flow
5. **carts** - Giỏ hàng của user
6. **cart_items** - Sản phẩm trong giỏ (product_id, quantity)
7. **orders** - Đơn hàng (5 trạng thái: pending/paid/shipping/completed/cancelled)
8. **order_items** - Chi tiết đơn hàng
9. **payments** - Thanh toán (method: COD/banking/wallet)

### Features
10. **favorites** - Sản phẩm yêu thích
11. **reviews** - Đánh giá sản phẩm (rating + comment)
12. **discount_codes** - Mã giảm giá

### Authentication
13. **password_reset_otps** - OTP để reset password
14. **social_accounts** - Login Google/Facebook (prepared for future)

---

## 🔌 API Endpoints Summary

### Public APIs (No Auth Required)
```
POST   /api/register              # Đăng ký
POST   /api/login                 # Đăng nhập
POST   /api/forgot-password       # Quên mật khẩu
POST   /api/verify-otp            # Xác nhận OTP
POST   /api/reset-password        # Reset password
GET    /api/products              # Danh sách sản phẩm
GET    /api/products/{id}         # Chi tiết sản phẩm
POST   /api/admin/login           # Admin đăng nhập
```

### Protected User APIs (Require User Token)
```
POST   /api/logout                # Đăng xuất
GET    /api/profile               # Thông tin cá nhân
PUT    /api/profile               # Cập nhật profile
GET    /api/cart                  # Xem giỏ hàng
POST   /api/cart/items            # Thêm vào giỏ
PUT    /api/cart/items/{id}       # Cập nhật số lượng
DELETE /api/cart/items/{id}       # Xóa khỏi giỏ
POST   /api/checkout              # Thanh toán
GET    /api/orders                # Danh sách đơn hàng
GET    /api/orders/{id}           # Chi tiết đơn
PUT    /api/orders/{id}/cancel    # Hủy đơn
GET    /api/favorites             # Danh sách yêu thích
POST   /api/favorites/{id}        # Thêm yêu thích
DELETE /api/favorites/{id}        # Xóa yêu thích
```

### Protected Admin APIs (Require Admin Token)
```
# Products
GET    /api/admin/products
POST   /api/admin/products
PUT    /api/admin/products/{id}
DELETE /api/admin/products/{id}

# Orders
GET    /api/admin/orders
PUT    /api/admin/orders/{id}
PUT    /api/admin/orders/{id}/cancel

# Users
GET    /api/admin/users
GET    /api/admin/users/{id}
PUT    /api/admin/users/{id}/status

# Categories
GET    /api/admin/categories
POST   /api/admin/categories
PUT    /api/admin/categories/{id}
DELETE /api/admin/categories/{id}

# Statistics
GET    /api/admin/statistics/dashboard
GET    /api/admin/statistics/revenue
GET    /api/admin/statistics/products
GET    /api/admin/statistics/categories
```

---

## 🔒 Security Features

✅ **Laravel Sanctum** - API token authentication
✅ **Password Hashing** - Bcrypt hashing
✅ **CSRF Protection** - Built-in Laravel protection
✅ **Admin Middleware** - Restrict admin-only routes
✅ **Input Validation** - Comprehensive validation rules
✅ **SQL Injection Protection** - Eloquent ORM
✅ **XSS Protection** - Laravel auto-escaping

---

## 📊 Sample Data (từ Seeder)

### Admin Account
- Email: admin@example.com
- Password: password

### Test User
- Email: user@example.com
- Password: password

### Categories (5)
- iPhone, Samsung, Xiaomi, Oppo, Vivo

### Products (3 sample products)
- iPhone 15 Pro Max - 29,990,000đ
- Samsung Galaxy S24 Ultra - 33,990,000đ
- Xiaomi 14 Pro - 19,990,000đ

---

## 🚀 Quick Start

```bash
# 1. Tạo database
CREATE DATABASE webmuabandt;

# 2. Cài đặt dependencies
composer install

# 3. Setup .env (đã config sẵn MySQL)
# DB_DATABASE=webmuabandt

# 4. Chạy migration + seed
php artisan migrate:fresh --seed

# 5. Tạo storage link
php artisan storage:link

# 6. Khởi động server
php artisan serve

# Server running at: http://localhost:8000
```

---

## 🧪 Testing APIs

### Method 1: Postman
1. Import `postman_collection.json`
2. Test endpoints với sample data

### Method 2: cURL
```bash
# Login
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com","password":"password"}'

# Get products
curl http://localhost:8000/api/products
```

---

## 📖 Documentation Files

1. **SETUP_GUIDE.md** - Hướng dẫn cài đặt chi tiết
2. **API_DOCUMENTATION.md** - Tài liệu API đầy đủ
3. **postman_collection.json** - Postman test collection
4. **README.md** - Project overview
5. **PROJECT_SUMMARY.md** - Tài liệu này

---

## ✅ Checklist Hoàn Thành

### Database ✅
- [x] 14 migrations created
- [x] All models with relationships
- [x] Database seeder with sample data

### Authentication ✅
- [x] User registration
- [x] User login/logout
- [x] Password reset with OTP
- [x] Admin login separate
- [x] Laravel Sanctum integrated

### User Features ✅
- [x] Product listing with search/filter/sort
- [x] Product detail with reviews
- [x] Shopping cart (CRUD)
- [x] Checkout & payment
- [x] Order management
- [x] Favorites
- [x] Profile management

### Admin Features ✅
- [x] Product management (CRUD)
- [x] Category management (CRUD)
- [x] Order management
- [x] User management
- [x] Dashboard statistics
- [x] Revenue reports
- [x] Product reports
- [x] Category reports

### API Routes ✅
- [x] Public routes configured
- [x] Protected user routes
- [x] Protected admin routes
- [x] Middleware configured

### Documentation ✅
- [x] Setup guide
- [x] API documentation
- [x] Postman collection
- [x] Code comments
- [x] README files

---

## 🎯 Next Steps (Frontend Integration)

1. **Chọn Frontend Framework**
   - React.js / Vue.js / Angular (Web)
   - React Native / Flutter (Mobile)

2. **Kết Nối API**
   - Sử dụng axios/fetch
   - Setup base URL: `http://localhost:8000/api`
   - Quản lý token với localStorage/AsyncStorage

3. **Implement Features**
   - Login/Register screens
   - Product listing & detail
   - Shopping cart
   - Checkout flow
   - Order tracking
   - Admin dashboard

4. **Testing**
   - Unit tests
   - Integration tests
   - E2E tests

5. **Deployment**
   - Backend: VPS/Cloud (DigitalOcean, AWS, etc.)
   - Frontend: Vercel, Netlify
   - Database: MySQL production server

---

## 💻 Tech Stack

- **Framework:** Laravel 11
- **Language:** PHP 8.2+
- **Database:** MySQL 8.0
- **Authentication:** Laravel Sanctum
- **ORM:** Eloquent
- **Validation:** Laravel Form Requests
- **File Storage:** Laravel Storage (public disk)

---

## 📞 Support & Resources

- Laravel Docs: https://laravel.com/docs
- Sanctum Docs: https://laravel.com/docs/sanctum
- MySQL Docs: https://dev.mysql.com/doc/

---

## 🏆 Project Completion Status

**Backend Development:** ✅ **100% COMPLETE**

- ✅ Database design & migrations
- ✅ Models & relationships
- ✅ Controllers & business logic
- ✅ Authentication & authorization
- ✅ API routes & middleware
- ✅ Input validation
- ✅ Error handling
- ✅ Documentation
- ✅ Sample data seeder
- ✅ Postman collection

**Ready for Frontend Integration! 🚀**

---

**Created with ❤️ using Laravel 11**
