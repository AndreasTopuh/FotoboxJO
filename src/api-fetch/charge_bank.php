<?php
require_once '../../vendor/autoload.php';
$dotenv = parse_ini_file('.env');

\Midtrans\Config::$serverKey = $dotenv['SERVER_KEY'];
\Midtrans\Config::$isProduction = false;

$orderId = 'ORDER-BCA-' . time();
$grossAmount = 44000;

$params = array(
    'payment_type' => 'bank_transfer',
    'transaction_details' => array(
        'order_id' => $orderId,
        'gross_amount' => $grossAmount,
    ),
    'bank_transfer' => array(
        'bank' => 'bca'
    )
);

try {
    $charge = \Midtrans\CoreApi::charge($params);
    echo json_encode([
        "va_number" => $charge->va_numbers[0]->va_number,
        "order_id" => $orderId
    ]);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
