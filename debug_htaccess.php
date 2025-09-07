<?php
/**
 * Debug .htaccess and Routing Issues
 */

echo "<h1>Debug .htaccess and Routing</h1>";
echo "<hr>";

// Check if we can access the file directly
echo "<h2>1. Direct File Access Test</h2>";

$files_to_test = [
    'index.php' => 'public_html/index.php',
    'public/index.php' => 'public_html/public/index.php',
    'bootstrap/app.php' => 'public_html/bootstrap/app.php',
    'vendor/autoload.php' => 'public_html/vendor/autoload.php'
];

foreach ($files_to_test as $name => $path) {
    if (file_exists($path)) {
        echo "✅ $name exists at $path<br>";
    } else {
        echo "❌ $name missing at $path<br>";
    }
}

echo "<h2>2. Test Direct index.php Access</h2>";

// Test if we can load index.php directly
try {
    ob_start();
    include __DIR__ . '/index.php';
    $output = ob_get_clean();
    
    if (strlen($output) > 0) {
        echo "✅ index.php can be executed directly<br>";
        echo "&nbsp;&nbsp;Output length: " . strlen($output) . " characters<br>";
        
        if (strpos($output, 'Association Westmount') !== false) {
            echo "✅ Output contains expected content<br>";
        } else {
            echo "⚠️ Output doesn't contain expected content<br>";
            echo "&nbsp;&nbsp;First 500 characters:<br>";
            echo "<pre style='background: #f5f5f5; padding: 10px; border: 1px solid #ddd; max-height: 200px; overflow-y: auto;'>";
            echo htmlspecialchars(substr($output, 0, 500));
            echo "</pre>";
        }
    } else {
        echo "❌ index.php produces no output<br>";
    }
    
} catch (Exception $e) {
    echo "❌ index.php execution failed: " . $e->getMessage() . "<br>";
} catch (Error $e) {
    echo "❌ index.php PHP error: " . $e->getMessage() . "<br>";
}

echo "<h2>3. Check .htaccess Syntax</h2>";

$htaccess_path = __DIR__ . '/.htaccess';
if (file_exists($htaccess_path)) {
    $htaccess_content = file_get_contents($htaccess_path);
    echo "✅ .htaccess file exists<br>";
    echo "&nbsp;&nbsp;Content:<br>";
    echo "<pre style='background: #f5f5f5; padding: 10px; border: 1px solid #ddd; max-height: 200px; overflow-y: auto;'>";
    echo htmlspecialchars($htaccess_content);
    echo "</pre>";
} else {
    echo "❌ .htaccess file missing<br>";
}

echo "<h2>4. Test Different URLs</h2>";

$test_urls = [
    'https://associationwestmount.com/',
    'https://associationwestmount.com/index.php',
    'https://associationwestmount.com/public/index.php'
];

foreach ($test_urls as $url) {
    echo "Testing: $url<br>";
    // Note: We can't actually make HTTP requests, but we can check if the files exist
    if ($url === 'https://associationwestmount.com/index.php') {
        if (file_exists(__DIR__ . '/index.php')) {
            echo "&nbsp;&nbsp;✅ File exists for direct access<br>";
        } else {
            echo "&nbsp;&nbsp;❌ File missing for direct access<br>";
        }
    }
}

echo "<h2>5. Check Server Configuration</h2>";

echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
echo "Script Name: " . ($_SERVER['SCRIPT_NAME'] ?? 'Not set') . "<br>";
echo "Request URI: " . ($_SERVER['REQUEST_URI'] ?? 'Not set') . "<br>";
echo "HTTP Host: " . ($_SERVER['HTTP_HOST'] ?? 'Not set') . "<br>";
echo "Server Name: " . ($_SERVER['SERVER_NAME'] ?? 'Not set') . "<br>";

echo "<h2>6. Check File Permissions</h2>";

$important_files = [
    'index.php',
    '.htaccess',
    'bootstrap/app.php',
    'vendor/autoload.php'
];

foreach ($important_files as $file) {
    $path = __DIR__ . '/' . $file;
    if (file_exists($path)) {
        $perms = fileperms($path);
        $readable = is_readable($path);
        echo "✅ $file exists (perms: " . decoct($perms & 0777) . ", readable: " . ($readable ? 'yes' : 'no') . ")<br>";
    } else {
        echo "❌ $file missing<br>";
    }
}

echo "<hr>";
echo "<h2>Next Steps</h2>";
echo "<p>If index.php can be executed directly but the homepage still fails:</p>";
echo "<ol>";
echo "<li><strong>Try accessing:</strong> https://associationwestmount.com/index.php directly</li>";
echo "<li><strong>Check Hostinger error logs</strong> for specific error messages</li>";
echo "<li><strong>Contact Hostinger support</strong> - this might be a server configuration issue</li>";
echo "<li><strong>Check if mod_rewrite is enabled</strong> on your hosting</li>";
echo "</ol>";
?>
