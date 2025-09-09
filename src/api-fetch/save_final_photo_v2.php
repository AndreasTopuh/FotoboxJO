<?php
// Start output buffering to prevent any unwanted output
ob_start();

// Set timezone to Makassar (WITA)
date_default_timezone_set('Asia/Makassar');

session_start();

// Try to load QR code library if available
$vendorPath = __DIR__ . '/../../vendor/autoload.php';
if (file_exists($vendorPath)) {
    require_once $vendorPath;
}

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

    // NEW: Get raw photos if provided (4 foto mentahan)
    $rawPhotos = $input['raw_photos'] ?? [];
    $layoutType = $input['layout'] ?? 'unknown';

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
    $metaFile = "{$photoDir}/{$token}.meta"; // NEW: metadata file
    $qrFile = "{$photoDir}/{$token}_qr.png"; // NEW: QR code file
    $expire = time() + (2 * 60 * 60); // 2 hours from now

    // Save final image
    $imageContent = base64_decode(preg_replace('/^data:image\/(png|jpg|jpeg);base64,/', '', $imageData));
    if ($imageContent === false) {
        throw new Exception('Failed to decode base64 image');
    }

    $bytesWritten = @file_put_contents($filename, $imageContent);
    if ($bytesWritten === false) {
        throw new Exception('Failed to save image to file system');
    }

    // NEW: Save raw photos (4 foto mentahan)
    $rawPhotoFiles = [];
    foreach ($rawPhotos as $index => $rawPhotoData) {
        if (!empty($rawPhotoData)) {
            $rawFilename = "{$photoDir}/{$token}_raw_{$index}.png";
            $rawContent = base64_decode(preg_replace('/^data:image\/(png|jpg|jpeg);base64,/', '', $rawPhotoData));
            if ($rawContent !== false) {
                $rawBytesWritten = @file_put_contents($rawFilename, $rawContent);
                if ($rawBytesWritten !== false) {
                    $rawPhotoFiles[] = [
                        'index' => $index,
                        'filename' => $rawFilename,
                        'basename' => basename($rawFilename)
                    ];
                }
            }
        }
    }

    // NEW: Generate QR Code
    $photoUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') .
        "://{$_SERVER['HTTP_HOST']}/src/pages/yourphotos_v2.php?token={$token}";

    // Check if QR code library is available
    $qrGenerated = false;
    if (class_exists('Endroid\QrCode\QrCode')) {
        try {
            $qrCode = new \Endroid\QrCode\QrCode($photoUrl);
            $qrCode->setSize(300);
            $qrCode->setMargin(10);

            $writer = new \Endroid\QrCode\Writer\PngWriter();
            $result = $writer->write($qrCode);

            $qrBytesWritten = @file_put_contents($qrFile, $result->getString());
            if ($qrBytesWritten !== false) {
                $qrGenerated = true;
            }
        } catch (Exception $qrError) {
            // QR generation failed, continue without it
            error_log("QR generation failed: " . $qrError->getMessage());
        }
    }

    // NEW: Save metadata to file (more reliable than session)
    $metadata = [
        'token' => $token,
        'expire' => $expire,
        'final_photo' => [
            'filename' => $filename,
            'basename' => basename($filename)
        ],
        'raw_photos' => $rawPhotoFiles,
        'layout_type' => $layoutType,
        'qr_code' => $qrGenerated ? [
            'filename' => $qrFile,
            'basename' => basename($qrFile)
        ] : null,
        'photo_url' => $photoUrl,
        'created' => time(),
        'timezone' => date_default_timezone_get()
    ];

    $metaWritten = @file_put_contents($metaFile, json_encode($metadata));
    if ($metaWritten === false) {
        throw new Exception('Failed to save metadata file');
    }

    // KEEP: Also save to session for backward compatibility
    if (!isset($_SESSION['photo_tokens'])) {
        $_SESSION['photo_tokens'] = [];
    }

    $_SESSION['photo_tokens'][$token] = [
        'expire' => $expire,
        'filename' => $filename,
        'created' => time()
    ];

    // Return success response
    $response = [
        'success' => true,
        'url' => "/src/pages/yourphotos_v2.php?token={$token}",
        'token' => $token,
        'photo_url' => $photoUrl,
        'qr_code_url' => $qrGenerated ? "/src/api-fetch/get_qr.php?token={$token}" : null,
        'expires_at' => date('Y-m-d H:i:s', $expire),
        'final_photo_count' => 1,
        'raw_photos_count' => count($rawPhotoFiles),
        'layout_type' => $layoutType,
        'storage_method' => 'file_and_session'
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
        'error' => $e->getMessage(),
        'debug' => [
            'timezone' => date_default_timezone_get(),
            'time' => date('Y-m-d H:i:s'),
            'timestamp' => time()
        ]
    ];

    echo json_encode($errorResponse);
    exit;
}
