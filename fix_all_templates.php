<?php
/**
 * Fix All Public Page Templates
 * This script will update all public page templates to use the new PageContent structure
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo '<h1>🔧 Fixing All Public Page Templates</h1>';

$templates = [
    'about.blade.php',
    'contact.blade.php', 
    'services.blade.php',
    'death-contributions.blade.php',
    'sponsorship.blade.php',
    'online-management.blade.php',
    'technical-support.blade.php',
    'faq.blade.php'
];

$templatePath = 'resources/views/public/';

foreach ($templates as $template) {
    $filePath = $templatePath . $template;
    
    if (!file_exists($filePath)) {
        echo "<p style='color: orange;'>⚠️ Template not found: $template</p>";
        continue;
    }
    
    echo "<h2>Fixing $template...</h2>";
    
    $content = file_get_contents($filePath);
    $originalContent = $content;
    
    // Fix the old content structure patterns
    $patterns = [
        // Hero section patterns
        '/\{\{\s*\$content\[\'hero\'\]->where\(\'key\',\s*\'title\'\)->first\(\)->value\s*\?\?\s*\'([^\']+)\'\s*\}\}/' => '{{ $pageContent->title ?? \'$1\' }}',
        '/\{\{\s*\$content\[\'hero\'\]->where\(\'key\',\s*\'subtitle\'\)->first\(\)->value\s*\?\?\s*\'([^\']+)\'\s*\}\}/' => '{{ $pageContent->content ? strip_tags($pageContent->content) : \'$1\' }}',
        '/\{\{\s*\$content\[\'hero\'\]->where\(\'key\',\s*\'description\'\)->first\(\)->value\s*\?\?\s*\'([^\']+)\'\s*\}\}/' => '{{ $pageContent->content ? strip_tags($pageContent->content) : \'$1\' }}',
        
        // General section patterns
        '/\{\{\s*\$content\[\'[^\']+\'\]->where\(\'key\',\s*\'title\'\)->first\(\)->value\s*\?\?\s*\'([^\']+)\'\s*\}\}/' => '{{ $pageContent->title ?? \'$1\' }}',
        '/\{\{\s*\$content\[\'[^\']+\'\]->where\(\'key\',\s*\'content\'\)->first\(\)->value\s*\?\?\s*\'([^\']+)\'\s*\}\}/' => '{{ $pageContent->content ? strip_tags($pageContent->content) : \'$1\' }}',
        '/\{\{\s*\$content\[\'[^\']+\'\]->where\(\'key\',\s*\'description\'\)->first\(\)->value\s*\?\?\s*\'([^\']+)\'\s*\}\}/' => '{{ $pageContent->content ? strip_tags($pageContent->content) : \'$1\' }}',
        
        // Any remaining content array patterns
        '/\{\{\s*\$content\[\'[^\']+\'\]->where\(\'key\',\s*\'[^\']+\'\)->first\(\)->value\s*\?\?\s*\'([^\']+)\'\s*\}\}/' => '{{ $pageContent->content ? strip_tags($pageContent->content) : \'$1\' }}',
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
        echo "<p style='color: green;'>✅ Fixed $changes patterns in $template</p>";
    } else {
        echo "<p style='color: blue;'>ℹ️ No patterns found to fix in $template</p>";
    }
}

echo '<h2>🎉 Template Fix Complete!</h2>';
echo '<p>All public page templates have been updated to use the new PageContent structure.</p>';
echo '<p><a href="/" style="background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;">Test Home Page</a></p>';
echo '<p><a href="/about" style="background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;">Test About Page</a></p>';
?>

