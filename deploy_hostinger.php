<?php
/**
 * Hostinger Deployment Script
 * This script prepares your Laravel app for Hostinger shared hosting
 */

echo "🚀 Preparing Laravel app for Hostinger deployment...\n\n";

// Create deployment directory
$deployDir = 'hostinger_deploy';
if (!is_dir($deployDir)) {
    mkdir($deployDir, 0755, true);
}

// Create public_html directory
$publicDir = $deployDir . '/public_html';
if (!is_dir($publicDir)) {
    mkdir($publicDir, 0755, true);
}

// Create laravel directory
$laravelDir = $deployDir . '/laravel';
if (!is_dir($laravelDir)) {
    mkdir($laravelDir, 0755, true);
}

echo "📁 Created deployment directories\n";

// Copy public folder contents to public_html
echo "📋 Copying public folder contents...\n";
$publicSource = 'public';
$publicDest = $publicDir;

if (is_dir($publicSource)) {
    copyDirectory($publicSource, $publicDest);
    echo "✅ Public files copied to public_html\n";
} else {
    echo "❌ Public folder not found!\n";
    exit(1);
}

// Copy Laravel files to laravel directory
echo "📋 Copying Laravel application files...\n";
$laravelFiles = [
    'app', 'bootstrap', 'config', 'database', 'resources', 'routes', 'storage', 'vendor',
    'artisan', 'composer.json', 'composer.lock', '.env.example'
];

foreach ($laravelFiles as $file) {
    if (file_exists($file)) {
        if (is_dir($file)) {
            copyDirectory($file, $laravelDir . '/' . $file);
        } else {
            copy($file, $laravelDir . '/' . $file);
        }
        echo "✅ Copied: $file\n";
    }
}

// Update index.php to point to correct paths
echo "🔧 Updating index.php paths...\n";
$indexPath = $publicDir . '/index.php';
$indexContent = file_get_contents($indexPath);

// Update the paths to point to the laravel directory
$indexContent = str_replace(
    "__DIR__.'/../",
    "__DIR__.'/../laravel/",
    $indexContent
);

file_put_contents($indexPath, $indexContent);
echo "✅ Updated index.php paths\n";

// Create .htaccess for root if it doesn't exist
$htaccessPath = $deployDir . '/.htaccess';
if (!file_exists($htaccessPath)) {
    $htaccessContent = 'RewriteEngine On
RewriteCond %{REQUEST_URI} !^/public/
RewriteRule ^(.*)$ /public/$1 [L,QSA]';
    file_put_contents($htaccessPath, $htaccessContent);
    echo "✅ Created root .htaccess\n";
}

// Create deployment instructions
$instructions = "HOSTINGER DEPLOYMENT INSTRUCTIONS
=====================================

1. Upload the contents of 'public_html' folder to your public_html directory
2. Upload the 'laravel' folder to your hosting root (same level as public_html)
3. Create a .env file in the laravel folder with your production settings
4. Set proper permissions:
   - storage/ and bootstrap/cache/ should be 755
   - .env should be 644

5. Database Configuration:
   - Create a MySQL database in Hostinger control panel
   - Update .env with your database credentials
   - Run: php artisan migrate (if you have SSH access)

6. If you don't have SSH access, you may need to:
   - Run migrations through Hostinger's database manager
   - Set up cron jobs through Hostinger control panel

IMPORTANT: Make sure your .env file has:
APP_ENV=production
APP_DEBUG=false
DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password

Your Laravel app should now work at: https://associationwestmount.com/
";

file_put_contents($deployDir . '/DEPLOYMENT_INSTRUCTIONS.txt', $instructions);
echo "✅ Created deployment instructions\n";

echo "\n🎉 Deployment preparation complete!\n";
echo "📁 Check the 'hostinger_deploy' folder\n";
echo "📖 Read DEPLOYMENT_INSTRUCTIONS.txt for next steps\n";

function copyDirectory($src, $dst) {
    $dir = opendir($src);
    @mkdir($dst);
    while (($file = readdir($dir)) !== false) {
        if ($file != '.' && $file != '..') {
            if (is_dir($src . '/' . $file)) {
                copyDirectory($src . '/' . $file, $dst . '/' . $file);
            } else {
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}
?>
