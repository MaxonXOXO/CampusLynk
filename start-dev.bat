@echo off
echo ===================================================
echo   Starting Carmel Linx / CampusLynk Environment
echo ===================================================

echo [1/3] Checking MySQL (port 3306)...
netstat -ano | findstr :3306 | findstr LISTENING >nul
if %errorlevel% neq 0 (
    echo Starting MySQL daemon...
    start "" /B "C:\xampp\mysql\bin\mysqld.exe" --defaults-file=C:\xampp\mysql\bin\my.ini --standalone
    timeout /t 2 /nobreak >nul
) else (
    echo MySQL is already running.
)

echo [2/3] Checking Apache HTTP & phpMyAdmin (port 80)...
netstat -ano | findstr :80 | findstr LISTENING >nul
if %errorlevel% neq 0 (
    echo Starting Apache daemon...
    start "" /B "C:\xampp\apache\bin\httpd.exe"
    timeout /t 1 /nobreak >nul
) else (
    echo Apache is already running.
)

echo.
echo ===================================================
echo   Services are Active!
echo   - phpMyAdmin:  http://localhost/phpmyadmin/
echo   - Database:    carmel_linx_db (User: root, Password: [blank])
echo   - App URL:     http://localhost:8000
echo ===================================================
echo.

echo [3/3] Starting Laravel Development Server on http://localhost:8000 ...
cd /d "%~dp0carmel-linx-laravel"
"C:\xampp\php\php.exe" artisan serve --host=127.0.0.1 --port=8000
