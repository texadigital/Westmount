<?php
/**
 * Debug Homepage Route Specifically
 * Since everything else works, let's test the exact homepage route
 */

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Homepage Route Debug</h1>";
echo "<hr>";

try {
    // Load Laravel
    require_once __DIR__ . '/vendor/autoload.php';
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    
    echo "✅ Laravel loaded successfully<br>";
    
    echo "<h2>1. Test Homepage Route Directly</h2>";
    
    // Test the exact homepage route
    try {
        $request = Illuminate\Http\Request::create('/', 'GET');
        $response = app()->handle($request);
        
        if ($response->getStatusCode() === 200) {
            echo "✅ Homepage route returns 200 OK<br>";
            echo "&nbsp;&nbsp;Content length: " . strlen($response->getContent()) . " characters<br>";
        } else {
            echo "❌ Homepage route returns status: " . $response->getStatusCode() . "<br>";
        }
        
    } catch (Exception $e) {
        echo "❌ Homepage route failed: " . $e->getMessage() . "<br>";
        echo "&nbsp;&nbsp;File: " . $e->getFile() . "<br>";
        echo "&nbsp;&nbsp;Line: " . $e->getLine() . "<br>";
    }
    
    echo "<h2>2. Test HomeController Directly</h2>";
    
    try {
        $controller = new App\Http\Controllers\Public\HomeController();
        $request = new Illuminate\Http\Request();
        
        // Test the index method
        $response = $controller->index();
        
        if ($response instanceof Illuminate\View\View) {
            echo "✅ HomeController index method works<br>";
            echo "&nbsp;&nbsp;View: " . $response->getName() . "<br>";
            
            // Try to render the view
            $rendered = $response->render();
            echo "✅ View can be rendered (length: " . strlen($rendered) . ")<br>";
            
        } else {
            echo "❌ HomeController returned unexpected result<br>";
        }
        
    } catch (Exception $e) {
        echo "❌ HomeController failed: " . $e->getMessage() . "<br>";
        echo "&nbsp;&nbsp;File: " . $e->getFile() . "<br>";
        echo "&nbsp;&nbsp;Line: " . $e->getLine() . "<br>";
    }
    
    echo "<h2>3. Test View Files</h2>";
    
    // Check if view files exist and are readable
    $viewFiles = [
        'public.home' => 'resources/views/public/home.blade.php',
        'layouts.public' => 'resources/views/layouts/public.blade.php',
    ];
    
    foreach ($viewFiles as $viewName => $filePath) {
        $fullPath = base_path($filePath);
        if (file_exists($fullPath)) {
            if (is_readable($fullPath)) {
                echo "✅ $viewName exists and is readable<br>";
            } else {
                echo "❌ $viewName exists but is NOT readable<br>";
            }
        } else {
            echo "❌ $viewName file missing: $fullPath<br>";
        }
    }
    
    echo "<h2>4. Test Database Queries from HomeController</h2>";
    
    // Test the exact queries from HomeController
    try {
        $stats = [
            'total_members' => App\Models\Member::where('is_active', true)->count(),
            'total_funds' => App\Models\Fund::where('is_active', true)->sum('current_balance'),
            'years_active' => 25,
        ];
        echo "✅ Stats query works: " . json_encode($stats) . "<br>";
        
        $memberTypes = App\Models\MemberType::active()->get();
        echo "✅ Member types query works (count: " . $memberTypes->count() . ")<br>";
        
        $content = App\Models\PageContent::getPageContent('home');
        if ($content) {
            echo "✅ Page content query works<br>";
        } else {
            echo "⚠️ Page content query returns null (this is okay)<br>";
        }
        
    } catch (Exception $e) {
        echo "❌ Database queries failed: " . $e->getMessage() . "<br>";
    }
    
    echo "<h2>5. Test View Rendering with Data</h2>";
    
    try {
        $view = view('public.home', [
            'stats' => [
                'total_members' => 100,
                'total_funds' => 50000,
                'years_active' => 25,
            ],
            'memberTypes' => collect([]),
            'content' => (object) [
                'title' => 'Test Title',
                'content' => 'Test Content'
            ]
        ]);
        
        $rendered = $view->render();
        echo "✅ View rendering with test data works<br>";
        echo "&nbsp;&nbsp;Content length: " . strlen($rendered) . " characters<br>";
        
        // Check if the rendered content looks correct
        if (strpos($rendered, 'Association Westmount') !== false) {
            echo "✅ Rendered content contains expected text<br>";
        } else {
            echo "⚠️ Rendered content doesn't contain expected text<br>";
        }
        
    } catch (Exception $e) {
        echo "❌ View rendering failed: " . $e->getMessage() . "<br>";
        echo "&nbsp;&nbsp;File: " . $e->getFile() . "<br>";
        echo "&nbsp;&nbsp;Line: " . $e->getLine() . "<br>";
    }
    
    echo "<h2>6. Test Middleware Stack</h2>";
    
    // Check if there are any middleware issues
    try {
        $route = Route::getRoutes()->getByName('public.home');
        if ($route) {
            $middleware = $route->gatherMiddleware();
            echo "✅ Homepage route has " . count($middleware) . " middleware<br>";
            foreach ($middleware as $mw) {
                echo "&nbsp;&nbsp;• $mw<br>";
            }
        }
    } catch (Exception $e) {
        echo "❌ Middleware check failed: " . $e->getMessage() . "<br>";
    }
    
    echo "<h2>7. Test Memory and Limits</h2>";
    
    echo "Memory limit: " . ini_get('memory_limit') . "<br>";
    echo "Max execution time: " . ini_get('max_execution_time') . "<br>";
    echo "Current memory usage: " . memory_get_usage(true) . " bytes<br>";
    echo "Peak memory usage: " . memory_get_peak_usage(true) . " bytes<br>";
    
} catch (Exception $e) {
    echo "❌ Fatal error: " . $e->getMessage() . "<br>";
    echo "&nbsp;&nbsp;File: " . $e->getFile() . "<br>";
    echo "&nbsp;&nbsp;Line: " . $e->getLine() . "<br>";
}

echo "<hr>";
echo "<h2>If Everything Shows ✅ But Homepage Still Fails:</h2>";
echo "<p>This suggests the issue might be:</p>";
echo "<ul>";
echo "<li><strong>Browser cache:</strong> Try accessing in incognito/private mode</li>";
echo "<li><strong>CDN cache:</strong> If you're using a CDN, clear its cache</li>";
echo "<li><strong>Server cache:</strong> Hostinger might have server-side caching</li>";
echo "<li><strong>Specific browser issue:</strong> Try a different browser</li>";
echo "<li><strong>URL issue:</strong> Try accessing with www. or without www.</li>";
echo "</ul>";

echo "<h2>Quick Tests to Try:</h2>";
echo "<ol>";
echo "<li><strong>Clear browser cache</strong> and try again</li>";
echo "<li><strong>Try incognito/private mode</strong></li>";
echo "<li><strong>Try a different browser</strong></li>";
echo "<li><strong>Try accessing with www.associationwestmount.com</strong></li>";
echo "<li><strong>Check if other pages work</strong> (like /register)</li>";
echo "</ol>";
?>
