<?php
/**
 * Database Connection Fix Script for Hostinger
 * This script will help diagnose and fix database connection issues
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo '<h1>🔧 Database Connection Diagnostic Tool</h1>';

// Check if .env file exists
if (!file_exists('.env')) {
    echo '<p style="color: red;">❌ .env file not found!</p>';
    echo '<p>Please make sure you have uploaded the .env file to your Hostinger server.</p>';
    exit;
}

// Read .env file
$envContent = file_get_contents('.env');
echo '<p>✅ .env file found</p>';

// Extract database credentials
preg_match('/DB_CONNECTION=(.*)/', $envContent, $connection);
preg_match('/DB_HOST=(.*)/', $envContent, $host);
preg_match('/DB_PORT=(.*)/', $envContent, $port);
preg_match('/DB_DATABASE=(.*)/', $envContent, $database);
preg_match('/DB_USERNAME=(.*)/', $envContent, $username);
preg_match('/DB_PASSWORD=(.*)/', $envContent, $password);

$dbConnection = trim($connection[1] ?? 'mysql');
$dbHost = trim($host[1] ?? 'localhost');
$dbPort = trim($port[1] ?? '3306');
$dbDatabase = trim($database[1] ?? '');
$dbUsername = trim($username[1] ?? 'root');
$dbPassword = trim($password[1] ?? '');

echo '<h2>Current Database Configuration:</h2>';
echo '<table border="1" cellpadding="5" cellspacing="0">';
echo '<tr><td><strong>Connection:</strong></td><td>' . htmlspecialchars($dbConnection) . '</td></tr>';
echo '<tr><td><strong>Host:</strong></td><td>' . htmlspecialchars($dbHost) . '</td></tr>';
echo '<tr><td><strong>Port:</strong></td><td>' . htmlspecialchars($dbPort) . '</td></tr>';
echo '<tr><td><strong>Database:</strong></td><td>' . htmlspecialchars($dbDatabase) . '</td></tr>';
echo '<tr><td><strong>Username:</strong></td><td>' . htmlspecialchars($dbUsername) . '</td></tr>';
echo '<tr><td><strong>Password:</strong></td><td>' . (empty($dbPassword) ? '<em>Empty</em>' : '***hidden***') . '</td></tr>';
echo '</table>';

// Test database connection
echo '<h2>Testing Database Connection:</h2>';

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
    
    // Test a simple query
    $stmt = $pdo->query("SELECT VERSION() as version");
    $version = $stmt->fetch();
    echo '<p>MySQL Version: ' . htmlspecialchars($version['version']) . '</p>';
    
    // Check if tables exist
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo '<p>Tables found: ' . count($tables) . '</p>';
    
    if (count($tables) > 0) {
        echo '<ul>';
        foreach ($tables as $table) {
            echo '<li>' . htmlspecialchars($table) . '</li>';
        }
        echo '</ul>';
    }
    
} catch (PDOException $e) {
    echo '<p style="color: red;">❌ Database connection failed!</p>';
    echo '<p><strong>Error:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>';
    
    // Provide troubleshooting steps
    echo '<h2>🔧 Troubleshooting Steps:</h2>';
    echo '<ol>';
    echo '<li><strong>Check your Hostinger database credentials:</strong>';
    echo '<ul>';
    echo '<li>Go to your Hostinger control panel</li>';
    echo '<li>Navigate to "Databases" → "MySQL Databases"</li>';
    echo '<li>Check the database name, username, and password</li>';
    echo '</ul></li>';
    
    echo '<li><strong>Update your .env file with correct credentials:</strong>';
    echo '<pre style="background: #f4f4f4; padding: 10px; border-radius: 4px;">';
    echo 'DB_CONNECTION=mysql' . "\n";
    echo 'DB_HOST=localhost' . "\n";
    echo 'DB_PORT=3306' . "\n";
    echo 'DB_DATABASE=your_database_name' . "\n";
    echo 'DB_USERNAME=your_username' . "\n";
    echo 'DB_PASSWORD=your_password' . "\n";
    echo '</pre></li>';
    
    echo '<li><strong>Common Hostinger database settings:</strong>';
    echo '<ul>';
    echo '<li>Host: localhost (usually)</li>';
    echo '<li>Port: 3306 (default)</li>';
    echo '<li>Database name: usually starts with your hosting account name</li>';
    echo '<li>Username: usually the same as database name</li>';
    echo '</ul></li>';
    echo '</ol>';
}

// Show current .env content (masked)
echo '<h2>Current .env Content (Database Section):</h2>';
echo '<pre style="background: #f4f4f4; padding: 10px; border-radius: 4px; max-height: 200px; overflow-y: auto;">';
$lines = explode("\n", $envContent);
foreach ($lines as $line) {
    if (strpos($line, 'DB_') === 0) {
        if (strpos($line, 'PASSWORD') !== false) {
            echo htmlspecialchars(preg_replace('/=(.*)/', '=***hidden***', $line)) . "\n";
        } else {
            echo htmlspecialchars($line) . "\n";
        }
    }
}
echo '</pre>';

echo '<h2>📝 Next Steps:</h2>';
echo '<ol>';
echo '<li>If connection failed, update your .env file with correct Hostinger database credentials</li>';
echo '<li>Once connection works, you can run the database update script</li>';
echo '<li>Make sure your database user has all necessary permissions</li>';
echo '</ol>';

echo '<p><a href="update_database.php?password=westmount2024">Try Database Update Script</a></p>';
?>
