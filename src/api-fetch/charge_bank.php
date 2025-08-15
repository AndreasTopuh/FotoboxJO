<?php
require_once '../../vendor/autoload.php';

// Load .env dengan aman
if (!file_exists(__DIR__ . '/../../.env')) {
    echo json_encode(['error' => '.env file not found']);
    exit;
}

$dotenv = parse_ini_file(__DIR__ . '/../../.env', false, INI_SCANNER_RAW);

// Validasi parsing .env
if (!$dotenv) {
    echo json_encode(['error' => 'Failed to parse .env file']);
    exit;
}

// Validasi SERVER_KEY
if (empty($dotenv['SERVER_KEY'])) {
    echo json_encode(['error' => 'SERVER_KEY not found in .env']);
    exit;
}

// Validasi format SERVER_KEY
if (strpos($dotenv['SERVER_KEY'], 'YOUR_ACTUAL_SERVER_KEY_HERE') !== false) {
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

// Buat order ID unik
$orderId = 'ORDER-BNI-' . time();
$grossAmount = 15000;

$params = array(
    'payment_type' => 'bank_transfer',
    'transaction_details' => array(
        'order_id' => $orderId,
        'gross_amount' => $grossAmount,
    ),
    'item_details' => array(
        array(
            'id' => 'PHOTO_SESSION',
            'price' => $grossAmount,
            'quantity' => 1,
            'name' => 'Photo Booth Session'
        )
    ),
    'bank_transfer' => array(
        'bank' => 'bni'
    )
);

try {
    error_log("Attempting to charge BNI with params: " . json_encode($params));
    
    $charge = \Midtrans\CoreApi::charge($params);
    
    error_log("Charge response: " . json_encode($charge));
    
    if (empty($charge->va_numbers)) {
        error_log("Error: No VA numbers in charge response");
        throw new Exception('No virtual account number generated');
    }

    echo json_encode([
        'success' => true,
        'mode' => $mode,
        'va_number' => $charge->va_numbers[0]->va_number ?? null,
        'order_id' => $orderId,
        'expiry_time' => $charge->expiry_time ?? null
    ]);
} catch (Exception $e) {
    error_log("Error in charge_bank.php: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'mode' => $mode
    ]);
}