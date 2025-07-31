<?php
// Set timezone to Makassar (WITA)
date_default_timezone_set('Asia/Makassar');

$searchToken = '8956ba2a5fa88a1b5f7f8be1101f5ba7';

echo "<h2>🔍 Hunting for Token: $searchToken</h2>";

// Check file system first
$photoDir = '/tmp/photobooth-photos';
$expectedFile = "{$photoDir}/{$searchToken}.png";

echo "<h3>📁 File System Check:</h3>";
echo "<strong>Photo directory exists:</strong> " . (is_dir($photoDir) ? 'YES' : 'NO') . "<br>";

if (is_dir($photoDir)) {
    $files = scandir($photoDir);
    echo "<strong>Files in directory:</strong><br>";
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            $fullPath = "{$photoDir}/{$file}";
            $fileTime = date('Y-m-d H:i:s', filemtime($fullPath));
            $fileSize = filesize($fullPath);
            
            if (strpos($file, $searchToken) !== false) {
                echo "<span style='color: green; font-weight: bold;'>🎯 FOUND: $file</span><br>";
            } else {
                echo "$file";
            }
            echo " (Size: {$fileSize}b, Modified: {$fileTime})<br>";
        }
    }
}

echo "<strong>Expected file exists:</strong> " . (file_exists($expectedFile) ? 'YES' : 'NO') . "<br>";

// Check all possible session files
echo "<br><h3>🍪 Session Hunt:</h3>";
$sessionPath = session_save_path();
if (empty($sessionPath)) {
    $sessionPath = '/tmp'; // Default path
}

echo "<strong>Session save path:</strong> $sessionPath<br>";

if (is_dir($sessionPath)) {
    $sessionFiles = glob("{$sessionPath}/sess_*");
    echo "<strong>Session files found:</strong> " . count($sessionFiles) . "<br>";
    
    foreach ($sessionFiles as $sessionFile) {
        $sessionData = file_get_contents($sessionFile);
        if (strpos($sessionData, $searchToken) !== false) {
            echo "<span style='color: green; font-weight: bold;'>🎯 TOKEN FOUND in session file: " . basename($sessionFile) . "</span><br>";
            echo "<strong>Session file content:</strong><br>";
            echo "<pre style='background: #f0f0f0; padding: 10px; border-radius: 4px;'>";
            echo htmlspecialchars($sessionData);
            echo "</pre>";
            break;
        }
    }
    
    if (strpos($sessionData, $searchToken) === false) {
        echo "<span style='color: red;'>❌ Token NOT FOUND in any session file</span><br>";
    }
} else {
    echo "<span style='color: red;'>❌ Session directory not found</span><br>";
}

// Check current session
echo "<br><h3>🔄 Current Session Check:</h3>";
session_start();

echo "<strong>Current session ID:</strong> " . session_id() . "<br>";

if (isset($_SESSION['photo_tokens'])) {
    echo "<strong>Tokens in current session:</strong> " . count($_SESSION['photo_tokens']) . "<br>";
    
    if (isset($_SESSION['photo_tokens'][$searchToken])) {
        echo "<span style='color: green; font-weight: bold;'>🎯 TOKEN FOUND in current session!</span><br>";
        $tokenData = $_SESSION['photo_tokens'][$searchToken];
        echo "<strong>Expire time:</strong> " . date('Y-m-d H:i:s', $tokenData['expire']) . "<br>";
        echo "<strong>Is expired:</strong> " . (time() > $tokenData['expire'] ? 'YES' : 'NO') . "<br>";
        echo "<strong>File path:</strong> {$tokenData['filename']}<br>";
        echo "<strong>File exists:</strong> " . (file_exists($tokenData['filename']) ? 'YES' : 'NO') . "<br>";
    } else {
        echo "<span style='color: red;'>❌ Token NOT FOUND in current session</span><br>";
        
        echo "<br><strong>Available tokens in current session:</strong><br>";
        foreach ($_SESSION['photo_tokens'] as $token => $data) {
            echo "- $token (expires: " . date('Y-m-d H:i:s', $data['expire']) . ")<br>";
        }
    }
} else {
    echo "<span style='color: red;'>❌ No photo_tokens in current session</span><br>";
}

// Manual test - create the missing token
echo "<br><h3>🔧 Manual Token Creation (if missing):</h3>";
if (!isset($_SESSION['photo_tokens'][$searchToken])) {
    // Check if file exists first
    if (file_exists($expectedFile)) {
        echo "<span style='color: blue;'>ℹ️ File exists but token missing from session. Creating token...</span><br>";
        
        if (!isset($_SESSION['photo_tokens'])) {
            $_SESSION['photo_tokens'] = [];
        }
        
        $_SESSION['photo_tokens'][$searchToken] = [
            'expire' => time() + (30 * 60), // New 30 minute expire
            'filename' => $expectedFile,
            'created' => time()
        ];
        
        echo "<span style='color: green;'>✅ Token recreated! Try the link again.</span><br>";
    } else {
        echo "<span style='color: red;'>❌ Both file and token are missing. Photo was never saved properly.</span><br>";
    }
}

echo "<br><a href='/src/pages/yourphotos.php?token=$searchToken' target='_blank' style='background: #2196F3; color: white; padding: 10px 15px; text-decoration: none; border-radius: 4px;'>🔗 Test Token Link</a>";
?>
