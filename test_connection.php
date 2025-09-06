<?php
/**
 * Test Database Connection for Hostinger
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo '<h1>🔧 Testing Hostinger Database Connection</h1>';

// Your Hostinger database credentials
$dbHost = 'localhost';
$dbPort = '3306';
$dbDatabase = 'u460961786_westmount_asso';
$dbUsername = 'u460961786_westmount_asso';
$dbPassword = 'Ayuk.texa1';

echo '<h2>Testing Connection with Your Credentials:</h2>';
echo '<table border="1" cellpadding="5" cellspacing="0">';
echo '<tr><td><strong>Host:</strong></td><td>' . htmlspecialchars($dbHost) . '</td></tr>';
echo '<tr><td><strong>Port:</strong></td><td>' . htmlspecialchars($dbPort) . '</td></tr>';
echo '<tr><td><strong>Database:</strong></td><td>' . htmlspecialchars($dbDatabase) . '</td></tr>';
echo '<tr><td><strong>Username:</strong></td><td>' . htmlspecialchars($dbUsername) . '</td></tr>';
echo '<tr><td><strong>Password:</strong></td><td>***hidden***</td></tr>';
echo '</table>';

try {
    // Test connection
    $pdo = new PDO(
        "mysql:host=$dbHost;port=$dbPort;dbname=$dbDatabase;charset=utf8mb4",
        $dbUsername,
        $dbPassword,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
    
    echo '<p style="color: green; font-size: 18px;">✅ Database connection successful!</p>';
    
    // Get MySQL version
    $stmt = $pdo->query("SELECT VERSION() as version");
    $version = $stmt->fetch();
    echo '<p><strong>MySQL Version:</strong> ' . htmlspecialchars($version['version']) . '</p>';
    
    // Check existing tables
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo '<p><strong>Tables found:</strong> ' . count($tables) . '</p>';
    
    if (count($tables) > 0) {
        echo '<h3>Existing Tables:</h3><ul>';
        foreach ($tables as $table) {
            echo '<li>' . htmlspecialchars($table) . '</li>';
        }
        echo '</ul>';
    }
    
    // Check if we can create a test table
    echo '<h3>Testing Table Creation:</h3>';
    try {
        $pdo->exec("CREATE TABLE IF NOT EXISTS test_connection (id INT AUTO_INCREMENT PRIMARY KEY, test_field VARCHAR(255))");
        echo '<p style="color: green;">✅ Can create tables</p>';
        
        // Clean up test table
        $pdo->exec("DROP TABLE IF EXISTS test_connection");
        echo '<p style="color: green;">✅ Can drop tables</p>';
        
    } catch (Exception $e) {
        echo '<p style="color: orange;">⚠️ Table creation test failed: ' . htmlspecialchars($e->getMessage()) . '</p>';
    }
    
    echo '<h2>🎉 Database is Ready!</h2>';
    echo '<p>Your database connection is working perfectly. You can now run the database update script.</p>';
    echo '<p><a href="update_database.php?password=westmount2024" style="background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;">Run Database Update</a></p>';
    
} catch (PDOException $e) {
    echo '<p style="color: red; font-size: 18px;">❌ Database connection failed!</p>';
    echo '<p><strong>Error:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>';
    
    // Common solutions
    echo '<h2>🔧 Possible Solutions:</h2>';
    echo '<ol>';
    echo '<li><strong>Check if the database exists:</strong> Make sure the database "u460961786_westmount_asso" exists in your Hostinger control panel</li>';
    echo '<li><strong>Check user permissions:</strong> Make sure the user "u460961786_westmount_asso" has full permissions on the database</li>';
    echo '<li><strong>Try different host:</strong> Some Hostinger setups use "mysql.hostinger.com" instead of "localhost"</li>';
    echo '<li><strong>Check firewall:</strong> Make sure there are no firewall restrictions</li>';
    echo '</ol>';
    
    // Test with alternative host
    echo '<h3>Testing with alternative host (mysql.hostinger.com):</h3>';
    try {
        $pdo2 = new PDO(
            "mysql:host=mysql.hostinger.com;port=3306;dbname=$dbDatabase;charset=utf8mb4",
            $dbUsername,
            $dbPassword,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
        echo '<p style="color: green;">✅ Connection successful with mysql.hostinger.com!</p>';
        echo '<p>Try updating your .env file to use: <code>DB_HOST=mysql.hostinger.com</code></p>';
    } catch (PDOException $e2) {
        echo '<p style="color: red;">❌ Alternative host also failed: ' . htmlspecialchars($e2->getMessage()) . '</p>';
    }
}

echo '<hr>';
echo '<h2>📝 Your Current .env Configuration:</h2>';
echo '<pre style="background: #f4f4f4; padding: 10px; border-radius: 4px;">';
echo 'DB_CONNECTION=mysql' . "\n";
echo 'DB_HOST=localhost' . "\n";
echo 'DB_PORT=3306' . "\n";
echo 'DB_DATABASE=u460961786_westmount_asso' . "\n";
echo 'DB_USERNAME=u460961786_westmount_asso' . "\n";
echo 'DB_PASSWORD=Ayuk.texa1' . "\n";
echo '</pre>';
?>
