<?php
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_SESSION['captured_photos'])) {
        echo json_encode([
            'success' => true, 
            'photos' => $_SESSION['captured_photos']
        ]);
    } else {
        echo json_encode([
            'success' => false, 
            'error' => 'No photos found in session'
        ]);
    }
} else {
    echo json_encode(['error' => 'Invalid request method']);
}
?>
