<?php
session_start();

// Check admin access
if (!isset($_SESSION['admin_access']) || $_SESSION['admin_access'] !== true) {
    header('Location: ../admin-login.php');
    exit();
}

require_once '../config/database.php';

if (isset($_GET['id'])) {
    try {
        $id = (int)$_GET['id'];

        // Get file info before deletion
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM table_frame_sticker_combo WHERE id = ? AND is_active = 1");
        $stmt->execute([$id]);
        $combo = $stmt->fetch();

        if (!$combo) {
            throw new Exception('Frame & sticker combo not found');
        }

        // Delete from database (soft delete)
        $success = Database::deleteFrameStickerCombo($id);

        if ($success) {
            // Optionally delete physical file
            $filePath = '../../' . ltrim($combo['file_path'], '/');
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            header('Location: ../admin.php?section=frame-sticker-combos&msg=Frame & sticker combo deleted successfully');
        } else {
            throw new Exception('Failed to delete from database');
        }
    } catch (Exception $e) {
        header('Location: ../admin.php?section=frame-sticker-combos&msg=Error: ' . urlencode($e->getMessage()));
    }
} else {
    header('Location: ../admin.php?section=frame-sticker-combos&msg=Invalid request');
}
