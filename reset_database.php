<?php
/**
 * Database Reset Script for Westmount Association
 * WARNING: This will delete ALL data in your database!
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

// Security check
if (app()->environment('production')) {
    die('This script is not allowed in production!');
}

// Password protection
$password = 'westmount2024';
if (!isset($_GET['password']) || $_GET['password'] !== $password) {
    echo '<h1>Database Reset - Westmount Association</h1>';
    echo '<p><strong>WARNING:</strong> This will delete ALL data!</p>';
    echo '<form method="GET">';
    echo '<input type="password" name="password" placeholder="Enter password" required>';
    echo '<button type="submit">Reset Database</button>';
    echo '</form>';
    exit;
}

echo '<h1>🔄 Database Reset in Progress...</h1>';

try {
    // Step 1: Drop all tables
    echo '<p>Step 1: Dropping all tables...</p>';
    $tables = DB::select('SHOW TABLES');
    $databaseName = DB::getDatabaseName();
    $tableKey = "Tables_in_$databaseName";
    
    foreach ($tables as $table) {
        $tableName = $table->$tableKey;
        if ($tableName !== 'migrations') {
            DB::statement("DROP TABLE IF EXISTS `$tableName`");
            echo "Dropped: $tableName<br>";
        }
    }
    
    // Step 2: Run migrations
    echo '<p>Step 2: Running migrations...</p>';
    Artisan::call('migrate:fresh', ['--force' => true, '--seed' => false]);
    echo '<p>Migrations completed!</p>';
    
    // Step 3: Create admin user
    echo '<p>Step 3: Creating admin user...</p>';
    DB::table('users')->insert([
        'name' => 'Admin Westmount',
        'email' => 'admin@westmount.ca',
        'email_verified_at' => now(),
        'password' => Hash::make('password123'),
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    echo '<p>Admin user created: admin@westmount.ca / password123</p>';
    
    // Step 4: Seed member types
    echo '<p>Step 4: Seeding member types...</p>';
    $memberTypes = [
        ['name' => 'Régulier', 'description' => 'Membre régulier', 'adhesion_fee' => 25.00, 'death_contribution' => 10.00, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Junior', 'description' => 'Membre junior', 'adhesion_fee' => 15.00, 'death_contribution' => 2.00, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Senior', 'description' => 'Membre senior', 'adhesion_fee' => 15.00, 'death_contribution' => 2.00, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Association', 'description' => 'Association', 'adhesion_fee' => 50.00, 'death_contribution' => 10.00, 'created_at' => now(), 'updated_at' => now()],
    ];
    DB::table('member_types')->insert($memberTypes);
    echo '<p>Member types seeded!</p>';
    
    // Step 5: Seed funds
    echo '<p>Step 5: Seeding funds...</p>';
    DB::table('funds')->insert([
        ['name' => 'Fonds Principal', 'description' => 'Fonds principal', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Fonds d\'Urgence', 'description' => 'Fonds d\'urgence', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
    ]);
    echo '<p>Funds seeded!</p>';
    
    // Step 6: Seed page content
    echo '<p>Step 6: Seeding page content...</p>';
    $pages = ['home', 'about', 'contact', 'services', 'death-contributions', 'sponsorship', 'online-management', 'technical-support', 'faq'];
    foreach ($pages as $page) {
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
    }
    echo '<p>Page content seeded!</p>';
    
    // Step 7: Seed bank settings
    echo '<p>Step 7: Seeding bank settings...</p>';
    $bankSettings = [
        ['key' => 'bank_name', 'value' => 'Association Westmount', 'type' => 'text', 'description' => 'Nom de la banque', 'group' => 'bank', 'sort_order' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ['key' => 'bank_account', 'value' => '1234567890', 'type' => 'text', 'description' => 'Numéro de compte', 'group' => 'bank', 'sort_order' => 2, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ['key' => 'bank_transit', 'value' => '00123', 'type' => 'text', 'description' => 'Numéro de transit', 'group' => 'bank', 'sort_order' => 3, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ['key' => 'bank_swift', 'value' => 'WESTCA1M', 'type' => 'text', 'description' => 'Code SWIFT', 'group' => 'bank', 'sort_order' => 4, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ['key' => 'bank_address', 'value' => '123 Rue Westmount, Montréal, QC H3Z 1A1', 'type' => 'text', 'description' => 'Adresse', 'group' => 'bank', 'sort_order' => 5, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ['key' => 'payment_currency', 'value' => 'CAD', 'type' => 'text', 'description' => 'Devise', 'group' => 'bank', 'sort_order' => 6, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ['key' => 'bank_instructions', 'value' => 'Inclure votre numéro de membre dans la référence.', 'type' => 'textarea', 'description' => 'Instructions', 'group' => 'bank', 'sort_order' => 7, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
    ];
    DB::table('settings')->insert($bankSettings);
    echo '<p>Bank settings seeded!</p>';
    
    // Clear caches
    echo '<p>Clearing caches...</p>';
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    
    echo '<h2>🎉 Database Reset Complete!</h2>';
    echo '<p><strong>Admin Login:</strong> admin@westmount.ca</p>';
    echo '<p><strong>Password:</strong> password123</p>';
    echo '<p><a href="/admin">Go to Admin Panel</a> | <a href="/">Go to Website</a></p>';
    
} catch (Exception $e) {
    echo '<h2>❌ Error occurred:</h2>';
    echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
}
?>