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

// Reset session menggunakan SessionManager
SessionManager::destroySession();

echo json_encode([
    'success' => true,
    'message' => 'Session reset successfully'
]);
?>
