<?php
/**
 * Simple Registration Debug Script
 * This script checks basic requirements without loading Laravel
 */

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Association Westmount - Simple Registration Debug</h1>";
echo "<hr>";

// Check if we can connect to the database directly
echo "<h2>1. Direct Database Connection Test</h2>";

// Database credentials (update these with your actual Hostinger database credentials)
$host = 'localhost';
$dbname = 'u460961786_westmount_ass'; // Update with your actual database name
$username = 'u460961786_westmount_ass'; // Update with your actual username
$password = 'your_password_here'; // Update with your actual password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Database connection successful<br>";
    echo "✅ Connected to database: <strong>$dbname</strong><br>";
} catch (PDOException $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "<br>";
    echo "<p><strong>Please update the database credentials in this script and try again.</strong></p>";
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
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            echo "✅ Table '$table' exists<br>";
        } else {
            echo "❌ Table '$table' is MISSING<br>";
        }
    } catch (PDOException $e) {
        echo "❌ Error checking table '$table': " . $e->getMessage() . "<br>";
    }
}

echo "<hr>";

// Check member types data
echo "<h2>3. Member Types Data Check</h2>";
try {
    $stmt = $pdo->query("SELECT * FROM member_types WHERE is_active = 1");
    $memberTypes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($memberTypes) > 0) {
        echo "✅ Found " . count($memberTypes) . " active member types<br>";
        foreach ($memberTypes as $type) {
            echo "&nbsp;&nbsp;• " . $type['name'] . " (ID: " . $type['id'] . ")<br>";
        }
    } else {
        echo "❌ No active member types found<br>";
        echo "<p><strong>This is likely why registration is failing!</strong></p>";
    }
} catch (PDOException $e) {
    echo "❌ Error checking member types: " . $e->getMessage() . "<br>";
}

echo "<hr>";

// Check page content data
echo "<h2>4. Page Content Data Check</h2>";
try {
    $stmt = $pdo->query("SELECT * FROM page_contents WHERE page = 'home' LIMIT 1");
    $pageContent = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($pageContent) {
        echo "✅ Home page content found<br>";
    } else {
        echo "❌ No home page content found<br>";
    }
} catch (PDOException $e) {
    echo "❌ Error checking page content: " . $e->getMessage() . "<br>";
}

echo "<hr>";

// Check if we can insert a test record
echo "<h2>5. Test Database Insert</h2>";
try {
    // Check if members table has the right structure
    $stmt = $pdo->query("DESCRIBE members");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "✅ Members table structure:<br>";
    foreach ($columns as $column) {
        echo "&nbsp;&nbsp;• " . $column['Field'] . " (" . $column['Type'] . ")<br>";
    }
    
    // Try to insert a test record
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
        'is_active' => 1,
        'email_verified_at' => date('Y-m-d H:i:s'),
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ];
    
    $sql = "INSERT INTO members (member_number, pin_code, first_name, last_name, birth_date, phone, email, address, city, province, postal_code, country, canadian_status_proof, member_type_id, is_active, email_verified_at, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array_values($testData));
    
    $memberId = $pdo->lastInsertId();
    echo "✅ Test member created successfully (ID: $memberId)<br>";
    
    // Clean up test member
    $pdo->query("DELETE FROM members WHERE id = $memberId");
    echo "✅ Test member cleaned up<br>";
    
} catch (PDOException $e) {
    echo "❌ Error testing member creation: " . $e->getMessage() . "<br>";
}

echo "<hr>";

// Check file permissions
echo "<h2>6. File Permissions Check</h2>";
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
echo "<h2>Debug Complete</h2>";

// Summary
echo "<h3>Summary of Issues:</h3>";
echo "<ul>";
echo "<li>If you see ❌ for any required tables, you need to run database migrations</li>";
echo "<li>If you see ❌ for member types data, you need to seed the database</li>";
echo "<li>If you see ❌ for file permissions, you need to set proper permissions</li>";
echo "<li>If you see ❌ for test member creation, there's a database structure issue</li>";
echo "</ul>";

echo "<h3>Next Steps:</h3>";
echo "<ol>";
echo "<li>Update the database credentials at the top of this script</li>";
echo "<li>Run this script again to see the actual issues</li>";
echo "<li>Based on the results, we'll know exactly what to fix</li>";
echo "</ol>";
?>
