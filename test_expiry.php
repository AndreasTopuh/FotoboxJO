<?php
// Test custom expiry format
date_default_timezone_set('Asia/Makassar');

echo "<h3>Testing Custom Expiry Format</h3>";

// Method 1: Current method
$orderTime1 = date('Y-m-d H:i:s +0800');
echo "Method 1 (current): $orderTime1<br>";

// Method 2: ISO format
$orderTime2 = date('c');
echo "Method 2 (ISO): $orderTime2<br>";

// Method 3: Explicit timezone
$orderTime3 = date('Y-m-d H:i:s', time()) . ' +0800';
echo "Method 3 (explicit): $orderTime3<br>";

// Method 4: Different format
$orderTime4 = gmdate('Y-m-d H:i:s', time() + (8 * 3600)) . ' +0800';
echo "Method 4 (GMT+8): $orderTime4<br>";

echo "<br><strong>Current Time Info:</strong><br>";
echo "Server timezone: " . date_default_timezone_get() . "<br>";
echo "Current time: " . date('Y-m-d H:i:s') . "<br>";
echo "UTC time: " . gmdate('Y-m-d H:i:s') . "<br>";
echo "Timestamp: " . time() . "<br>";

// Calculate 20 minutes later
$expiry = time() + (20 * 60);
echo "<br><strong>Expiry Calculation:</strong><br>";
echo "20 minutes from now: " . date('Y-m-d H:i:s', $expiry) . "<br>";
echo "20 minutes from now (UTC): " . gmdate('Y-m-d H:i:s', $expiry) . "<br>";
?>
