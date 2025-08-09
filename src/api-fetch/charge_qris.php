<?php
require_once '../../vendor/autoload.php';
$dotenv = parse_ini_file('.env');

// Get base URL from environment or use default
$baseUrl = isset($dotenv['BASE_URL']) ? $dotenv['BASE_URL'] : 'https://gofotobox.online';

\Midtrans\Config::$serverKey = $dotenv['SERVER_KEY'];
\Midtrans\Config::$isProduction = true;
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

$orderId = 'ORDER-QRIS-' . time();
$grossAmount = 15000;

$params = array(
    'payment_type' => 'gopay',
    'transaction_details' => array(
        'order_id' => $orderId,
        'gross_amount' => $grossAmount,
    ),
    'customer_details' => array(
        'first_name' => 'Customer',
        'last_name' => 'Name',
        'email' => 'customer@example.com',
        'phone' => '08123456789'
    ),
    'item_details' => array(
        array(
            'id' => 'PHOTO_SESSION',
            'price' => $grossAmount,
            'quantity' => 1,
            'name' => 'Photo Booth Session'
        )
    ),
    'gopay' => array(
        'enable_callback' => true,
        'callback_url' => $baseUrl . '/src/pages/selectlayout.php'
    )
);

try {
    $charge = \Midtrans\CoreApi::charge($params);
    if (!isset($charge->actions) || empty($charge->actions)) {
        throw new Exception('No QR code URL returned from Midtrans');
    }
    echo json_encode([
        'qr_url' => $charge->actions[0]->url,
        'order_id' => $orderId,
        'expiry_time' => $charge->expiry_time ?? null
    ]);
} catch (Exception $e) {
    error_log("Midtrans charge error: " . $e->getMessage());
    error_log("Using Server Key: " . \Midtrans\Config::$serverKey);
    echo json_encode([
        'error' => $e->getMessage(),
        'status_code' => $e->getCode()
    ]);
}