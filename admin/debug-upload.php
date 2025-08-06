<?php
// filepath: /var/www/html/FotoboxJO/admin/debug-upload.php
session_start();

echo "<h2>Upload Debug Information</h2>";

// 1. Check PHP Configuration
echo "<h3>📋 PHP Upload Configuration</h3>";
echo "<table border='1' cellpadding='5'>";
echo "<tr><td><strong>Setting</strong></td><td><strong>Value</strong></td><td><strong>Status</strong></td></tr>";

$settings = [
    'file_uploads' => ini_get('file_uploads'),
    'upload_max_filesize' => ini_get('upload_max_filesize'),
    'post_max_size' => ini_get('post_max_size'),
    'max_file_uploads' => ini_get('max_file_uploads'),
    'upload_tmp_dir' => ini_get('upload_tmp_dir') ?: 'Default',
    'max_execution_time' => ini_get('max_execution_time')
];

foreach ($settings as $key => $value) {
    $status = ($key === 'file_uploads') ? ($value ? '✅ Enabled' : '❌ Disabled') : '📊 ' . $value;
    echo "<tr><td>$key</td><td>$value</td><td>$status</td></tr>";
}
echo "</table>";

// 2. Check Directory Permissions
echo "<h3>📁 Directory Permissions</h3>";
$directories = [
    '/var/www/html/FotoboxJO/uploads' => '../../uploads',
    '/var/www/html/FotoboxJO/uploads/frames' => '../../uploads/frames',
    '/var/www/html/FotoboxJO/uploads/stickers' => '../../uploads/stickers'
];

echo "<table border='1' cellpadding='5'>";
echo "<tr><td><strong>Directory</strong></td><td><strong>Exists</strong></td><td><strong>Writable</strong></td><td><strong>Permissions</strong></td></tr>";

foreach ($directories as $fullPath => $relativePath) {
    $exists = is_dir($relativePath);
    $writable = $exists ? is_writable($relativePath) : false;
    $perms = $exists ? substr(sprintf('%o', fileperms($relativePath)), -4) : 'N/A';
    
    $existsStatus = $exists ? '✅ Yes' : '❌ No';
    $writableStatus = $writable ? '✅ Yes' : '❌ No';
    
    echo "<tr><td>$fullPath</td><td>$existsStatus</td><td>$writableStatus</td><td>$perms</td></tr>";
}
echo "</table>";

// 3. Test File Upload Simulation
echo "<h3>🧪 Upload Test Form</h3>";
?>

<form method="POST" enctype="multipart/form-data" style="border: 1px solid #ddd; padding: 20px; margin: 10px 0;">
    <h4>Test Frame Upload</h4>
    <p>
        <label>Frame Name:</label><br>
        <input type="text" name="test_nama" value="Test Frame" required>
    </p>
    <p>
        <label>Select Image:</label><br>
        <input type="file" name="test_file" accept="image/*" required>
    </p>
    <p>
        <input type="submit" name="test_upload" value="Test Upload" style="background: #E28585; color: white; padding: 10px 20px; border: none; border-radius: 5px;">
    </p>
</form>

<?php
// 4. Process Test Upload
if (isset($_POST['test_upload'])) {
    echo "<h3>🔬 Upload Test Results</h3>";
    
    if (isset($_FILES['test_file'])) {
        echo "<h4>File Information:</h4>";
        echo "<pre>";
        print_r($_FILES['test_file']);
        echo "</pre>";
        
        $file = $_FILES['test_file'];
        $uploadDir = '../../uploads/frames/';
        
        echo "<h4>Upload Process:</h4>";
        echo "<ol>";
        
        // Check upload error
        echo "<li><strong>Upload Error Check:</strong> ";
        if ($file['error'] === UPLOAD_ERR_OK) {
            echo "✅ No upload errors</li>";
        } else {
            $errors = [
                UPLOAD_ERR_INI_SIZE => 'File too large (php.ini limit)',
                UPLOAD_ERR_FORM_SIZE => 'File too large (form limit)',
                UPLOAD_ERR_PARTIAL => 'File partially uploaded',
                UPLOAD_ERR_NO_FILE => 'No file selected',
                UPLOAD_ERR_NO_TMP_DIR => 'No temporary directory',
                UPLOAD_ERR_CANT_WRITE => 'Cannot write to disk',
                UPLOAD_ERR_EXTENSION => 'Upload stopped by extension'
            ];
            echo "❌ Error: " . ($errors[$file['error']] ?? 'Unknown error') . "</li>";
        }
        
        // Check temporary file
        echo "<li><strong>Temporary File:</strong> ";
        if (file_exists($file['tmp_name'])) {
            echo "✅ Temporary file exists at: " . $file['tmp_name'] . "</li>";
        } else {
            echo "❌ Temporary file not found</li>";
        }
        
        // Check directory
        echo "<li><strong>Upload Directory:</strong> ";
        if (!is_dir($uploadDir)) {
            echo "❌ Directory doesn't exist. Creating... ";
            if (mkdir($uploadDir, 0755, true)) {
                echo "✅ Created successfully</li>";
            } else {
                echo "❌ Failed to create</li>";
            }
        } else {
            echo "✅ Directory exists</li>";
        }
        
        // Check permissions
        echo "<li><strong>Directory Writable:</strong> ";
        if (is_writable($uploadDir)) {
            echo "✅ Directory is writable</li>";
        } else {
            echo "❌ Directory is not writable</li>";
        }
        
        // Try to move file
        if ($file['error'] === UPLOAD_ERR_OK && file_exists($file['tmp_name'])) {
            $filename = 'test_' . time() . '.jpg';
            $destination = $uploadDir . $filename;
            
            echo "<li><strong>File Move:</strong> ";
            if (move_uploaded_file($file['tmp_name'], $destination)) {
                echo "✅ File moved successfully to: $destination</li>";
                echo "<li><strong>File Verification:</strong> ";
                if (file_exists($destination)) {
                    echo "✅ File exists at destination</li>";
                    // Clean up test file
                    unlink($destination);
                    echo "<li>🧹 Test file cleaned up</li>";
                } else {
                    echo "❌ File not found at destination</li>";
                }
            } else {
                echo "❌ Failed to move file. ";
                $error = error_get_last();
                echo "Last error: " . ($error['message'] ?? 'Unknown') . "</li>";
            }
        }
        
        echo "</ol>";
    } else {
        echo "❌ No file uploaded";
    }
}

echo "<h3>💡 Recommended Actions</h3>";
echo "<ul>";
echo "<li>Check if all directories exist and are writable</li>";
echo "<li>Verify PHP upload settings</li>";
echo "<li>Check server error logs</li>";
echo "<li>Test with a small image file first</li>";
echo "</ul>";
?>
