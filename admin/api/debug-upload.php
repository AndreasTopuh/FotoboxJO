<?php
session_start();

// Check admin access
if (!isset($_SESSION['admin_access']) || $_SESSION['admin_access'] !== true) {
    header('Location: ../admin-login.php');
    exit();
}

require_once '../config/database.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $warna = trim($_POST['warna'] ?? '#FFFFFF');

    echo "<h2>Debug Upload Frame</h2>";
    echo "<p><strong>Frame Name:</strong> " . htmlspecialchars($nama) . "</p>";
    echo "<p><strong>Frame Color:</strong> " . htmlspecialchars($warna) . "</p>";

    if (empty($nama)) {
        echo "<p style='color: red;'>Error: Frame name is required</p>";
        exit();
    }

    // Validate color format
    if (!preg_match('/^#[0-9A-Fa-f]{6}$/', $warna)) {
        echo "<p style='color: red;'>Error: Invalid color format</p>";
        exit();
    }

    echo "<h3>File Upload Debug:</h3>";
    echo "<pre>";
    print_r($_FILES);
    echo "</pre>";

    // Handle file upload
    if (isset($_FILES['frame_image'])) {
        $uploadDir = '../../uploads/frames/';
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        echo "<p><strong>Upload Directory:</strong> " . $uploadDir . "</p>";
        echo "<p><strong>Upload Directory Exists:</strong> " . (is_dir($uploadDir) ? 'YES' : 'NO') . "</p>";
        echo "<p><strong>Upload Directory Writable:</strong> " . (is_writable($uploadDir) ? 'YES' : 'NO') . "</p>";

        if ($_FILES['frame_image']['error'] === UPLOAD_ERR_OK) {
            $fileType = $_FILES['frame_image']['type'];
            $fileSize = $_FILES['frame_image']['size'];
            $tmpName = $_FILES['frame_image']['tmp_name'];

            echo "<p><strong>File Type:</strong> " . $fileType . "</p>";
            echo "<p><strong>File Size:</strong> " . $fileSize . " bytes</p>";
            echo "<p><strong>Temp Name:</strong> " . $tmpName . "</p>";
            echo "<p><strong>Temp File Exists:</strong> " . (file_exists($tmpName) ? 'YES' : 'NO') . "</p>";

            // Validate file type
            if (!in_array($fileType, $allowedTypes)) {
                echo "<p style='color: red;'>Error: Invalid file type. Please upload JPG, PNG, GIF, or WebP</p>";
                echo "<p>Allowed types: " . implode(', ', $allowedTypes) . "</p>";
                exit();
            }

            // Validate file size
            if ($fileSize > $maxSize) {
                echo "<p style='color: red;'>Error: File too large. Maximum size is 5MB</p>";
                exit();
            }

            // Generate unique filename
            $extension = pathinfo($_FILES['frame_image']['name'], PATHINFO_EXTENSION);
            $filename = 'frame_' . time() . '_' . uniqid() . '.' . $extension;
            $filePath = $uploadDir . $filename;
            $relativePath = '/uploads/frames/' . $filename;

            echo "<p><strong>Generated Filename:</strong> " . $filename . "</p>";
            echo "<p><strong>Full Path:</strong> " . $filePath . "</p>";
            echo "<p><strong>Relative Path:</strong> " . $relativePath . "</p>";

            // Create directory if it doesn't exist
            if (!is_dir($uploadDir)) {
                echo "<p>Creating directory: " . $uploadDir . "</p>";
                $created = mkdir($uploadDir, 0755, true);
                echo "<p>Directory created: " . ($created ? 'YES' : 'NO') . "</p>";
            }

            // Move uploaded file
            echo "<p>Attempting to move uploaded file...</p>";
            if (move_uploaded_file($tmpName, $filePath)) {
                echo "<p style='color: green;'>File uploaded successfully!</p>";

                // Save to database
                try {
                    $result = Database::insertFrame($nama, $warna, $filename, $relativePath, $fileSize);
                    echo "<p><strong>Database Insert Result:</strong> " . ($result ? 'SUCCESS' : 'FAILED') . "</p>";

                    if ($result) {
                        echo "<p style='color: green;'>Frame saved to database successfully!</p>";
                        echo "<a href='../admin.php?section=assets&msg=success: Frame uploaded successfully'>Go back to admin</a>";
                    } else {
                        echo "<p style='color: red;'>Failed to save frame to database</p>";
                        // Delete the uploaded file if database insert fails
                        unlink($filePath);
                    }
                } catch (Exception $e) {
                    echo "<p style='color: red;'>Database error: " . $e->getMessage() . "</p>";
                    // Delete the uploaded file if database error
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
            } else {
                echo "<p style='color: red;'>Failed to move uploaded file</p>";
                echo "<p><strong>Error:</strong> " . error_get_last()['message'] . "</p>";
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
            echo "<p style='color: red;'>Upload Error: " . $error . " (Code: " . $_FILES['frame_image']['error'] . ")</p>";
        }
    } else {
        echo "<p style='color: red;'>No file uploaded</p>";
    }
} else {
    echo "<p>This script only accepts POST requests</p>";
    echo "<a href='../admin.php?section=assets'>Go back to admin</a>";
}
