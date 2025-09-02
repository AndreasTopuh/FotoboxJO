<?php
header('Content-Type: application/json');

require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../admin.php?section=layout-frames&msg=Invalid request method');
    exit;
}

try {
    // Validasi parameter layout_id
    if (!isset($_POST['layout_id']) || empty($_POST['layout_id'])) {
        throw new Exception('Layout ID is required');
    }

    // Validasi parameter frame_id
    if (!isset($_POST['frame_id']) || empty($_POST['frame_id'])) {
        throw new Exception('Frame ID is required');
    }

    $layout_id = intval($_POST['layout_id']);
    $frame_id = intval($_POST['frame_id']);

    // Validasi range layout_id (1-6)
    if ($layout_id < 1 || $layout_id > 6) {
        throw new Exception('Invalid layout ID. Must be between 1-6');
    }

    // Get frame info before deletion for file cleanup
    $frame = Database::getFrameFromLayout($layout_id, $frame_id);

    if (!$frame) {
        throw new Exception('Frame not found');
    }

    // Delete from database (soft delete)
    $result = Database::deleteFrameFromLayout($layout_id, $frame_id);

    if ($result) {
        // Optional: Delete physical file (uncomment if you want to delete files)
        // $file_path = '../../' . ltrim($frame['file_path'], '/');
        // if (file_exists($file_path)) {
        //     unlink($file_path);
        // }

        header('Location: ../admin.php?section=layout-frames&msg=Frame deleted successfully from Layout ' . $layout_id);
        exit;
    } else {
        throw new Exception('Failed to delete frame from database');
    }
} catch (Exception $e) {
    header('Location: ../admin.php?section=layout-frames&msg=Error: ' . urlencode($e->getMessage()));
    exit;
}
