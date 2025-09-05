<?php
echo "<h2>Reset Database for Clean Import</h2>";

try {
    $pdo = new PDO('mysql:host=localhost;dbname=u760706628_westmount_asso', 'u760706628_westmount_asso', 'Ayuk.texa1');
    echo "✅ Database connection successful!<br><br>";
    
    // Disable foreign key checks
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    echo "✅ Foreign key checks disabled<br>";
    
    // Get all tables
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    
    echo "📋 Found " . count($tables) . " tables to drop:<br>";
    foreach($tables as $table) {
        echo "- $table<br>";
    }
    echo "<br>";
    
    // Drop all tables
    foreach($tables as $table) {
        $pdo->exec("DROP TABLE IF EXISTS `$table`");
        echo "✅ Dropped table: $table<br>";
    }
    
    // Re-enable foreign key checks
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    echo "<br>✅ Foreign key checks re-enabled<br>";
    
    echo "<br>🎉 <strong>Database reset complete!</strong><br>";
    echo "You can now import the complete_database_export_fixed.sql file safely.<br>";
    echo "Go to phpMyAdmin SQL tab and paste the content of the fixed SQL file.";
    
} catch(PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}
?>
