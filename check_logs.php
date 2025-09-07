<?php
/**
 * Check Laravel Logs for Homepage Error
 */

echo "<h1>Laravel Logs Check</h1>";
echo "<hr>";

// Check if log file exists
$logFile = __DIR__ . '/storage/logs/laravel.log';
if (file_exists($logFile)) {
    echo "✅ Log file exists: $logFile<br>";
    
    // Get the last 50 lines of the log
    $lines = file($logFile);
    $lastLines = array_slice($lines, -50);
    
    echo "<h2>Last 50 lines of Laravel log:</h2>";
    echo "<pre style='background: #f5f5f5; padding: 10px; border: 1px solid #ddd; max-height: 400px; overflow-y: auto;'>";
    foreach ($lastLines as $line) {
        echo htmlspecialchars($line);
    }
    echo "</pre>";
    
} else {
    echo "❌ Log file not found: $logFile<br>";
    
    // Check if storage/logs directory exists
    $logsDir = __DIR__ . '/storage/logs';
    if (is_dir($logsDir)) {
        echo "✅ Logs directory exists<br>";
        
        // List files in logs directory
        $files = scandir($logsDir);
        echo "Files in logs directory:<br>";
        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
                echo "&nbsp;&nbsp;• $file<br>";
            }
        }
    } else {
        echo "❌ Logs directory not found<br>";
    }
}

// Check if we can write to logs directory
$logsDir = __DIR__ . '/storage/logs';
if (is_dir($logsDir)) {
    if (is_writable($logsDir)) {
        echo "✅ Logs directory is writable<br>";
    } else {
        echo "❌ Logs directory is NOT writable<br>";
    }
}

echo "<hr>";
echo "<h2>PHP Error Log</h2>";

// Check PHP error log
$phpErrorLog = ini_get('error_log');
if ($phpErrorLog && file_exists($phpErrorLog)) {
    echo "✅ PHP error log found: $phpErrorLog<br>";
    
    // Get last 20 lines
    $lines = file($phpErrorLog);
    $lastLines = array_slice($lines, -20);
    
    echo "<h3>Last 20 lines of PHP error log:</h3>";
    echo "<pre style='background: #f5f5f5; padding: 10px; border: 1px solid #ddd; max-height: 300px; overflow-y: auto;'>";
    foreach ($lastLines as $line) {
        echo htmlspecialchars($line);
    }
    echo "</pre>";
} else {
    echo "❌ PHP error log not found or not configured<br>";
}

echo "<hr>";
echo "<h2>System Information</h2>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Error Reporting: " . error_reporting() . "<br>";
echo "Display Errors: " . (ini_get('display_errors') ? 'On' : 'Off') . "<br>";
echo "Log Errors: " . (ini_get('log_errors') ? 'On' : 'Off') . "<br>";
echo "Error Log: " . (ini_get('error_log') ?: 'Not set') . "<br>";
?>
