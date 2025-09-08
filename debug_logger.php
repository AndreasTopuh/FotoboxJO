<?php

/**
 * Debug Logger untuk FotoboxJO
 * Mencatat semua aktivitas debugging ke file log
 */

function debugLog($message, $context = null)
{
    // Get current date for log file
    $date = date('Y-m-d');
    $timestamp = date('Y-m-d H:i:s');

    // Create logs directory if it doesn't exist
    $logDir = __DIR__ . '/logs';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }

    // Log file path
    $logFile = $logDir . '/debug_' . $date . '.log';

    // Format the log message
    $logMessage = "[$timestamp] DEBUG: $message";

    // Add context if provided
    if ($context !== null) {
        if (is_array($context) || is_object($context)) {
            $logMessage .= ' - ' . json_encode($context);
        } else {
            $logMessage .= ' - ' . $context;
        }
    }

    $logMessage .= PHP_EOL;

    // Write to file
    file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);

    // Also log to PHP error log for immediate visibility
    error_log("DEBUG: $message" . ($context ? ' - ' . json_encode($context) : ''));
}

/**
 * Log error messages
 */
function errorLog($message, $context = null)
{
    $date = date('Y-m-d');
    $timestamp = date('Y-m-d H:i:s');

    $logDir = __DIR__ . '/logs';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }

    $logFile = $logDir . '/debug_' . $date . '.log';

    $logMessage = "[$timestamp] ERROR: $message";

    if ($context !== null) {
        if (is_array($context) || is_object($context)) {
            $logMessage .= ' - ' . json_encode($context);
        } else {
            $logMessage .= ' - ' . $context;
        }
    }

    $logMessage .= PHP_EOL;

    file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
    error_log("ERROR: $message" . ($context ? ' - ' . json_encode($context) : ''));
}

/**
 * Log warning messages
 */
function warningLog($message, $context = null)
{
    $date = date('Y-m-d');
    $timestamp = date('Y-m-d H:i:s');

    $logDir = __DIR__ . '/logs';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }

    $logFile = $logDir . '/debug_' . $date . '.log';

    $logMessage = "[$timestamp] WARNING: $message";

    if ($context !== null) {
        if (is_array($context) || is_object($context)) {
            $logMessage .= ' - ' . json_encode($context);
        } else {
            $logMessage .= ' - ' . $context;
        }
    }

    $logMessage .= PHP_EOL;

    file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
    error_log("WARNING: $message" . ($context ? ' - ' . json_encode($context) : ''));
}
