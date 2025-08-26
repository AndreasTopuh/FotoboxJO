<?php
require_once '../../vendor/autoload.php';
$dotenv = parse_ini_file('.env');
\Midtrans\Config::$serverKey = $dotenv['SERVER_KEY'];
\Midtrans\Config::$isProduction = true;

$orderId = $_GET['order_id'] ?? null;

if (!$orderId || !is_string($orderId) || strlen($orderId) < 5) {
    echo json_encode(['error' => 'Invalid or missing order_id']);
    exit();
}

try {
    $status = \Midtrans\Transaction::status($orderId);
    echo json_encode($status);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}