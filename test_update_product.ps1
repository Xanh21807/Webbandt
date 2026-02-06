# Test Update Product
Write-Host "Testing Update Product API" -ForegroundColor Cyan
Write-Host ""

# 1. Login
Write-Host "1. Admin Login..." -ForegroundColor Yellow
$loginBody = @{
    email = "admin@example.com"
    password = "password"
} | ConvertTo-Json

$loginResponse = Invoke-RestMethod -Uri "http://localhost:8000/api/admin/login" `
    -Method Post `
    -Body $loginBody `
    -ContentType "application/json"

$token = $loginResponse.data.access_token
Write-Host "Login Success! Token: $token" -ForegroundColor Green
Write-Host ""

# 2. Update Product
Write-Host "2. Update Product ID=1..." -ForegroundColor Yellow
$updateBody = @{
    name = "iPhone 15 Pro Max - Updated"
    price = 27990000
    quantity = 45
} | ConvertTo-Json

$headers = @{
    "Authorization" = "Bearer $token"
    "Accept" = "application/json"
}

try {
    $response = Invoke-RestMethod -Uri "http://localhost:8000/api/admin/products/1" `
        -Method Put `
        -Body $updateBody `
        -ContentType "application/json" `
        -Headers $headers
    
    Write-Host "Success!" -ForegroundColor Green
    $response | ConvertTo-Json -Depth 5
} catch {
    Write-Host "Error: $($_.Exception.Message)" -ForegroundColor Red
    if ($_.ErrorDetails.Message) {
        Write-Host "Details: $($_.ErrorDetails.Message)" -ForegroundColor Red
    }
}
