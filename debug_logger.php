<?php
/**
 * Simple debug logger for FotoboxJO
 * Logs debug messages to error log with timestamp
 */

if (!function_exists('debugLog')) {
    function debugLog($message, $context = null) {
        $timestamp = date('Y-m-d H:i:s');
        $contextStr = $context ? ' - ' . json_encode($context) : '';
        $logMessage = "[{$timestamp}] DEBUG: {$message}{$contextStr}";
        
        // Log to error log
        error_log($logMessage);
        
        // Also log to custom debug file if logs directory exists
        $logDir = __DIR__ . '/logs';
        if (is_dir($logDir) && is_writable($logDir)) {
            $logFile = $logDir . '/debug_' . date('Y-m-d') . '.log';
            file_put_contents($logFile, $logMessage . PHP_EOL, FILE_APPEND | LOCK_EX);
        }
    }
}

// Also provide a function to log errors specifically
if (!function_exists('errorLog')) {
    function errorLog($message, $context = null) {
        $timestamp = date('Y-m-d H:i:s');
        $contextStr = $context ? ' - ' . json_encode($context) : '';
        $logMessage = "[{$timestamp}] ERROR: {$message}{$contextStr}";
        
        // Log to error log
        error_log($logMessage);
        
        // Also log to custom error file if logs directory exists
        $logDir = __DIR__ . '/logs';
        if (is_dir($logDir) && is_writable($logDir)) {
            $logFile = $logDir . '/error_' . date('Y-m-d') . '.log';
            file_put_contents($logFile, $logMessage . PHP_EOL, FILE_APPEND | LOCK_EX);
        }
    }
}
?>
