<?php
require_once '../../vendor/autoload.php';

// Set timezone ke WITA (Waktu Indonesia Tengah) - UTC+8
date_default_timezone_set('Asia/Makassar');

// Set headers
header('Content-Type: application/json; charset=utf-8');

// Load .env dengan aman dari direktori api-fetch
$envPath = __DIR__ . '/.env';
if (!file_exists($envPath)) {
    error_log("Error: .env file not found at " . $envPath);
    http_response_code(500);
    echo json_encode(['error' => '.env file not found']);
    exit;
}

$dotenv = parse_ini_file($envPath, false, INI_SCANNER_RAW);

// Validasi parsing .env
if (!$dotenv) {
    error_log("Error: Failed to parse .env file");
    http_response_code(500);
    echo json_encode(['error' => 'Failed to parse .env file']);
    exit;
}

$baseUrl = $dotenv['BASE_URL'] ?? 'https://gofotobox.online';

// Validasi SERVER_KEY
if (empty($dotenv['SERVER_KEY'])) {
    http_response_code(500);
    echo json_encode(['error' => 'SERVER_KEY not found in .env']);
    exit;
}

// Validasi format SERVER_KEY
if (strpos($dotenv['SERVER_KEY'], 'YOUR_ACTUAL_SERVER_KEY_HERE') !== false) {
    http_response_code(500);
    echo json_encode(['error' => 'Please configure your actual Midtrans SERVER_KEY in .env file']);
    exit;
}

// Gunakan production jika PRODUCTION=true di .env, default sandbox
$isProduction = isset($dotenv['PRODUCTION']) && strtolower($dotenv['PRODUCTION']) === 'true';

\Midtrans\Config::$serverKey = trim($dotenv['SERVER_KEY']);
\Midtrans\Config::$isProduction = $isProduction;
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

// Cek mode
$mode = $isProduction ? 'PRODUCTION' : 'SANDBOX';

// Read input data
$input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
$orderId = $input['order_id'] ?? 'ORDER-GOPAY-' . time();
$grossAmount = $input['gross_amount'] ?? 15000;

// Validasi input
if (!$orderId || strlen($orderId) < 5) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid order_id']);
    exit;
}

if (!$grossAmount || !is_numeric($grossAmount) || $grossAmount <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid gross_amount']);
    exit;
}

$params = array(
    'payment_type' => 'gopay',
    'transaction_details' => array(
        'order_id' => $orderId,
        'gross_amount' => intval($grossAmount),
    ),
    'customer_details' => array(
        'first_name' => 'GoPay',
        'last_name' => 'User',
        'email' => 'gopay@example.com',
        'phone' => '08123456789'
    ),
    'item_details' => array(
        array(
            'id' => 'PHOTO_SESSION',
            'price' => intval($grossAmount),
            'quantity' => 1,
            'name' => 'Photo Booth Session'
        )
    ),
    'gopay' => array(
        'enable_callback' => true,
        'callback_url' => null
    ),
    'custom_expiry' => array(
        'order_time' => gmdate('Y-m-d H:i:s', time()) . ' +0000',
        'expiry_duration' => 20,
        'unit' => 'minute'
    )
);

try {
    error_log("Attempting to charge GoPay with params: " . json_encode($params));
    error_log("Current server time: " . date('Y-m-d H:i:s O'));
    
    $charge = \Midtrans\CoreApi::charge($params);
    
    error_log("Charge response: " . json_encode($charge));
    error_log("Expiry time from Midtrans: " . ($charge->expiry_time ?? 'null'));
    
    // Validasi response
    if (empty($charge->actions)) {
        error_log("Error: No actions in charge response");
        throw new Exception('No payment actions generated');
    }

    // Extract QR code URL dari actions
    $qrCodeUrl = null;
    
    foreach ($charge->actions as $action) {
        if ($action->name === 'generate-qr-code') {
            $qrCodeUrl = $action->url;
        }
    }

    echo json_encode([
        'success' => true,
        'mode' => $mode,
        'transaction_id' => $charge->transaction_id ?? null,
        'order_id' => $orderId,
        'qr_code_url' => $qrCodeUrl,
        'expiry_time' => $charge->expiry_time ?? null,
        'transaction_status' => $charge->transaction_status ?? 'pending'
    ]);
    
} catch (Exception $e) {
    error_log("Error in charge_ewallet.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'mode' => $mode
    ]);
}
?>
