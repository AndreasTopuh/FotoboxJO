<?php
// Midtrans payment notification handler
require_once __DIR__ . '/../vendor/autoload.php';

use Midtrans\Config;
use Midtrans\Notification;

// Configure Midtrans
Config::$serverKey = 'SB-Mid-server-YOUR_SERVER_KEY_HERE';
Config::$isProduction = false; // Set to true for production

try {
    // Create notification object from POST data
    $notification = new Notification();

    // Get transaction data from notification object
    $transaction_status = $notification->transaction_status;
    $order_id = $notification->order_id;
    $fraud_status = isset($notification->fraud_status) ? $notification->fraud_status : '';

    // Log the notification (you can enhance this)
    error_log("Payment notification received for order: $order_id, status: $transaction_status");

    // Process based on transaction status
    if ($transaction_status == 'capture') {
        if ($fraud_status == 'challenge') {
            // Challenge fraud status, set payment status in session/database
            error_log("Payment challenge for order: $order_id");
        } else if ($fraud_status == 'accept') {
            // Success payment, set payment status
            session_start();
            $_SESSION['payment_status'] = 'success';
            $_SESSION['order_id'] = $order_id;
            $_SESSION['has_paid'] = true;
            error_log("Payment success for order: $order_id");
        }
    } else if ($transaction_status == 'settlement') {
        // Success payment
        session_start();
        $_SESSION['payment_status'] = 'success';
        $_SESSION['order_id'] = $order_id;
        $_SESSION['has_paid'] = true;
        error_log("Payment settled for order: $order_id");
    } else if ($transaction_status == 'pending') {
        // Payment pending
        session_start();
        $_SESSION['payment_status'] = 'pending';
        $_SESSION['order_id'] = $order_id;
        error_log("Payment pending for order: $order_id");
    } else if ($transaction_status == 'deny') {
        // Payment denied
        session_start();
        $_SESSION['payment_status'] = 'denied';
        error_log("Payment denied for order: $order_id");
    } else if ($transaction_status == 'expire') {
        // Payment expired
        session_start();
        $_SESSION['payment_status'] = 'expired';
        error_log("Payment expired for order: $order_id");
    } else if ($transaction_status == 'cancel') {
        // Payment canceled
        session_start();
        $_SESSION['payment_status'] = 'canceled';
        error_log("Payment canceled for order: $order_id");
    }

    // Return 200 OK to Midtrans
    http_response_code(200);
    echo "OK";
} catch (Exception $e) {
    error_log("Payment notification error: " . $e->getMessage());
    http_response_code(500);
    echo "Error: " . $e->getMessage();
}
