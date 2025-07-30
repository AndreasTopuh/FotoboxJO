<?php
// Set proper session configuration for PWA Production
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1); // HTTPS in production
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.use_strict_mode', 0);

session_start();

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    $updated_fields = [];
    
    // Handle order ID setting
    if (isset($input['order_id'])) {
        $_SESSION['current_order_id'] = $input['order_id'];
        $_SESSION['payment_completed'] = true;
        $updated_fields[] = 'order_id';
    }
    
    // Handle payment completion
    if (isset($input['payment_completed'])) {
        $_SESSION['payment_completed'] = $input['payment_completed'];
        $updated_fields[] = 'payment_completed';
    }
    
    // Handle session type
    if (isset($input['session_type'])) {
        $_SESSION['session_type'] = $input['session_type'];
        $updated_fields[] = 'session_type';
    }
    
    // Handle extended session time for PWA
    if (isset($input['payment_expired_time'])) {
        $_SESSION['payment_expired_time'] = $input['payment_expired_time'];
        $updated_fields[] = 'payment_expired_time';
    } elseif (isset($input['payment_completed']) && $input['payment_completed'] === true) {
        // Auto-set expiration time if payment completed
        $_SESSION['payment_expired_time'] = time() + (15 * 60); // 15 minutes
        $updated_fields[] = 'auto_payment_expired_time';
    }
    
    // Handle manual session expires (for testing)
    if (isset($input['session_expires'])) {
        $_SESSION['session_expires'] = $input['session_expires'];
        $updated_fields[] = 'session_expires';
    }
    
    // Set session type to layout selection if payment completed
    if (isset($input['payment_completed']) && $input['payment_completed'] === true) {
        $_SESSION['session_type'] = 'layout_selection';
        if (!in_array('session_type', $updated_fields)) {
            $updated_fields[] = 'auto_session_type';
        }
    }
    
    echo json_encode([
        'success' => true, 
        'session_updated' => true,
        'session_id' => session_id(),
        'updated_fields' => $updated_fields,
        'current_session' => $_SESSION,
        'timestamp' => date('Y-m-d H:i:s')
    ]);
} else {
    echo json_encode([
        'error' => 'Invalid request method',
        'method' => $_SERVER['REQUEST_METHOD'],
        'allowed' => 'POST'
    ]);
}
?>
