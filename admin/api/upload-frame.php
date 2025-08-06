<?php
session_start();

// Check admin access
if (!isset($_SESSION['admin_access']) || $_SESSION['admin_access'] !== true) {
    header('Location: ../admin-login.php');
    exit();
}

require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $warna = trim($_POST['warna'] ?? '#FFFFFF');

    if (empty($nama)) {
        header('Location: ../admin.php?section=assets&msg=error: Frame name is required');
        exit();
    }

    // Validate color format
    if (!preg_match('/^#[0-9A-Fa-f]{6}$/', $warna)) {
        header('Location: ../admin.php?section=assets&msg=error: Invalid color format');
        exit();
    }

    // Handle file upload
    if (isset($_FILES['frame_image']) && $_FILES['frame_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../../uploads/frames/';
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        $fileType = $_FILES['frame_image']['type'];
        $fileSize = $_FILES['frame_image']['size'];
        $tmpName = $_FILES['frame_image']['tmp_name'];

        // Validate file type
        if (!in_array($fileType, $allowedTypes)) {
            header('Location: ../admin.php?section=assets&msg=error: Invalid file type. Please upload JPG, PNG, GIF, or WebP');
            exit();
        }

        // Validate file size
        if ($fileSize > $maxSize) {
            header('Location: ../admin.php?section=assets&msg=error: File too large. Maximum size is 5MB');
            exit();
        }

        // Generate unique filename
        $extension = pathinfo($_FILES['frame_image']['name'], PATHINFO_EXTENSION);
        $filename = 'frame_' . time() . '_' . uniqid() . '.' . $extension;
        $filePath = $uploadDir . $filename;
        $relativePath = '/uploads/frames/' . $filename;

        // Create directory if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Move uploaded file
        if (move_uploaded_file($tmpName, $filePath)) {
            // Save to database
            try {
                if (Database::insertFrame($nama, $warna, $filename, $relativePath, $fileSize)) {
                    header('Location: ../admin.php?section=assets&msg=success: Frame uploaded successfully');
                } else {
                    // Delete the uploaded file if database insert fails
                    unlink($filePath);
                    header('Location: ../admin.php?section=assets&msg=error: Failed to save frame to database');
                }
            } catch (Exception $e) {
                // Delete the uploaded file if database error
                unlink($filePath);
                header('Location: ../admin.php?section=assets&msg=error: Database error: ' . $e->getMessage());
            }
        } else {
            header('Location: ../admin.php?section=assets&msg=error: Failed to upload file');
        }
    } else {
        $error = match ($_FILES['frame_image']['error']) {
            UPLOAD_ERR_INI_SIZE => 'File too large (server limit)',
            UPLOAD_ERR_FORM_SIZE => 'File too large (form limit)',
            UPLOAD_ERR_PARTIAL => 'File partially uploaded',
            UPLOAD_ERR_NO_FILE => 'No file selected',
            UPLOAD_ERR_NO_TMP_DIR => 'No temporary directory',
            UPLOAD_ERR_CANT_WRITE => 'Cannot write to disk',
            UPLOAD_ERR_EXTENSION => 'Upload stopped by extension',
            default => 'Unknown upload error'
        };

        header('Location: ../admin.php?section=assets&msg=error: ' . $error);
    }
} else {
    header('Location: ../admin.php?section=assets');
}

exit();
