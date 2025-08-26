<?php
session_start();

// Set headers untuk menangani CORS
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Reset previous customize session jika ada
        unset($_SESSION['customize_start_time']);
        unset($_SESSION['customize_expired_time']);
        
        // Set session customize dengan waktu expired 3 menit
        $_SESSION['customize_start_time'] = time();
        $_SESSION['customize_expired_time'] = time() + (3 * 60); // 3 menit
        $_SESSION['session_type'] = 'customize';
        
        echo json_encode([
            'success' => true,
            'message' => 'Customize session created',
            'expires_in' => 180 // 3 minutes in seconds
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => 'Failed to create customize session: ' . $e->getMessage()
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed. Use POST.']);
}
?>
