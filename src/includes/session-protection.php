<?php
// Session protection untuk halaman-halaman yang memerlukan pembayaran dan layout selection
require_once __DIR__ . '/session-manager.php';

session_start();

// Fungsi untuk melindungi halaman canvas/customize
function protectCanvasPage() {
    // Require canvas access (layout must be selected)
    if (!SessionManager::requireCanvasAccess()) {
        // Akan redirect otomatis jika belum memenuhi syarat
        exit();
    }
    
    // Start photo session if not already started
    if (SessionManager::getSessionState() === SessionManager::STATE_LAYOUT_SELECTED) {
        SessionManager::startPhotoSession();
    }
    
    // Optional: Add session timer monitoring untuk halaman canvas
    $timeRemaining = SessionManager::getTimeRemaining();
    if ($timeRemaining < 300) { // Kurang dari 5 menit
        // Bisa tambahkan warning atau extend session
        error_log("Canvas session akan berakhir dalam " . $timeRemaining . " detik");
    }
}

// Auto-call protection
protectCanvasPage();
?>
