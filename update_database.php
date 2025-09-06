<?php
/**
 * Database Update Script for Westmount Association
 * This script will update existing database with new tables and data
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);
set_time_limit(300);

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;

// Password protection
$password = 'westmount2024';
if (!isset($_GET['password']) || $_GET['password'] !== $password) {
    echo '<h1>Database Update - Westmount Association</h1>';
    echo '<p>This will update your database with new tables and data.</p>';
    echo '<form method="GET">';
    echo '<input type="password" name="password" placeholder="Enter password" required>';
    echo '<button type="submit">Update Database</button>';
    echo '</form>';
    exit;
}

echo '<h1>🔄 Database Update in Progress...</h1>';

try {
    // Step 1: Run migrations
    echo '<p>Step 1: Running migrations...</p>';
    Artisan::call('migrate', ['--force' => true]);
    echo '<p>Migrations completed!</p>';
    
    // Step 2: Check if admin user exists, create if not
    echo '<p>Step 2: Checking admin user...</p>';
    $adminExists = DB::table('users')->where('email', 'admin@westmount.ca')->exists();
    if (!$adminExists) {
        DB::table('users')->insert([
            'name' => 'Admin Westmount',
            'email' => 'admin@westmount.ca',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        echo '<p>Admin user created: admin@westmount.ca / password123</p>';
    } else {
        echo '<p>Admin user already exists</p>';
    }
    
    // Step 3: Update member types with correct values
    echo '<p>Step 3: Updating member types...</p>';
    $memberTypes = [
        'Régulier' => ['adhesion_fee' => 25.00, 'death_contribution' => 10.00],
        'Junior' => ['adhesion_fee' => 15.00, 'death_contribution' => 2.00],
        'Senior' => ['adhesion_fee' => 15.00, 'death_contribution' => 2.00],
        'Association' => ['adhesion_fee' => 50.00, 'death_contribution' => 10.00],
    ];
    
    foreach ($memberTypes as $name => $data) {
        DB::table('member_types')->updateOrInsert(
            ['name' => $name],
            array_merge($data, ['updated_at' => now()])
        );
    }
    echo '<p>Member types updated!</p>';
    
    // Step 4: Update payment methods in existing payments
    echo '<p>Step 4: Updating payment methods...</p>';
    DB::table('payments')->where('payment_method', 'cash')->update(['payment_method' => 'interac']);
    echo '<p>Payment methods updated!</p>';
    
    // Step 5: Seed page content if not exists
    echo '<p>Step 5: Adding page content...</p>';
    $pages = ['home', 'about', 'contact', 'services', 'death-contributions', 'sponsorship', 'online-management', 'technical-support', 'faq'];
    foreach ($pages as $page) {
        $exists = DB::table('page_contents')->where('page', $page)->exists();
        if (!$exists) {
            DB::table('page_contents')->insert([
                'page' => $page,
                'title' => ucfirst(str_replace('-', ' ', $page)),
                'content' => '<h2>' . ucfirst(str_replace('-', ' ', $page)) . '</h2><p>Contenu de la page ' . $page . '</p>',
                'meta_title' => ucfirst(str_replace('-', ' ', $page)) . ' - Association Westmount',
                'meta_description' => 'Page ' . $page . ' de l\'Association Westmount',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            echo "Added page: $page<br>";
        }
    }
    echo '<p>Page content updated!</p>';
    
    // Step 6: Update bank settings
    echo '<p>Step 6: Updating bank settings...</p>';
    $bankSettings = [
        'bank_name' => 'Association Westmount',
        'bank_account' => '1234567890',
        'bank_transit' => '00123',
        'bank_swift' => 'WESTCA1M',
        'bank_address' => '123 Rue Westmount, Montréal, QC H3Z 1A1',
        'payment_currency' => 'CAD',
        'bank_instructions' => 'Inclure votre numéro de membre dans la référence.',
    ];
    
    foreach ($bankSettings as $key => $value) {
        DB::table('settings')->updateOrInsert(
            ['key' => $key],
            [
                'value' => $value,
                'type' => 'text',
                'description' => 'Bank setting',
                'group' => 'bank',
                'sort_order' => 1,
                'is_active' => true,
                'updated_at' => now(),
            ]
        );
    }
    echo '<p>Bank settings updated!</p>';
    
    // Clear caches
    echo '<p>Clearing caches...</p>';
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    
    echo '<h2>🎉 Database Update Complete!</h2>';
    echo '<p>Your database has been successfully updated with all new features.</p>';
    echo '<p><a href="/admin">Go to Admin Panel</a> | <a href="/">Go to Website</a></p>';
    
} catch (Exception $e) {
    echo '<h2>❌ Error occurred:</h2>';
    echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
}
?>
