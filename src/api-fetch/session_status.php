<?php
require_once '../includes/session-manager.php';

session_start();

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Check session validity
$sessionInfo = SessionManager::getSessionInfo();

if (!$sessionInfo) {
    echo json_encode([
        'valid' => false,
        'expired' => true,
        'redirect' => '/index.php',
        'message' => 'Session expired or invalid'
    ]);
    exit();
}

// Return session information dengan state details
echo json_encode([
    'valid' => true,
    'expired' => false,
    'session_state' => $sessionInfo['session_state'],
    'state_display' => SessionManager::getStateDisplayName($sessionInfo['session_state']),
    'session_type' => $sessionInfo['session_type'],
    'time_remaining' => $sessionInfo['time_remaining'],
    'payment_completed' => $sessionInfo['payment_completed'],
    'order_id' => $sessionInfo['order_id'],
    'selected_layout' => $sessionInfo['selected_layout'],
    'expires_at' => date('Y-m-d H:i:s', $sessionInfo['expires_at']),
    'can_access_payment' => SessionManager::canAccessPaymentPage(),
    'can_access_layout' => SessionManager::canAccessLayoutSelection(),
    'can_access_canvas' => SessionManager::canAccessCanvas()
]);
?>
