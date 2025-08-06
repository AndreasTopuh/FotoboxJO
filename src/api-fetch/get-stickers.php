<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../../admin/config/database.php';

try {
    $stickers = Database::getAllStickers();

    // Transform data untuk frontend
    $response = [
        'success' => true,
        'data' => $stickers,
        'count' => count($stickers)
    ];

    echo json_encode($response);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database error: ' . $e->getMessage()
    ]);
}
