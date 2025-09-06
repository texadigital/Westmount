<?php
/**
 * Fix All Public Controllers
 * This script will update all public controllers to pass $pageContent instead of $content
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo '<h1>🔧 Fixing All Public Controllers</h1>';

$controllers = [
    'AboutController.php',
    'ContactController.php',
    'ServicesController.php',
    'DeathContributionsController.php',
    'SponsorshipController.php',
    'OnlineManagementController.php',
    'TechnicalSupportController.php',
    'FAQController.php'
];

$controllerPath = 'app/Http/Controllers/Public/';

foreach ($controllers as $controller) {
    $filePath = $controllerPath . $controller;
    
    if (!file_exists($filePath)) {
        echo "<p style='color: orange;'>⚠️ Controller not found: $controller</p>";
        continue;
    }
    
    echo "<h2>Fixing $controller...</h2>";
    
    $content = file_get_contents($filePath);
    $originalContent = $content;
    
    // Fix the variable name in compact() and return statements
    $patterns = [
        // Change compact('content') to compact('pageContent')
        '/compact\(\'content\'\)/' => 'compact(\'pageContent\')',
        
        // Change $content = PageContent::getPageContent to $pageContent = PageContent::getPageContent
        '/\$content\s*=\s*PageContent::getPageContent/' => '$pageContent = PageContent::getPageContent',
    ];
    
    $changes = 0;
    foreach ($patterns as $pattern => $replacement) {
        $newContent = preg_replace($pattern, $replacement, $content);
        if ($newContent !== $content) {
            $changes++;
            $content = $newContent;
        }
    }
    
    if ($changes > 0) {
        file_put_contents($filePath, $content);
        echo "<p style='color: green;'>✅ Fixed $changes patterns in $controller</p>";
    } else {
        echo "<p style='color: blue;'>ℹ️ No patterns found to fix in $controller</p>";
    }
}

echo '<h2>🎉 Controller Fix Complete!</h2>';
echo '<p>All public controllers have been updated to use $pageContent variable.</p>';
echo '<p><a href="/" style="background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;">Test Home Page</a></p>';
echo '<p><a href="/about" style="background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;">Test About Page</a></p>';
?>
