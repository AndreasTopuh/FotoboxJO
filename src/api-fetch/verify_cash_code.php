<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Get the request data
$input = json_decode(file_get_contents('php://input'), true);
$code = $input['code'] ?? '';

if (empty($code)) {
    echo json_encode(['success' => false, 'message' => 'Kode tidak boleh kosong']);
    exit();
}

// Function to get data file path (same as admin)
function getDataFilePath()
{
    // Use user-photos directory which has working permissions like image uploads
    $userPhotosPath = '/var/www/html/FotoboxJO/src/user-photos/cash_codes.json';

    // Check if user-photos path exists and is writable
    if (file_exists($userPhotosPath) && is_writable(dirname($userPhotosPath))) {
        return $userPhotosPath;
    }

    // Create in user-photos if it doesn't exist
    if (!file_exists($userPhotosPath)) {
        $dir = dirname($userPhotosPath);
        if (is_dir($dir) && is_writable($dir)) {
            file_put_contents($userPhotosPath, '{}');
            chmod($userPhotosPath, 0666);
            return $userPhotosPath;
        }
    }

    // Fallback paths
    $paths = [
        __DIR__ . '/../user-photos/cash_codes.json',
        __DIR__ . '/../data/cash_codes.json',
        '/var/www/html/FotoboxJO/src/data/cash_codes.json',
        $_SERVER['DOCUMENT_ROOT'] . '/FotoboxJO/src/data/cash_codes.json'
    ];

    foreach ($paths as $path) {
        if (file_exists($path) && is_writable(dirname($path))) {
            return $path;
        }
    }

    // Default fallback to user-photos
    return $userPhotosPath;
}

// Path to cash codes file
$codesFile = getDataFilePath();

if (!file_exists($codesFile)) {
    echo json_encode(['success' => false, 'message' => 'File kode cash tidak ditemukan: ' . $codesFile]);
    exit();
}

// Load existing codes
$content = file_get_contents($codesFile);
if ($content === false) {
    echo json_encode(['success' => false, 'message' => 'Gagal membaca file kode cash']);
    exit();
}

$codes = json_decode($content, true);

if (!$codes || !is_array($codes)) {
    echo json_encode(['success' => false, 'message' => 'Format file kode cash tidak valid']);
    exit();
}

if (!isset($codes[$code])) {
    echo json_encode(['success' => false, 'message' => 'Kode tidak valid atau tidak ditemukan']);
    exit();
}

// Check if code is already used
if ($codes[$code]['used']) {
    echo json_encode(['success' => false, 'message' => 'Kode sudah terpakai']);
    exit();
}

// Mark code as used
$codes[$code]['used'] = true;
$codes[$code]['used_at'] = date('Y-m-d H:i:s');

// Save updated codes
$updatedContent = json_encode($codes, JSON_PRETTY_PRINT);

// Add debug info before attempting to write
$debugInfo = [
    'file_path' => $codesFile,
    'file_exists' => file_exists($codesFile),
    'file_readable' => is_readable($codesFile),
    'file_writable' => is_writable($codesFile),
    'dir_exists' => is_dir(dirname($codesFile)),
    'dir_writable' => is_writable(dirname($codesFile)),
    'content_length' => strlen($updatedContent),
    'file_owner' => fileowner($codesFile),
    'file_group' => filegroup($codesFile),
    'file_perms' => substr(sprintf('%o', fileperms($codesFile)), -4)
];

$writeResult = file_put_contents($codesFile, $updatedContent);

if ($writeResult !== false) {
    echo json_encode([
        'success' => true,
        'message' => 'Kode valid dan berhasil digunakan',
        'code' => $code,
        'used_at' => $codes[$code]['used_at'],
        'bytes_written' => $writeResult
    ]);
} else {
    // Get more error details
    $lastError = error_get_last();
    $errorDetails = $lastError ? $lastError['message'] : 'Unknown error';

    echo json_encode([
        'success' => false,
        'message' => 'Gagal menyimpan status kode: ' . $errorDetails,
        'debug_info' => $debugInfo,
        'last_error' => $lastError
    ]);
}
