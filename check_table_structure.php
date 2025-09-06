<?php
/**
 * Check Page Contents Table Structure
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo '<h1>🔍 Checking Page Contents Table Structure</h1>';

// Your Hostinger database credentials
$dbHost = 'localhost';
$dbPort = '3306';
$dbDatabase = 'u460961786_westmount_asso';
$dbUsername = 'u460961786_westmount_asso';
$dbPassword = 'Ayuk.texa1';

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
    
    // Check table structure
    echo '<h2>Page Contents Table Structure:</h2>';
    $stmt = $pdo->query("DESCRIBE page_contents");
    $columns = $stmt->fetchAll();
    
    echo '<table border="1" cellpadding="5" cellspacing="0">';
    echo '<tr><th>Column</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>';
    foreach ($columns as $column) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($column['Field']) . '</td>';
        echo '<td>' . htmlspecialchars($column['Type']) . '</td>';
        echo '<td>' . htmlspecialchars($column['Null']) . '</td>';
        echo '<td>' . htmlspecialchars($column['Key']) . '</td>';
        echo '<td>' . htmlspecialchars($column['Default']) . '</td>';
        echo '<td>' . htmlspecialchars($column['Extra']) . '</td>';
        echo '</tr>';
    }
    echo '</table>';
    
    // Check sample data
    echo '<h2>Sample Data:</h2>';
    $stmt = $pdo->query("SELECT * FROM page_contents LIMIT 3");
    $samples = $stmt->fetchAll();
    
    if (count($samples) > 0) {
        echo '<table border="1" cellpadding="5" cellspacing="0">';
        echo '<tr>';
        foreach (array_keys($samples[0]) as $column) {
            echo '<th>' . htmlspecialchars($column) . '</th>';
        }
        echo '</tr>';
        foreach ($samples as $row) {
            echo '<tr>';
            foreach ($row as $value) {
                echo '<td>' . htmlspecialchars(substr($value, 0, 50)) . (strlen($value) > 50 ? '...' : '') . '</td>';
            }
            echo '</tr>';
        }
        echo '</table>';
    } else {
        echo '<p>No data found in page_contents table</p>';
    }
    
} catch (Exception $e) {
    echo '<p style="color: red;">❌ Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
}
?>

