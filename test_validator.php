<?php
require_once 'src/includes/email-validator.php';

echo "Test Email Validator Class:\n";
echo "==========================\n";

$emails = [
    'valid@example.com',
    'test@domain.co.id',
    'invalid.email',
    'user@gmail.com',
    'admin@univ.ac.id'
];

foreach ($emails as $email) {
    echo "Testing: $email\n";
    $result = EmailValidator::validateComprehensive($email);
    echo "Valid: " . ($result['valid'] ? 'YES' : 'NO');
    if (!$result['valid']) {
        echo " - Error: " . $result['error'];
    }
    echo "\n";
    echo "Sanitized: " . EmailValidator::sanitize($email) . "\n";
    echo "---\n";
}
