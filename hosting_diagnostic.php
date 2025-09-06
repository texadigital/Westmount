<?php
/**
 * Hosting Environment Diagnostic Script
 * Upload this to your hosting and visit: https://associationwestmount.com/hosting_diagnostic.php
 */

echo "<h1>🔍 Hosting Environment Diagnostic</h1>";
echo "<p><strong>Domain:</strong> " . ($_SERVER['HTTP_HOST'] ?? 'Unknown') . "</p>";
echo "<p><strong>Server:</strong> " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "</p>";
echo "<p><strong>Date:</strong> " . date('Y-m-d H:i:s') . "</p><hr>";

// 1. PHP Version Check
echo "<h2>1. PHP Version</h2>";
$phpVersion = phpversion();
echo "<p><strong>Current PHP Version:</strong> " . $phpVersion . "</p>";

if (version_compare($phpVersion, '8.1.0', '>=')) {
    echo "<p style='color: green;'>✅ PHP version is compatible with Laravel 12</p>";
} else {
    echo "<p style='color: red;'>❌ PHP version is too old. Laravel 12 requires PHP 8.1+</p>";
    echo "<p><strong>Action Required:</strong> Contact hosting provider to upgrade PHP to 8.1 or higher</p>";
}

// 2. Required PHP Extensions
echo "<h2>2. Required PHP Extensions</h2>";
$requiredExtensions = [
    'pdo_mysql' => 'Database connectivity',
    'mbstring' => 'String handling',
    'openssl' => 'Encryption',
    'tokenizer' => 'Code parsing',
    'xml' => 'XML processing',
    'ctype' => 'Character type checking',
    'json' => 'JSON processing',
    'bcmath' => 'Arbitrary precision math',
    'fileinfo' => 'File type detection',
    'curl' => 'HTTP requests'
];

$missingExtensions = [];
foreach ($requiredExtensions as $ext => $description) {
    if (extension_loaded($ext)) {
        echo "<p style='color: green;'>✅ $ext - $description</p>";
    } else {
        echo "<p style='color: red;'>❌ $ext - $description</p>";
        $missingExtensions[] = $ext;
    }
}

if (empty($missingExtensions)) {
    echo "<p style='color: green;'><strong>✅ All required extensions are installed!</strong></p>";
} else {
    echo "<p style='color: red;'><strong>❌ Missing extensions: " . implode(', ', $missingExtensions) . "</strong></p>";
    echo "<p><strong>Action Required:</strong> Contact hosting provider to install missing extensions</p>";
}

// 3. File Permissions Check
echo "<h2>3. File Permissions</h2>";
$checkPaths = [
    '.' => 'Current directory',
    'storage' => 'Storage directory',
    'bootstrap/cache' => 'Bootstrap cache',
    '.env' => 'Environment file'
];

foreach ($checkPaths as $path => $description) {
    if (file_exists($path)) {
        $perms = fileperms($path);
        $readable = is_readable($path);
        $writable = is_writable($path);
        
        echo "<p><strong>$description ($path):</strong> ";
        echo "Readable: " . ($readable ? "✅" : "❌") . " ";
        echo "Writable: " . ($writable ? "✅" : "❌") . " ";
        echo "Permissions: " . substr(sprintf('%o', $perms), -4) . "</p>";
    } else {
        echo "<p style='color: red;'>❌ $description ($path) - File/directory not found</p>";
    }
}

// 4. Database Connection Test
echo "<h2>4. Database Connection Test</h2>";
if (file_exists('.env')) {
    echo "<p>✅ .env file found</p>";
    
    // Try to load Laravel and test database
    try {
        require_once 'vendor/autoload.php';
        $app = require_once 'bootstrap/app.php';
        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
        
        $pdo = new PDO(
            'mysql:host=' . env('DB_HOST', 'localhost') . ';dbname=' . env('DB_DATABASE'),
            env('DB_USERNAME'),
            env('DB_PASSWORD')
        );
        echo "<p style='color: green;'>✅ Database connection successful</p>";
        
        // Test if tables exist
        $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        echo "<p><strong>Tables found:</strong> " . count($tables) . "</p>";
        
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Database connection failed: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p style='color: red;'>❌ .env file not found</p>";
}

// 5. Web Server Configuration
echo "<h2>5. Web Server Configuration</h2>";
echo "<p><strong>Server Software:</strong> " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "</p>";
echo "<p><strong>Document Root:</strong> " . ($_SERVER['DOCUMENT_ROOT'] ?? 'Unknown') . "</p>";
echo "<p><strong>Script Name:</strong> " . ($_SERVER['SCRIPT_NAME'] ?? 'Unknown') . "</p>";

// Check if mod_rewrite is available (for Apache)
if (function_exists('apache_get_modules')) {
    $modules = apache_get_modules();
    if (in_array('mod_rewrite', $modules)) {
        echo "<p style='color: green;'>✅ mod_rewrite is enabled</p>";
    } else {
        echo "<p style='color: red;'>❌ mod_rewrite is not enabled</p>";
    }
}

// 6. Memory and Limits
echo "<h2>6. PHP Configuration</h2>";
echo "<p><strong>Memory Limit:</strong> " . ini_get('memory_limit') . "</p>";
echo "<p><strong>Max Execution Time:</strong> " . ini_get('max_execution_time') . " seconds</p>";
echo "<p><strong>Upload Max Filesize:</strong> " . ini_get('upload_max_filesize') . "</p>";
echo "<p><strong>Post Max Size:</strong> " . ini_get('post_max_size') . "</p>";

// 7. Laravel Specific Checks
echo "<h2>7. Laravel Application Check</h2>";

$laravelFiles = [
    'artisan' => 'Laravel command line tool',
    'composer.json' => 'Composer configuration',
    'bootstrap/app.php' => 'Laravel bootstrap file',
    'vendor/autoload.php' => 'Composer autoloader',
    'public/index.php' => 'Laravel entry point'
];

foreach ($laravelFiles as $file => $description) {
    if (file_exists($file)) {
        echo "<p style='color: green;'>✅ $description ($file)</p>";
    } else {
        echo "<p style='color: red;'>❌ $description ($file) - Missing</p>";
    }
}

// 8. Recommendations
echo "<h2>8. Recommendations</h2>";

if (version_compare($phpVersion, '8.1.0', '<')) {
    echo "<p style='color: red;'><strong>CRITICAL:</strong> Upgrade PHP to 8.1+ immediately</p>";
}

if (!empty($missingExtensions)) {
    echo "<p style='color: red;'><strong>CRITICAL:</strong> Install missing PHP extensions</p>";
}

if (!file_exists('.env')) {
    echo "<p style='color: red;'><strong>CRITICAL:</strong> Create .env file with database credentials</p>";
}

echo "<p><strong>Next Steps:</strong></p>";
echo "<ul>";
echo "<li>1. Fix any critical issues listed above</li>";
echo "<li>2. Upload complete_database_export_fixed.sql to your database</li>";
echo "<li>3. Test the application again</li>";
echo "<li>4. If still getting 502 error, contact hosting provider with this diagnostic report</li>";
echo "</ul>";

echo "<hr><p><em>Diagnostic completed at " . date('Y-m-d H:i:s') . "</em></p>";
?>
