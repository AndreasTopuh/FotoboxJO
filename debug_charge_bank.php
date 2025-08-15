<?php
// Debug script untuk charge_bank.php

echo "=== DEBUG CHARGE_BANK.PHP ===\n";

// Test path file .env
$envPath = __DIR__ . '/src/api-fetch/../../.env';
echo "Checking .env file at: " . $envPath . "\n";

if (!file_exists($envPath)) {
    echo "❌ .env file not found!\n";
    
    // Try alternative paths
    $altPaths = [
        __DIR__ . '/.env',
        __DIR__ . '/../../.env',
        '/var/www/html/FotoboxJO/.env'
    ];
    
    foreach ($altPaths as $path) {
        echo "Trying: " . $path . " - " . (file_exists($path) ? "✅ Found" : "❌ Not found") . "\n";
    }
    
} else {
    echo "✅ .env file found\n";
}

// Test from api-fetch directory perspective
$apiEnvPath = __DIR__ . '/src/api-fetch/../../.env';
echo "\nFrom api-fetch perspective: " . $apiEnvPath . "\n";
echo "Real path: " . realpath(__DIR__ . '/src/api-fetch/../..') . "\n";

// Test file content
if (file_exists(__DIR__ . '/.env')) {
    echo "\n✅ Testing .env parsing:\n";
    $dotenv = parse_ini_file(__DIR__ . '/.env', false, INI_SCANNER_RAW);
    if ($dotenv) {
        echo "SERVER_KEY: " . (isset($dotenv['SERVER_KEY']) ? "Found (length: " . strlen($dotenv['SERVER_KEY']) . ")" : "Not found") . "\n";
    } else {
        echo "❌ Failed to parse .env\n";
    }
}
?>
