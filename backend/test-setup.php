<?php
// Test file to verify Midtrans SDK setup
require_once __DIR__ . '/../vendor/autoload.php';

// Load configuration
$config = require_once __DIR__ . '/config.php';

use Midtrans\Config;

// Test basic configuration
echo "<h2>FotoboxJO - Midtrans Setup Test</h2>";

try {
    // Test if Midtrans classes can be loaded
    echo "<p>✅ Midtrans SDK loaded successfully</p>";

    // Test configuration
    Config::$serverKey = $config['midtrans']['server_key'];
    Config::$clientKey = $config['midtrans']['client_key'];
    Config::$isProduction = $config['midtrans']['is_production'];

    echo "<p>✅ Configuration loaded successfully</p>";

    // Check PHP version
    echo "<p>✅ PHP Version: " . PHP_VERSION . "</p>";

    // Check required extensions
    $required_extensions = ['curl', 'json', 'openssl'];
    foreach ($required_extensions as $ext) {
        if (extension_loaded($ext)) {
            echo "<p>✅ $ext extension: Available</p>";
        } else {
            echo "<p>❌ $ext extension: Missing</p>";
        }
    }

    // Test server key format
    if (strpos($config['midtrans']['server_key'], 'YOUR_SERVER_KEY_HERE') !== false) {
        echo "<p>⚠️ Please update your Midtrans server key in backend/config.php</p>";
    } else {
        echo "<p>✅ Server key configured</p>";
    }

    if (strpos($config['midtrans']['client_key'], 'YOUR_CLIENT_KEY_HERE') !== false) {
        echo "<p>⚠️ Please update your Midtrans client key in backend/config.php</p>";
    } else {
        echo "<p>✅ Client key configured</p>";
    }

    echo "<h3>Next Steps:</h3>";
    echo "<ol>";
    echo "<li>Update Midtrans credentials in <code>backend/config.php</code></li>";
    echo "<li>Test payment integration</li>";
    echo "<li>Configure notification URL in Midtrans dashboard</li>";
    echo "</ol>";
} catch (Exception $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
}
