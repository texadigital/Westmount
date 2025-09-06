<?php
/**
 * Test Page Content Model
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo '<h1>🧪 Testing Page Content Model</h1>';

try {
    // Bootstrap Laravel
    require_once __DIR__ . '/vendor/autoload.php';
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    use App\Models\PageContent;
    
    echo '<p style="color: green;">✅ Laravel bootstrapped successfully!</p>';
    
    // Test getting home page content
    echo '<h2>Testing Home Page Content:</h2>';
    
    try {
        $homeContent = PageContent::getPageContent('home');
        
        if ($homeContent) {
            echo '<p style="color: green;">✅ Home page content found!</p>';
            echo '<h3>Content Details:</h3>';
            echo '<ul>';
            echo '<li><strong>Page:</strong> ' . htmlspecialchars($homeContent->page) . '</li>';
            echo '<li><strong>Title:</strong> ' . htmlspecialchars($homeContent->title) . '</li>';
            echo '<li><strong>Content:</strong> ' . htmlspecialchars(substr($homeContent->content, 0, 100)) . '...</li>';
            echo '<li><strong>Meta Title:</strong> ' . htmlspecialchars($homeContent->meta_title) . '</li>';
            echo '<li><strong>Meta Description:</strong> ' . htmlspecialchars($homeContent->meta_description) . '</li>';
            echo '<li><strong>Active:</strong> ' . ($homeContent->is_active ? 'Yes' : 'No') . '</li>';
            echo '</ul>';
        } else {
            echo '<p style="color: orange;">⚠️ No home page content found</p>';
        }
        
    } catch (Exception $e) {
        echo '<p style="color: red;">❌ Error getting home content: ' . htmlspecialchars($e->getMessage()) . '</p>';
    }
    
    // Test getting all pages
    echo '<h2>Testing All Pages:</h2>';
    
    try {
        $allPages = PageContent::getAllPages();
        
        echo '<p style="color: green;">✅ Found ' . count($allPages) . ' pages</p>';
        echo '<ul>';
        foreach ($allPages as $page) {
            echo '<li>' . htmlspecialchars($page->page) . ' - ' . htmlspecialchars($page->title) . '</li>';
        }
        echo '</ul>';
        
    } catch (Exception $e) {
        echo '<p style="color: red;">❌ Error getting all pages: ' . htmlspecialchars($e->getMessage()) . '</p>';
    }
    
    // Test direct database query
    echo '<h2>Testing Direct Database Query:</h2>';
    
    try {
        $pages = DB::table('page_contents')
            ->where('page', 'home')
            ->where('is_active', true)
            ->first();
            
        if ($pages) {
            echo '<p style="color: green;">✅ Direct query successful!</p>';
            echo '<p><strong>Title:</strong> ' . htmlspecialchars($pages->title) . '</p>';
        } else {
            echo '<p style="color: orange;">⚠️ No home page found in direct query</p>';
        }
        
    } catch (Exception $e) {
        echo '<p style="color: red;">❌ Direct query failed: ' . htmlspecialchars($e->getMessage()) . '</p>';
    }
    
    echo '<h2>🎉 Test Complete!</h2>';
    echo '<p>If all tests passed, your website should now work properly.</p>';
    echo '<p><a href="/" style="background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;">Test Website</a></p>';
    
} catch (Exception $e) {
    echo '<p style="color: red;">❌ Bootstrap error: ' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
}
?>
