<?php
echo "=== Testing Cash Codes Path Fix ===\n";

// Test the getDataFilePath function like in verify_cash_code.php
function getDataFilePath()
{
    // Use user-photos directory which has working permissions like image uploads
    $userPhotosPath = '/var/www/html/FotoboxJO/src/user-photos/cash_codes.json';

    // Check if user-photos path exists and is writable
    if (file_exists($userPhotosPath) && is_writable(dirname($userPhotosPath))) {
        return $userPhotosPath;
    }

    // Create in user-photos if it doesn't exist
    if (!file_exists($userPhotosPath)) {
        $dir = dirname($userPhotosPath);
        if (is_dir($dir) && is_writable($dir)) {
            file_put_contents($userPhotosPath, '{}');
            chmod($userPhotosPath, 0666);
            return $userPhotosPath;
        }
    }

    // Fallback paths
    $paths = [
        __DIR__ . '/../user-photos/cash_codes.json',
        __DIR__ . '/../data/cash_codes.json',
        '/var/www/html/FotoboxJO/src/data/cash_codes.json',
        $_SERVER['DOCUMENT_ROOT'] . '/FotoboxJO/src/data/cash_codes.json'
    ];

    foreach ($paths as $path) {
        if (file_exists($path) && is_writable(dirname($path))) {
            return $path;
        }
    }

    // Default fallback to user-photos
    return $userPhotosPath;
}

$codesFile = getDataFilePath();
echo "Using file: $codesFile\n";
echo "File exists: " . (file_exists($codesFile) ? 'YES' : 'NO') . "\n";
echo "File readable: " . (is_readable($codesFile) ? 'YES' : 'NO') . "\n";
echo "File writable: " . (is_writable($codesFile) ? 'YES' : 'NO') . "\n";
echo "Dir writable: " . (is_writable(dirname($codesFile)) ? 'YES' : 'NO') . "\n";

if (file_exists($codesFile)) {
    echo "\nTesting file operations...\n";

    // Read current content
    $content = file_get_contents($codesFile);
    echo "Content read: " . strlen($content) . " bytes\n";

    $codes = json_decode($content, true);
    if ($codes === null) {
        echo "JSON decode failed!\n";
    } else {
        echo "JSON decode successful, " . count($codes) . " codes found\n";

        // Test writing
        $testCode = '99999';
        if (isset($codes[$testCode])) {
            if (!$codes[$testCode]['used']) {
                echo "Testing with code: $testCode\n";

                // Mark as used
                $codes[$testCode]['used'] = true;
                $codes[$testCode]['used_at'] = date('Y-m-d H:i:s');

                // Try to write
                $updatedContent = json_encode($codes, JSON_PRETTY_PRINT);
                $writeResult = file_put_contents($codesFile, $updatedContent);

                if ($writeResult !== false) {
                    echo "✅ WRITE TEST SUCCESSFUL! Wrote $writeResult bytes\n";

                    // Verify the write
                    $verifyContent = file_get_contents($codesFile);
                    $verifyData = json_decode($verifyContent, true);
                    echo "Verification: Code 99999 used = " . ($verifyData[$testCode]['used'] ? 'TRUE' : 'FALSE') . "\n";
                } else {
                    echo "❌ WRITE TEST FAILED!\n";
                    $error = error_get_last();
                    echo "Error: " . ($error ? $error['message'] : 'Unknown') . "\n";
                }
            } else {
                echo "Code $testCode already used, cannot test\n";
            }
        } else {
            echo "Code $testCode not found in file\n";
        }
    }
} else {
    echo "❌ File not found!\n";
}
