<?php
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (isset($input['layout'])) {
        // Reset previous photo session jika ada
        unset($_SESSION['photo_start_time']);
        unset($_SESSION['photo_expired_time']);
        unset($_SESSION['customize_start_time']);
        unset($_SESSION['customize_expired_time']);
        
        // Set session photo dengan waktu expired 7 menit
        $_SESSION['photo_start_time'] = time();
        $_SESSION['photo_expired_time'] = time() + (7 * 60); // 7 menit
        $_SESSION['session_type'] = 'photo';
        $_SESSION['selected_layout'] = $input['layout'];
        
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Layout not provided']);
    }
} else {
    echo json_encode(['error' => 'Invalid request method']);
}
?>
