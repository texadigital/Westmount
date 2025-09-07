<?php
/**
 * Debug Real Browser Request
 * This simulates the exact same request path as a real browser
 */

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Real Browser Request Debug</h1>";
echo "<hr>";

// Simulate the exact same request as a browser
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/';
$_SERVER['HTTP_HOST'] = 'associationwestmount.com';
$_SERVER['HTTPS'] = 'on';
$_SERVER['SERVER_PORT'] = '443';
$_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36';
$_SERVER['HTTP_ACCEPT'] = 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8';
$_SERVER['HTTP_ACCEPT_LANGUAGE'] = 'en-US,en;q=0.5';
$_SERVER['HTTP_ACCEPT_ENCODING'] = 'gzip, deflate';
$_SERVER['HTTP_CONNECTION'] = 'keep-alive';
$_SERVER['HTTP_UPGRADE_INSECURE_REQUESTS'] = '1';

try {
    // Load Laravel exactly as the real request would
    require_once __DIR__ . '/vendor/autoload.php';
    $app = require_once __DIR__ . '/bootstrap/app.php';
    
    echo "✅ Laravel loaded<br>";
    
    // Capture the request exactly as Laravel would
    $request = Illuminate\Http\Request::capture();
    echo "✅ Request captured<br>";
    echo "&nbsp;&nbsp;Method: " . $request->method() . "<br>";
    echo "&nbsp;&nbsp;URI: " . $request->getRequestUri() . "<br>";
    echo "&nbsp;&nbsp;Host: " . $request->getHost() . "<br>";
    
    // Handle the request exactly as the real application would
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    echo "✅ Kernel created<br>";
    
    // This is the exact same call that happens in public/index.php
    $response = $kernel->handle($request);
    echo "✅ Request handled<br>";
    echo "&nbsp;&nbsp;Status: " . $response->getStatusCode() . "<br>";
    echo "&nbsp;&nbsp;Content length: " . strlen($response->getContent()) . "<br>";
    
    if ($response->getStatusCode() === 200) {
        echo "✅ Response is successful<br>";
        
        // Check if it's the expected homepage content
        $content = $response->getContent();
        if (strpos($content, 'Association Westmount') !== false) {
            echo "✅ Content contains expected homepage text<br>";
        } else {
            echo "⚠️ Content doesn't contain expected homepage text<br>";
            echo "&nbsp;&nbsp;First 500 characters:<br>";
            echo "<pre style='background: #f5f5f5; padding: 10px; border: 1px solid #ddd; max-height: 200px; overflow-y: auto;'>";
            echo htmlspecialchars(substr($content, 0, 500));
            echo "</pre>";
        }
    } else {
        echo "❌ Response failed with status: " . $response->getStatusCode() . "<br>";
        echo "&nbsp;&nbsp;Content:<br>";
        echo "<pre style='background: #f5f5f5; padding: 10px; border: 1px solid #ddd; max-height: 200px; overflow-y: auto;'>";
        echo htmlspecialchars($response->getContent());
        echo "</pre>";
    }
    
} catch (Exception $e) {
    echo "❌ Fatal error: " . $e->getMessage() . "<br>";
    echo "&nbsp;&nbsp;File: " . $e->getFile() . "<br>";
    echo "&nbsp;&nbsp;Line: " . $e->getLine() . "<br>";
    echo "&nbsp;&nbsp;Stack trace:<br>";
    echo "<pre style='background: #f5f5f5; padding: 10px; border: 1px solid #ddd; max-height: 300px; overflow-y: auto;'>";
    echo htmlspecialchars($e->getTraceAsString());
    echo "</pre>";
} catch (Error $e) {
    echo "❌ Fatal error: " . $e->getMessage() . "<br>";
    echo "&nbsp;&nbsp;File: " . $e->getFile() . "<br>";
    echo "&nbsp;&nbsp;Line: " . $e->getLine() . "<br>";
    echo "&nbsp;&nbsp;Stack trace:<br>";
    echo "<pre style='background: #f5f5f5; padding: 10px; border: 1px solid #ddd; max-height: 300px; overflow-y: auto;'>";
    echo htmlspecialchars($e->getTraceAsString());
    echo "</pre>";
}

echo "<hr>";
echo "<h2>Additional Debugging</h2>";

// Check if there are any recent errors in the log
$logFile = __DIR__ . '/storage/logs/laravel.log';
if (file_exists($logFile)) {
    $lines = file($logFile);
    $recentLines = array_slice($lines, -10);
    
    echo "<h3>Recent Laravel Log Entries:</h3>";
    echo "<pre style='background: #f5f5f5; padding: 10px; border: 1px solid #ddd; max-height: 200px; overflow-y: auto;'>";
    foreach ($recentLines as $line) {
        echo htmlspecialchars($line);
    }
    echo "</pre>";
}

// Check PHP error log
$phpErrorLog = ini_get('error_log');
if ($phpErrorLog && file_exists($phpErrorLog)) {
    $lines = file($phpErrorLog);
    $recentLines = array_slice($lines, -5);
    
    echo "<h3>Recent PHP Error Log Entries:</h3>";
    echo "<pre style='background: #f5f5f5; padding: 10px; border: 1px solid #ddd; max-height: 200px; overflow-y: auto;'>";
    foreach ($recentLines as $line) {
        echo htmlspecialchars($line);
    }
    echo "</pre>";
}

echo "<h2>If This Still Shows Success But Browser Shows 500:</h2>";
echo "<p>The issue might be:</p>";
echo "<ul>";
echo "<li><strong>Web server configuration:</strong> Apache/Nginx might be intercepting the request</li>";
echo "<li><strong>PHP-FPM issues:</strong> Different PHP execution context</li>";
echo "<li><strong>Memory limits:</strong> Different memory limits for web requests</li>";
echo "<li><strong>File permissions:</strong> Web server can't access certain files</li>";
echo "<li><strong>Mod_security:</strong> Security module blocking the request</li>";
echo "</ul>";
?>
