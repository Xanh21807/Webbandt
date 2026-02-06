# Simple login test
$loginData = @{
    email = "admin@example.com"
    password = "admin123"
} | ConvertTo-Json

Write-Host "Testing login..." -ForegroundColor Yellow
Write-Host "URL: http://localhost:8000/api/admin/login" -ForegroundColor Gray
Write-Host "Data: $loginData" -ForegroundColor Gray
Write-Host ""

try {
    $response = Invoke-WebRequest -Uri "http://localhost:8000/api/admin/login" -Method Post `
        -Body $loginData -ContentType "application/json"
    
    Write-Host "Status: $($response.StatusCode)" -ForegroundColor Green
    Write-Host "Response:" -ForegroundColor Green
    $response.Content | ConvertFrom-Json | ConvertTo-Json -Depth 5
} catch {
    Write-Host "Error:" -ForegroundColor Red
    Write-Host "Status: $($_.Exception.Response.StatusCode.value__)" -ForegroundColor Red
    Write-Host "Message: $($_.Exception.Message)" -ForegroundColor Red
    if ($_.ErrorDetails.Message) {
        Write-Host "Details:" -ForegroundColor Red
        $_.ErrorDetails.Message
    }
}
