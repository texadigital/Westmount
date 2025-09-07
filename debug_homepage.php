<?php
/**
 * Debug Homepage 500 Error
 * This will help identify what's causing the homepage to fail
 */

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Homepage Debug - 500 Error Investigation</h1>";
echo "<hr>";

try {
    // Load Laravel
    require_once __DIR__ . '/vendor/autoload.php';
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    
    echo "✅ Laravel loaded successfully<br>";
    
    // Test database connection
    $pdo = DB::connection()->getPdo();
    echo "✅ Database connection successful<br>";
    
    echo "<h2>1. Testing Homepage Route</h2>";
    
    // Test the route
    $route = Route::getRoutes()->getByName('public.home');
    if ($route) {
        echo "✅ Homepage route exists<br>";
        echo "&nbsp;&nbsp;Controller: " . $route->getActionName() . "<br>";
    } else {
        echo "❌ Homepage route not found<br>";
    }
    
    echo "<h2>2. Testing HomeController</h2>";
    
    // Test if HomeController exists and can be instantiated
    try {
        $controller = new App\Http\Controllers\Public\HomeController();
        echo "✅ HomeController can be instantiated<br>";
        
        // Test the index method
        $request = new Illuminate\Http\Request();
        $response = $controller->index();
        
        if ($response instanceof Illuminate\View\View) {
            echo "✅ HomeController index method works<br>";
            echo "&nbsp;&nbsp;View: " . $response->getName() . "<br>";
        } else {
            echo "❌ HomeController index method returned unexpected result<br>";
        }
        
    } catch (Exception $e) {
        echo "❌ HomeController error: " . $e->getMessage() . "<br>";
        echo "&nbsp;&nbsp;File: " . $e->getFile() . "<br>";
        echo "&nbsp;&nbsp;Line: " . $e->getLine() . "<br>";
    }
    
    echo "<h2>3. Testing Required Models</h2>";
    
    // Test Member model
    try {
        $memberCount = App\Models\Member::count();
        echo "✅ Member model works (count: $memberCount)<br>";
    } catch (Exception $e) {
        echo "❌ Member model error: " . $e->getMessage() . "<br>";
    }
    
    // Test MemberType model
    try {
        $memberTypeCount = App\Models\MemberType::count();
        echo "✅ MemberType model works (count: $memberTypeCount)<br>";
    } catch (Exception $e) {
        echo "❌ MemberType model error: " . $e->getMessage() . "<br>";
    }
    
    // Test Fund model
    try {
        $fundCount = App\Models\Fund::count();
        echo "✅ Fund model works (count: $fundCount)<br>";
    } catch (Exception $e) {
        echo "❌ Fund model error: " . $e->getMessage() . "<br>";
    }
    
    // Test PageContent model
    try {
        $pageContentCount = App\Models\PageContent::count();
        echo "✅ PageContent model works (count: $pageContentCount)<br>";
    } catch (Exception $e) {
        echo "❌ PageContent model error: " . $e->getMessage() . "<br>";
    }
    
    echo "<h2>4. Testing View File</h2>";
    
    // Check if the view file exists
    $viewPath = resource_path('views/public/home.blade.php');
    if (file_exists($viewPath)) {
        echo "✅ Home view file exists<br>";
    } else {
        echo "❌ Home view file missing: $viewPath<br>";
    }
    
    // Check if the layout file exists
    $layoutPath = resource_path('views/layouts/public.blade.php');
    if (file_exists($layoutPath)) {
        echo "✅ Public layout file exists<br>";
    } else {
        echo "❌ Public layout file missing: $layoutPath<br>";
    }
    
    echo "<h2>5. Testing Database Queries</h2>";
    
    // Test the exact queries from HomeController
    try {
        $activeMembers = App\Models\Member::where('is_active', true)->count();
        echo "✅ Active members query works (count: $activeMembers)<br>";
    } catch (Exception $e) {
        echo "❌ Active members query error: " . $e->getMessage() . "<br>";
    }
    
    try {
        $totalFunds = App\Models\Fund::where('is_active', true)->sum('current_balance');
        echo "✅ Total funds query works (sum: $totalFunds)<br>";
    } catch (Exception $e) {
        echo "❌ Total funds query error: " . $e->getMessage() . "<br>";
    }
    
    try {
        $memberTypes = App\Models\MemberType::active()->get();
        echo "✅ Active member types query works (count: " . $memberTypes->count() . ")<br>";
    } catch (Exception $e) {
        echo "❌ Active member types query error: " . $e->getMessage() . "<br>";
    }
    
    try {
        $pageContent = App\Models\PageContent::getPageContent('home');
        if ($pageContent) {
            echo "✅ Home page content query works<br>";
        } else {
            echo "⚠️ Home page content query returns null (this is okay)<br>";
        }
    } catch (Exception $e) {
        echo "❌ Home page content query error: " . $e->getMessage() . "<br>";
    }
    
    echo "<h2>6. Testing View Rendering</h2>";
    
    try {
        // Test if we can render the view
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
        echo "✅ View can be rendered successfully<br>";
        echo "&nbsp;&nbsp;Content length: " . strlen($rendered) . " characters<br>";
        
    } catch (Exception $e) {
        echo "❌ View rendering error: " . $e->getMessage() . "<br>";
        echo "&nbsp;&nbsp;File: " . $e->getFile() . "<br>";
        echo "&nbsp;&nbsp;Line: " . $e->getLine() . "<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Fatal error: " . $e->getMessage() . "<br>";
    echo "&nbsp;&nbsp;File: " . $e->getFile() . "<br>";
    echo "&nbsp;&nbsp;Line: " . $e->getLine() . "<br>";
}

echo "<hr>";
echo "<h2>Debug Complete</h2>";
echo "<p>If you see any ❌ errors above, those are the issues preventing the homepage from working.</p>";
echo "<p>Most common fixes:</p>";
echo "<ul>";
echo "<li>Missing view files: Check if all Blade templates exist</li>";
echo "<li>Database errors: Check if all required tables exist</li>";
echo "<li>Model errors: Check if all models are properly defined</li>";
echo "<li>Permission errors: Check file permissions</li>";
echo "</ul>";
?>
