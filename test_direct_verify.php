<?php
// Direct test of cash code verification logic
echo "=== Direct Cash Code Test ===\n";

// Path to cash codes file (same logic as verify_cash_code.php)
function getDataFilePath()
{
    $paths = [
        __DIR__ . '/src/data/cash_codes.json',
        '/var/www/html/FotoboxJO/src/data/cash_codes.json',
        $_SERVER['DOCUMENT_ROOT'] . '/FotoboxJO/src/data/cash_codes.json',
        dirname($_SERVER['SCRIPT_FILENAME']) . '/src/data/cash_codes.json'
    ];

    foreach ($paths as $path) {
        if (file_exists($path)) {
            return $path;
        }
    }

    return __DIR__ . '/src/data/cash_codes.json';
}

$codesFile = getDataFilePath();
echo "Using file: $codesFile\n";
echo "File exists: " . (file_exists($codesFile) ? 'YES' : 'NO') . "\n";
echo "File writable: " . (is_writable($codesFile) ? 'YES' : 'NO') . "\n";
echo "Dir writable: " . (is_writable(dirname($codesFile)) ? 'YES' : 'NO') . "\n";

if (!file_exists($codesFile)) {
    echo "ERROR: File not found!\n";
    exit;
}

// Load codes
$content = file_get_contents($codesFile);
echo "File content loaded: " . strlen($content) . " bytes\n";

$codes = json_decode($content, true);
if (!$codes) {
    echo "ERROR: Invalid JSON!\n";
    exit;
}

echo "Available codes: " . implode(', ', array_keys($codes)) . "\n";

// Test code
$testCode = '67890';
echo "\nTesting code: $testCode\n";

if (!isset($codes[$testCode])) {
    echo "ERROR: Code not found!\n";
    exit;
}

if ($codes[$testCode]['used']) {
    echo "ERROR: Code already used!\n";
    exit;
}

// Mark as used
echo "Marking code as used...\n";
$codes[$testCode]['used'] = true;
$codes[$testCode]['used_at'] = date('Y-m-d H:i:s');

// Try to save
$updatedContent = json_encode($codes, JSON_PRETTY_PRINT);
echo "Updated content length: " . strlen($updatedContent) . " bytes\n";

$writeResult = file_put_contents($codesFile, $updatedContent);

if ($writeResult !== false) {
    echo "SUCCESS: File saved with $writeResult bytes\n";

    // Verify the save
    $verifyContent = file_get_contents($codesFile);
    $verifyData = json_decode($verifyContent, true);

    echo "Verification - Code used status: " . ($verifyData[$testCode]['used'] ? 'TRUE' : 'FALSE') . "\n";
    echo "Verification - Used at: " . $verifyData[$testCode]['used_at'] . "\n";
} else {
    echo "ERROR: Failed to save file\n";
    $error = error_get_last();
    if ($error) {
        echo "Last error: " . $error['message'] . "\n";
    }
}

echo "\nFinal file content:\n";
echo file_get_contents($codesFile) . "\n";
