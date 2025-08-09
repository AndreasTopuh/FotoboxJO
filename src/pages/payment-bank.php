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
  <title>Pembayaran Virtual Bank BNI - GoBooth</title>
  
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

    .va-container {
      background: rgba(226, 133, 133, 0.1);
      border: 2px solid #E28585;
      border-radius: 15px;
      padding: 2rem;
      text-align: center;
      width: 100%;
      max-width: 400px;
    }

    .va-number {
      font-size: 1.3rem;
      font-weight: bold;
      color: #E28585;
      padding: 1.2rem;
      background: rgba(255, 255, 255, 0.9);
      border-radius: 8px;
      border: 2px solid #E28585;
      letter-spacing: 1px;
      margin: 1rem 0;
      word-break: break-all;
      line-height: 1.4;
      font-family: 'Courier New', monospace;
    }

    .expiry-info {
      color: #333;
      font-weight: 600;
      margin-top: 1rem;
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
      <!-- Back Button with Session Reset -->
      <a href="#" onclick="resetSessionAndGoHome()" class="back-button">← Kembali ke Home</a>
      
      <div class="payment-content">
        <!-- Kiri -->
        <div class="payment-left">
          <h1 class="payment-title">Pembayaran Virtual Bank BNI</h1>
          <div class="payment-instructions">
            <p style="margin-bottom: 0.5rem;"><strong>Cara Pembayaran:</strong></p>
            <ol>
              <li>Buka aplikasi mobile banking BNI atau ATM BNI</li>
              <li>Pilih menu "Transfer" atau "Virtual Account"</li>
              <li>Masukkan nomor Virtual Account di sebelah kanan</li>
              <li>Konfirmasi nominal pembayaran (Rp 15.000)</li>
              <li>Selesaikan transaksi pembayaran</li>
            </ol>
          </div>
        </div>

        <!-- Kanan -->
        <div class="payment-right">
          <!-- Loading Spinner -->
          <div id="loading-container" class="loading-container">
            <div class="loading-spinner"></div>
            <p style="color: #333; font-weight: 600;">Membuat Virtual Account...</p>
            <div class="loading-progress">
              <div class="progress-bar"></div>
            </div>
          </div>
          
          <!-- VA Container (Hidden saat loading) -->
          <div id="va-container" class="va-container" style="display: none;">
            <h4 style="margin: 0 0 1rem 0; color: #333;">Nomor Virtual Account BNI:</h4>
            <div id="va" class="va-number"></div>
            <p id="expiry" class="expiry-info"></p>
          </div>
          
          <!-- Error Container (Hidden saat loading) -->
          <div id="error-container" class="error-container" style="display: none;">
            <p id="error-message" style="margin: 0; font-weight: 600;"></p>
          </div>
          
          <!-- Status Container (Hidden saat loading) -->
          <div id="status-container" class="status-container" style="display: none;">
            <p id="status" style="margin: 0; color: #333; font-weight: 600;">Status: Menunggu pembayaran...</p>
          </div>

          <button id="next" class="start-btn" style="display:none;" onclick="proceedToLayout()">Lanjutkan</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    let orderId = "";
    let statusInterval;

    // Function untuk reset session dan kembali ke home
    function resetSessionAndGoHome() {
      // Clear all intervals first
      clearAllIntervals();
      
      // Reset session via API
      fetch('../api-fetch/reset_session.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        }
      })
      .then(response => response.json())
      .then(result => {
        console.log('Session reset result:', result);
        // Navigate to home regardless of reset result
        window.location.href = '/index.php';
      })
      .catch(error => {
        console.error('Error resetting session:', error);
        // Navigate to home even if reset fails
        window.location.href = '/index.php';
      });
    }

    // Clear all intervals
    function clearAllIntervals() {
      if (statusInterval) clearInterval(statusInterval);
    }

    // Function untuk hide loading dan show VA
    function showVirtualAccount() {
      // Hide loading
      document.getElementById('loading-container').style.display = 'none';
      
      // Show VA dan status (timer dihandle oleh session-timer.js)
      document.getElementById('va-container').style.display = 'block';
      document.getElementById('status-container').style.display = 'block';
    }

    // Function untuk show error
    function showError(message) {
      document.getElementById('loading-container').style.display = 'none';
      document.getElementById('error-container').style.display = 'block';
      document.getElementById('error-message').textContent = message;
    }

    // Fetch Virtual Account dengan loading
    fetch('../api-fetch/charge_bank.php')
      .then(res => res.json())
      .then(data => {
        if (data.error || !data.va_number || !data.order_id) {
          console.error('Error response:', data);
          if (data.status_code === '402') {
            showError('Metode pembayaran BNI belum diaktifkan. Silakan coba metode pembayaran lain atau hubungi support@gofotobox.online.');
          } else {
            showError(data.error || 'Invalid response from server. Silakan coba lagi nanti.');
          }
          return;
        }
        
        orderId = data.order_id;

        // Set session order_id via fetch
        fetch('../api-fetch/set_session.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({ order_id: orderId })
        });

        // Tampilkan Virtual Account dan waktu kedaluwarsa
        document.getElementById('va').textContent = data.va_number;
        document.getElementById('expiry').textContent = 'Kedaluwarsa: ' + (data.expiry_time || 'Tidak tersedia');

        // Simulasi loading delay (2 detik) untuk UX yang lebih baik
        setTimeout(() => {
          showVirtualAccount();
          pollStatus();
        }, 2000);
      })
      .catch(error => {
        console.error('Error:', error);
        showError('Terjadi kesalahan saat membuat Virtual Account. Silakan coba lagi atau hubungi support@gofotobox.online.');
      });

    function pollStatus() {
      statusInterval = setInterval(() => {
        fetch(`../api-fetch/check_status.php?order_id=${orderId}`)
          .then(res => res.json())
          .then(data => {
            if (data.error) {
              console.error('Error checking status:', data.error);
              return;
            }
            document.getElementById('status').innerText = "Status: " + data.transaction_status;
            if (data.transaction_status === "settlement") {
              clearAllIntervals();
              
              // Complete payment in session
              fetch('../api-fetch/complete_payment.php', {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json',
                },
                body: JSON.stringify({ order_id: orderId })
              })
              .then(response => response.json())
              .then(result => {
                if (result.success) {
                  document.getElementById('status').style.color = "#28a745";
                  document.getElementById('next').style.display = "block";
                } else {
                  console.error('Failed to complete payment:', result);
                }
              });
            } else if (data.transaction_status === "expire" || data.transaction_status === "cancel") {
              clearAllIntervals();
              document.getElementById('status').style.color = "#dc3545";
              document.getElementById('status').innerText = "Status: Transaksi " + data.transaction_status;
              showError('Transaksi telah kedaluwarsa atau dibatalkan. Silakan mulai ulang.');
            }
          })
          .catch(error => {
            console.error('Error checking status:', error);
          });
      }, 3000);
    }

    // Function untuk proceed ke layout selection
    function proceedToLayout() {
      console.log('Payment completed, transitioning to layout selection');
      
      // Clear intervals before navigation
      clearAllIntervals();
      
      // Navigate langsung karena payment sudah completed via complete_payment.php
      window.location.href = './selectlayout.php';
    }

    // Handle page unload
    window.addEventListener('beforeunload', () => {
      clearAllIntervals();
    });
  </script>

  <!-- Session Timer Script -->
  <script src="../includes/session-timer.js"></script>
  
  <?php PWAHelper::addPWAScript(); ?>
</body>

</html>