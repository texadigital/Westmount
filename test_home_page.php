<?php
/**
 * Test Home Page Fix
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo '<h1>🧪 Testing Home Page Fix</h1>';

try {
    // Bootstrap Laravel
    require_once __DIR__ . '/vendor/autoload.php';
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    use App\Models\Member;
    use App\Models\MemberType;
    use App\Models\Fund;
    use App\Models\PageContent;
    
    echo '<p style="color: green;">✅ Laravel bootstrapped successfully!</p>';
    
    // Test the home controller logic
    echo '<h2>Testing Home Controller Logic:</h2>';
    
    try {
        // Get statistics
        $stats = [
            'total_members' => Member::where('is_active', true)->count(),
            'total_funds' => Fund::where('is_active', true)->sum('current_balance'),
            'years_active' => 25,
        ];
        
        echo '<p>✅ Statistics loaded:</p>';
        echo '<ul>';
        echo '<li>Total members: ' . $stats['total_members'] . '</li>';
        echo '<li>Total funds: $' . number_format($stats['total_funds'], 2) . '</li>';
        echo '<li>Years active: ' . $stats['years_active'] . '</li>';
        echo '</ul>';
        
        // Get member types
        $memberTypes = MemberType::where('is_active', true)->get();
        echo '<p>✅ Member types loaded: ' . count($memberTypes) . '</p>';
        
        // Get page content
        $content = PageContent::where('page', 'home')->where('is_active', true)->first();
        
        if ($content) {
            echo '<p>✅ Home page content loaded:</p>';
            echo '<ul>';
            echo '<li>Title: ' . htmlspecialchars($content->title) . '</li>';
            echo '<li>Content: ' . htmlspecialchars(substr($content->content, 0, 100)) . '...</li>';
            echo '<li>Meta Title: ' . htmlspecialchars($content->meta_title) . '</li>';
            echo '</ul>';
        } else {
            echo '<p style="color: orange;">⚠️ No home page content found</p>';
        }
        
        // Test the view rendering
        echo '<h2>Testing View Rendering:</h2>';
        
        try {
            $view = view('public.home', compact('stats', 'memberTypes', 'content'));
            $html = $view->render();
            echo '<p style="color: green;">✅ View rendered successfully!</p>';
            echo '<p>HTML length: ' . strlen($html) . ' characters</p>';
            
            // Check if the problematic content is in the HTML
            if (strpos($html, 'Solidarité & Entraide') !== false) {
                echo '<p style="color: green;">✅ Hero title found in rendered HTML</p>';
            } else {
                echo '<p style="color: orange;">⚠️ Hero title not found in rendered HTML</p>';
            }
            
        } catch (Exception $e) {
            echo '<p style="color: red;">❌ View rendering failed: ' . htmlspecialchars($e->getMessage()) . '</p>';
        }
        
    } catch (Exception $e) {
        echo '<p style="color: red;">❌ Controller logic failed: ' . htmlspecialchars($e->getMessage()) . '</p>';
        echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
    }
    
    echo '<h2>🎉 Test Complete!</h2>';
    echo '<p>If all tests passed, your home page should now work.</p>';
    echo '<p><a href="/" style="background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;">Test Home Page</a></p>';
    
} catch (Exception $e) {
    echo '<p style="color: red;">❌ Bootstrap error: ' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
}
?>
