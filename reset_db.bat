@echo off
chcp 65001 > nul
echo ========================================
echo RESET DATABASE WITH SAMPLE ORDERS
echo ========================================
echo.
echo Dropping all tables and recreating...
php artisan migrate:fresh --seed
echo.
echo ========================================
echo Done! Database ready with:
echo - 2 users (admin + test user)
echo - 5 categories
echo - 3 products  
echo - 2 sample orders
echo ========================================
pause
