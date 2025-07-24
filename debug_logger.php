<?php
// Debug logger untuk FotoboxJO
function debugLog($message, $data = null) {
    $timestamp = date('Y-m-d H:i:s');
    $logEntry = "[$timestamp] $message";
    
    if ($data !== null) {
        $logEntry .= " | Data: " . (is_array($data) || is_object($data) ? json_encode($data) : $data);
    }
    
    $logEntry .= PHP_EOL;
    
    // Write to debug log file
    error_log($logEntry, 3, '/tmp/fotobox_debug.log');
    
    // Also output to console if in development
    if (php_sapi_name() === 'cli') {
        echo $logEntry;
    }
}

// Function to clear debug log
function clearDebugLog() {
    if (file_exists('/tmp/fotobox_debug.log')) {
        unlink('/tmp/fotobox_debug.log');
    }
}

// Function to read debug log
function getDebugLog($lines = 50) {
    if (file_exists('/tmp/fotobox_debug.log')) {
        $content = file_get_contents('/tmp/fotobox_debug.log');
        $lines_array = explode(PHP_EOL, $content);
        return array_slice($lines_array, -$lines);
    }
    return [];
}
?>
