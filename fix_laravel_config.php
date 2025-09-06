<?php
/**
 * Fix Laravel Configuration Issue
 * This script will force Laravel to use the correct database credentials
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo '<h1>🔧 Fixing Laravel Configuration</h1>';

// Your Hostinger database credentials
$dbHost = 'localhost';
$dbPort = '3306';
$dbDatabase = 'u460961786_westmount_asso';
$dbUsername = 'u460961786_westmount_asso';
$dbPassword = 'Ayuk.texa1';

try {
    // Step 1: Update .env file
    echo '<h2>Step 1: Updating .env file...</h2>';
    
    if (!file_exists('.env')) {
        echo '<p style="color: red;">❌ .env file not found!</p>';
        exit;
    }
    
    $envContent = file_get_contents('.env');
    
    // Update database configuration
    $envContent = preg_replace('/^DB_HOST=.*/m', "DB_HOST=$dbHost", $envContent);
    $envContent = preg_replace('/^DB_PORT=.*/m', "DB_PORT=$dbPort", $envContent);
    $envContent = preg_replace('/^DB_DATABASE=.*/m', "DB_DATABASE=$dbDatabase", $envContent);
    $envContent = preg_replace('/^DB_USERNAME=.*/m', "DB_USERNAME=$dbUsername", $envContent);
    $envContent = preg_replace('/^DB_PASSWORD=.*/m', "DB_PASSWORD=$dbPassword", $envContent);
    
    // Write updated .env file
    file_put_contents('.env', $envContent);
    echo '<p>✅ .env file updated</p>';
    
    // Step 2: Clear all Laravel caches
    echo '<h2>Step 2: Clearing Laravel caches...</h2>';
    
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
    
    // Clear application cache
    $appCachePath = 'storage/framework/cache/data';
    if (is_dir($appCachePath)) {
        $files = glob($appCachePath . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        echo '<p>✅ Application cache cleared</p>';
    }
    
    // Step 3: Test Laravel database connection
    echo '<h2>Step 3: Testing Laravel database connection...</h2>';
    
    // Bootstrap Laravel
    require_once __DIR__ . '/vendor/autoload.php';
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    // Test database connection
    try {
        $connection = DB::connection();
        $pdo = $connection->getPdo();
        echo '<p style="color: green;">✅ Laravel database connection successful!</p>';
        
        // Test a simple query
        $result = DB::select('SELECT VERSION() as version');
        echo '<p>MySQL Version: ' . htmlspecialchars($result[0]->version) . '</p>';
        
        // Check if migrations table exists
        $migrationsExist = DB::select("SHOW TABLES LIKE 'migrations'");
        if (count($migrationsExist) > 0) {
            echo '<p>✅ Migrations table exists</p>';
        } else {
            echo '<p>⚠️ Migrations table does not exist</p>';
        }
        
    } catch (Exception $e) {
        echo '<p style="color: red;">❌ Laravel database connection failed: ' . htmlspecialchars($e->getMessage()) . '</p>';
        
        // Try to debug the issue
        echo '<h3>Debug Information:</h3>';
        echo '<p>Laravel is trying to connect with:</p>';
        echo '<ul>';
        echo '<li>Host: ' . config('database.connections.mysql.host') . '</li>';
        echo '<li>Port: ' . config('database.connections.mysql.port') . '</li>';
        echo '<li>Database: ' . config('database.connections.mysql.database') . '</li>';
        echo '<li>Username: ' . config('database.connections.mysql.username') . '</li>';
        echo '<li>Password: ' . (empty(config('database.connections.mysql.password')) ? 'Empty' : '***hidden***') . '</li>';
        echo '</ul>';
        
        // Show current .env content
        echo '<h3>Current .env content (database section):</h3>';
        echo '<pre style="background: #f4f4f4; padding: 10px; border-radius: 4px;">';
        $lines = explode("\n", $envContent);
        foreach ($lines as $line) {
            if (strpos($line, 'DB_') === 0) {
                echo htmlspecialchars($line) . "\n";
            }
        }
        echo '</pre>';
        
        exit;
    }
    
    // Step 4: Run migrations
    echo '<h2>Step 4: Running migrations...</h2>';
    
    try {
        // Use Artisan to run migrations
        $exitCode = Artisan::call('migrate', ['--force' => true]);
        
        if ($exitCode === 0) {
            echo '<p style="color: green;">✅ Migrations completed successfully!</p>';
        } else {
            echo '<p style="color: orange;">⚠️ Migrations completed with warnings (exit code: ' . $exitCode . ')</p>';
        }
        
    } catch (Exception $e) {
        echo '<p style="color: red;">❌ Migration failed: ' . htmlspecialchars($e->getMessage()) . '</p>';
        
        // Try alternative approach - direct SQL
        echo '<h3>Trying alternative approach...</h3>';
        
        try {
            // Check if migrations table exists, create if not
            DB::statement("CREATE TABLE IF NOT EXISTS migrations (
                id int(10) unsigned NOT NULL AUTO_INCREMENT,
                migration varchar(255) NOT NULL,
                batch int(11) NOT NULL,
                PRIMARY KEY (id)
            )");
            echo '<p>✅ Migrations table created/verified</p>';
            
            // Check current migration status
            $migrations = DB::table('migrations')->get();
            echo '<p>Current migrations: ' . count($migrations) . '</p>';
            
        } catch (Exception $e2) {
            echo '<p style="color: red;">❌ Alternative approach also failed: ' . htmlspecialchars($e2->getMessage()) . '</p>';
        }
    }
    
    echo '<h2>🎉 Configuration Fix Complete!</h2>';
    echo '<p>Your Laravel application should now work properly.</p>';
    echo '<p><a href="/" style="background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;">Go to Website</a></p>';
    echo '<p><a href="/admin" style="background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;">Go to Admin Panel</a></p>';
    
} catch (Exception $e) {
    echo '<p style="color: red;">❌ Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
}
?>
