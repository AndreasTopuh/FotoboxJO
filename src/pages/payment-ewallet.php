<?php
// Include session manager dan PWA helper
require_once '../includes/session-manager.php';
require_once '../includes/pwa-helper.php';

session_start();

// Strict validation - only allow if in payment pending state
if (!SessionManager::canAccessPaymentPage()) {
    $currentState = SessionManager::getSessionState();
    
    if ($currentState === SessionManager::STATE_PAYMENT_COMPLETED) {
        // Already paid, redirect to layout
        header('Location: selectlayout.php');
        exit();
    } elseif ($currentState === SessionManager::STATE_LAYOUT_SELECTED) {
        // Layout already selected
        header('Location: selectlayout.php');
        exit();
    } elseif ($currentState === SessionManager::STATE_PHOTO_SESSION) {
        // Already in photo session
        header('Location: selectlayout.php');
        exit();
    } else {
        // Invalid access, redirect to payment selection
        header('Location: selectpayment.php');
        exit();
    }
}

// Additional validation using the original method
SessionManager::requireValidPaymentSession();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pembayaran E-Wallet GoPay/QRIS - GoBooth</title>
  
  <?php PWAHelper::addPWAHeaders(); ?>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../../static/css/main.css?v=<?php echo time(); ?>" />
  <link rel="stylesheet" href="../../static/css/payment.css?v=<?php echo time(); ?>" />
  <link rel="stylesheet" href="../../static/css/responsive.css?v=<?php echo time(); ?>" />
  <style>
    .container {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      padding: 2rem;
      max-width: 900px;
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
      max-width: 800px;
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

    .payment-content {
      display: flex;
      gap: 3rem;
      align-items: flex-start;
    }

    .payment-left {
      flex: 1;
    }

    .payment-right {
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .payment-title {
      color: #333;
      font-size: 2rem;
      font-weight: 700;
      margin-bottom: 1rem;
    }

    .payment-instructions {
      color: #666;
      line-height: 1.6;
    }

    .payment-instructions ol {
      margin: 0;
      padding-left: 20px;
    }

    .payment-instructions li {
      margin-bottom: 0.5rem;
    }

    .qr-container {
      background: rgba(226, 133, 133, 0.1);
      border: 2px solid #E28585;
      border-radius: 15px;
      padding: 2rem;
      text-align: center;
      width: 100%;
      max-width: 400px;
    }

    .qr-code {
      background: white;
      border-radius: 10px;
      padding: 20px;
      margin: 1rem auto;
      display: inline-block;
      border: 2px solid #E28585;
    }

    .qr-code img {
      width: 200px;
      height: 200px;
      display: block;
    }

    .qr-label {
      font-size: 1.1rem;
      font-weight: 600;
      color: #333;
      margin-bottom: 1rem;
    }

    .expiry-info {
      color: #333;
      font-weight: 600;
      margin-top: 1rem;
      font-size: 0.9rem;
    }

    .status-container {
      margin-top: 1rem;
      padding: 1rem;
      background: rgba(226, 133, 133, 0.1);
      border-radius: 10px;
      border-left: 4px solid #E28585;
    }

    .error-container {
      margin-top: 1rem;
      padding: 1rem;
      background: rgba(220, 53, 69, 0.1);
      border-radius: 10px;
      border-left: 4px solid #dc3545;
      color: #dc3545;
      text-align: center;
    }

    .loading-container {
      text-align: center;
      padding: 2rem;
    }

    .loading-spinner {
      border: 4px solid rgba(226, 133, 133, 0.3);
      border-radius: 50%;
      border-top: 4px solid #E28585;
      width: 50px;
      height: 50px;
      animation: spin 1s linear infinite;
      margin: 0 auto 1rem;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    .loading-progress {
      width: 100%;
      height: 6px;
      background: rgba(226, 133, 133, 0.3);
      border-radius: 3px;
      overflow: hidden;
      margin-top: 1rem;
    }

    .progress-bar {
      width: 0%;
      height: 100%;
      background: #E28585;
      border-radius: 3px;
      animation: progress 2s ease-in-out;
    }

    @keyframes progress {
      0% { width: 0%; }
      100% { width: 100%; }
    }

    .start-btn {
      background: #E28585;
      color: white;
      border: none;
      border-radius: 25px;
      padding: 12px 30px;
      font-weight: 600;
      font-size: 1rem;
      cursor: pointer;
      transition: all 0.3s ease;
      margin-top: 1rem;
    }

    .start-btn:hover {
      background: #d16969;
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(226, 133, 133, 0.3);
    }

    .wallet-logos {
      display: flex;
      justify-content: center;
      gap: 1rem;
      margin-top: 1rem;
      padding: 1rem;
      background: rgba(255, 255, 255, 0.7);
      border-radius: 10px;
    }

    .wallet-logo {
      width: 40px;
      height: 40px;
      object-fit: contain;
    }

    .payment-amount {
      font-size: 1.5rem;
      font-weight: 700;
      color: #E28585;
      text-align: center;
      margin: 1rem 0;
      padding: 1rem;
      background: rgba(255, 255, 255, 0.7);
      border-radius: 10px;
      border: 2px solid #E28585;
    }

    @media (max-width: 768px) {
      .payment-content {
        flex-direction: column;
        gap: 2rem;
      }
      
      .container {
        padding: 1rem;
      }
      
      .payment-card {
        padding: 2rem;
      }
    }
  </style>
</head>

<body>
  <div class="gradientBgCanvas"></div>

  <div class="container">
    <div class="payment-card">
      <a href="#" onclick="cancelAndGoHome()" class="back-button">← Kembali</a>
      
      <div class="payment-content">
        <div class="payment-left">
          <h1 class="payment-title">Pembayaran E-Wallet</h1>
          <div class="payment-amount">Rp 30.000</div>
          
          <div class="payment-instructions">
            <p><strong>Cara Pembayaran:</strong></p>
            <ol>
              <li>Scan QR Code dengan aplikasi e-wallet Anda</li>
              <li>Konfirmasi pembayaran di aplikasi</li>
              <li>Tunggu konfirmasi pembayaran berhasil</li>
              <li>Sistem akan otomatis melanjutkan ke tahap selanjutnya</li>
            </ol>
            
            <p><strong>E-Wallet yang Didukung:</strong></p>
            <div class="wallet-logos">
              <span style="font-size: 12px; color: #666; text-align: center; width: 100%;">
                GoPay, OVO, DANA, LinkAja, ShopeePay, dan e-wallet lainnya yang mendukung QRIS
              </span>
            </div>
          </div>
        </div>

        <div class="payment-right">
          <div id="loadingContainer" class="loading-container">
            <div class="loading-spinner"></div>
            <p style="color: #666; margin-top: 1rem;">Menyiapkan QR Code...</p>
            <div class="loading-progress">
              <div class="progress-bar"></div>
            </div>
          </div>

          <div id="qrContainer" class="qr-container" style="display: none;">
            <div class="qr-label">Scan QR Code di bawah ini</div>
            <div class="qr-code">
              <img id="qrImage" src="" alt="QR Code" />
            </div>
            <div class="expiry-info">
              <p id="expiryText">Berlaku sampai: <span id="expiryTime">-</span></p>
            </div>
          </div>

          <div id="errorContainer" class="error-container" style="display: none;">
            <p id="errorMessage"></p>
            <button class="start-btn" onclick="window.location.reload()">Coba Lagi</button>
          </div>

          <div id="statusContainer" class="status-container" style="display: none;">
            <p id="statusMessage">Menunggu pembayaran...</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    let orderId = "";
    let transactionId = "";
    let statusInterval;

    // Function untuk cancel transaksi dan kembali ke home
    function cancelAndGoHome() {
      clearAllIntervals();
      
      // Cancel transaksi jika ada
      if (orderId) {
        fetch('../api-fetch/cancel_transaction.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            order_id: orderId
          })
        })
        .then(response => response.json())
        .then(result => {
          console.log('Cancel transaction result:', result);
        })
        .catch(error => {
          console.error('Error canceling transaction:', error);
        })
        .finally(() => {
          // Reset session dan redirect
          resetSessionAndGoHome();
        });
      } else {
        resetSessionAndGoHome();
      }
    }

    // Function untuk reset session dan kembali ke home
    function resetSessionAndGoHome() {
      fetch('../api-fetch/reset_session.php', {
        method: 'POST'
      }).finally(() => {
        window.location.href = '/';
      });
    }

    // Clear all intervals
    function clearAllIntervals() {
      if (statusInterval) {
        clearInterval(statusInterval);
        statusInterval = null;
      }
    }

    // Function untuk show QR code
    function showQRCode(qrImageUrl, expiry) {
      document.getElementById('loadingContainer').style.display = 'none';
      
      const qrContainer = document.getElementById('qrContainer');
      const qrImage = document.getElementById('qrImage');
      const expiryTime = document.getElementById('expiryTime');
      
      // Set QR image
      qrImage.src = qrImageUrl;
      qrImage.onerror = function() {
        showError('Gagal memuat QR Code. Silakan coba lagi.');
      };
      
      // Set expiry time
      if (expiry) {
        const expiryDate = new Date(expiry);
        expiryTime.textContent = expiryDate.toLocaleString('id-ID', {
          timeZone: 'Asia/Makassar',
          year: 'numeric',
          month: '2-digit',
          day: '2-digit', 
          hour: '2-digit',
          minute: '2-digit',
          second: '2-digit'
        });
      }
      
      qrContainer.style.display = 'block';
      
      // Show status container
      document.getElementById('statusContainer').style.display = 'block';
    }

    // Function untuk show error
    function showError(message) {
      document.getElementById('loadingContainer').style.display = 'none';
      document.getElementById('qrContainer').style.display = 'none';
      document.getElementById('errorContainer').style.display = 'block';
      document.getElementById('errorMessage').textContent = message;
    }

    // Initialize payment
    fetch('../api-fetch/charge_ewallet.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        gross_amount: 30000
      })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        orderId = data.order_id;
        transactionId = data.transaction_id;
        
        // Build QR image URL
        const qrImageUrl = `../api-fetch/get_qr_image.php?transaction_id=${encodeURIComponent(transactionId)}`;
        
        showQRCode(qrImageUrl, data.expiry_time);
        
        // Start polling status
        statusInterval = setInterval(() => pollStatus(), 3000);
        pollStatus(); // Initial check
        
      } else {
        showError(data.error || 'Gagal membuat transaksi');
      }
    })
    .catch(error => {
      console.error('Error:', error);
      showError('Gagal menghubungi server. Silakan coba lagi.');
    });

    function pollStatus() {
      if (!orderId) return;
      
      fetch(`../api-fetch/check_status.php?order_id=${encodeURIComponent(orderId)}`)
      .then(response => response.json())
      .then(data => {
        if (data.error) {
          console.error('Status check error:', data.error);
          return;
        }
        
        const status = (data.transaction_status || '').toLowerCase();
        const statusMessage = document.getElementById('statusMessage');
        
        switch(status) {
          case 'capture':
          case 'settlement':
          case 'success':
            // Payment successful
            clearAllIntervals();
            statusMessage.innerHTML = '<strong style="color: #28a745;">✓ Pembayaran Berhasil!</strong><br>Mengalihkan ke halaman selanjutnya...';
            
            // Complete payment via session manager
            fetch('../api-fetch/complete_payment.php', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json'
              },
              body: JSON.stringify({
                order_id: orderId
              })
            })
            .then(response => response.json())
            .then(result => {
              if (result.success) {
                setTimeout(() => {
                  window.location.href = 'selectlayout.php';
                }, 2000);
              } else {
                showError('Gagal menyelesaikan pembayaran: ' + result.error);
              }
            })
            .catch(error => {
              console.error('Complete payment error:', error);
              showError('Gagal menyelesaikan pembayaran');
            });
            break;
            
          case 'pending':
            statusMessage.textContent = 'Menunggu pembayaran...';
            break;
            
          case 'deny':
          case 'cancel':
          case 'expire':
          case 'failed':
            // Payment failed
            clearAllIntervals();
            statusMessage.innerHTML = `<strong style="color: #dc3545;">✗ Pembayaran ${status.toUpperCase()}</strong><br>Silakan coba lagi.`;
            
            setTimeout(() => {
              resetSessionAndGoHome();
            }, 3000);
            break;
            
          default:
            statusMessage.textContent = `Status: ${status}`;
        }
      })
      .catch(error => {
        console.error('Status polling error:', error);
      });
    }

    // Handle page unload
    window.addEventListener('beforeunload', () => {
      clearAllIntervals();
    });

    // Auto-refresh QR code every 5 minutes to prevent expiry
    setInterval(() => {
      if (transactionId) {
        const qrImage = document.getElementById('qrImage');
        const currentSrc = qrImage.src;
        qrImage.src = currentSrc.split('?')[0] + `?transaction_id=${encodeURIComponent(transactionId)}&t=${Date.now()}`;
      }
    }, 300000); // 5 minutes
  </script>

  <!-- Session Timer Script -->
  <script src="../includes/session-timer.js"></script>
  
  <?php PWAHelper::addPWAScript(); ?>
</body>

</html>
