<?php
session_start();

// Check admin access
if (!isset($_SESSION['admin_access']) || $_SESSION['admin_access'] !== true) {
    header('Location: ../admin-login.php');
    exit();
}

require_once '../config/database.php';

$id = $_GET['id'] ?? 0;

if ($id > 0) {
    try {
        // Get sticker info first to delete file
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM table_sticker WHERE id = ? AND is_active = 1");
        $stmt->execute([$id]);
        $sticker = $stmt->fetch();

        if ($sticker) {
            // Delete from database
            if (Database::deleteSticker($id)) {
                // Delete physical file
                $filePath = '../../' . ltrim($sticker['file_path'], '/');
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                header('Location: ../admin.php?section=assets&msg=success: Sticker deleted successfully');
            } else {
                header('Location: ../admin.php?section=assets&msg=error: Failed to delete sticker');
            }
        } else {
            header('Location: ../admin.php?section=assets&msg=error: Sticker not found');
        }
    } catch (Exception $e) {
        header('Location: ../admin.php?section=assets&msg=error: Database error: ' . $e->getMessage());
    }
} else {
    header('Location: ../admin.php?section=assets&msg=error: Invalid sticker ID');
}

exit();
