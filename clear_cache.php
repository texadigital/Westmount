<?php

/**
 * Laravel Cache Clearing Script
 * This script clears Laravel's configuration, route, and view caches
 */

echo "Starting Laravel cache clearing process...\n\n";

// Function to run a command and display output
function runCommand($command) {
    echo "Running: $command\n";
    $output = [];
    $returnCode = 0;
    
    exec($command . ' 2>&1', $output, $returnCode);
    
    if ($returnCode === 0) {
        echo "✅ Success!\n";
        if (!empty($output)) {
            echo "Output: " . implode("\n", $output) . "\n";
        }
    } else {
        echo "❌ Error (Code: $returnCode)\n";
        if (!empty($output)) {
            echo "Error: " . implode("\n", $output) . "\n";
        }
    }
    echo "\n";
}

// Check if we're in a Laravel project
if (!file_exists('artisan')) {
    echo "❌ Error: This doesn't appear to be a Laravel project (artisan file not found)\n";
    echo "Please run this script from your Laravel project root directory.\n";
    exit(1);
}

// Run the cache clearing commands
echo "1. Clearing configuration cache...\n";
runCommand('php artisan config:cache');

echo "2. Clearing route cache...\n";
runCommand('php artisan route:cache');

echo "3. Clearing view cache...\n";
runCommand('php artisan view:cache');

echo "4. Clearing general cache...\n";
runCommand('php artisan cache:clear');

echo "🎉 Cache clearing process completed!\n";
echo "\nYour Laravel application caches have been cleared and rebuilt.\n";
echo "The sponsorship section should now work properly.\n";
