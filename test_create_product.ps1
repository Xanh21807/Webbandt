# Test tạo sản phẩm đơn giản

# Login first
Write-Host "Logging in..." -ForegroundColor Yellow
$loginData = @{
    email = "admin@example.com"
    password = "admin123"
} | ConvertTo-Json

try {
    $loginResponse = Invoke-RestMethod -Uri "http://localhost:8000/api/admin/login" -Method Post `
        -Body $loginData -ContentType "application/json"

    $token = $loginResponse.data.access_token
    Write-Host "✓ Logged in! Token: $($token.Substring(0,20))..." -ForegroundColor Green
    Write-Host ""
} catch {
    Write-Host "✗ Login failed!" -ForegroundColor Red
    exit
}

$productData = @{
    category_id = 1
    name = "iPhone 15 Pro Max Test"
    brand = "Apple"
    price = 29990000
    ram = "8GB"
    storage = "256GB"
    battery = "4422mAh"
    description = "Test product"
    quantity = 10
    status = "active"
} | ConvertTo-Json

Write-Host "Creating product..." -ForegroundColor Yellow

try {
    $response = Invoke-RestMethod -Uri "http://localhost:8000/api/admin/products" -Method Post `
        -Body $productData -ContentType "application/json" `
        -Headers @{
            "Authorization" = "Bearer $token"
            "Accept" = "application/json"
        }
    
    Write-Host "✓ Product created successfully!" -ForegroundColor Green
    Write-Host "ID: $($response.data.product.id)" -ForegroundColor Gray
    Write-Host "Name: $($response.data.product.name)" -ForegroundColor Gray
    Write-Host "Brand: $($response.data.product.brand)" -ForegroundColor Gray
    Write-Host "Price: $($response.data.product.price)" -ForegroundColor Gray
} catch {
    Write-Host "✗ Failed to create product!" -ForegroundColor Red
    if ($_.ErrorDetails.Message) {
        $error = $_.ErrorDetails.Message | ConvertFrom-Json
        Write-Host "Message: $($error.message)" -ForegroundColor Red
        if ($error.errors) {
            Write-Host "Errors:" -ForegroundColor Yellow
            $error.errors | ConvertTo-Json
        }
    }
}
