@echo off
chcp 65001 > nul
echo ========================================
echo Testing Admin API
echo ========================================
echo.

echo 1. Admin Login...
echo.
powershell -Command "$body = @{email='admin@example.com'; password='password'} | ConvertTo-Json; $response = Invoke-RestMethod -Uri 'http://localhost:8000/api/admin/login' -Method Post -Body $body -ContentType 'application/json'; $response | ConvertTo-Json -Depth 10; $global:token = $response.data.access_token; Write-Host ''; Write-Host 'Token saved!' -ForegroundColor Green"
echo.

echo ========================================
echo Press any key to test Admin Products API...
pause > nul
echo.

echo 2. Get Admin Products...
powershell -Command "$response = Invoke-RestMethod -Uri 'http://localhost:8000/api/admin/products' -Method Get -Headers @{Authorization='Bearer ' + $global:token; Accept='application/json'}; $response | ConvertTo-Json -Depth 10"
echo.

pause
