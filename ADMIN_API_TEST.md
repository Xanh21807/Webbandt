=== HƯỚNG DẪN TEST ADMIN API ===

Bước 1: Mở terminal PowerShell mới và khởi động server
-------------------------------------------------------
cd e:\webcanhan\webmuabandt
php artisan serve

Bước 2: Mở terminal PowerShell khác và chạy test
-------------------------------------------------------
cd e:\webcanhan\webmuabandt
.\test_admin.ps1

HOẶC test bằng Postman:
-------------------------------------------------------
1. Mở Postman
2. Import file: postman_collection.json
3. Gọi endpoint "Admin Login" trong folder "Admin - Auth"
4. Token sẽ tự động lưu vào biến {{admin_token}}
5. Test các endpoint admin khác (Products, Categories, Orders, Statistics)

Test Accounts:
-------------------------------------------------------
Admin: admin@example.com / password
User: user@example.com / password

Nếu lỗi đã được sửa, chạy lệnh sau để test nhanh:
-------------------------------------------------------
$body = @{email='admin@example.com'; password='password'} | ConvertTo-Json
$login = Invoke-RestMethod -Uri 'http://localhost:8000/api/admin/login' -Method Post -Body $body -ContentType 'application/json'
$token = $login.data.access_token
$headers = @{Authorization="Bearer $token"; Accept="application/json"}

# Test Statistics
Invoke-RestMethod -Uri 'http://localhost:8000/api/admin/statistics/dashboard' -Headers $headers | ConvertTo-Json -Depth 10
