<?php
/**
 * Debug Session Issue - Find the Real Problem
 * Since this was working before, let's find what changed
 */

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Session Issue Debug - What Changed?</h1>";
echo "<hr>";

try {
    // Load Laravel
    require_once __DIR__ . '/vendor/autoload.php';
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    
    echo "✅ Laravel loaded successfully<br>";
    
    echo "<h2>1. Check Sessions Table</h2>";
    
    // Check if sessions table exists
    try {
        $sessions = DB::table('sessions')->count();
        echo "✅ Sessions table exists and has $sessions records<br>";
    } catch (Exception $e) {
        echo "❌ Sessions table issue: " . $e->getMessage() . "<br>";
        
        // Check if table exists at all
        try {
            $tables = DB::select("SHOW TABLES LIKE 'sessions'");
            if (count($tables) > 0) {
                echo "&nbsp;&nbsp;✅ Sessions table exists but has structure issues<br>";
                
                // Check table structure
                $structure = DB::select("DESCRIBE sessions");
                echo "&nbsp;&nbsp;Table structure:<br>";
                foreach ($structure as $column) {
                    echo "&nbsp;&nbsp;&nbsp;&nbsp;• " . $column->Field . " (" . $column->Type . ")<br>";
                }
            } else {
                echo "&nbsp;&nbsp;❌ Sessions table does not exist<br>";
            }
        } catch (Exception $e2) {
            echo "&nbsp;&nbsp;❌ Cannot check table existence: " . $e2->getMessage() . "<br>";
        }
    }
    
    echo "<h2>2. Test Session Creation</h2>";
    
    // Test if we can create a session
    try {
        $sessionId = 'test_' . time();
        $sessionData = [
            'id' => $sessionId,
            'user_id' => null,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Test Agent',
            'payload' => base64_encode(serialize(['test' => 'data'])),
            'last_activity' => time(),
        ];
        
        DB::table('sessions')->insert($sessionData);
        echo "✅ Can insert session data<br>";
        
        // Test if we can read it back
        $retrieved = DB::table('sessions')->where('id', $sessionId)->first();
        if ($retrieved) {
            echo "✅ Can retrieve session data<br>";
        } else {
            echo "❌ Cannot retrieve session data<br>";
        }
        
        // Clean up test session
        DB::table('sessions')->where('id', $sessionId)->delete();
        echo "✅ Test session cleaned up<br>";
        
    } catch (Exception $e) {
        echo "❌ Session creation test failed: " . $e->getMessage() . "<br>";
        echo "&nbsp;&nbsp;File: " . $e->getFile() . "<br>";
        echo "&nbsp;&nbsp;Line: " . $e->getLine() . "<br>";
    }
    
    echo "<h2>3. Check Laravel Session Configuration</h2>";
    
    echo "Session Driver: " . config('session.driver') . "<br>";
    echo "Session Lifetime: " . config('session.lifetime') . "<br>";
    echo "Session Encrypt: " . (config('session.encrypt') ? 'true' : 'false') . "<br>";
    echo "Session Path: " . config('session.path') . "<br>";
    echo "Session Domain: " . (config('session.domain') ?: 'null') . "<br>";
    
    echo "<h2>4. Test Simple Route Without Sessions</h2>";
    
    // Test if we can access a simple route
    try {
        $request = Illuminate\Http\Request::create('/');
        $response = app()->handle($request);
        echo "✅ Simple request handling works<br>";
    } catch (Exception $e) {
        echo "❌ Simple request failed: " . $e->getMessage() . "<br>";
    }
    
    echo "<h2>5. Check Recent Changes</h2>";
    
    // Check if there are any recent error logs
    $logFile = __DIR__ . '/storage/logs/laravel.log';
    if (file_exists($logFile)) {
        $lines = file($logFile);
        $recentLines = array_slice($lines, -20);
        
        echo "Recent log entries:<br>";
        echo "<pre style='background: #f5f5f5; padding: 10px; border: 1px solid #ddd; max-height: 200px; overflow-y: auto;'>";
        foreach ($recentLines as $line) {
            echo htmlspecialchars($line);
        }
        echo "</pre>";
    }
    
    echo "<h2>6. Test Database Connection</h2>";
    
    try {
        $pdo = DB::connection()->getPdo();
        echo "✅ Database connection works<br>";
        
        // Test a simple query
        $result = DB::select("SELECT 1 as test");
        echo "✅ Simple database query works<br>";
        
    } catch (Exception $e) {
        echo "❌ Database connection issue: " . $e->getMessage() . "<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Fatal error: " . $e->getMessage() . "<br>";
    echo "&nbsp;&nbsp;File: " . $e->getFile() . "<br>";
    echo "&nbsp;&nbsp;Line: " . $e->getLine() . "<br>";
}

echo "<hr>";
echo "<h2>Possible Causes Since It Was Working Before:</h2>";
echo "<ul>";
echo "<li><strong>Database changes:</strong> Sessions table structure changed or corrupted</li>";
echo "<li><strong>File permissions:</strong> Laravel can't write to storage directory</li>";
echo "<li><strong>Configuration changes:</strong> .env file was modified</li>";
echo "<li><strong>Code updates:</strong> Recent code changes broke something</li>";
echo "<li><strong>Server changes:</strong> Hosting provider made changes</li>";
echo "<li><strong>Cache issues:</strong> Corrupted cache files</li>";
echo "</ul>";

echo "<h2>Quick Fixes to Try:</h2>";
echo "<ol>";
echo "<li><strong>Clear all caches:</strong> Delete bootstrap/cache/ and storage/framework/cache/</li>";
echo "<li><strong>Check file permissions:</strong> Make sure storage/ is writable</li>";
echo "<li><strong>Recreate sessions table:</strong> Drop and recreate the sessions table</li>";
echo "<li><strong>Check recent uploads:</strong> See if any recent file uploads broke something</li>";
echo "</ol>";
?>
