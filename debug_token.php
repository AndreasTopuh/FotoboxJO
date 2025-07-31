<?php
// Set timezone to Makassar (WITA)
date_default_timezone_set('Asia/Makassar');

session_start();

$token = $_GET['token'] ?? '8956ba2a5fa88a1b5f7f8be1101f5ba7';

echo "<h2>Debug Token: $token</h2>";
echo "<strong>Current time:</strong> " . date('Y-m-d H:i:s') . " (" . time() . ")<br>";
echo "<strong>Timezone:</strong> " . date_default_timezone_get() . "<br><br>";

if (isset($_SESSION['photo_tokens'])) {
    echo "<h3>All tokens in session:</h3>";
    foreach ($_SESSION['photo_tokens'] as $key => $data) {
        $isExpired = time() > $data['expire'];
        $expireTime = date('Y-m-d H:i:s', $data['expire']);
        $createdTime = date('Y-m-d H:i:s', $data['created']);
        
        echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px 0;'>";
        echo "<strong>Token:</strong> $key<br>";
        echo "<strong>Created:</strong> $createdTime ({$data['created']})<br>";
        echo "<strong>Expires:</strong> $expireTime ({$data['expire']})<br>";
        echo "<strong>File:</strong> {$data['filename']}<br>";
        echo "<strong>File exists:</strong> " . (file_exists($data['filename']) ? 'YES' : 'NO') . "<br>";
        echo "<strong>Is expired:</strong> " . ($isExpired ? 'YES' : 'NO') . "<br>";
        
        if ($key === $token) {
            echo "<strong style='color: red;'>👆 THIS IS THE REQUESTED TOKEN</strong><br>";
        }
        
        echo "</div>";
    }
} else {
    echo "<strong style='color: red;'>❌ No photo_tokens in session!</strong><br>";
}

echo "<br><h3>Session Info:</h3>";
echo "<strong>Session ID:</strong> " . session_id() . "<br>";
echo "<strong>Session file:</strong> " . session_save_path() . "/sess_" . session_id() . "<br>";

// Check if session file exists
$sessionFile = session_save_path() . "/sess_" . session_id();
if (file_exists($sessionFile)) {
    echo "<strong>Session file exists:</strong> YES<br>";
    echo "<strong>Session file size:</strong> " . filesize($sessionFile) . " bytes<br>";
    echo "<strong>Session file modified:</strong> " . date('Y-m-d H:i:s', filemtime($sessionFile)) . "<br>";
} else {
    echo "<strong>Session file exists:</strong> NO<br>";
}

echo "<br><h3>Raw Session Data:</h3>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";
?>
