<?php
/**
 * Direct Database Update Script
 * This script bypasses Laravel and directly updates the database
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo '<h1>🔄 Direct Database Update</h1>';

// Your Hostinger database credentials
$dbHost = 'localhost';
$dbPort = '3306';
$dbDatabase = 'u460961786_westmount_asso';
$dbUsername = 'u460961786_westmount_asso';
$dbPassword = 'Ayuk.texa1';

try {
    // Connect to database
    $pdo = new PDO(
        "mysql:host=$dbHost;port=$dbPort;dbname=$dbDatabase;charset=utf8mb4",
        $dbUsername,
        $dbPassword,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
    
    echo '<p style="color: green;">✅ Database connection successful!</p>';
    
    // Step 1: Update member types with correct values
    echo '<h2>Step 1: Updating member types...</h2>';
    
    $memberTypes = [
        'Régulier' => ['adhesion_fee' => 25.00, 'death_contribution' => 10.00],
        'Junior' => ['adhesion_fee' => 15.00, 'death_contribution' => 2.00],
        'Senior' => ['adhesion_fee' => 15.00, 'death_contribution' => 2.00],
        'Association' => ['adhesion_fee' => 50.00, 'death_contribution' => 10.00],
    ];
    
    foreach ($memberTypes as $name => $data) {
        $stmt = $pdo->prepare("UPDATE member_types SET adhesion_fee = ?, death_contribution = ?, updated_at = NOW() WHERE name = ?");
        $stmt->execute([$data['adhesion_fee'], $data['death_contribution'], $name]);
        echo "<p>Updated member type: $name</p>";
    }
    
    // Step 2: Update payment methods (remove cash, add interac)
    echo '<h2>Step 2: Updating payment methods...</h2>';
    
    $stmt = $pdo->prepare("UPDATE payments SET payment_method = 'interac' WHERE payment_method = 'cash'");
    $stmt->execute();
    $affectedRows = $stmt->rowCount();
    echo "<p>Updated $affectedRows payments from 'cash' to 'interac'</p>";
    
    // Step 3: Add page content if not exists
    echo '<h2>Step 3: Adding page content...</h2>';
    
    $pages = [
        'home' => 'Bienvenue à l\'Association Westmount',
        'about' => 'À Propos de Nous',
        'contact' => 'Contactez-Nous',
        'services' => 'Nos Services',
        'death-contributions' => 'Contributions Décès',
        'sponsorship' => 'Système de Parrainage',
        'online-management' => 'Gestion en Ligne',
        'technical-support' => 'Support Technique',
        'faq' => 'FAQ',
    ];
    
    foreach ($pages as $page => $title) {
        // Check if page exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM page_contents WHERE page = ?");
        $stmt->execute([$page]);
        $exists = $stmt->fetchColumn() > 0;
        
        if (!$exists) {
            $stmt = $pdo->prepare("INSERT INTO page_contents (page, title, content, meta_title, meta_description, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, ?, 1, NOW(), NOW())");
            $content = "<h2>$title</h2><p>Contenu de la page $page</p>";
            $metaTitle = "$title - Association Westmount";
            $metaDescription = "Page $page de l'Association Westmount";
            
            $stmt->execute([$page, $title, $content, $metaTitle, $metaDescription]);
            echo "<p>Added page: $page</p>";
        } else {
            echo "<p>Page already exists: $page</p>";
        }
    }
    
    // Step 4: Update bank settings
    echo '<h2>Step 4: Updating bank settings...</h2>';
    
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
        // Check if setting exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM settings WHERE `key` = ?");
        $stmt->execute([$key]);
        $exists = $stmt->fetchColumn() > 0;
        
        if ($exists) {
            $stmt = $pdo->prepare("UPDATE settings SET `value` = ?, updated_at = NOW() WHERE `key` = ?");
            $stmt->execute([$value, $key]);
            echo "<p>Updated setting: $key</p>";
        } else {
            $stmt = $pdo->prepare("INSERT INTO settings (`key`, `value`, `type`, `description`, `group`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES (?, ?, 'text', 'Bank setting', 'bank', 1, 1, NOW(), NOW())");
            $stmt->execute([$key, $value]);
            echo "<p>Added setting: $key</p>";
        }
    }
    
    // Step 5: Check if admin user exists
    echo '<h2>Step 5: Checking admin user...</h2>';
    
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = 'admin@westmount.ca'");
    $stmt->execute();
    $adminExists = $stmt->fetchColumn() > 0;
    
    if (!$adminExists) {
        $hashedPassword = password_hash('password123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, email_verified_at, password, created_at, updated_at) VALUES (?, ?, NOW(), ?, NOW(), NOW())");
        $stmt->execute(['Admin Westmount', 'admin@westmount.ca', $hashedPassword]);
        echo "<p>Created admin user: admin@westmount.ca / password123</p>";
    } else {
        echo "<p>Admin user already exists</p>";
    }
    
    // Step 6: Verify updates
    echo '<h2>Step 6: Verifying updates...</h2>';
    
    // Check member types
    $stmt = $pdo->query("SELECT name, adhesion_fee, death_contribution FROM member_types ORDER BY name");
    $memberTypes = $stmt->fetchAll();
    echo '<h3>Member Types:</h3><ul>';
    foreach ($memberTypes as $type) {
        echo '<li>' . htmlspecialchars($type['name']) . ' - Adhesion: $' . $type['adhesion_fee'] . ', Contribution: $' . $type['death_contribution'] . '</li>';
    }
    echo '</ul>';
    
    // Check page content
    $stmt = $pdo->query("SELECT page, title FROM page_contents ORDER BY page");
    $pages = $stmt->fetchAll();
    echo '<h3>Page Content:</h3><ul>';
    foreach ($pages as $page) {
        echo '<li>' . htmlspecialchars($page['page']) . ' - ' . htmlspecialchars($page['title']) . '</li>';
    }
    echo '</ul>';
    
    // Check bank settings
    $stmt = $pdo->query("SELECT `key`, `value` FROM settings WHERE `group` = 'bank' ORDER BY `key`");
    $settings = $stmt->fetchAll();
    echo '<h3>Bank Settings:</h3><ul>';
    foreach ($settings as $setting) {
        $value = $setting['key'] === 'bank_instructions' ? $setting['value'] : (strlen($setting['value']) > 20 ? substr($setting['value'], 0, 20) . '...' : $setting['value']);
        echo '<li>' . htmlspecialchars($setting['key']) . ': ' . htmlspecialchars($value) . '</li>';
    }
    echo '</ul>';
    
    echo '<h2>🎉 Database Update Complete!</h2>';
    echo '<p>All updates have been applied successfully.</p>';
    echo '<p><a href="/" style="background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;">Go to Website</a></p>';
    echo '<p><a href="/admin" style="background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;">Go to Admin Panel</a></p>';
    
} catch (Exception $e) {
    echo '<p style="color: red;">❌ Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
}
?>
