<?php
/**
 * Test Registration Form Submission
 * This will help us see what's happening when the form is submitted
 */

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Test Registration Form Submission</h1>";
echo "<hr>";

// Check if this is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h2>Form Submitted Successfully!</h2>";
    echo "<p>This means the form is working and reaching the server.</p>";
    
    echo "<h3>Form Data Received:</h3>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    
    echo "<h3>CSRF Token Check:</h3>";
    if (isset($_POST['_token'])) {
        echo "✅ CSRF token present: " . $_POST['_token'] . "<br>";
    } else {
        echo "❌ CSRF token missing<br>";
    }
    
    echo "<h3>Required Fields Check:</h3>";
    $requiredFields = [
        'first_name', 'last_name', 'birth_date', 'phone', 'email',
        'address', 'city', 'province', 'postal_code', 'country',
        'canadian_status_proof', 'member_type_id', 'pin_code', 'pin_code_confirmation'
    ];
    
    foreach ($requiredFields as $field) {
        if (isset($_POST[$field]) && !empty($_POST[$field])) {
            echo "✅ $field: " . $_POST[$field] . "<br>";
        } else {
            echo "❌ $field: MISSING or EMPTY<br>";
        }
    }
    
} else {
    // Show the test form
    echo "<h2>Test Registration Form</h2>";
    echo "<p>Fill out this form and submit it to test if the registration process is working.</p>";
    
    echo "<form method='POST' action=''>";
    echo "<input type='hidden' name='_token' value='test_token_123'>";
    
    echo "<h3>Personal Information</h3>";
    echo "First Name: <input type='text' name='first_name' value='Test' required><br><br>";
    echo "Last Name: <input type='text' name='last_name' value='User' required><br><br>";
    echo "Birth Date: <input type='date' name='birth_date' value='1990-01-01' required><br><br>";
    echo "Phone: <input type='tel' name='phone' value='555-1234' required><br><br>";
    echo "Email: <input type='email' name='email' value='test@example.com' required><br><br>";
    
    echo "<h3>Address</h3>";
    echo "Address: <input type='text' name='address' value='123 Test St' required><br><br>";
    echo "City: <input type='text' name='city' value='Montreal' required><br><br>";
    echo "Province: <input type='text' name='province' value='QC' required><br><br>";
    echo "Postal Code: <input type='text' name='postal_code' value='H1A 1A1' required><br><br>";
    echo "Country: <input type='text' name='country' value='Canada' required><br><br>";
    echo "Canadian Status: <select name='canadian_status_proof' required>";
    echo "<option value='citizen'>Citizen</option>";
    echo "<option value='permanent_resident'>Permanent Resident</option>";
    echo "</select><br><br>";
    
    echo "<h3>Membership</h3>";
    echo "Member Type: <select name='member_type_id' required>";
    echo "<option value='1'>Régulier</option>";
    echo "<option value='2'>Senior</option>";
    echo "<option value='3'>Junior</option>";
    echo "<option value='4'>Association</option>";
    echo "</select><br><br>";
    
    echo "<h3>Security</h3>";
    echo "PIN Code: <input type='password' name='pin_code' value='1234' required><br><br>";
    echo "Confirm PIN: <input type='password' name='pin_code_confirmation' value='1234' required><br><br>";
    
    echo "<br><button type='submit'>Test Submit</button>";
    echo "</form>";
}

echo "<hr>";
echo "<h2>Debug Information</h2>";
echo "Request Method: " . $_SERVER['REQUEST_METHOD'] . "<br>";
echo "Content Type: " . ($_SERVER['CONTENT_TYPE'] ?? 'Not set') . "<br>";
echo "User Agent: " . ($_SERVER['HTTP_USER_AGENT'] ?? 'Not set') . "<br>";
?>
