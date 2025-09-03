<?php
session_start();

// Check admin access
if (!isset($_SESSION['admin_access']) || $_SESSION['admin_access'] !== true) {
    header('Location: ../admin-login.php');
    exit();
}

require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $layout_id = $_POST['layout_id'] ?? null;
        $nama = $_POST['nama'] ?? '';

        if (!$layout_id || $layout_id < 1 || $layout_id > 6) {
            throw new Exception('Invalid layout ID');
        }

        if (!isset($_FILES['combo_image']) || $_FILES['combo_image']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Upload error: ' . ($_FILES['combo_image']['error'] ?? 'No file'));
        }

        $uploadFile = $_FILES['combo_image'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

        if (!in_array($uploadFile['type'], $allowedTypes)) {
            throw new Exception('Invalid file type. Only JPEG, PNG, GIF, and WebP are allowed.');
        }

        $maxSize = 10 * 1024 * 1024; // 10MB
        if ($uploadFile['size'] > $maxSize) {
            throw new Exception('File too large. Maximum size is 10MB.');
        }

        // Create upload directory if it doesn't exist
        $uploadDir = '../../uploads/frame-sticker-combos/layout' . $layout_id . '/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Generate unique filename
        $fileExtension = pathinfo($uploadFile['name'], PATHINFO_EXTENSION);
        $filename = 'layout' . $layout_id . '_combo_' . time() . '_' . uniqid() . '.' . $fileExtension;
        $filePath = $uploadDir . $filename;

        if (move_uploaded_file($uploadFile['tmp_name'], $filePath)) {
            // Insert into database
            $dbFilePath = '/uploads/frame-sticker-combos/layout' . $layout_id . '/' . $filename;
            $success = Database::insertFrameStickerCombo($layout_id, $nama, $filename, $dbFilePath, $uploadFile['size']);

            if ($success) {
                header('Location: ../admin.php?section=frame-sticker-combos&msg=Layout ' . $layout_id . ' frame & sticker combo uploaded successfully');
            } else {
                unlink($filePath); // Delete uploaded file if database insert fails
                throw new Exception('Database insert failed');
            }
        } else {
            throw new Exception('Failed to move uploaded file');
        }
    } catch (Exception $e) {
        header('Location: ../admin.php?section=frame-sticker-combos&msg=Error: ' . urlencode($e->getMessage()));
    }
} else {
    header('Location: ../admin.php?section=frame-sticker-combos');
}
