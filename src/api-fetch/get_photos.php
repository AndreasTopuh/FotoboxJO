<?php
session_start();

// Include debug logger
if (file_exists(__DIR__ . '/../../debug_logger.php')) {
    require_once(__DIR__ . '/../../debug_logger.php');
} else {
    // Fallback function if debug_logger.php doesn't exist
    function debugLog($message, $context = null) {
        // Simple fallback - you can implement actual logging here
        error_log("DEBUG: $message" . ($context ? ' - ' . json_encode($context) : ''));
    }
}
debugLog("🔄 get_photos.php called", $_SERVER['REQUEST_METHOD']);

// Set headers untuk menangani CORS
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

// Set timeout untuk request besar
set_time_limit(30);
ini_set('max_execution_time', 30);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        debugLog("📥 Processing GET request", ['session_id' => session_id(), 'session_type' => $_SESSION['session_type'] ?? 'unknown']);
        
        // Log all session keys for debugging
        $sessionKeys = array_keys($_SESSION);
        debugLog("🔍 Session debugging", ['all_session_keys' => $sessionKeys]);
        
        if (isset($_SESSION['captured_photos']) && is_array($_SESSION['captured_photos'])) {
            $photos = $_SESSION['captured_photos'];
            $photosCount = count($photos);
            $dataSize = strlen(json_encode($photos));
            
            debugLog("✅ Photos found in session", ['count' => $photosCount, 'size_mb' => round($dataSize / 1024 / 1024, 2)]);
            
            echo json_encode([
                'success' => true, 
                'photos' => $photos,
                'count' => $photosCount,
                'data_size' => round($dataSize / 1024 / 1024, 2) . 'MB',
                'session_time' => isset($_SESSION['photos_saved_time']) ? $_SESSION['photos_saved_time'] : 'unknown'
            ]);
        } else {
            // Session expired or no photos - provide more details
            $sessionInfo = [
                'session_keys' => $sessionKeys,
                'session_type' => $_SESSION['session_type'] ?? 'unknown',
                'customize_start_time' => $_SESSION['customize_start_time'] ?? null,
                'customize_expired_time' => $_SESSION['customize_expired_time'] ?? null,
                'current_time' => time()
            ];
            debugLog("❌ No photos in session", $sessionInfo);
            
            http_response_code(404);
            echo json_encode([
                'success' => false, 
                'error' => 'No photos found in session. Session might have expired.',
                'debug_info' => $sessionInfo
            ]);
        }
    } catch (Exception $e) {
        debugLog("❌ Error in get_photos", ['error' => $e->getMessage()]);
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => 'Server error: ' . $e->getMessage()
        ]);
    }
} else {
    debugLog("❌ Invalid method", $_SERVER['REQUEST_METHOD']);
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed. Use GET.']);
}
?>
