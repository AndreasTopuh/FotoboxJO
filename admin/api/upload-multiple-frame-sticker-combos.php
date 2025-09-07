<?php
// Clear any output buffer and ensure clean JSON response
ob_clean();
ini_set('display_errors', 0);
error_reporting(0);

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

    // Validate layout_id
    $layoutId = $_POST['layout_id'] ?? '';

    // Debug info for troubleshooting
    $debugInfo = [
        'received_layout_id' => $layoutId,
        'post_keys' => array_keys($_POST),
        'files_keys' => array_keys($_FILES)
    ];

    if (empty($layoutId) || !in_array($layoutId, ['1', '2', '3', '4', '5', '6'])) {
        $response['message'] = 'Invalid layout ID. Debug: ' . json_encode($debugInfo);
        echo json_encode($response);
        exit();
    }
    $layoutId = intval($layoutId);

    // Validate if files were uploaded
    if (!isset($_FILES['combo_images']) || !is_array($_FILES['combo_images']['tmp_name'])) {
        $response['message'] = 'No files uploaded';
        echo json_encode($response);
        exit();
    }

    $uploadDir = '../../uploads/frame-sticker-combos/layout' . $layoutId . '/';
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $maxSize = 5 * 1024 * 1024; // 5MB

    // Create upload directory if it doesn't exist
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileCount = count($_FILES['combo_images']['tmp_name']);
    $namePrefix = $_POST['name_prefix'] ?? 'Layout ' . $layoutId . ' Combo';

    for ($i = 0; $i < $fileCount; $i++) {
        $tmpName = $_FILES['combo_images']['tmp_name'][$i];
        $originalName = $_FILES['combo_images']['name'][$i];
        $fileType = $_FILES['combo_images']['type'][$i];
        $fileSize = $_FILES['combo_images']['size'][$i];
        $error = $_FILES['combo_images']['error'][$i];

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
        $uniqueFileName = 'layout' . $layoutId . '_combo_' . uniqid() . '_' . dechex(time()) . '.' . $extension;
        $targetPath = $uploadDir . $uniqueFileName;

        // Move uploaded file
        if (move_uploaded_file($tmpName, $targetPath)) {
            try {
                // Generate combo name
                $comboName = $namePrefix . ' ' . ($i + 1) . ' - ' . $baseFileName;

                // Save to database
                $result = Database::insertFrameStickerCombo(
                    $layoutId,
                    $comboName,
                    $uniqueFileName,
                    '/uploads/frame-sticker-combos/layout' . $layoutId . '/' . $uniqueFileName,
                    $fileSize
                );

                if ($result) {
                    $fileDetail['success'] = true;
                    $fileDetail['message'] = 'Successfully uploaded to Layout ' . $layoutId;
                    $fileDetail['combo_name'] = $comboName;
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
        $response['message'] = "Successfully uploaded {$response['uploaded']} frame-sticker combos to Layout {$layoutId}";
    } elseif ($response['uploaded'] > 0 && $response['failed'] > 0) {
        $response['message'] = "Uploaded {$response['uploaded']} combos to Layout {$layoutId}, {$response['failed']} failed";
    } else {
        $response['message'] = "All uploads failed";
    }

    echo json_encode($response);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
