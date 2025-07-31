<?php
session_start();

// Test data
$testData = [
    'image' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8/5+hHgAHggJ/PchI7wAAAABJRU5ErkJggg=='
];

// Simulate POST request
$_SERVER['REQUEST_METHOD'] = 'POST';

// Simulate input
$GLOBALS['HTTP_RAW_POST_DATA'] = json_encode($testData);

// Override file_get_contents for php://input
function test_file_get_contents($filename) {
    if ($filename === 'php://input') {
        return json_encode($GLOBALS['testData']);
    }
    return file_get_contents($filename);
}

$GLOBALS['testData'] = $testData;

// Test the API
echo "Testing save_final_photo.php API...\n";
echo "=================================\n";

try {
    $input = $testData; // Direct assignment instead of file_get_contents
    
    if (!isset($input['image'])) {
        throw new Exception('No image data provided');
    }

    $imageData = $input['image'];
    $token = bin2hex(random_bytes(16));
    
    $photoDir = __DIR__ . '/src/user-photos';
    if (!file_exists($photoDir)) {
        if (!mkdir($photoDir, 0755, true)) {
            throw new Exception('Failed to create photo directory');
        }
    }
    
    $filename = "{$photoDir}/{$token}.png";
    $expire = time() + (30 * 60);
    
    // Save image
    $imageContent = base64_decode(str_replace('data:image/png;base64,', '', $imageData));
    if ($imageContent === false) {
        throw new Exception('Failed to decode base64 image');
    }
    
    if (file_put_contents($filename, $imageContent) === false) {
        throw new Exception('Failed to save image to file');
    }
    
    // Initialize photo_tokens session if not exists
    if (!isset($_SESSION['photo_tokens'])) {
        $_SESSION['photo_tokens'] = [];
    }
    
    // Save token and expire time
    $_SESSION['photo_tokens'][$token] = [
        'expire' => $expire,
        'filename' => $filename
    ];
    
    $result = [
        'success' => true,
        'url' => "/src/pages/yourphotos.php?token={$token}",
        'token' => $token,
        'expires_at' => date('Y-m-d H:i:s', $expire),
        'debug' => [
            'photo_dir' => $photoDir,
            'filename' => $filename,
            'file_exists' => file_exists($filename),
            'file_size' => file_exists($filename) ? filesize($filename) : 0
        ]
    ];
    
    echo "SUCCESS:\n";
    echo json_encode($result, JSON_PRETTY_PRINT);
    
} catch (Exception $e) {
    $result = [
        'success' => false,
        'message' => $e->getMessage(),
        'debug' => [
            'method' => $_SERVER['REQUEST_METHOD'],
            'input_received' => isset($input),
            'image_data_length' => isset($imageData) ? strlen($imageData) : 0,
            'photo_dir' => isset($photoDir) ? $photoDir : 'not set',
            'photo_dir_exists' => isset($photoDir) ? file_exists($photoDir) : false,
            'photo_dir_writable' => isset($photoDir) ? is_writable($photoDir) : false
        ]
    ];
    
    echo "ERROR:\n";
    echo json_encode($result, JSON_PRETTY_PRINT);
}
?>
