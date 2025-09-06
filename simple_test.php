<?php
/**
 * Simple PHP Test Script
 * Upload this to test basic PHP functionality
 */

echo "<h1>✅ PHP is Working!</h1>";
echo "<p><strong>PHP Version:</strong> " . phpversion() . "</p>";
echo "<p><strong>Server:</strong> " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "</p>";
echo "<p><strong>Date:</strong> " . date('Y-m-d H:i:s') . "</p>";

// Test basic functionality
echo "<h2>Basic Tests:</h2>";
echo "<p>✅ PHP execution: Working</p>";
echo "<p>✅ Date function: " . date('Y-m-d H:i:s') . "</p>";
echo "<p>✅ File system: " . (is_writable('.') ? 'Writable' : 'Not writable') . "</p>";

// Test MySQL extension
if (extension_loaded('pdo_mysql')) {
    echo "<p>✅ PDO MySQL: Available</p>";
} else {
    echo "<p>❌ PDO MySQL: Not available</p>";
}

echo "<p><strong>If you see this page, PHP is working correctly!</strong></p>";
?>
