<?php
session_start();

// Check admin access
if (!isset($_SESSION['admin_access']) || $_SESSION['admin_access'] !== true) {
    echo "Please login first";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h3>POST Data Received:</h3>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    echo "<h3>FILES Data Received:</h3>";
    echo "<pre>";
    print_r($_FILES);
    echo "</pre>";

    // Test the API directly
    if (isset($_POST['layout_id'])) {
        echo "<h4>Testing API with received data...</h4>";

        // Forward to the actual API
        $_POST_BACKUP = $_POST;
        $_FILES_BACKUP = $_FILES;

        ob_start();
        include 'api/upload-multiple-frame-sticker-combos.php';
        $output = ob_get_clean();

        echo "<h4>API Response:</h4>";
        echo "<pre>" . htmlspecialchars($output) . "</pre>";
    }

    echo "<hr><a href='debug-combo.php'>Back to test</a>";
    exit();
}

echo "<h3>Debug Frame-Sticker-Combo Upload</h3>";

// Test form for layout 2
?>
<form method="POST" enctype="multipart/form-data">
    <h4>Test Layout 2 Upload</h4>
    <input type="hidden" name="layout_id" value="2">
    <div>
        <label>Name Prefix:</label>
        <input type="text" name="name_prefix" value="Debug Test Layout 2" required>
    </div>
    <div>
        <label>Files:</label>
        <input type="file" name="combo_images[]" accept="image/*" multiple required>
    </div>
    <button type="submit">Test Upload Layout 2</button>
</form>

<hr>

<form method="POST" enctype="multipart/form-data">
    <h4>Test Layout 3 Upload</h4>
    <input type="hidden" name="layout_id" value="3">
    <div>
        <label>Name Prefix:</label>
        <input type="text" name="name_prefix" value="Debug Test Layout 3" required>
    </div>
    <div>
        <label>Files:</label>
        <input type="file" name="combo_images[]" accept="image/*" multiple required>
    </div>
    <button type="submit">Test Upload Layout 3</button>
</form>

<hr>

<h4>Debug Info:</h4>
<pre>
<?php
echo "Current working directory: " . getcwd() . "\n";
echo "Upload API exists: " . (file_exists('api/upload-multiple-frame-sticker-combos.php') ? 'YES' : 'NO') . "\n";
echo "Upload directory Layout 2: " . (is_dir('../uploads/frame-sticker-combos/layout2/') ? 'EXISTS' : 'MISSING') . "\n";
echo "Upload directory Layout 3: " . (is_dir('../uploads/frame-sticker-combos/layout3/') ? 'EXISTS' : 'MISSING') . "\n";

echo "\nForm IDs that should exist in main admin.php:\n";
for ($i = 1; $i <= 6; $i++) {
    echo "- multiple-combo-layout-{$i}-form\n";
}
?>
</pre>