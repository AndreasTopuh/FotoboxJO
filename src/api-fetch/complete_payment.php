<?php
require_once '../includes/session-manager.php';

session_start();

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Validate session state - should be in payment pending
    if (SessionManager::getSessionState() !== SessionManager::STATE_PAYMENT_PENDING) {
        echo json_encode([
            'success' => false,
            'error' => 'Invalid session state for payment completion',
            'current_state' => SessionManager::getSessionState()
        ]);
        exit();
    }
    
    // Validate session
    if (!SessionManager::isValidSession()) {
        echo json_encode([
            'success' => false,
            'error' => 'Invalid or expired session'
        ]);
        exit();
    }
    
    // Complete payment and transition to layout selection state
    $orderId = $input['order_id'] ?? null;
    $success = SessionManager::completePayment($orderId);
    
    if ($success) {
        echo json_encode([
            'success' => true,
            'message' => 'Payment completed successfully',
            'order_id' => $orderId,
            'session_state' => SessionManager::getSessionState(),
            'session_info' => SessionManager::getSessionInfo()
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'error' => 'Failed to complete payment'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'error' => 'Invalid request method'
    ]);
}
?>
