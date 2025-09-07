<?php
session_start();

// Check admin access
if (!isset($_SESSION['admin_access']) || $_SESSION['admin_access'] !== true) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Access denied']);
    exit();
}

require_once '../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = [
        'success' => false,
        'message' => '',
        'uploaded' => 0,
        'failed' => 0,
        'details' => []
    ];

    // Validate if files were uploaded
    if (!isset($_FILES['sticker_images']) || !is_array($_FILES['sticker_images']['tmp_name'])) {
        $response['message'] = 'No files uploaded';
        echo json_encode($response);
        exit();
    }

    $uploadDir = '../../uploads/stickers/';
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $maxSize = 5 * 1024 * 1024; // 5MB

    // Create upload directory if it doesn't exist
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileCount = count($_FILES['sticker_images']['tmp_name']);
    $namePrefix = $_POST['name_prefix'] ?? 'Sticker';
    $layoutId = $_POST['layout_id'] ?? null;

    // Validate layout_id if provided
    if ($layoutId !== null && $layoutId !== '') {
        $layoutId = intval($layoutId);
        if ($layoutId < 1 || $layoutId > 6) {
            $layoutId = null;
        }
    } else {
        $layoutId = null;
    }

    for ($i = 0; $i < $fileCount; $i++) {
        $tmpName = $_FILES['sticker_images']['tmp_name'][$i];
        $originalName = $_FILES['sticker_images']['name'][$i];
        $fileType = $_FILES['sticker_images']['type'][$i];
        $fileSize = $_FILES['sticker_images']['size'][$i];
        $error = $_FILES['sticker_images']['error'][$i];

        $fileDetail = [
            'original_name' => $originalName,
            'success' => false,
            'message' => ''
        ];

        // Skip if no file uploaded for this index
        if ($error === UPLOAD_ERR_NO_FILE) {
            continue;
        }

        // Check for upload errors
        if ($error !== UPLOAD_ERR_OK) {
            $fileDetail['message'] = 'Upload error: ' . $error;
            $response['details'][] = $fileDetail;
            $response['failed']++;
            continue;
        }

        // Validate file type
        if (!in_array($fileType, $allowedTypes)) {
            $fileDetail['message'] = 'Invalid file type. Please upload JPG, PNG, GIF, or WebP';
            $response['details'][] = $fileDetail;
            $response['failed']++;
            continue;
        }

        // Validate file size
        if ($fileSize > $maxSize) {
            $fileDetail['message'] = 'File too large. Maximum size is 5MB';
            $response['details'][] = $fileDetail;
            $response['failed']++;
            continue;
        }

        // Generate unique filename
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $baseFileName = pathinfo($originalName, PATHINFO_FILENAME);
        $uniqueFileName = 'sticker_' . uniqid() . '_' . time() . '.' . $extension;
        $targetPath = $uploadDir . $uniqueFileName;

        // Move uploaded file
        if (move_uploaded_file($tmpName, $targetPath)) {
            try {
                // Generate sticker name
                $stickerName = $namePrefix . ' ' . ($i + 1) . ' - ' . $baseFileName;

                // Save to database
                $result = Database::insertSticker(
                    $stickerName,
                    $uniqueFileName,
                    'uploads/stickers/' . $uniqueFileName,
                    $fileSize,
                    $layoutId
                );

                if ($result) {
                    $fileDetail['success'] = true;
                    $fileDetail['message'] = 'Successfully uploaded';
                    $fileDetail['sticker_name'] = $stickerName;
                    $fileDetail['layout_id'] = $layoutId;
                    $response['uploaded']++;
                } else {
                    $fileDetail['message'] = 'Database error';
                    $response['failed']++;
                    // Delete the uploaded file since database insert failed
                    unlink($targetPath);
                }
            } catch (Exception $e) {
                $fileDetail['message'] = 'Database error: ' . $e->getMessage();
                $response['failed']++;
                // Delete the uploaded file since database insert failed
                unlink($targetPath);
            }
        } else {
            $fileDetail['message'] = 'Failed to move uploaded file';
            $response['failed']++;
        }

        $response['details'][] = $fileDetail;
    }

    // Set overall success status
    $response['success'] = $response['uploaded'] > 0;

    if ($response['uploaded'] > 0 && $response['failed'] == 0) {
        $response['message'] = "Successfully uploaded {$response['uploaded']} stickers";
    } elseif ($response['uploaded'] > 0 && $response['failed'] > 0) {
        $response['message'] = "Uploaded {$response['uploaded']} stickers, {$response['failed']} failed";
    } else {
        $response['message'] = "All uploads failed";
    }

    echo json_encode($response);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
