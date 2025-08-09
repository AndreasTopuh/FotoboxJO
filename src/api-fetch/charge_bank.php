<?php
require_once '../../vendor/autoload.php';

// Load .env dengan aman
if (!file_exists(__DIR__ . '/.env')) {
    echo json_encode(['error' => '.env file not found']);
    exit;
}

$dotenv = parse_ini_file(__DIR__ . '/.env', false, INI_SCANNER_RAW);

// Validasi SERVER_KEY
if (empty($dotenv['SERVER_KEY'])) {
    echo json_encode(['error' => 'SERVER_KEY not found in .env']);
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
    $charge = \Midtrans\CoreApi::charge($params);

    echo json_encode([
        'success' => true,
        'mode' => $mode,
        'va_number' => $charge->va_numbers[0]->va_number ?? null,
        'order_id' => $orderId,
        'expiry_time' => $charge->expiry_time ?? null
    ]);
} catch (\Midtrans\Error\ApiException $e) {
    // Error dari Midtrans API
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'status_code' => $e->getCode(),
        'mode' => $mode
    ]);
} catch (\Exception $e) {
    // Error umum PHP
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'status_code' => $e->getCode(),
        'mode' => $mode
    ]);
}
