<?php
session_start();

// Include debug logger
if (file_exists(__DIR__ . '/../../debug_logger.php')) {
    require_once(__DIR__ . '/../../debug_logger.php');
} else {
    // Fallback function if debug_logger.php doesn't exist
    function debugLog($message, $context = null) {
        error_log("DEBUG: $message" . ($context ? ' - ' . json_encode($context) : ''));
    }
}

debugLog("🔄 create_customize_session.php called", $_SERVER['REQUEST_METHOD']);

// Set headers untuk menangani CORS
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        debugLog("📥 Creating customize session", ['session_id' => session_id()]);
        
        // Check if photos exist in session before creating customize session
        $hasPhotos = isset($_SESSION['captured_photos']) && is_array($_SESSION['captured_photos']) && count($_SESSION['captured_photos']) > 0;
        debugLog("📊 Photos in session", ['has_photos' => $hasPhotos, 'count' => $hasPhotos ? count($_SESSION['captured_photos']) : 0]);
        
        // Reset previous customize session jika ada
        unset($_SESSION['customize_start_time']);
        unset($_SESSION['customize_expired_time']);
        
        // Set session customize dengan waktu expired 15 menit (sama dengan customizeLayout)
        $_SESSION['customize_start_time'] = time();
        $_SESSION['customize_expired_time'] = time() + (15 * 60); // 15 menit
        $_SESSION['session_type'] = 'customize';
        
        debugLog("✅ Customize session created", ['expires_at' => $_SESSION['customize_expired_time'], 'has_photos' => $hasPhotos]);
        
        echo json_encode([
            'success' => true,
            'message' => 'Customize session created',
            'expires_in' => 900, // 15 minutes in seconds
            'has_photos' => $hasPhotos,
            'photos_count' => $hasPhotos ? count($_SESSION['captured_photos']) : 0
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
