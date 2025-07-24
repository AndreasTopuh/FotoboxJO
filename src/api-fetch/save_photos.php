<?php
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (isset($input['photos']) && is_array($input['photos'])) {
        // Simpan foto-foto ke session
        $_SESSION['captured_photos'] = $input['photos'];
        
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Photos data not provided or invalid']);
    }
} else {
    echo json_encode(['error' => 'Invalid request method']);
}
?>
