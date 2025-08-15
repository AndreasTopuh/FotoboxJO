<?php
// Debug script untuk mengecek konfigurasi Midtrans

// Test path file .env
$envPath = __DIR__ . '/.env';
echo "Checking .env file at: " . $envPath . "\n";

if (!file_exists($envPath)) {
    echo "❌ .env file not found!\n";
    exit;
} else {
    echo "✅ .env file found\n";
}

$dotenv = parse_ini_file($envPath);
if (!$dotenv) {
    echo "❌ Failed to parse .env file\n";
    exit;
} else {
    echo "✅ .env file parsed successfully\n";
}

echo "\nEnvironment variables:\n";
echo "SERVER_KEY: " . (isset($dotenv['SERVER_KEY']) ? "Found (length: " . strlen($dotenv['SERVER_KEY']) . ")" : "Not found") . "\n";
echo "CLIENT_KEY: " . (isset($dotenv['CLIENT_KEY']) ? $dotenv['CLIENT_KEY'] : "Not found") . "\n";
echo "BASE_URL: " . (isset($dotenv['BASE_URL']) ? $dotenv['BASE_URL'] : "Not found") . "\n";

// Test Midtrans configuration
require_once 'vendor/autoload.php';

if (isset($dotenv['SERVER_KEY']) && !empty($dotenv['SERVER_KEY'])) {
    \Midtrans\Config::$serverKey = $dotenv['SERVER_KEY'];
    \Midtrans\Config::$isProduction = true;
    \Midtrans\Config::$isSanitized = true;
    \Midtrans\Config::$is3ds = true;
    
    echo "\n✅ Midtrans configuration set\n";
    
    // Test simple API call
    try {
        $orderId = 'TEST-' . time();
        $params = [
            'payment_type' => 'gopay',
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => 15000,
            ],
            'customer_details' => [
                'first_name' => 'Test',
                'last_name' => 'User',
                'email' => 'test@example.com',
                'phone' => '08123456789'
            ],
            'item_details' => [
                [
                    'id' => 'TEST_SESSION',
                    'price' => 15000,
                    'quantity' => 1,
                    'name' => 'Test Photo Session'
                ]
            ]
        ];
        
        echo "\n🧪 Testing Midtrans API call...\n";
        $charge = \Midtrans\CoreApi::charge($params);
        
        if (isset($charge->actions) && !empty($charge->actions)) {
            echo "✅ Midtrans API working! QR URL generated\n";
            echo "QR URL: " . $charge->actions[0]->url . "\n";
        } else {
            echo "❌ No QR code generated in response\n";
            echo "Response: " . json_encode($charge) . "\n";
        }
        
    } catch (Exception $e) {
        echo "❌ Midtrans API error: " . $e->getMessage() . "\n";
    }
} else {
    echo "❌ SERVER_KEY not configured properly\n";
}
?>
