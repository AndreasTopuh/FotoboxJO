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

// JANGAN start payment session di sini - akan dimulai saat user klik metode pembayaran
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
  <style>
    .container {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      padding: 2rem;
      max-width: 800px;
      margin: 0 auto;
    }

    .payment-card {
      background: rgba(255, 255, 255, 0.9);
      border-radius: 20px;
      padding: 3rem;
      backdrop-filter: blur(10px);
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.3);
      width: 100%;
      max-width: 700px;
    }

    .back-button {
      background: rgba(226, 133, 133, 0.1);
      color: #E28585;
      border: 2px solid #E28585;
      border-radius: 25px;
      padding: 10px 20px;
      font-weight: 600;
      text-decoration: none;
      display: inline-block;
      transition: all 0.3s ease;
      margin-bottom: 2rem;
    }

    .back-button:hover {
      background: #E28585;
      color: white;
    }

    .payment-title {
      color: #333;
      font-size: 2.2rem;
      font-weight: 700;
      margin-bottom: 1rem;
      text-align: center;
    }

    .payment-subtitle {
      color: #666;
      font-size: 1rem;
      text-align: center;
      margin-bottom: 3rem;
      line-height: 1.5;
    }

    .payment-options {
      display: flex;
      flex-direction: column;
      gap: 1.5rem;
      align-items: center;
    }

    .payment-option {
      background: rgba(226, 133, 133, 0.1);
      border: 2px solid #E28585;
      border-radius: 15px;
      padding: 1.5rem;
      width: 100%;
      max-width: 400px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      text-decoration: none;
      transition: all 0.3s ease;
      cursor: pointer;
    }

    .payment-option:hover {
      background: rgba(226, 133, 133, 0.2);
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(226, 133, 133, 0.3);
    }

    .payment-option span {
      color: #333;
      font-weight: 600;
      font-size: 1.1rem;
    }

    .payment-option img {
      width: 50px;
      height: 50px;
      object-fit: contain;
    }
  </style>
</head>

<body>
  <div class="gradientBgCanvas"></div>

  <div class="container">
    <div class="payment-card">
      <!-- Back Button -->
      <a href="description.php" class="back-button">← Kembali</a>
      
      <!-- Title and Description -->
      <h1 class="payment-title">Pilih Metode Pembayaran</h1>
      <p class="payment-subtitle">Gunakan salah satu metode berikut untuk menyelesaikan pembayaran.</p>
      
      <!-- Payment Options -->
      <div class="payment-options">
        <!-- QRIS -->
        <a href="#" onclick="startPaymentSession('qris'); return false;" class="payment-option">
          <span>QRIS</span>
          <img src="../assets/qris.png" alt="QRIS Logo">
        </a>

        <!-- BCA -->
        <a href="#" onclick="startPaymentSession('bank'); return false;" class="payment-option">
          <span>Virtual Bank (BCA)</span>
          <img src="../assets/bca.png" alt="BCA Logo">
        </a>
      </div>
    </div>
  </div>
  
  <script>
    async function startPaymentSession(method) {
      try {
        // Start payment session on server
        const response = await fetch('../api-fetch/set_session.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            action: 'start_payment',
            payment_method: method
          })
        });

        const result = await response.json();
        
        if (result.success) {
          // Timer 20 menit sudah dimulai di server, redirect ke halaman pembayaran
          if (method === 'qris') {
            window.location.href = 'payment-qris.php';
          } else if (method === 'bank') {
            window.location.href = 'payment-bank.php';
          }
        } else {
          alert('Gagal memulai sesi pembayaran. Silakan coba lagi.');
        }
      } catch (error) {
        console.error('Error starting payment session:', error);
        alert('Terjadi kesalahan. Silakan coba lagi.');
      }
    }
  </script>
  
  <?php PWAHelper::addPWAScript(); ?>
</body>

</html>
