<?php
header('Content-Type: text/html; charset=utf-8');

echo "<h1>Debug Cash Code System</h1>";

// Test file path function
function getDataFilePath()
{
    $paths = [
        __DIR__ . '/../data/cash_codes.json',
        '/var/www/html/FotoboxJO/src/data/cash_codes.json',
        $_SERVER['DOCUMENT_ROOT'] . '/FotoboxJO/src/data/cash_codes.json',
        dirname($_SERVER['SCRIPT_FILENAME']) . '/../data/cash_codes.json'
    ];

    foreach ($paths as $path) {
        if (file_exists($path)) {
            return $path;
        }
    }

    return __DIR__ . '/../data/cash_codes.json';
}

$codesFile = getDataFilePath();
echo "<p><strong>File path used:</strong> " . htmlspecialchars($codesFile) . "</p>";
echo "<p><strong>File exists:</strong> " . (file_exists($codesFile) ? 'YES' : 'NO') . "</p>";

if (file_exists($codesFile)) {
    $content = file_get_contents($codesFile);
    echo "<p><strong>File content:</strong></p>";
    echo "<pre>" . htmlspecialchars($content) . "</pre>";

    $codes = json_decode($content, true);
    if ($codes) {
        echo "<p><strong>Available codes:</strong></p>";
        echo "<ul>";
        foreach ($codes as $code => $data) {
            $status = $data['used'] ? 'USED' : 'AVAILABLE';
            echo "<li><strong>$code</strong> - $status (generated: {$data['generated_at']})</li>";
        }
        echo "</ul>";
    } else {
        echo "<p><em>No codes found or invalid JSON</em></p>";
    }
}

// Test code verification
if (isset($_GET['test_code'])) {
    $testCode = $_GET['test_code'];
    echo "<h2>Testing Code: $testCode</h2>";

    if (file_exists($codesFile)) {
        $codes = json_decode(file_get_contents($codesFile), true);
        if (isset($codes[$testCode])) {
            if ($codes[$testCode]['used']) {
                echo "<p style='color: red;'>❌ Code already used</p>";
            } else {
                echo "<p style='color: green;'>✅ Code is valid and available</p>";
            }
        } else {
            echo "<p style='color: red;'>❌ Code not found</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ Codes file not found</p>";
    }
}

echo "<hr>";
echo "<p><a href='admin-fixed.php'>Go to Admin</a> | <a href='?test_code=82195'>Test code 82195</a></p>";
