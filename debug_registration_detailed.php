<?php
/**
 * Detailed Registration Debug
 * This will test the exact registration process step by step
 */

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Detailed Registration Debug</h1>";
echo "<hr>";

// Load Laravel
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Member;
use App\Models\MemberType;
use App\Models\Membership;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

echo "<h2>1. Testing Registration Process Step by Step</h2>";

try {
    // Test data (using the same data from your form)
    $testData = [
        'first_name' => 'Ayuk',
        'last_name' => 'Ndip',
        'birth_date' => '2000-01-06', // Fixed date format
        'phone' => '674226623',
        'email' => 'test' . time() . '@example.com', // Unique email
        'address' => 'Buea',
        'city' => 'limbe',
        'province' => 'quebec', // Fixed spelling
        'postal_code' => 'M3K',
        'country' => 'Cameroon',
        'canadian_status_proof' => 'citizen',
        'member_type_id' => 1, // régulier
        'pin_code' => '1234',
        'pin_code_confirmation' => '1234',
    ];

    echo "<h3>Step 1: Validate Member Type</h3>";
    $memberType = MemberType::find($testData['member_type_id']);
    if ($memberType) {
        echo "✅ Member type found: " . $memberType->name . "<br>";
        echo "&nbsp;&nbsp;Adhesion fee: $" . $memberType->adhesion_fee . "<br>";
        echo "&nbsp;&nbsp;Death contribution: $" . $memberType->death_contribution . "<br>";
        
        // Check age validation
        $age = Carbon::parse($testData['birth_date'])->age;
        echo "&nbsp;&nbsp;Calculated age: $age<br>";
        
        if ($memberType->isValidAge($age)) {
            echo "✅ Age validation passed<br>";
        } else {
            echo "❌ Age validation failed<br>";
        }
    } else {
        echo "❌ Member type not found<br>";
    }

    echo "<h3>Step 2: Test Member Number Generation</h3>";
    $memberNumber = Member::generateMemberNumber();
    echo "✅ Generated member number: $memberNumber<br>";

    echo "<h3>Step 3: Test Database Transaction</h3>";
    
    DB::beginTransaction();
    
    try {
        // Create member
        echo "&nbsp;&nbsp;Creating member...<br>";
        $member = Member::create([
            'member_number' => $memberNumber,
            'pin_code' => $testData['pin_code'],
            'first_name' => $testData['first_name'],
            'last_name' => $testData['last_name'],
            'birth_date' => $testData['birth_date'],
            'phone' => $testData['phone'],
            'email' => $testData['email'],
            'address' => $testData['address'],
            'city' => $testData['city'],
            'province' => $testData['province'],
            'postal_code' => $testData['postal_code'],
            'country' => $testData['country'],
            'canadian_status_proof' => $testData['canadian_status_proof'],
            'member_type_id' => $testData['member_type_id'],
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        
        echo "✅ Member created successfully (ID: " . $member->id . ")<br>";

        // Create membership
        echo "&nbsp;&nbsp;Creating membership...<br>";
        $membership = Membership::create([
            'member_id' => $member->id,
            'status' => 'active',
            'start_date' => now(),
            'end_date' => now()->addYear(),
            'is_active' => true,
        ]);
        
        echo "✅ Membership created successfully (ID: " . $membership->id . ")<br>";

        // Create payment
        echo "&nbsp;&nbsp;Creating payment...<br>";
        $payment = Payment::create([
            'member_id' => $member->id,
            'membership_id' => $membership->id,
            'type' => 'adhesion',
            'amount' => $memberType->adhesion_fee,
            'currency' => 'CAD',
            'status' => 'pending',
            'payment_method' => 'pending',
            'description' => 'Paiement d\'adhésion initial',
        ]);
        
        echo "✅ Payment created successfully (ID: " . $payment->id . ")<br>";

        DB::commit();
        echo "✅ Database transaction completed successfully<br>";

        // Clean up test data
        echo "<h3>Step 4: Cleaning up test data</h3>";
        $member->delete();
        echo "✅ Test data cleaned up<br>";

    } catch (Exception $e) {
        DB::rollBack();
        echo "❌ Database transaction failed: " . $e->getMessage() . "<br>";
        echo "&nbsp;&nbsp;File: " . $e->getFile() . "<br>";
        echo "&nbsp;&nbsp;Line: " . $e->getLine() . "<br>";
    }

} catch (Exception $e) {
    echo "❌ Registration process failed: " . $e->getMessage() . "<br>";
    echo "&nbsp;&nbsp;File: " . $e->getFile() . "<br>";
    echo "&nbsp;&nbsp;Line: " . $e->getLine() . "<br>";
}

echo "<hr>";

echo "<h2>2. Check for Common Issues</h2>";

// Check if Member model has the generateMemberNumber method
echo "<h3>Member Model Methods</h3>";
if (method_exists(Member::class, 'generateMemberNumber')) {
    echo "✅ generateMemberNumber method exists<br>";
} else {
    echo "❌ generateMemberNumber method missing<br>";
}

// Check if MemberType model has the isValidAge method
echo "<h3>MemberType Model Methods</h3>";
if (method_exists(MemberType::class, 'isValidAge')) {
    echo "✅ isValidAge method exists<br>";
} else {
    echo "❌ isValidAge method missing<br>";
}

// Check database constraints
echo "<h3>Database Constraints</h3>";
try {
    $tables = ['members', 'memberships', 'payments'];
    foreach ($tables as $table) {
        $constraints = DB::select("SHOW CREATE TABLE $table");
        echo "✅ Table $table structure loaded<br>";
    }
} catch (Exception $e) {
    echo "❌ Error checking table constraints: " . $e->getMessage() . "<br>";
}

echo "<hr>";
echo "<h2>Debug Complete</h2>";
echo "<p>If you see any ❌ errors above, those are the issues preventing registration from working.</p>";
?>
