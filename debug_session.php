<?php
// Set timezone to Makassar (WITA)
date_default_timezone_set('Asia/Makassar');

// Check session configuration
echo "<h2>🔍 Session Configuration Debug</h2>";
echo "<strong>session.save_path:</strong> " . ini_get('session.save_path') . "<br>";
echo "<strong>session.name:</strong> " . ini_get('session.name') . "<br>";
echo "<strong>session.cookie_lifetime:</strong> " . ini_get('session.cookie_lifetime') . "<br>";
echo "<strong>session.cookie_domain:</strong> " . ini_get('session.cookie_domain') . "<br>";
echo "<strong>session.cookie_path:</strong> " . ini_get('session.cookie_path') . "<br>";
echo "<strong>session.cookie_secure:</strong> " . ini_get('session.cookie_secure') . "<br>";
echo "<strong>session.cookie_httponly:</strong> " . ini_get('session.cookie_httponly') . "<br>";

// Show cookies
echo "<br><h3>🍪 Current Cookies:</h3>";
if (!empty($_COOKIE)) {
    foreach ($_COOKIE as $name => $value) {
        echo "<strong>$name:</strong> $value<br>";
    }
} else {
    echo "No cookies found<br>";
}

session_start();

echo "<br><h3>📋 Session Info:</h3>";
echo "<strong>Session ID:</strong> " . session_id() . "<br>";
echo "<strong>Session Status:</strong> " . session_status() . "<br>";

// Let's manually create a test token to see if it persists
$testToken = 'test_' . date('His');
$testExpire = time() + (30 * 60);

if (!isset($_SESSION['photo_tokens'])) {
    $_SESSION['photo_tokens'] = [];
}

$_SESSION['photo_tokens'][$testToken] = [
    'expire' => $testExpire,
    'filename' => '/tmp/photobooth-photos/test.png',
    'created' => time()
];

echo "<br><h3>🧪 Test Token Created:</h3>";
echo "<strong>Token:</strong> $testToken<br>";
echo "<strong>Expires:</strong> " . date('Y-m-d H:i:s', $testExpire) . "<br>";
echo "<strong>Test URL:</strong> <a href='/src/pages/yourphotos.php?token=$testToken' target='_blank'>Test Link</a><br>";

echo "<br><h3>📊 All Session Data:</h3>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

// Force save session
session_write_close();
echo "<br><strong>✅ Session saved</strong><br>";

// Check if token exists after reload
echo "<br><a href='?reload=1' style='background: #4CAF50; color: white; padding: 10px 15px; text-decoration: none; border-radius: 4px;'>🔄 Reload Page</a>";

if (isset($_GET['reload'])) {
    echo "<br><br><h3>🔄 After Reload Check:</h3>";
    session_start();
    
    if (isset($_SESSION['photo_tokens'][$testToken])) {
        echo "<strong style='color: green;'>✅ Test token still exists!</strong><br>";
    } else {
        echo "<strong style='color: red;'>❌ Test token lost after reload!</strong><br>";
    }
}
?>
