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
    
    if (!isset($input['layout'])) {
        echo json_encode([
            'success' => false,
            'error' => 'Layout ID is required'
        ]);
        exit();
    }
    
    $layoutId = $input['layout'];
    
    // Validate current session state
    if (!SessionManager::canAccessLayoutSelection()) {
        echo json_encode([
            'success' => false,
            'error' => 'Invalid session state for layout selection',
            'current_state' => SessionManager::getSessionState()
        ]);
        exit();
    }
    
    // Select layout and transition state
    $success = SessionManager::selectLayout($layoutId);
    
    if ($success) {
        echo json_encode([
            'success' => true,
            'message' => 'Layout selected successfully',
            'layout' => $layoutId,
            'session_state' => SessionManager::getSessionState(),
            'session_info' => SessionManager::getSessionInfo()
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'error' => 'Failed to select layout',
            'current_state' => SessionManager::getSessionState()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'error' => 'Invalid request method'
    ]);
}
?>
