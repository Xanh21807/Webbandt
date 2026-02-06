# Test Admin API
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Testing Admin API" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# 1. Admin Login
Write-Host "1. Admin Login..." -ForegroundColor Yellow
$loginBody = @{
    email = "admin@example.com"
    password = "password"
} | ConvertTo-Json

try {
    $loginResponse = Invoke-RestMethod -Uri "http://localhost:8000/api/admin/login" `
        -Method Post `
        -Body $loginBody `
        -ContentType "application/json"
    
    Write-Host "Login Success!" -ForegroundColor Green
    Write-Host ""
    Write-Host "User: $($loginResponse.data.user.name) ($($loginResponse.data.user.role))" -ForegroundColor White
    
    $token = $loginResponse.data.access_token
    Write-Host "Token: $token" -ForegroundColor Cyan
    Write-Host ""
    
    # 2. Get Admin Products
    Write-Host "2. Get Admin Products..." -ForegroundColor Yellow
    $headers = @{
        "Authorization" = "Bearer $token"
        "Accept" = "application/json"
    }
    
    $productsResponse = Invoke-RestMethod -Uri "http://localhost:8000/api/admin/products" `
        -Method Get `
        -Headers $headers
    
    Write-Host "Products Retrieved! Total: $($productsResponse.data.total)" -ForegroundColor Green
    Write-Host ""
    
    # 3. Get Dashboard Statistics
    Write-Host "3. Get Dashboard Statistics..." -ForegroundColor Yellow
    $statsResponse = Invoke-RestMethod -Uri "http://localhost:8000/api/admin/statistics/dashboard" `
        -Method Get `
        -Headers $headers
    
    Write-Host "Statistics Retrieved!" -ForegroundColor Green
    Write-Host "Total Revenue: $($statsResponse.data.total_revenue)" -ForegroundColor White
    Write-Host "Total Orders: $($statsResponse.data.total_orders)" -ForegroundColor White
    Write-Host "Total Users: $($statsResponse.data.total_users)" -ForegroundColor White
    Write-Host ""
    
    # 4. Get All Categories
    Write-Host "4. Get All Categories..." -ForegroundColor Yellow
    $categoriesResponse = Invoke-RestMethod -Uri "http://localhost:8000/api/admin/categories" `
        -Method Get `
        -Headers $headers
    
    Write-Host "Categories Retrieved! Total: $($categoriesResponse.data.Count)" -ForegroundColor Green
    Write-Host ""
    
    Write-Host "========================================" -ForegroundColor Green
    Write-Host "All Admin API Tests Passed!" -ForegroundColor Green
    Write-Host "========================================" -ForegroundColor Green
    
} catch {
    Write-Host "Error: $($_.Exception.Message)" -ForegroundColor Red
    if ($_.ErrorDetails.Message) {
        Write-Host "Details: $($_.ErrorDetails.Message)" -ForegroundColor Red
    }
}
