<?php
// Cleanup expired photos
// This script should be run via cron job every 5 minutes

// Set timezone to Makassar (WITA)
date_default_timezone_set('Asia/Makassar');

session_start();

$photoDir = '/tmp/photobooth-photos';

// Clean up based on session data
if (isset($_SESSION['photo_tokens'])) {
    $currentTime = time();
    $cleanedCount = 0;
    
    foreach ($_SESSION['photo_tokens'] as $token => $data) {
        if ($currentTime > $data['expire']) {
            // Delete expired file
            if (file_exists($data['filename'])) {
                unlink($data['filename']);
                $cleanedCount++;
            }
            // Remove from session
            unset($_SESSION['photo_tokens'][$token]);
        }
    }
    
    echo "Cleaned up {$cleanedCount} expired photos from session.\n";
}

// Also clean up any orphaned files in the directory (older than 30 minutes)
if (is_dir($photoDir)) {
    $files = glob($photoDir . '/*.png');
    $orphanedCount = 0;
    $currentTime = time();
    
    foreach ($files as $file) {
        $fileTime = filemtime($file);
        // If file is older than 30 minutes, delete it
        if (($currentTime - $fileTime) > (30 * 60)) {
            unlink($file);
            $orphanedCount++;
        }
    }
    
    echo "Cleaned up {$orphanedCount} orphaned photos from directory.\n";
}

echo "Cleanup completed at " . date('Y-m-d H:i:s') . "\n";
?>
