@echo off
echo ====================================
echo Testing Phone Shop API
echo ====================================
echo.

echo [1/3] Testing User Login...
curl -X POST http://localhost:8000/api/login -H "Content-Type: application/json" -d "{\"email\":\"user@example.com\",\"password\":\"password\"}"
echo.
echo.

echo [2/3] Testing Products List...
curl http://localhost:8000/api/products
echo.
echo.

echo [3/3] Testing Admin Login...
curl -X POST http://localhost:8000/api/admin/login -H "Content-Type: application/json" -d "{\"email\":\"admin@example.com\",\"password\":\"password\"}"
echo.
echo.

echo ====================================
echo Tests Complete!
echo ====================================
pause
