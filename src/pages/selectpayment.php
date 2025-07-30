<?php
// Include session manager and PWA helper
require_once '../includes/session-manager.php';
require_once '../includes/pwa-helper.php';

// Check current state and handle redirects
$currentState = SessionManager::getSessionState();

if ($currentState === SessionManager::STATE_PAYMENT_COMPLETED) {
    // Already paid, redirect to layout
    header('Location: selectlayout.php');
    exit();
} elseif ($currentState === SessionManager::STATE_LAYOUT_SELECTED) {
    // Layout already selected, let user access layout page
    header('Location: selectlayout.php');
    exit();
} elseif ($currentState === SessionManager::STATE_PHOTO_SESSION) {
    // Already in photo session, redirect to layout
    header('Location: selectlayout.php');
    exit();
}

// Start payment session if in valid state
SessionManager::startPaymentSession();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pilih Metode Pembayaran - GoBooth</title>
  
  <?php PWAHelper::addPWAHeaders(); ?>
  
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="home-styles.css" />
</head>

<body>
  <div class="gradientBgCanvas"></div>

  <div class="container">
    <div class="glass-card">
      <div class="select-payment">

        <!-- Kiri -->
        <div class="select-payment-left">
          <h1 class="hero-title" style="font-size: 2rem;">Pilih Metode Pembayaran</h1>
          <p class="hero-subtitle">Gunakan salah satu metode berikut untuk menyelesaikan pembayaran.</p>
        </div>

        <!-- Kanan -->
        <div class="select-payment-right">
          <!-- QRIS -->
          <a href="payment-qris.php" style="width: 275px; text-decoration: none;">
            <div class="select-payment-option">
              <span class="hero-title">QRIS</span>
              <img src="../assets/qris.png" alt="QRIS Logo">
            </div>
          </a>

          <!-- BCA -->
          <a href="payment-bank.php" style="width: 275px; text-decoration: none;">
            <div class="select-payment-option">
              <span class="hero-title">Virtual Bank (BCA)</span>
              <img src="../assets/bca.png" alt="BCA Logo">
            </div>
          </a>
        </div>

      </div>
    </div>
  </div>
  
  <?php PWAHelper::addPWAScript(); ?>
</body>

</html>
