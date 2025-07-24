<?php
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Reset previous customize session jika ada
    unset($_SESSION['customize_start_time']);
    unset($_SESSION['customize_expired_time']);
    
    // Set session customize dengan waktu expired 3 menit
    $_SESSION['customize_start_time'] = time();
    $_SESSION['customize_expired_time'] = time() + (3 * 60); // 3 menit
    $_SESSION['session_type'] = 'customize';
    
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Invalid request method']);
}
?>
