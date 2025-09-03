<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../../admin/config/database.php';

try {
    // Get layout_id from query parameter
    $layout_id = isset($_GET['layout_id']) ? (int)$_GET['layout_id'] : null;

    if (!$layout_id || $layout_id < 1 || $layout_id > 6) {
        throw new Exception('Invalid layout_id. Must be between 1-6.');
    }

    $combos = Database::getFrameStickerComboByLayout($layout_id);

    // Transform data untuk frontend
    $response = [
        'success' => true,
        'data' => $combos,
        'layout_id' => $layout_id,
        'count' => count($combos)
    ];

    echo json_encode($response);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
