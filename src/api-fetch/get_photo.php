<?php
// Set timezone to Makassar (WITA)
date_default_timezone_set('Asia/Makassar');

// Disable error display
ini_set('display_errors', 0);
error_reporting(0);

$token = $_GET['token'] ?? '';
$type = $_GET['type'] ?? 'final'; // 'final' or 'raw'
$index = $_GET['index'] ?? '0'; // for raw photos

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

// Determine file path based on type
$filePath = '';
$filename = '';

if ($type === 'final') {
    $filePath = "{$photoDir}/{$token}.png";
    $filename = "photobooth-final-{$token}.png";
} else if ($type === 'raw') {
    $rawIndex = intval($index);
    $filePath = "{$photoDir}/{$token}_raw_{$rawIndex}.png";
    $filename = "photobooth-raw-" . ($rawIndex + 1) . "-{$token}.png";
} else {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Invalid type parameter']);
    exit;
}

// Check if photo file exists
if (!file_exists($filePath)) {
    http_response_code(404);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Photo not found']);
    exit;
}

// Serve photo file
header('Content-Type: image/png');
header('Content-Length: ' . filesize($filePath));
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: public, max-age=3600'); // Cache for 1 hour
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 3600) . ' GMT');

readfile($filePath);
exit;
