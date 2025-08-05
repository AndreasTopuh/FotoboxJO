<?php
echo "<h3>Testing Cash Code Verification</h3>";

// Test 1: Check file exists and readable
$file_path = __DIR__ . '/src/data/cash_codes.json';
echo "<p><strong>Test 1 - File Path:</strong> $file_path</p>";
echo "<p><strong>File exists:</strong> " . (file_exists($file_path) ? 'YES' : 'NO') . "</p>";
echo "<p><strong>File readable:</strong> " . (is_readable($file_path) ? 'YES' : 'NO') . "</p>";

// Test 2: Read file content
if (file_exists($file_path)) {
    $content = file_get_contents($file_path);
    echo "<p><strong>Test 2 - File Content:</strong></p>";
    echo "<pre>" . htmlspecialchars($content) . "</pre>";

    // Test 3: JSON decode
    $codes = json_decode($content, true);
    echo "<p><strong>Test 3 - JSON Valid:</strong> " . (json_last_error() === JSON_ERROR_NONE ? 'YES' : 'NO') . "</p>";
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo "<p><strong>JSON Error:</strong> " . json_last_error_msg() . "</p>";
    } else {
        echo "<p><strong>Decoded codes:</strong></p>";
        echo "<pre>" . print_r($codes, true) . "</pre>";
    }
}

// Test 4: Simulate verification request
echo "<p><strong>Test 4 - Manual Verification Test:</strong></p>";
$test_code = "82195";
if (isset($codes[$test_code])) {
    $code_data = $codes[$test_code];
    echo "<p>Code $test_code found!</p>";
    echo "<p>Used: " . ($code_data['used'] ? 'YES' : 'NO') . "</p>";
    echo "<p>Generated at: " . $code_data['generated_at'] . "</p>";

    if (!$code_data['used']) {
        echo "<p style='color: green;'><strong>✅ Code $test_code is VALID and can be used!</strong></p>";
    } else {
        echo "<p style='color: red;'>❌ Code $test_code already used</p>";
    }
} else {
    echo "<p style='color: red;'>❌ Code $test_code not found</p>";
}

// Test 5: Call verification API directly
echo "<p><strong>Test 5 - API Verification Test:</strong></p>";
echo "<div id='api-test-result'>Loading...</div>";
?>

<script>
    // Test API call
    fetch('/FotoboxJO/src/api-fetch/verify_cash_code.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                code: '82195'
            })
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('api-test-result').innerHTML =
                '<p><strong>API Response:</strong></p>' +
                '<pre>' + JSON.stringify(data, null, 2) + '</pre>';
        })
        .catch(error => {
            document.getElementById('api-test-result').innerHTML =
                '<p style="color: red;">Error: ' + error + '</p>';
        });
</script>