<?php
require_once '../includes/session-manager.php';

SessionManager::initializeSession();

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
$timeRemaining = SessionManager::getMainTimerRemaining();
$isExpired = SessionManager::isMainTimerExpired();

if (!$sessionInfo || $isExpired) {
    echo json_encode([
        'success' => true,
        'session_active' => false,
        'expired' => true,
        'time_remaining' => 0,
        'redirect' => '/index.php',
        'message' => 'Session expired or invalid'
    ]);
    exit();
}

// Return session information dengan timer details
echo json_encode([
    'success' => true,
    'session_active' => true,
    'expired' => false,
    'session_state' => $sessionInfo['session_state'],
    'state_display' => SessionManager::getStateDisplayName($sessionInfo['session_state']),
    'session_type' => $sessionInfo['session_type'],
    'time_remaining' => $timeRemaining,
    'main_timer_start' => $_SESSION['main_timer_start'] ?? null,
    'session_expires' => $_SESSION['session_expires'] ?? null,
    'payment_completed' => $sessionInfo['payment_completed'],
    'order_id' => $sessionInfo['order_id'],
    'selected_layout' => $sessionInfo['selected_layout'],
    'expires_at' => isset($_SESSION['session_expires']) ? date('Y-m-d H:i:s', $_SESSION['session_expires']) : null,
    'can_access_payment' => SessionManager::canAccessPaymentPage(),
    'can_access_layout' => SessionManager::canAccessLayoutSelection(),
    'can_access_canvas' => SessionManager::canAccessCanvas()
]);
?>
