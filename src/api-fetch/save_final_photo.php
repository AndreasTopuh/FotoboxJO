<?php
// Start output buffering to prevent any unwanted output
ob_start();

// Set timezone to Makassar (WITA)
date_default_timezone_set('Asia/Makassar');

session_start();

// Set headers first
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

// Disable all error output to prevent HTML in JSON response
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(0);

// Clean any previous output
ob_clean();

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Method not allowed');
    }

    $rawInput = file_get_contents('php://input');
    if (empty($rawInput)) {
        throw new Exception('No input data received');
    }

    $input = json_decode($rawInput, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid JSON input: ' . json_last_error_msg());
    }
    
    if (!isset($input['image']) || empty($input['image'])) {
        throw new Exception('No image data provided');
    }

    $imageData = $input['image'];
    
    // Validate base64 image data
    if (!preg_match('/^data:image\/(png|jpg|jpeg);base64,/', $imageData)) {
        throw new Exception('Invalid image data format');
    }
    
    // Generate unique token
    $token = bin2hex(random_bytes(16));
    
    // Use /tmp directory for guaranteed write permissions
    $photoDir = '/tmp/photobooth-photos';
    if (!file_exists($photoDir)) {
        if (!@mkdir($photoDir, 0777, true)) {
            throw new Exception('Failed to create photo directory');
        }
    }
    
    $filename = "{$photoDir}/{$token}.png";
    $expire = time() + (30 * 60); // 30 minutes from now
    
    // Save image
    $imageContent = base64_decode(preg_replace('/^data:image\/(png|jpg|jpeg);base64,/', '', $imageData));
    if ($imageContent === false) {
        throw new Exception('Failed to decode base64 image');
    }
    
    $bytesWritten = @file_put_contents($filename, $imageContent);
    if ($bytesWritten === false) {
        throw new Exception('Failed to save image to file system');
    }
    
    // Initialize photo_tokens session if not exists
    if (!isset($_SESSION['photo_tokens'])) {
        $_SESSION['photo_tokens'] = [];
    }
    
    // Save token and expire time
    $_SESSION['photo_tokens'][$token] = [
        'expire' => $expire,
        'filename' => $filename,
        'created' => time()
    ];
    
    // Return success response
    $response = [
        'success' => true,
        'url' => "/src/pages/yourphotos.php?token={$token}",
        'token' => $token,
        'expires_at' => date('Y-m-d H:i:s', $expire)
    ];
    
    // Clean output buffer and send JSON
    ob_clean();
    echo json_encode($response);
    exit;

} catch (Exception $e) {
    // Clean output buffer before sending error
    ob_clean();
    
    $errorResponse = [
        'success' => false,
        'message' => $e->getMessage(),
        'timestamp' => date('Y-m-d H:i:s')
    ];
    
    echo json_encode($errorResponse);
    exit;
}
?>
?>
