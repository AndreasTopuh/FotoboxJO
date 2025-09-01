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
$orderId = $input['order_id'] ?? 'ORDER-BNI-' . time();
$grossAmount = $input['gross_amount'] ?? 30000;

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
    'payment_type' => 'bank_transfer',
    'transaction_details' => array(
        'order_id' => $orderId,
        'gross_amount' => intval($grossAmount),
    ),
    'customer_details' => array(
        'first_name' => 'Bank',
        'last_name' => 'User',
        'email' => 'bank@example.com',
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
    'bank_transfer' => array(
        'bank' => 'bni'
    ),
    'custom_expiry' => array(
        'order_time' => gmdate('Y-m-d H:i:s', time()) . ' +0000',
        'expiry_duration' => 20,
        'unit' => 'minute'
    )
);

try {
    error_log("Attempting to charge BNI with params: " . json_encode($params));
    error_log("Current server time: " . date('Y-m-d H:i:s O'));
    
    $charge = \Midtrans\CoreApi::charge($params);
    
    error_log("Charge response: " . json_encode($charge));
    error_log("Expiry time from Midtrans: " . ($charge->expiry_time ?? 'null'));
    
    if (empty($charge->va_numbers)) {
        error_log("Error: No VA numbers in charge response");
        throw new Exception('No virtual account number generated');
    }

    echo json_encode([
        'success' => true,
        'mode' => $mode,
        'transaction_id' => $charge->transaction_id ?? null,
        'va_number' => $charge->va_numbers[0]->va_number ?? null,
        'order_id' => $orderId,
        'expiry_time' => $charge->expiry_time ?? null,
        'transaction_status' => $charge->transaction_status ?? 'pending'
    ]);
} catch (Exception $e) {
    error_log("Error in charge_bank.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'mode' => $mode
    ]);
}