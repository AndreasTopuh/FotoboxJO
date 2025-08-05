<?php
echo "=== Testing File Paths ===\n";

$paths = [
    __DIR__ . '/../data/cash_codes.json',
    '/var/www/html/FotoboxJO/src/data/cash_codes.json',
    $_SERVER['DOCUMENT_ROOT'] . '/FotoboxJO/src/data/cash_codes.json',
    dirname($_SERVER['SCRIPT_FILENAME']) . '/../data/cash_codes.json'
];

foreach ($paths as $index => $path) {
    echo "\nPath " . ($index + 1) . ": $path\n";
    echo "Exists: " . (file_exists($path) ? 'YES' : 'NO') . "\n";
    echo "Readable: " . (is_readable($path) ? 'YES' : 'NO') . "\n";
    echo "Writable: " . (is_writable($path) ? 'YES' : 'NO') . "\n";
    echo "Directory writable: " . (is_writable(dirname($path)) ? 'YES' : 'NO') . "\n";

    if (file_exists($path)) {
        echo "✅ This path works!\n";

        // Test writing
        $testContent = file_get_contents($path);
        $writeResult = file_put_contents($path, $testContent);

        if ($writeResult !== false) {
            echo "✅ Write test SUCCESSFUL!\n";
        } else {
            echo "❌ Write test FAILED!\n";
            $error = error_get_last();
            echo "Error: " . ($error ? $error['message'] : 'Unknown') . "\n";
        }
    }

    echo str_repeat("-", 50) . "\n";
}

// Test current location
echo "\nCurrent script location: " . __FILE__ . "\n";
echo "Current working directory: " . getcwd() . "\n";
echo "Script directory: " . __DIR__ . "\n";
