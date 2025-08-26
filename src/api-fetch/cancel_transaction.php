<?php
require_once '../../vendor/autoload.php';

// Set headers
header('Content-Type: application/json; charset=utf-8');

// Load .env
$envPath = __DIR__ . '/.env';
if (!file_exists($envPath)) {
    http_response_code(500);
    echo json_encode(['error' => '.env file not found']);
    exit;
}

$dotenv = parse_ini_file($envPath, false, INI_SCANNER_RAW);

// Validasi SERVER_KEY
if (empty($dotenv['SERVER_KEY'])) {
    http_response_code(500);
    echo json_encode(['error' => 'SERVER_KEY not configured']);
    exit;
}

$isProduction = isset($dotenv['PRODUCTION']) && strtolower($dotenv['PRODUCTION']) === 'true';

// Set Midtrans config
\Midtrans\Config::$serverKey = trim($dotenv['SERVER_KEY']);
\Midtrans\Config::$isProduction = $isProduction;
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

// Read input data
$input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
$orderId = $input['order_id'] ?? null;

if (!$orderId || !is_string($orderId) || strlen($orderId) < 5) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid or missing order_id']);
    exit;
}

try {
    error_log("Attempting to cancel order: " . $orderId);
    
    // Cancel transaksi via Midtrans
    $cancel = \Midtrans\Transaction::cancel($orderId);
    
    error_log("Cancel response: " . json_encode($cancel));
    
    echo json_encode([
        'success' => true,
        'order_id' => $orderId,
        'transaction_status' => $cancel->transaction_status ?? 'cancel',
        'status_message' => $cancel->status_message ?? 'Transaction cancelled successfully'
    ]);
    
} catch (Exception $e) {
    error_log("Error in cancel_transaction.php: " . $e->getMessage());
    
    // Even if cancel fails, return success if it's already cancelled/expired
    $errorMsg = $e->getMessage();
    if (strpos($errorMsg, 'already cancelled') !== false || 
        strpos($errorMsg, 'expired') !== false ||
        strpos($errorMsg, 'settlement') !== false) {
        
        echo json_encode([
            'success' => true,
            'order_id' => $orderId,
            'transaction_status' => 'cancel',
            'status_message' => 'Transaction already processed or cancelled'
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => $errorMsg,
            'order_id' => $orderId
        ]);
    }
}
?>
