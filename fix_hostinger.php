<?php
/**
 * Quick fix for Hostinger deployment
 * This creates the correct index.php for your current setup
 */

echo "🔧 Creating fixed index.php for Hostinger...\n";

$indexContent = '<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define(\'LARAVEL_START\', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.\'/../storage/framework/maintenance.php\')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.\'/../vendor/autoload.php\';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.\'/../bootstrap/app.php\';

$app->handleRequest(Request::capture());
';

file_put_contents('public/index.php', $indexContent);
echo "✅ Updated public/index.php\n";

echo "\n📋 INSTRUCTIONS FOR HOSTINGER:\n";
echo "================================\n\n";
echo "1. In Hostinger File Manager:\n";
echo "   - Go to public_html directory\n";
echo "   - Select and DELETE these folders/files:\n";
echo "     • app, bootstrap, config, database, resources, routes, storage, vendor\n";
echo "     • .env, artisan, composer.json, composer.lock\n";
echo "     • All other root files (keep only public folder contents)\n\n";
echo "2. Move the deleted files to the parent directory (outside public_html)\n\n";
echo "3. Upload this fixed index.php to public_html/\n\n";
echo "4. Your structure should look like:\n";
echo "   /\n";
echo "   ├── public_html/\n";
echo "   │   ├── index.php (this fixed one)\n";
echo "   │   ├── .htaccess\n";
echo "   │   ├── css/, js/, build/\n";
echo "   │   └── favicon.ico\n";
echo "   ├── app/\n";
echo "   ├── bootstrap/\n";
echo "   ├── config/\n";
echo "   ├── database/\n";
echo "   ├── resources/\n";
echo "   ├── routes/\n";
echo "   ├── storage/\n";
echo "   ├── vendor/\n";
echo "   ├── .env\n";
echo "   └── artisan\n\n";
echo "5. Set proper permissions:\n";
echo "   - storage/ and bootstrap/cache/ should be 755\n";
echo "   - .env should be 644\n\n";
echo "6. Update your .env file with production database settings\n\n";
echo "Your site should work at: https://associationwestmount.com/\n";
?>
