<?php
/**
 * Fix Laravel Database Configuration
 * This script will update the .env file and clear Laravel caches
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo '<h1>🔧 Fixing Laravel Database Configuration</h1>';

// Your Hostinger database credentials
$dbHost = 'localhost';
$dbPort = '3306';
$dbDatabase = 'u460961786_westmount_asso';
$dbUsername = 'u460961786_westmount_asso';
$dbPassword = 'Ayuk.texa1';

try {
    // Read current .env file
    if (!file_exists('.env')) {
        echo '<p style="color: red;">❌ .env file not found!</p>';
        exit;
    }
    
    $envContent = file_get_contents('.env');
    echo '<p>✅ .env file found</p>';
    
    // Update database configuration
    $envContent = preg_replace('/^DB_HOST=.*/m', "DB_HOST=$dbHost", $envContent);
    $envContent = preg_replace('/^DB_PORT=.*/m', "DB_HOST=$dbPort", $envContent);
    $envContent = preg_replace('/^DB_DATABASE=.*/m', "DB_DATABASE=$dbDatabase", $envContent);
    $envContent = preg_replace('/^DB_USERNAME=.*/m', "DB_USERNAME=$dbUsername", $envContent);
    $envContent = preg_replace('/^DB_PASSWORD=.*/m', "DB_PASSWORD=$dbPassword", $envContent);
    
    // Write updated .env file
    file_put_contents('.env', $envContent);
    echo '<p>✅ .env file updated with correct database credentials</p>';
    
    // Bootstrap Laravel
    require_once __DIR__ . '/vendor/autoload.php';
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    // Clear Laravel caches
    echo '<p>Clearing Laravel caches...</p>';
    
    // Clear config cache
    if (file_exists('bootstrap/cache/config.php')) {
        unlink('bootstrap/cache/config.php');
        echo '<p>✅ Config cache cleared</p>';
    }
    
    // Clear route cache
    if (file_exists('bootstrap/cache/routes-v7.php')) {
        unlink('bootstrap/cache/routes-v7.php');
        echo '<p>✅ Route cache cleared</p>';
    }
    
    // Clear view cache
    $viewCachePath = 'storage/framework/views';
    if (is_dir($viewCachePath)) {
        $files = glob($viewCachePath . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        echo '<p>✅ View cache cleared</p>';
    }
    
    // Test database connection
    echo '<p>Testing database connection...</p>';
    try {
        $pdo = new PDO(
            "mysql:host=$dbHost;port=$dbPort;dbname=$dbDatabase;charset=utf8mb4",
            $dbUsername,
            $dbPassword,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
        
        echo '<p style="color: green;">✅ Database connection successful!</p>';
        
        // Test Laravel database connection
        $connection = DB::connection();
        $connection->getPdo();
        echo '<p style="color: green;">✅ Laravel database connection successful!</p>';
        
        echo '<h2>🎉 Configuration Fixed!</h2>';
        echo '<p>Your Laravel application should now work properly.</p>';
        echo '<p><a href="/" style="background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;">Go to Website</a></p>';
        echo '<p><a href="/admin" style="background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;">Go to Admin Panel</a></p>';
        
    } catch (Exception $e) {
        echo '<p style="color: red;">❌ Database connection failed: ' . htmlspecialchars($e->getMessage()) . '</p>';
        echo '<p>Please check your Hostinger database settings.</p>';
    }
    
} catch (Exception $e) {
    echo '<p style="color: red;">❌ Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
}
?>

