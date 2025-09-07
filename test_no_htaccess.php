<?php
/**
 * Test without .htaccess interference
 */

echo "<h1>Test Without .htaccess</h1>";
echo "<p>This file should work regardless of .htaccess issues.</p>";
echo "<p>Current time: " . date('Y-m-d H:i:s') . "</p>";

// Test Laravel loading
try {
    require_once __DIR__ . '/vendor/autoload.php';
    echo "<p>✅ Laravel autoloader loaded</p>";
    
    $app = require_once __DIR__ . '/bootstrap/app.php';
    echo "<p>✅ Laravel app loaded</p>";
    
    // Test a simple route
    $request = Illuminate\Http\Request::create('/', 'GET');
    $response = $app->handle($request);
    
    echo "<p>✅ Laravel request handling works</p>";
    echo "<p>Response status: " . $response->getStatusCode() . "</p>";
    echo "<p>Content length: " . strlen($response->getContent()) . "</p>";
    
} catch (Exception $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
}
?>
