<?php
// Debug timezone dan waktu
date_default_timezone_set('Asia/Makassar');

echo "<h2>Debug Timezone Information</h2>";
echo "<strong>Server Time Info:</strong><br>";
echo "Current timezone: " . date_default_timezone_get() . "<br>";
echo "Current server time: " . date('Y-m-d H:i:s') . "<br>";
echo "Current server time with timezone: " . date('Y-m-d H:i:s O') . "<br>";
echo "Unix timestamp: " . time() . "<br>";

echo "<br><strong>Testing Different Timezones:</strong><br>";

$timezones = [
    'Asia/Jakarta' => 'WIB (UTC+7)',
    'Asia/Makassar' => 'WITA (UTC+8)', 
    'Asia/Jayapura' => 'WIT (UTC+9)',
    'Asia/Singapore' => 'SGT (UTC+8)',
    'UTC' => 'UTC+0'
];

foreach ($timezones as $tz => $desc) {
    date_default_timezone_set($tz);
    echo "$desc: " . date('Y-m-d H:i:s O') . "<br>";
}

// Reset ke Makassar
date_default_timezone_set('Asia/Makassar');

echo "<br><strong>Custom Expiry Test:</strong><br>";
$orderTime = date('Y-m-d H:i:s +0800');
echo "Order time for Midtrans: $orderTime<br>";

// Simulate expiry calculation
$expiryTimestamp = strtotime('+20 minutes');
echo "Expiry timestamp: $expiryTimestamp<br>";
echo "Expiry time: " . date('Y-m-d H:i:s', $expiryTimestamp) . "<br>";
echo "Expiry time with timezone: " . date('Y-m-d H:i:s O', $expiryTimestamp) . "<br>";

// Test JavaScript format
echo "<br><strong>JavaScript Date Test:</strong><br>";
echo "For JavaScript new Date(): " . date('c') . "<br>";
?>

<script>
// Test JavaScript timezone
console.log('=== JavaScript Timezone Debug ===');
console.log('Browser timezone:', Intl.DateTimeFormat().resolvedOptions().timeZone);
console.log('Current browser time:', new Date().toLocaleString());
console.log('Current browser time (id-ID):', new Date().toLocaleString('id-ID'));
console.log('Current browser time (Asia/Makassar):', new Date().toLocaleString('id-ID', {timeZone: 'Asia/Makassar'}));

// Simulate expiry date parsing
const testDate = new Date('2025-08-27T05:33:53+08:00');
console.log('Test date parsing:', testDate.toLocaleString('id-ID', {
    timeZone: 'Asia/Makassar',
    year: 'numeric',
    month: '2-digit', 
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit'
}));
</script>
