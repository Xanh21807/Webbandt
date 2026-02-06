# Test Admin Product CRUD API

$baseUrl = "http://localhost:8000/api"

Write-Host "=== TEST ADMIN PRODUCT CRUD ===" -ForegroundColor Cyan
Write-Host ""

# Step 1: Login as admin
Write-Host "1. Logging in as admin..." -ForegroundColor Yellow
$loginData = @{
    email = "admin@example.com"
    password = "admin123"
} | ConvertTo-Json

try {
    $loginResponse = Invoke-RestMethod -Uri "$baseUrl/admin/login" -Method Post `
        -Body $loginData -ContentType "application/json"
    
    $token = $loginResponse.data.access_token
    Write-Host "✓ Login successful! Token: $($token.Substring(0,20))..." -ForegroundColor Green
    Write-Host ""
} catch {
    Write-Host "✗ Login failed:" -ForegroundColor Red
    Write-Host $_.Exception.Message -ForegroundColor Red
    if ($_.ErrorDetails.Message) {
        Write-Host $_.ErrorDetails.Message -ForegroundColor Red
    }
    exit
}

# Step 2: Get categories
Write-Host "2. Getting categories..." -ForegroundColor Yellow
try {
    $categoriesResponse = Invoke-RestMethod -Uri "$baseUrl/categories" -Method Get `
        -Headers @{
            "Accept" = "application/json"
        }
    
    $categories = $categoriesResponse.data.data
    Write-Host "✓ Found $($categories.Count) categories" -ForegroundColor Green
    
    if ($categories.Count -gt 0) {
        $categoryId = $categories[0].id
        Write-Host "  Using category: $($categories[0].name) (ID: $categoryId)" -ForegroundColor Gray
    } else {
        Write-Host "✗ No categories found!" -ForegroundColor Red
        exit
    }
    Write-Host ""
} catch {
    Write-Host "✗ Failed to get categories:" -ForegroundColor Red
    Write-Host $_.Exception.Message -ForegroundColor Red
    if ($_.ErrorDetails.Message) {
        Write-Host $_.ErrorDetails.Message -ForegroundColor Red
    }
    exit
}

# Step 3: Create a new product
Write-Host "3. Creating a new product..." -ForegroundColor Yellow
$productData = @{
    category_id = $categoryId
    name = "Test iPhone 15 Pro Max"
    brand = "Apple"
    price = 29990000
    ram = "8GB"
    storage = "256GB"
    battery = "4422mAh"
    description = "Sản phẩm test từ PowerShell script"
    quantity = 50
    status = "active"
} | ConvertTo-Json

try {
    $createResponse = Invoke-RestMethod -Uri "$baseUrl/admin/products" -Method Post `
        -Body $productData -ContentType "application/json" `
        -Headers @{
            "Authorization" = "Bearer $token"
            "Accept" = "application/json"
        }
    
    $productId = $createResponse.data.product.id
    Write-Host "✓ Product created successfully! ID: $productId" -ForegroundColor Green
    Write-Host "  Name: $($createResponse.data.product.name)" -ForegroundColor Gray
    Write-Host "  Brand: $($createResponse.data.product.brand)" -ForegroundColor Gray
    Write-Host "  Price: $($createResponse.data.product.price)" -ForegroundColor Gray
    Write-Host ""
} catch {
    $errorDetails = $_.ErrorDetails.Message | ConvertFrom-Json
    Write-Host "✗ Failed to create product:" -ForegroundColor Red
    Write-Host $_.Exception.Message -ForegroundColor Red
    if ($errorDetails.errors) {
        Write-Host "Validation errors:" -ForegroundColor Red
        $errorDetails.errors | ConvertTo-Json
    }
    exit
}

# Step 4: Update the product
Write-Host "4. Updating the product..." -ForegroundColor Yellow
$updateData = @{
    name = "Updated iPhone 15 Pro Max"
    price = 27990000
    quantity = 45
} | ConvertTo-Json

try {
    $updateResponse = Invoke-RestMethod -Uri "$baseUrl/admin/products/$productId" -Method Put `
        -Body $updateData -ContentType "application/json" `
        -Headers @{
            "Authorization" = "Bearer $token"
            "Accept" = "application/json"
        }
    
    Write-Host "✓ Product updated successfully!" -ForegroundColor Green
    Write-Host "  New name: $($updateResponse.data.product.name)" -ForegroundColor Gray
    Write-Host "  New price: $($updateResponse.data.product.price)" -ForegroundColor Gray
    Write-Host ""
} catch {
    Write-Host "✗ Failed to update product: $($_.Exception.Message)" -ForegroundColor Red
}

# Step 5: Get product details
Write-Host "5. Getting product details..." -ForegroundColor Yellow
try {
    $productResponse = Invoke-RestMethod -Uri "$baseUrl/admin/products/$productId" -Method Get `
        -Headers @{
            "Authorization" = "Bearer $token"
            "Accept" = "application/json"
        }
    
    Write-Host "✓ Product retrieved successfully!" -ForegroundColor Green
    $productResponse.data.product | ConvertTo-Json -Depth 3
    Write-Host ""
} catch {
    Write-Host "✗ Failed to get product: $($_.Exception.Message)" -ForegroundColor Red
}

# Step 6: Delete the product
Write-Host "6. Deleting the product..." -ForegroundColor Yellow
try {
    $deleteResponse = Invoke-RestMethod -Uri "$baseUrl/admin/products/$productId" -Method Delete `
        -Headers @{
            "Authorization" = "Bearer $token"
            "Accept" = "application/json"
        }
    
    Write-Host "✓ Product deleted successfully!" -ForegroundColor Green
    Write-Host ""
} catch {
    Write-Host "✗ Failed to delete product: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host "=== TEST COMPLETED ===" -ForegroundColor Cyan
