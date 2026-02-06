# ⚡ Quick Reference - API Endpoints

## 📍 Base URL
```
http://localhost:8000/api
```

## 🔑 Authentication Headers
```
Authorization: Bearer {your_token_here}
Content-Type: application/json
```

---

## 👤 USER APIS

### Auth (Public)
```bash
# Đăng ký
POST /register
Body: { "name", "email", "password", "password_confirmation" }

# Đăng nhập
POST /login
Body: { "email", "password" }
Response: { "user", "access_token" }

# Quên mật khẩu
POST /forgot-password
Body: { "email" }

# Xác nhận OTP
POST /verify-otp
Body: { "email", "otp" }

# Reset mật khẩu
POST /reset-password
Body: { "email", "otp", "password", "password_confirmation" }
```

### Products (Public)
```bash
# Danh sách
GET /products?keyword=iphone&brand=Apple&min_price=10000000&sort_by=price_asc

# Chi tiết
GET /products/{id}
```

### Profile (Protected)
```bash
# Xem profile
GET /profile
Headers: Authorization: Bearer {token}

# Cập nhật
PUT /profile
Body: { "name", "phone", "address" }
```

### Cart (Protected)
```bash
# Xem giỏ
GET /cart

# Thêm sản phẩm
POST /cart/items
Body: { "product_id": 1, "quantity": 2 }

# Cập nhật
PUT /cart/items/{id}
Body: { "quantity": 3 }

# Xóa
DELETE /cart/items/{id}

# Xóa toàn bộ
DELETE /cart
```

### Orders (Protected)
```bash
# Thanh toán
POST /checkout
Body: {
  "receiver_name": "John Doe",
  "receiver_phone": "0123456789",
  "receiver_address": "123 Street",
  "payment_method": "cod"
}

# Danh sách đơn
GET /orders

# Chi tiết đơn
GET /orders/{id}

# Hủy đơn
PUT /orders/{id}/cancel
```

### Favorites (Protected)
```bash
# Danh sách yêu thích
GET /favorites

# Thêm
POST /favorites/{product_id}

# Xóa
DELETE /favorites/{product_id}
```

---

## 👨‍💼 ADMIN APIS

### Admin Auth
```bash
# Đăng nhập admin
POST /admin/login
Body: { "email": "admin@example.com", "password": "password" }

# Đăng xuất
POST /admin/logout
```

### Products Management
```bash
# Danh sách
GET /admin/products?keyword=iphone&status=active

# Chi tiết
GET /admin/products/{id}

# Thêm mới
POST /admin/products
Body: {
  "category_id": 1,
  "name": "iPhone 15",
  "brand": "Apple",
  "price": 25000000,
  "ram": "8GB",
  "storage": "256GB",
  "battery": "4000mAh",
  "description": "...",
  "quantity": 50,
  "status": "active"
}

# Cập nhật
PUT /admin/products/{id}

# Xóa
DELETE /admin/products/{id}
```

### Categories Management
```bash
# Danh sách
GET /admin/categories

# Thêm
POST /admin/categories
Body: { "name": "iPhone", "description": "Apple products" }

# Cập nhật
PUT /admin/categories/{id}

# Xóa
DELETE /admin/categories/{id}
```

### Orders Management
```bash
# Danh sách
GET /admin/orders?status=pending

# Chi tiết
GET /admin/orders/{id}

# Cập nhật trạng thái
PUT /admin/orders/{id}
Body: { "status": "shipping" }

# Hủy đơn
PUT /admin/orders/{id}/cancel
Body: { "reason": "Out of stock" }
```

### Users Management
```bash
# Danh sách
GET /admin/users?role=user&status=active

# Chi tiết
GET /admin/users/{id}

# Cập nhật trạng thái
PUT /admin/users/{id}/status
Body: { "status": "blocked" }

# Cập nhật thông tin
PUT /admin/users/{id}
Body: { "name", "phone", "address", "role" }
```

### Statistics & Reports
```bash
# Dashboard tổng quan
GET /admin/statistics/dashboard?start_date=2024-01-01&end_date=2024-12-31

# Báo cáo doanh thu
GET /admin/statistics/revenue?group_by=month

# Báo cáo sản phẩm
GET /admin/statistics/products?start_date=2024-01-01&end_date=2024-12-31

# Báo cáo danh mục
GET /admin/statistics/categories?start_date=2024-01-01&end_date=2024-12-31
```

---

## 📊 Response Format

### Success Response
```json
{
  "success": true,
  "message": "Operation successful",
  "data": {
    // Response data here
  }
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error message",
  "errors": {
    "field": ["Validation error message"]
  }
}
```

---

## 🔐 Test Accounts

```
Admin:
Email: admin@example.com
Password: password

User:
Email: user@example.com
Password: password
```

---

## 🎯 Common Query Parameters

### Products
```
?keyword=iphone          # Tìm kiếm
?brand=Apple            # Lọc theo hãng
?ram=8GB                # Lọc theo RAM
?storage=256GB          # Lọc theo bộ nhớ
?min_price=10000000     # Giá tối thiểu
?max_price=30000000     # Giá tối đa
?category_id=1          # Lọc theo danh mục
?sort_by=price_asc      # Sắp xếp (price_asc, price_desc, newest)
?per_page=15            # Số item mỗi trang
```

### Orders
```
?status=pending         # Lọc theo trạng thái
?keyword=john           # Tìm theo tên/email/sdt
```

### Statistics
```
?start_date=2024-01-01  # Từ ngày
?end_date=2024-12-31    # Đến ngày
?group_by=day           # Nhóm theo (day/week/month)
```

---

## ⚡ Quick cURL Examples

### Login & Get Token
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com","password":"password"}'
```

### Get Products
```bash
curl http://localhost:8000/api/products
```

### Add to Cart (with token)
```bash
curl -X POST http://localhost:8000/api/cart/items \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{"product_id":1,"quantity":2}'
```

### Admin Dashboard
```bash
curl -X GET http://localhost:8000/api/admin/statistics/dashboard \
  -H "Authorization: Bearer ADMIN_TOKEN_HERE"
```

---

## 📱 HTTP Status Codes

```
200 OK              - Success
201 Created         - Resource created
400 Bad Request     - Invalid request
401 Unauthorized    - Missing/invalid token
403 Forbidden       - No permission
404 Not Found       - Resource not found
409 Conflict        - Duplicate entry
422 Unprocessable   - Validation failed
500 Server Error    - Internal error
```

---

**🚀 Ready to integrate with your frontend!**
