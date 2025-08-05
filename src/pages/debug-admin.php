<?php
// Debug admin.php issues
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Debug Admin Generate Code</h1>";

// Test file path
$codesFile = __DIR__ . '/../data/cash_codes.json';
echo "<p>File path: " . $codesFile . "</p>";

// Check if directory exists
$dataDir = dirname($codesFile);
echo "<p>Data directory: " . $dataDir . "</p>";
echo "<p>Directory exists: " . (file_exists($dataDir) ? 'YES' : 'NO') . "</p>";

// Create directory if needed
if (!file_exists($dataDir)) {
    echo "<p>Creating directory...</p>";
    $result = mkdir($dataDir, 0755, true);
    echo "<p>Create result: " . ($result ? 'SUCCESS' : 'FAILED') . "</p>";
}

// Check permissions
if (file_exists($dataDir)) {
    $perms = fileperms($dataDir);
    echo "<p>Directory permissions: " . substr(sprintf('%o', $perms), -4) . "</p>";
    echo "<p>Directory writable: " . (is_writable($dataDir) ? 'YES' : 'NO') . "</p>";
}

// Test file operations
echo "<h2>Testing File Operations</h2>";

// Test read
if (file_exists($codesFile)) {
    echo "<p>File exists: YES</p>";
    $content = file_get_contents($codesFile);
    echo "<p>File content: " . htmlspecialchars($content) . "</p>";

    $codes = json_decode($content, true);
    echo "<p>JSON decode result: " . (is_array($codes) ? 'SUCCESS' : 'FAILED') . "</p>";
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo "<p>JSON error: " . json_last_error_msg() . "</p>";
    }
} else {
    echo "<p>File exists: NO</p>";
}

// Test write
echo "<h3>Testing Write Operation</h3>";
$testData = [
    'test_code' => [
        'generated_at' => date('Y-m-d H:i:s'),
        'used' => false,
        'used_at' => null
    ]
];

$json = json_encode($testData, JSON_PRETTY_PRINT);
echo "<p>Test JSON: " . htmlspecialchars($json) . "</p>";

$writeResult = file_put_contents($codesFile, $json);
echo "<p>Write result: " . ($writeResult !== false ? 'SUCCESS (' . $writeResult . ' bytes)' : 'FAILED') . "</p>";

if ($writeResult === false) {
    $error = error_get_last();
    echo "<p>Last error: " . print_r($error, true) . "</p>";
}

// Check file after write
if (file_exists($codesFile)) {
    $perms = fileperms($codesFile);
    echo "<p>File permissions: " . substr(sprintf('%o', $perms), -4) . "</p>";
    echo "<p>File writable: " . (is_writable($codesFile) ? 'YES' : 'NO') . "</p>";
    echo "<p>File size: " . filesize($codesFile) . " bytes</p>";
}
