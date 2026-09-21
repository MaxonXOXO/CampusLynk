@echo off
echo Stopping Apache and MySQL services...
taskkill /F /IM httpd.exe 2>nul
taskkill /F /IM mysqld.exe 2>nul
echo Services stopped.
pause
