<?php
/**
 * Complete Fix for All Public Pages
 * This script will fix all templates and controllers to use the new PageContent structure
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo '<h1>🔧 Complete Fix for All Public Pages</h1>';

// Step 1: Fix Controllers
echo '<h2>Step 1: Fixing Controllers...</h2>';

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
    
    $content = file_get_contents($filePath);
    
    // Fix the variable name in compact() and return statements
    $patterns = [
        '/compact\(\'content\'\)/' => 'compact(\'pageContent\')',
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
        echo "<p style='color: green;'>✅ Fixed $controller ($changes changes)</p>";
    } else {
        echo "<p style='color: blue;'>ℹ️ $controller already correct</p>";
    }
}

// Step 2: Fix Templates
echo '<h2>Step 2: Fixing Templates...</h2>';

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
    
    $content = file_get_contents($filePath);
    
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
        echo "<p style='color: green;'>✅ Fixed $template ($changes changes)</p>";
    } else {
        echo "<p style='color: blue;'>ℹ️ $template already correct</p>";
    }
}

// Step 3: Test the fixes
echo '<h2>Step 3: Testing Fixes...</h2>';

try {
    // Bootstrap Laravel
    require_once __DIR__ . '/vendor/autoload.php';
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    use App\Models\PageContent;
    
    echo '<p style="color: green;">✅ Laravel bootstrapped successfully!</p>';
    
    // Test getting page content
    $pages = ['home', 'about', 'contact', 'services', 'death-contributions', 'sponsorship', 'online-management', 'technical-support', 'faq'];
    
    foreach ($pages as $page) {
        try {
            $content = PageContent::where('page', $page)->where('is_active', true)->first();
            if ($content) {
                echo "<p style='color: green;'>✅ $page page content loaded</p>";
            } else {
                echo "<p style='color: orange;'>⚠️ No content found for $page page</p>";
            }
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ Error loading $page: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }
    
} catch (Exception $e) {
    echo '<p style="color: red;">❌ Laravel bootstrap failed: ' . htmlspecialchars($e->getMessage()) . '</p>';
}

echo '<h2>🎉 Fix Complete!</h2>';
echo '<p>All public pages have been fixed to use the new PageContent structure.</p>';
echo '<p><strong>Test your pages:</strong></p>';
echo '<ul>';
echo '<li><a href="/" style="color: #007bff;">Home Page</a></li>';
echo '<li><a href="/about" style="color: #007bff;">About Page</a></li>';
echo '<li><a href="/contact" style="color: #007bff;">Contact Page</a></li>';
echo '<li><a href="/services" style="color: #007bff;">Services Page</a></li>';
echo '<li><a href="/contributions-deces" style="color: #007bff;">Death Contributions Page</a></li>';
echo '<li><a href="/parrainage" style="color: #007bff;">Sponsorship Page</a></li>';
echo '<li><a href="/gestion-en-ligne" style="color: #007bff;">Online Management Page</a></li>';
echo '<li><a href="/support-technique" style="color: #007bff;">Technical Support Page</a></li>';
echo '<li><a href="/faq" style="color: #007bff;">FAQ Page</a></li>';
echo '</ul>';
?>

