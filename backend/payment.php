<?php
// Basic Midtrans payment implementation for FotoboxJO
session_start();

// Include Midtrans SDK
require_once __DIR__ . '/../vendor/autoload.php';

use Midtrans\Config;
use Midtrans\Snap;

// Configure Midtrans
// TODO: Replace with your actual Midtrans credentials
Config::$serverKey = 'SB-Mid-server-YOUR_SERVER_KEY_HERE';
Config::$clientKey = 'SB-Mid-client-YOUR_CLIENT_KEY_HERE';
Config::$isProduction = false; // Set to true for production
Config::$isSanitized = true;
Config::$is3ds = true;

// Check if this is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get request data
        $input = json_decode(file_get_contents('php://input'), true);

        // Basic validation
        if (!isset($input['amount']) || !isset($input['order_id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required fields']);
            exit;
        }

        // Transaction details
        $transaction_details = array(
            'order_id' => $input['order_id'],
            'gross_amount' => (int)$input['amount']
        );

        // Item details
        $item_details = array(
            array(
                'id' => 'photobooth_session',
                'price' => (int)$input['amount'],
                'quantity' => 1,
                'name' => 'Photobooth Session - ' . ($input['layout'] ?? 'Standard Layout')
            )
        );

        // Customer details (optional, can be enhanced)
        $customer_details = array(
            'first_name' => $input['customer_name'] ?? 'Customer',
            'email' => $input['email'] ?? 'customer@example.com',
            'phone' => $input['phone'] ?? '+62812345678'
        );

        // Transaction data
        $transaction = array(
            'transaction_details' => $transaction_details,
            'item_details' => $item_details,
            'customer_details' => $customer_details
        );

        // Get Snap token
        $snapToken = Snap::getSnapToken($transaction);

        // Return success response
        echo json_encode([
            'success' => true,
            'snap_token' => $snapToken,
            'order_id' => $input['order_id']
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'error' => 'Payment initialization failed',
            'message' => $e->getMessage()
        ]);
    }
} else {
    // Handle GET request or other methods
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
