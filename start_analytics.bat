@echo off
echo 🚀 Starting ZACL Analytics System...
echo.

echo 📊 Checking analytics status...
php check_analytics.php
echo.

echo 🌐 Starting development server...
echo 📍 User Dashboard: http://localhost:8000/dashboard
echo 📍 Admin Dashboard: http://localhost:8000/admin/dashboard
echo.
echo 💡 Login with demo users (password: password) or your admin credentials
echo.

php artisan serve --host=0.0.0.0 --port=8000
