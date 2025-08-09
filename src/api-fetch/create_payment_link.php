<?php
require_once '../../vendor/autoload.php';

// Load .env dengan aman
$envPath = __DIR__ . '/.env';
if (!file_exists($envPath)) {
    echo json_encode(['error' => '.env file not found at: ' . $envPath]);
    exit;
}

$dotenv = parse_ini_file($envPath, false, INI_SCANNER_RAW);

// Validasi SERVER_KEY
if (empty($dotenv['SERVER_KEY'])) {
    echo json_encode(['error' => 'SERVER_KEY not found in .env']);
    exit;
}

// Gunakan production jika PRODUCTION=true di .env
$isProduction = isset($dotenv['PRODUCTION']) && strtolower($dotenv['PRODUCTION']) === 'true';

\Midtrans\Config::$serverKey = trim($dotenv['SERVER_KEY']);
\Midtrans\Config::$isProduction = $isProduction;
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

// Handle POST request
$requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'POST';
if ($requestMethod !== 'POST') {
    echo json_encode(['error' => 'Only POST method allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

// Validasi input
$firstName = $input['first_name'] ?? '';
$lastName = $input['last_name'] ?? '';
$email = $input['email'] ?? '';
$phone = $input['phone'] ?? '';

if (empty($firstName) || empty($phone)) {
    echo json_encode(['error' => 'First name and phone are required']);
    exit;
}

// Validate phone number (Indonesian format)
if (!preg_match('/^(\+62|62|0)8[1-9][0-9]{6,9}$/', $phone)) {
    echo json_encode(['error' => 'Invalid phone number format']);
    exit;
}

// Generate order ID
$orderId = 'ORDER-LINK-' . time();
$grossAmount = 15000;

// Get base URL from environment
$baseUrl = isset($dotenv['BASE_URL']) ? $dotenv['BASE_URL'] : 'https://gofotobox.online';

$params = array(
    'transaction_details' => array(
        'order_id' => $orderId,
        'gross_amount' => $grossAmount,
    ),
    'customer_details' => array(
        'first_name' => $firstName,
        'last_name' => $lastName ?: 'Customer',
        'email' => $email ?: 'customer@gofotobox.com',
        'phone' => $phone,
    ),
    'item_details' => array(
        array(
            'id' => 'PHOTO_SESSION',
            'price' => $grossAmount,
            'quantity' => 1,
            'name' => 'Photo Booth Session'
        )
    ),
    'enabled_payments' => array(
        'gopay', 'shopeepay', 'qris', 'cstore', 'bank_transfer'
    ),
    'callbacks' => array(
        'finish' => $baseUrl . '/src/pages/payment-success.php?order_id=' . $orderId
    ),
    'expiry' => array(
        'start_time' => date('Y-m-d H:i:s O'),
        'unit' => 'minutes',
        'duration' => 20
    ),
    'custom_field1' => 'photo_booth_session',
    'custom_field2' => $firstName . ' ' . $lastName,
    'custom_field3' => $phone
);

try {
    $snapToken = \Midtrans\Snap::getSnapToken($params);
    
    // Construct payment link URL
    $paymentLinkUrl = $isProduction 
        ? "https://app.midtrans.com/snap/v2/vtweb/" . $snapToken
        : "https://app.sandbox.midtrans.com/snap/v2/vtweb/" . $snapToken;
    
    echo json_encode([
        'success' => true,
        'payment_url' => $paymentLinkUrl,
        'snap_token' => $snapToken,
        'order_id' => $orderId,
        'mode' => $isProduction ? 'PRODUCTION' : 'SANDBOX',
        'expires_at' => date('Y-m-d H:i:s', strtotime('+20 minutes')),
        'customer' => [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'phone' => $phone,
            'email' => $email
        ]
    ]);
    
} catch (Exception $e) {
    error_log("Midtrans Payment Link Error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'mode' => $isProduction ? 'PRODUCTION' : 'SANDBOX'
    ]);
}
