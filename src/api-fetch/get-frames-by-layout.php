<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../../admin/config/database.php';

try {
    // Validasi parameter layout_id
    if (!isset($_GET['layout_id']) || empty($_GET['layout_id'])) {
        throw new Exception('Layout ID is required');
    }

    $layout_id = intval($_GET['layout_id']);

    // Validasi range layout_id (1-6)
    if ($layout_id < 1 || $layout_id > 6) {
        throw new Exception('Invalid layout ID. Must be between 1-6');
    }

    // Ambil frame berdasarkan layout
    $frames = Database::getFramesByLayout($layout_id);

    // Format response
    $response = [
        'success' => true,
        'layout_id' => $layout_id,
        'total_frames' => count($frames),
        'frames' => $frames
    ];

    echo json_encode($response);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
