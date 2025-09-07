<?php
session_start();

// Check admin access
if (!isset($_SESSION['admin_access']) || $_SESSION['admin_access'] !== true) {
    echo "Please login first";
    exit();
}

echo "<h3>Test Multiple Frame-Sticker-Combo Upload API</h3>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Test the actual API
    $url = 'api/upload-multiple-frame-sticker-combos.php';

    // Create a curl request to test the API
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $_POST);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/x-www-form-urlencoded'
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    echo "<h4>API Response:</h4>";
    echo "<p><strong>HTTP Code:</strong> {$httpCode}</p>";
    echo "<p><strong>Raw Response:</strong></p>";
    echo "<pre>" . htmlspecialchars($response) . "</pre>";

    echo "<p><strong>JSON Decode Test:</strong></p>";
    $jsonData = json_decode($response, true);
    if ($jsonData === null) {
        echo "<p style='color: red;'>❌ Invalid JSON Response!</p>";
        echo "<p>JSON Error: " . json_last_error_msg() . "</p>";
    } else {
        echo "<p style='color: green;'>✅ Valid JSON Response!</p>";
        echo "<pre>" . print_r($jsonData, true) . "</pre>";
    }

    echo "<hr><a href='test-combo-api.php'>Back to test</a>";
    exit();
}
?>

<form method="POST">
    <h4>Test API with Layout ID only (no files)</h4>
    <div>
        <label>Layout ID:</label>
        <select name="layout_id">
            <option value="">-- Select Layout --</option>
            <option value="1">Layout 1</option>
            <option value="2">Layout 2</option>
            <option value="3">Layout 3</option>
            <option value="4">Layout 4</option>
            <option value="5">Layout 5</option>
            <option value="6">Layout 6</option>
        </select>
    </div>
    <div>
        <label>Name Prefix:</label>
        <input type="text" name="name_prefix" value="Test Combo">
    </div>
    <button type="submit">Test API Response</button>
</form>

<p><em>This will test if the API returns valid JSON even with no files (should return "No files uploaded" error).</em></p>