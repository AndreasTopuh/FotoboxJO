<?php
header('Content-Type: application/json');

require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

try {
    // Validasi parameter layout_id
    if (!isset($_POST['layout_id']) || empty($_POST['layout_id'])) {
        throw new Exception('Layout ID is required');
    }

    $layout_id = intval($_POST['layout_id']);

    // Validasi range layout_id (1-6)
    if ($layout_id < 1 || $layout_id > 6) {
        throw new Exception('Invalid layout ID. Must be between 1-6');
    }

    // Validasi input
    if (!isset($_POST['nama']) || empty($_POST['nama'])) {
        throw new Exception('Frame name is required');
    }

    // Default warna jika tidak diset
    $warna = isset($_POST['warna']) ? $_POST['warna'] : '#FFFFFF';

    // Validasi file upload
    if (!isset($_FILES['frame']) || $_FILES['frame']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Please select a valid frame file');
    }

    $file = $_FILES['frame'];
    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];

    if (!in_array($file['type'], $allowed_types)) {
        throw new Exception('Invalid file type. Only JPG, PNG, and GIF are allowed');
    }

    // Generate unique filename
    $timestamp = time();
    $random = uniqid();
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = "frame_layout{$layout_id}_{$timestamp}_{$random}.{$extension}";

    // Upload directory
    $upload_dir = '../../uploads/frames/';
    $file_path = $upload_dir . $filename;

    // Create directory if not exists
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    // Move uploaded file
    if (!move_uploaded_file($file['tmp_name'], $file_path)) {
        throw new Exception('Failed to upload frame file');
    }

    // Save to database
    $relative_path = '/uploads/frames/' . $filename;
    $result = Database::insertFrameToLayout($layout_id, $_POST['nama'], $warna, $filename, $relative_path, $file['size']);

    if ($result) {
        echo json_encode([
            'success' => true,
            'message' => 'Frame uploaded successfully to layout ' . $layout_id,
            'filename' => $filename,
            'layout_id' => $layout_id
        ]);
    } else {
        // Delete file if database insert failed
        unlink($file_path);
        throw new Exception('Failed to save frame to database');
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
