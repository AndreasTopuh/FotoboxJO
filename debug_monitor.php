<?php
// Debug monitor untuk FotoboxJO
require_once('debug_logger.php');

echo "=== FotoboxJO Debug Monitor ===" . PHP_EOL;
echo "Time: " . date('Y-m-d H:i:s') . PHP_EOL;
echo "=============================" . PHP_EOL;

// Show recent debug logs
$logs = getDebugLog(20);
if (empty($logs)) {
    echo "No debug logs found." . PHP_EOL;
} else {
    echo "Recent debug logs:" . PHP_EOL;
    foreach ($logs as $log) {
        if (!empty(trim($log))) {
            echo $log . PHP_EOL;
        }
    }
}

echo "=============================" . PHP_EOL;
echo "Commands:" . PHP_EOL;
echo "- php debug_monitor.php clear  (clear logs)" . PHP_EOL;
echo "- php debug_monitor.php tail   (show live logs)" . PHP_EOL;

// Handle commands
if (isset($argv[1])) {
    switch ($argv[1]) {
        case 'clear':
            clearDebugLog();
            echo "✅ Debug logs cleared!" . PHP_EOL;
            break;
        case 'tail':
            echo "🔄 Monitoring logs (Ctrl+C to stop)..." . PHP_EOL;
            $lastSize = 0;
            while (true) {
                if (file_exists('/tmp/fotobox_debug.log')) {
                    $currentSize = filesize('/tmp/fotobox_debug.log');
                    if ($currentSize > $lastSize) {
                        $handle = fopen('/tmp/fotobox_debug.log', 'r');
                        fseek($handle, $lastSize);
                        echo fread($handle, $currentSize - $lastSize);
                        fclose($handle);
                        $lastSize = $currentSize;
                    }
                }
                sleep(1);
            }
            break;
    }
}
?>
