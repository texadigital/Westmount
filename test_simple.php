<?php
/**
 * Simple Test - Check if basic PHP is working
 */

echo "<h1>Simple PHP Test</h1>";
echo "<p>If you can see this, PHP is working.</p>";
echo "<p>Current time: " . date('Y-m-d H:i:s') . "</p>";
echo "<p>PHP version: " . phpversion() . "</p>";

// Test if we can include Laravel
echo "<h2>Laravel Include Test</h2>";
try {
    require_once __DIR__ . '/vendor/autoload.php';
    echo "✅ Laravel autoloader loaded successfully<br>";
    
    $app = require_once __DIR__ . '/bootstrap/app.php';
    echo "✅ Laravel app loaded successfully<br>";
    
    echo "✅ Laravel is working from web context<br>";
    
} catch (Exception $e) {
    echo "❌ Laravel error: " . $e->getMessage() . "<br>";
} catch (Error $e) {
    echo "❌ PHP error: " . $e->getMessage() . "<br>";
}
?>
