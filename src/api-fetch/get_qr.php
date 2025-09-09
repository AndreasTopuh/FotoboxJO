<?php
// Set timezone to Makassar (WITA)
date_default_timezone_set('Asia/Makassar');

// Disable error display
ini_set('display_errors', 0);
error_reporting(0);

$token = $_GET['token'] ?? '';

if (empty($token)) {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Invalid token']);
    exit;
}

// Validate token format (32 hex characters)
if (!preg_match('/^[a-f0-9]{32}$/', $token)) {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Invalid token format']);
    exit;
}

$photoDir = '/tmp/photobooth-photos';
$qrPath = "{$photoDir}/{$token}_qr.png";
$metaPath = "{$photoDir}/{$token}.meta";

// Check if metadata exists and token is valid
if (!file_exists($metaPath)) {
    http_response_code(404);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Token not found']);
    exit;
}

// Check if token is expired
$metadata = json_decode(file_get_contents($metaPath), true);
if (!$metadata || time() > $metadata['expire']) {
    http_response_code(410);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Token expired']);
    exit;
}

// Check if QR code file exists
if (!file_exists($qrPath)) {
    http_response_code(404);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'QR code not found']);
    exit;
}

// Serve QR code image
header('Content-Type: image/png');
header('Content-Length: ' . filesize($qrPath));
header('Cache-Control: public, max-age=3600'); // Cache for 1 hour
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 3600) . ' GMT');

readfile($qrPath);
exit;
