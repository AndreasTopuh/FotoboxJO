<?php
// Test save_photos.php API
echo "=== Testing save_photos.php API ===" . PHP_EOL;

session_start();

// Simulate sample photo data
$sample_photos = [
    'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==',
    'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg=='
];

// Test 1: Save photos
echo "Test 1: Saving photos..." . PHP_EOL;
$_SESSION['captured_photos'] = $sample_photos;
$_SESSION['photos_saved_time'] = time();
echo "✅ Photos saved to session" . PHP_EOL;

// Test 2: Get photos
echo "Test 2: Getting photos..." . PHP_EOL;
if (isset($_SESSION['captured_photos'])) {
    $photos = $_SESSION['captured_photos'];
    echo "✅ Found " . count($photos) . " photos in session" . PHP_EOL;
    echo "📊 Data size: " . round(strlen(json_encode($photos)) / 1024, 2) . " KB" . PHP_EOL;
} else {
    echo "❌ No photos found in session" . PHP_EOL;
}

// Test 3: Create customize session
echo "Test 3: Creating customize session..." . PHP_EOL;
$_SESSION['customize_start_time'] = time();
$_SESSION['customize_expired_time'] = time() + (3 * 60);
$_SESSION['session_type'] = 'customize';
echo "✅ Customize session created" . PHP_EOL;

echo "=== All tests completed ===" . PHP_EOL;
echo "Session ID: " . session_id() . PHP_EOL;
echo "Session data: " . json_encode([
    'photos_count' => count($_SESSION['captured_photos'] ?? []),
    'session_type' => $_SESSION['session_type'] ?? 'not set'
], JSON_PRETTY_PRINT) . PHP_EOL;
?>
