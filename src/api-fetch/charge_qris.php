<?php
require_once '../../vendor/autoload.php';

// Ganti parse_ini_file dengan path yang benar
$envPath = __DIR__ . '/../../.env';
if (!file_exists($envPath)) {
    error_log("Error: .env file not found at " . $envPath);
    echo json_encode(['error' => '.env file not found']);
    exit;
}

$dotenv = parse_ini_file($envPath);
if (!$dotenv) {
    error_log("Error: Failed to parse .env file");
    echo json_encode(['error' => 'Failed to parse .env file']);
    exit;
}

$baseUrl = $dotenv['BASE_URL'] ?? 'https://gofotobox.online';

// Pastikan SERVER_KEY ada dan valid
if (!isset($dotenv['SERVER_KEY']) || empty($dotenv['SERVER_KEY'])) {
    error_log("Error: SERVER_KEY not found in .env");
    echo json_encode(['error' => 'SERVER_KEY not configured']);
    exit;
}

// Validasi format SERVER_KEY
if (strpos($dotenv['SERVER_KEY'], 'YOUR_ACTUAL_SERVER_KEY_HERE') !== false) {
    error_log("Error: SERVER_KEY needs to be replaced with actual key");
    echo json_encode(['error' => 'Please configure your actual Midtrans SERVER_KEY in .env file']);
    exit;
}

\Midtrans\Config::$serverKey = $dotenv['SERVER_KEY'];
\Midtrans\Config::$isProduction = true;
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

$orderId = 'CUSTOM-QRIS-' . time();
$grossAmount = 15000;

$params = [
    'payment_type' => 'qris',
    'transaction_details' => [
        'order_id' => $orderId,
        'gross_amount' => $grossAmount,
    ],
    'customer_details' => [
        'first_name' => 'Custom',
        'last_name' => 'User',
        'email' => 'custom@example.com',
        'phone' => '08123456789'
    ],
    'item_details' => [
        [
            'id' => 'CUSTOM_SESSION',
            'price' => $grossAmount,
            'quantity' => 1,
            'name' => 'Custom Photo Session'
        ]
    ],
    'qris' => [
        'acquirer' => 'gopay'
    ]
];

try {
    error_log("Attempting to charge with params: " . json_encode($params));
    
    $charge = \Midtrans\CoreApi::charge($params);
    
    error_log("Charge response: " . json_encode($charge));
    
    if (empty($charge->actions)) {
        error_log("Error: No actions in charge response");
        throw new Exception('No QR code URL generated');
    }
    
    // Find the specific generate-qr-code action to avoid deeplinks
    $qrUrl = null;
    foreach ($charge->actions as $action) {
        if ($action->name === 'generate-qr-code') {
            $qrUrl = $action->url;
            break;
        }
    }
    
    if (!$qrUrl) {
        error_log("Error: No QR code URL found in actions");
        throw new Exception('No QR code URL found');
    }
    
    echo json_encode([
        'qr_url' => $qrUrl,
        'order_id' => $orderId,
        'expiry_time' => $charge->expiry_time ?? null
    ]);
} catch (Exception $e) {
    error_log("Error in charge_qris.php: " . $e->getMessage());
    echo json_encode(['error' => $e->getMessage()]);
}
?>