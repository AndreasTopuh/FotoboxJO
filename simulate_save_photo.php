<?php
// Set timezone to Makassar (WITA)
date_default_timezone_set('Asia/Makassar');

// Start session dengan konfigurasi khusus
ini_set('session.cookie_lifetime', 86400); // 24 hours
ini_set('session.gc_maxlifetime', 86400); // 24 hours

session_start();

echo "<h2>🔧 Save Photo Test (Simulate)</h2>";

// Simulate what save_final_photo.php does
$token = bin2hex(random_bytes(16));
$expire = time() + (30 * 60); // 30 minutes from now

echo "<strong>Generated Token:</strong> $token<br>";
echo "<strong>Current Time:</strong> " . date('Y-m-d H:i:s') . " (" . time() . ")<br>";
echo "<strong>Expire Time:</strong> " . date('Y-m-d H:i:s', $expire) . " (" . $expire . ")<br>";
echo "<strong>Minutes until expire:</strong> " . (($expire - time()) / 60) . "<br>";

// Create photo directory if not exists
$photoDir = '/tmp/photobooth-photos';
if (!file_exists($photoDir)) {
    if (!mkdir($photoDir, 0777, true)) {
        echo "<strong style='color: red;'>❌ Failed to create photo directory</strong><br>";
    } else {
        echo "<strong style='color: green;'>✅ Photo directory created</strong><br>";
    }
} else {
    echo "<strong style='color: green;'>✅ Photo directory exists</strong><br>";
}

$filename = "{$photoDir}/{$token}.png";

// Create a dummy image file
$dummyImage = imagecreate(400, 300);
$bg = imagecolorallocate($dummyImage, 76, 175, 80);
$text = imagecolorallocate($dummyImage, 255, 255, 255);
imagestring($dummyImage, 5, 100, 140, 'TEST IMAGE', $text);
imagestring($dummyImage, 3, 120, 160, date('Y-m-d H:i:s'), $text);

if (imagepng($dummyImage, $filename)) {
    echo "<strong style='color: green;'>✅ Test image saved to: $filename</strong><br>";
    echo "<strong>File size:</strong> " . filesize($filename) . " bytes<br>";
} else {
    echo "<strong style='color: red;'>❌ Failed to save test image</strong><br>";
}

imagedestroy($dummyImage);

// Initialize photo_tokens session if not exists
if (!isset($_SESSION['photo_tokens'])) {
    $_SESSION['photo_tokens'] = [];
}

// Save token data
$_SESSION['photo_tokens'][$token] = [
    'expire' => $expire,
    'filename' => $filename,
    'created' => time()
];

echo "<br><h3>📋 Session Token Saved</h3>";
echo "<strong>Token:</strong> $token<br>";
echo "<strong>URL:</strong> <a href='/src/pages/yourphotos.php?token=$token' target='_blank'>/src/pages/yourphotos.php?token=$token</a><br>";

// Force session save
session_write_close();

echo "<br><h3>✨ Simulation Complete</h3>";
echo "<p>Token telah disimpan dengan waktu expire 30 menit dari sekarang. Coba buka link di atas untuk test.</p>";

// Show all current tokens
session_start();
echo "<h3>📊 All Current Tokens:</h3>";
if (isset($_SESSION['photo_tokens']) && !empty($_SESSION['photo_tokens'])) {
    foreach ($_SESSION['photo_tokens'] as $tokenKey => $data) {
        $isExpired = time() > $data['expire'];
        $expireTime = date('Y-m-d H:i:s', $data['expire']);
        $timeLeft = $data['expire'] - time();
        
        echo "<div style='border: 1px solid " . ($isExpired ? 'red' : 'green') . "; padding: 10px; margin: 5px 0;'>";
        echo "<strong>Token:</strong> $tokenKey<br>";
        echo "<strong>Expires:</strong> $expireTime<br>";
        echo "<strong>Status:</strong> " . ($isExpired ? '❌ EXPIRED' : "✅ VALID ({$timeLeft}s left)") . "<br>";
        echo "<strong>File:</strong> {$data['filename']} (" . (file_exists($data['filename']) ? 'EXISTS' : 'NOT FOUND') . ")<br>";
        echo "</div>";
    }
} else {
    echo "<p style='color: red;'>❌ No tokens found in session</p>";
}
?>
