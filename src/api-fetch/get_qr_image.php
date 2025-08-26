<?php
require_once '../../vendor/autoload.php';

// Load .env 
$envPath = __DIR__ . '/.env';
if (!file_exists($envPath)) {
    http_response_code(500);
    echo json_encode(['error' => '.env file not found']);
    exit;
}

$dotenv = parse_ini_file($envPath, false, INI_SCANNER_RAW);

// Validasi SERVER_KEY
if (empty($dotenv['SERVER_KEY'])) {
    http_response_code(500);
    echo json_encode(['error' => 'SERVER_KEY not configured']);
    exit;
}

$isProduction = isset($dotenv['PRODUCTION']) && strtolower($dotenv['PRODUCTION']) === 'true';

// Set Midtrans config
\Midtrans\Config::$serverKey = trim($dotenv['SERVER_KEY']);
\Midtrans\Config::$isProduction = $isProduction;

$transactionId = $_GET['transaction_id'] ?? null;

if (!$transactionId) {
    http_response_code(400);
    echo json_encode(['error' => 'transaction_id is required']);
    exit;
}

try {
    // Get QR code dari Midtrans
    $baseUrl = $isProduction ? 'https://api.midtrans.com' : 'https://api.sandbox.midtrans.com';
    $qrUrl = $baseUrl . '/v2/gopay/' . $transactionId . '/qr-code';
    
    // Setup auth header
    $serverKey = trim($dotenv['SERVER_KEY']);
    $auth = base64_encode($serverKey . ':');
    
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'header' => [
                'Accept: application/json',
                'Authorization: Basic ' . $auth,
                'Content-Type: application/json'
            ]
        ]
    ]);
    
    $qrResponse = file_get_contents($qrUrl, false, $context);
    
    if ($qrResponse === false) {
        throw new Exception('Failed to fetch QR code from Midtrans');
    }
    
    // Set appropriate headers untuk QR code image
    header('Content-Type: image/png');
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');
    
    echo $qrResponse;
    
} catch (Exception $e) {
    error_log("Error in get_qr_image.php: " . $e->getMessage());
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()]);
}
?>
