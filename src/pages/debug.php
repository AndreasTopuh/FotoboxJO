<?php
session_start();

header('Content-Type: application/json');

echo json_encode([
    'session_data' => $_SESSION,
    'session_id' => session_id(),
    'php_version' => PHP_VERSION,
    'server_time' => date('Y-m-d H:i:s'),
    'working_directory' => getcwd(),
    'include_path' => get_include_path(),
    'files_exist' => [
        'selectlayout.php' => file_exists('selectlayout.php'),
        'pwa-helper.php' => file_exists('../includes/pwa-helper.php'),
        'payment-qris.php' => file_exists('payment-qris.php')
    ]
]);
?>
