<?php
session_start();

header('Content-Type: application/json');

echo json_encode([
    'session_id' => session_id(),
    'session_status' => session_status(),
    'session_data' => [
        'captured_photos' => isset($_SESSION['captured_photos']) ? 'YES (' . count($_SESSION['captured_photos']) . ' photos)' : 'NO',
        'photos_saved_time' => $_SESSION['photos_saved_time'] ?? 'not set',
        'customize_start_time' => $_SESSION['customize_start_time'] ?? 'not set',
        'session_type' => $_SESSION['session_type'] ?? 'not set'
    ],
    'php_config' => [
        'session_save_path' => session_save_path() ?: ini_get('session.save_path') ?: '/tmp',
        'max_execution_time' => ini_get('max_execution_time'),
        'memory_limit' => ini_get('memory_limit'),
        'post_max_size' => ini_get('post_max_size'),
        'upload_max_filesize' => ini_get('upload_max_filesize')
    ]
]);
?>
