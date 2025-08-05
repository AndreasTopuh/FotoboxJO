<?php
// Test script for cash code verification
echo "=== Testing Cash Code Verification ===\n";

// Simulate POST request for verify_cash_code.php
$_SERVER['REQUEST_METHOD'] = 'POST';

// Mock php://input
$json_input = json_encode(['code' => '67890']);

// Capture output
ob_start();

// Create a custom stream context for php://input
$context = stream_context_create([
    'php' => [
        'input' => $json_input
    ]
]);

// Include verification script
include 'src/api-fetch/verify_cash_code.php';

$output = ob_get_clean();

echo "API Response:\n";
echo $output . "\n";

// Check file status after
echo "\nFile status after verification:\n";
$cash_file = 'src/data/cash_codes.json';
if (file_exists($cash_file)) {
    echo "File exists: YES\n";
    echo "File writable: " . (is_writable($cash_file) ? 'YES' : 'NO') . "\n";
    echo "File content:\n";
    echo file_get_contents($cash_file) . "\n";
} else {
    echo "File not found!\n";
}
