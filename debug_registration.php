<?php
/**
 * Debug Registration Issues
 * Upload this file to your public_html directory and run it to diagnose issues
 */

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Association Westmount - Registration Debug</h1>";
echo "<hr>";

// Check if we can connect to the database
echo "<h2>1. Database Connection Test</h2>";
try {
    // Load Laravel environment from the correct path
    require_once __DIR__ . '/vendor/autoload.php';
    
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    
    echo "✅ Laravel loaded successfully<br>";
    
    // Test database connection
    $pdo = DB::connection()->getPdo();
    echo "✅ Database connection successful<br>";
    
    // Check database name
    $dbName = DB::connection()->getDatabaseName();
    echo "✅ Connected to database: <strong>$dbName</strong><br>";
    
} catch (Exception $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "<br>";
    exit;
}

echo "<hr>";

// Check required tables
echo "<h2>2. Required Tables Check</h2>";
$requiredTables = [
    'members',
    'member_types', 
    'memberships',
    'payments',
    'sponsorships',
    'organizations',
    'page_contents',
    'migrations'
];

foreach ($requiredTables as $table) {
    try {
        $exists = DB::select("SHOW TABLES LIKE '$table'");
        if (count($exists) > 0) {
            echo "✅ Table '$table' exists<br>";
        } else {
            echo "❌ Table '$table' is MISSING<br>";
        }
    } catch (Exception $e) {
        echo "❌ Error checking table '$table': " . $e->getMessage() . "<br>";
    }
}

echo "<hr>";

// Check member types data
echo "<h2>3. Member Types Data Check</h2>";
try {
    $memberTypes = DB::table('member_types')->where('is_active', true)->get();
    if ($memberTypes->count() > 0) {
        echo "✅ Found " . $memberTypes->count() . " active member types<br>";
        foreach ($memberTypes as $type) {
            echo "&nbsp;&nbsp;• " . $type->name . " (ID: $type->id)<br>";
        }
    } else {
        echo "❌ No active member types found<br>";
    }
} catch (Exception $e) {
    echo "❌ Error checking member types: " . $e->getMessage() . "<br>";
}

echo "<hr>";

// Check page content data
echo "<h2>4. Page Content Data Check</h2>";
try {
    $pageContent = DB::table('page_contents')->where('page', 'home')->first();
    if ($pageContent) {
        echo "✅ Home page content found<br>";
    } else {
        echo "❌ No home page content found<br>";
    }
} catch (Exception $e) {
    echo "❌ Error checking page content: " . $e->getMessage() . "<br>";
}

echo "<hr>";

// Check file permissions
echo "<h2>5. File Permissions Check</h2>";
$directories = [
    'storage',
    'storage/framework',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'bootstrap/cache'
];

foreach ($directories as $dir) {
    $fullPath = __DIR__ . '/' . $dir;
    if (is_dir($fullPath)) {
        if (is_writable($fullPath)) {
            echo "✅ Directory '$dir' is writable<br>";
        } else {
            echo "❌ Directory '$dir' is NOT writable<br>";
        }
    } else {
        echo "❌ Directory '$dir' does not exist<br>";
    }
}

echo "<hr>";

// Test member creation
echo "<h2>6. Test Member Creation</h2>";
try {
    // Check if we can create a test member
    $testData = [
        'member_number' => 'TEST' . time(),
        'pin_code' => '1234',
        'first_name' => 'Test',
        'last_name' => 'User',
        'birth_date' => '1990-01-01',
        'phone' => '555-1234',
        'email' => 'test' . time() . '@example.com',
        'address' => '123 Test St',
        'city' => 'Test City',
        'province' => 'QC',
        'postal_code' => 'H1A 1A1',
        'country' => 'Canada',
        'canadian_status_proof' => 'Passport',
        'member_type_id' => 1,
        'is_active' => true,
        'email_verified_at' => now(),
    ];
    
    $memberId = DB::table('members')->insertGetId($testData);
    echo "✅ Test member created successfully (ID: $memberId)<br>";
    
    // Clean up test member
    DB::table('members')->where('id', $memberId)->delete();
    echo "✅ Test member cleaned up<br>";
    
} catch (Exception $e) {
    echo "❌ Error creating test member: " . $e->getMessage() . "<br>";
}

echo "<hr>";

// Check Laravel configuration
echo "<h2>7. Laravel Configuration</h2>";
echo "App Environment: " . config('app.env') . "<br>";
echo "App Debug: " . (config('app.debug') ? 'true' : 'false') . "<br>";
echo "Database Driver: " . config('database.default') . "<br>";

echo "<hr>";
echo "<h2>Debug Complete</h2>";
echo "<p>If you see any ❌ errors above, those are the issues preventing registration from working.</p>";
echo "<p>Most common fixes:</p>";
echo "<ul>";
echo "<li>Run database migrations: <code>php artisan migrate</code></li>";
echo "<li>Seed the database: <code>php artisan db:seed</code></li>";
echo "<li>Set proper file permissions: <code>chmod -R 755 storage bootstrap/cache</code></li>";
echo "<li>Clear Laravel cache: <code>php artisan cache:clear</code></li>";
echo "</ul>";
?>
