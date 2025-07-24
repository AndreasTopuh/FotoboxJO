<?php
require_once '../../vendor/autoload.php';
$dotenv = parse_ini_file('.env');

// Get base URL from environment or use default
$baseUrl = isset($dotenv['BASE_URL']) ? $dotenv['BASE_URL'] : 'https://gofotobox.online';

\Midtrans\Config::$serverKey = $dotenv['SERVER_KEY'];
\Midtrans\Config::$isProduction = false;
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

$orderId = 'ORDER-QRIS-' . time();
$grossAmount = 15000;

$params = array(
    'transaction_details' => array(
        'order_id' => $orderId,
        'gross_amount' => $grossAmount,
    ),
    'payment_type' => 'gopay',
    'gopay' => array(
        'enable_callback' => true,
        'callback_url' => $baseUrl . '/src/pages/selectlayout.php'
    )
);

try {
    $charge = \Midtrans\CoreApi::charge($params);
    echo json_encode([
        "qr_url" => $charge->actions[0]->url,
        "order_id" => $orderId
    ]);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
