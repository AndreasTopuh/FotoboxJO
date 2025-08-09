<?php
// Include session manager
require_once '../includes/session-manager.php';
require_once '../includes/pwa-helper.php';

session_start();

// Get order_id from URL
$orderId = $_GET['order_id'] ?? null;
$transactionStatus = $_GET['transaction_status'] ?? null;
$statusCode = $_GET['status_code'] ?? null;

// Default redirect URL
$redirectUrl = '/index.php';

if ($orderId && ($transactionStatus === 'settlement' || $transactionStatus === 'capture' || $statusCode === '200')) {
    // Payment successful
    
    // Try to complete payment in session
    if (SessionManager::isValidSession() && SessionManager::getSessionState() === SessionManager::STATE_PAYMENT_PENDING) {
        $success = SessionManager::completePayment($orderId);
        
        if ($success) {
            $redirectUrl = './selectlayout.php';
        }
    }
    
    $message = 'Pembayaran berhasil!';
    $messageType = 'success';
    
} elseif ($orderId && ($transactionStatus === 'pending')) {
    // Payment pending
    $message = 'Pembayaran sedang diproses. Silakan tunggu beberapa saat.';
    $messageType = 'warning';
    $redirectUrl = './payment-link.php';
    
} elseif ($orderId && ($transactionStatus === 'deny' || $transactionStatus === 'cancel' || $transactionStatus === 'expire')) {
    // Payment failed/cancelled/expired
    $message = 'Pembayaran dibatalkan atau gagal.';
    $messageType = 'error';
    $redirectUrl = './selectpayment.php';
    
} else {
    // Invalid access
    $message = 'Akses tidak valid.';
    $messageType = 'error';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Status Pembayaran - GoBooth</title>
  
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
      max-width: 600px;
      margin: 0 auto;
      text-align: center;
    }

    .status-card {
      background: rgba(255, 255, 255, 0.9);
      border-radius: 20px;
      padding: 3rem;
      backdrop-filter: blur(10px);
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.3);
      width: 100%;
      max-width: 500px;
    }

    .status-icon {
      font-size: 4rem;
      margin-bottom: 1rem;
    }

    .status-title {
      color: #333;
      font-size: 1.8rem;
      font-weight: 700;
      margin-bottom: 1rem;
    }

    .status-message {
      color: #666;
      font-size: 1.1rem;
      line-height: 1.6;
      margin-bottom: 2rem;
    }

    .success {
      color: #28a745;
    }

    .warning {
      color: #ffc107;
    }

    .error {
      color: #dc3545;
    }

    .redirect-info {
      background: rgba(226, 133, 133, 0.1);
      border: 2px solid #E28585;
      border-radius: 10px;
      padding: 1rem;
      margin-bottom: 2rem;
      color: #333;
    }

    .countdown {
      font-weight: 700;
      color: #E28585;
    }

    .action-button {
      background: #E28585;
      color: white;
      border: none;
      border-radius: 25px;
      padding: 12px 30px;
      font-weight: 600;
      font-size: 1rem;
      cursor: pointer;
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-block;
      margin: 0 10px;
    }

    .action-button:hover {
      background: #d16969;
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(226, 133, 133, 0.3);
    }

    .action-button.secondary {
      background: #6c757d;
    }

    .action-button.secondary:hover {
      background: #5a6268;
    }

    .order-info {
      background: rgba(248, 249, 250, 0.9);
      border-radius: 10px;
      padding: 1rem;
      margin-bottom: 2rem;
      font-size: 0.9rem;
      color: #666;
    }

    @media (max-width: 768px) {
      .container {
        padding: 1rem;
      }
      
      .status-card {
        padding: 2rem;
      }
    }
  </style>
</head>

<body>
  <div class="gradientBgCanvas"></div>

  <div class="container">
    <div class="status-card">
      <!-- Status Icon -->
      <div class="status-icon <?php echo $messageType; ?>">
        <?php if ($messageType === 'success'): ?>
          ✅
        <?php elseif ($messageType === 'warning'): ?>
          ⏳
        <?php else: ?>
          ❌
        <?php endif; ?>
      </div>

      <!-- Status Title -->
      <h1 class="status-title">
        <?php if ($messageType === 'success'): ?>
          Pembayaran Berhasil!
        <?php elseif ($messageType === 'warning'): ?>
          Pembayaran Sedang Diproses
        <?php else: ?>
          Pembayaran Gagal
        <?php endif; ?>
      </h1>

      <!-- Status Message -->
      <p class="status-message"><?php echo htmlspecialchars($message); ?></p>

      <!-- Order Info -->
      <?php if ($orderId): ?>
      <div class="order-info">
        <strong>Order ID:</strong> <?php echo htmlspecialchars($orderId); ?><br>
        <?php if ($transactionStatus): ?>
        <strong>Status:</strong> <?php echo htmlspecialchars($transactionStatus); ?><br>
        <?php endif; ?>
        <strong>Waktu:</strong> <?php echo date('d/m/Y H:i:s'); ?>
      </div>
      <?php endif; ?>

      <!-- Redirect Info -->
      <div class="redirect-info">
        <?php if ($messageType === 'success'): ?>
          🎉 Selamat! Anda akan diarahkan ke halaman pemilihan layout dalam <span class="countdown" id="countdown">5</span> detik.
        <?php elseif ($messageType === 'warning'): ?>
          ⏳ Anda akan diarahkan kembali ke halaman pembayaran dalam <span class="countdown" id="countdown">10</span> detik.
        <?php else: ?>
          🔄 Anda akan diarahkan ke halaman pemilihan pembayaran dalam <span class="countdown" id="countdown">5</span> detik.
        <?php endif; ?>
      </div>

      <!-- Action Buttons -->
      <div>
        <?php if ($messageType === 'success'): ?>
          <a href="./selectlayout.php" class="action-button">Lanjut ke Layout</a>
        <?php elseif ($messageType === 'warning'): ?>
          <a href="./payment-link.php" class="action-button">Kembali ke Pembayaran</a>
        <?php else: ?>
          <a href="./selectpayment.php" class="action-button">Coba Lagi</a>
        <?php endif; ?>
        
        <a href="/index.php" class="action-button secondary">Kembali ke Home</a>
      </div>
    </div>
  </div>

  <script>
    // Auto redirect with countdown
    let countdown = <?php echo ($messageType === 'warning') ? 10 : 5; ?>;
    const redirectUrl = '<?php echo $redirectUrl; ?>';
    
    const countdownElement = document.getElementById('countdown');
    
    const timer = setInterval(() => {
      countdown--;
      countdownElement.textContent = countdown;
      
      if (countdown <= 0) {
        clearInterval(timer);
        window.location.href = redirectUrl;
      }
    }, 1000);

    // Cancel auto redirect if user interacts with the page
    document.addEventListener('click', () => {
      clearInterval(timer);
      countdownElement.textContent = '∞';
      countdownElement.parentElement.innerHTML = countdownElement.parentElement.innerHTML.replace('dalam <span class="countdown" id="countdown">∞</span> detik', 'secara otomatis dibatalkan');
    });
  </script>

  <?php PWAHelper::addPWAScript(); ?>
</body>

</html>
