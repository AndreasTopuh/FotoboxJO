<?php
// Set timezone to Makassar (WITA)
date_default_timezone_set('Asia/Makassar');

echo "Current timezone: " . date_default_timezone_get() . "\n";
echo "Current time: " . date('Y-m-d H:i:s') . "\n";
echo "Current timestamp: " . time() . "\n";
echo "30 minutes from now: " . date('Y-m-d H:i:s', time() + (30 * 60)) . "\n";

// Show what 30 minutes looks like in seconds
echo "30 minutes in seconds: " . (30 * 60) . "\n";
echo "Test expire calculation: " . (time() + (30 * 60)) . "\n";
?>
