@echo off
echo Preparing Westmount files for upload...
echo.

echo Creating upload directory...
if exist "upload" rmdir /s /q "upload"
mkdir "upload"

echo Copying application files...
xcopy "app" "upload\app" /E /I /Y
xcopy "bootstrap" "upload\bootstrap" /E /I /Y
xcopy "config" "upload\config" /E /I /Y
xcopy "database" "upload\database" /E /I /Y
xcopy "public" "upload\public" /E /I /Y
xcopy "resources" "upload\resources" /E /I /Y
xcopy "routes" "upload\routes" /E /I /Y
xcopy "storage" "upload\storage" /E /I /Y
xcopy "vendor" "upload\vendor" /E /I /Y

echo Copying configuration files...
copy "artisan" "upload\artisan" /Y
copy "composer.json" "upload\composer.json" /Y
copy "composer.lock" "upload\composer.lock" /Y
copy "package.json" "upload\package.json" /Y
copy "package-lock.json" "upload\package-lock.json" /Y
copy "tailwind.config.js" "upload\tailwind.config.js" /Y
copy "vite.config.js" "upload\vite.config.js" /Y

echo Cleaning up cache files...
if exist "upload\storage\framework\cache" rmdir /s /q "upload\storage\framework\cache"
if exist "upload\storage\framework\sessions" rmdir /s /q "upload\storage\framework\sessions"
if exist "upload\storage\framework\views" rmdir /s /q "upload\storage\framework\views"
if exist "upload\storage\logs" rmdir /s /q "upload\storage\logs"

echo Creating upload folder structure...
mkdir "upload\storage\framework\cache"
mkdir "upload\storage\framework\sessions"
mkdir "upload\storage\framework\views"
mkdir "upload\storage\logs"

echo.
echo ✅ Upload preparation complete!
echo.
echo 📁 Files ready in: upload\ folder
echo 📋 Follow the deployment_instructions.md file
echo.
echo 🚀 Next steps:
echo 1. Go to Hostinger File Manager
echo 2. Upload all files from the 'upload' folder
echo 3. Set proper permissions
echo 4. Run database migrations
echo.
pause


