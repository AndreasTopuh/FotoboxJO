<?php
require_once '../../vendor/autoload.php';
$dotenv = parse_ini_file('.env');
\Midtrans\Config::$serverKey = $dotenv['SERVER_KEY'];
\Midtrans\Config::$isProduction = false;

$orderId = $_GET['order_id'] ?? null;

if ($orderId) {
    try {
        $status = \Midtrans\Transaction::status($orderId);
        echo json_encode($status);
    } catch (Exception $e) {
        echo json_encode(["error" => $e->getMessage()]);
    }
} else {
    echo json_encode(["error" => "Missing order_id"]);
}
