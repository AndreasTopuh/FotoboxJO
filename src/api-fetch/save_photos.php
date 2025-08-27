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
debugLog("🔄 save_photos.php called", $_SERVER['REQUEST_METHOD']);

// Set headers untuk menangani CORS dan timeout
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Set timeout lebih lama untuk request besar
set_time_limit(60);
ini_set('max_execution_time', 60);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        debugLog("📥 Processing POST request");
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid JSON data: ' . json_last_error_msg());
        }
        
        debugLog("📊 JSON decoded successfully", ['photos_count' => isset($input['photos']) ? count($input['photos']) : 0]);
        
        if (isset($input['photos']) && is_array($input['photos'])) {
            // Validasi ukuran data
            $dataSize = strlen(json_encode($input['photos']));
            $maxSize = 50 * 1024 * 1024; // 50MB untuk session
            
            debugLog("💾 Data size check", ['size_mb' => round($dataSize / 1024 / 1024, 2), 'max_mb' => 50]);
            
            if ($dataSize > $maxSize) {
                throw new Exception('Photo data too large: ' . round($dataSize / 1024 / 1024, 2) . 'MB');
            }
            
            // Simpan foto-foto ke session
            $_SESSION['captured_photos'] = $input['photos'];
            $_SESSION['photos_saved_time'] = time();
            
            debugLog("✅ Photos saved to session", ['count' => count($input['photos']), 'session_id' => session_id()]);
            
            echo json_encode([
                'success' => true, 
                'photos_count' => count($input['photos']),
                'data_size' => round($dataSize / 1024 / 1024, 2) . 'MB'
            ]);
        } else {
            throw new Exception('Photos data not provided or invalid format');
        }
    } catch (Exception $e) {
        debugLog("❌ Error in save_photos", ['error' => $e->getMessage()]);
        http_response_code(400);
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    debugLog("❌ Invalid method", $_SERVER['REQUEST_METHOD']);
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed. Use POST.']);
}
?>
