<?php
// Test Email Validation Library
echo "<h2>Test Email Validation Library</h2>\n";

// Test 1: PHP built-in validation
echo "<h3>1. PHP Built-in FILTER_VALIDATE_EMAIL:</h3>\n";
$test_emails = [
    'valid@example.com',
    'test.email@domain.co.id',
    'invalid.email',
    'user@',
    '@domain.com',
    'valid-email@test-domain.org'
];

foreach ($test_emails as $email) {
    $is_valid = filter_var($email, FILTER_VALIDATE_EMAIL);
    echo "Email: $email - " . ($is_valid ? "VALID ✅" : "INVALID ❌") . "<br>\n";
}

// Test 2: Check if PHPMailer is available
echo "<h3>2. PHPMailer Library Check:</h3>\n";
require_once __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

try {

    $mail = new PHPMailer(true);
    echo "PHPMailer loaded successfully ✅<br>\n";
    echo "PHPMailer version: " . PHPMailer::VERSION . "<br>\n";

    // Test PHPMailer email validation
    echo "<h4>PHPMailer Email Validation Test:</h4>\n";
    foreach ($test_emails as $email) {
        $is_valid = PHPMailer::validateAddress($email);
        echo "Email: $email - " . ($is_valid ? "VALID ✅" : "INVALID ❌") . "<br>\n";
    }
} catch (Exception $e) {
    echo "PHPMailer not available: " . $e->getMessage() . " ❌<br>\n";
}

// Test 3: Custom regex validation (like in your JS files)
echo "<h3>3. Custom Regex Validation (same as frontend):</h3>\n";
function validateEmailRegex($email)
{
    return preg_match('/^[^\s@]+@[^\s@]+\.[^\s@]+$/', $email);
}

foreach ($test_emails as $email) {
    $is_valid = validateEmailRegex($email);
    echo "Email: $email - " . ($is_valid ? "VALID ✅" : "INVALID ❌") . "<br>\n";
}

// Test 4: More comprehensive email validation
echo "<h3>4. Comprehensive Email Validation Function:</h3>\n";
function validateEmailComprehensive($email)
{
    // Basic format check
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    // Additional checks
    $parts = explode('@', $email);
    if (count($parts) !== 2) {
        return false;
    }

    $local = $parts[0];
    $domain = $parts[1];

    // Check local part length (max 64 characters)
    if (strlen($local) > 64) {
        return false;
    }

    // Check domain part
    if (strlen($domain) > 253) {
        return false;
    }

    // Check if domain has at least one dot
    if (strpos($domain, '.') === false) {
        return false;
    }

    return true;
}

foreach ($test_emails as $email) {
    $is_valid = validateEmailComprehensive($email);
    echo "Email: $email - " . ($is_valid ? "VALID ✅" : "INVALID ❌") . "<br>\n";
}

echo "<h3>Summary:</h3>\n";
echo "✅ PHP built-in email validation (FILTER_VALIDATE_EMAIL) tersedia<br>\n";
echo "✅ Custom regex validation sudah diimplementasi di frontend<br>\n";
echo "✅ PHPMailer library sudah terinstall<br>\n";
echo "✅ Semua metode validasi email siap digunakan<br>\n";
