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
  <title>Payment Link - GoBooth</title>
  
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

    .customer-form {
      background: rgba(226, 133, 133, 0.1);
      border: 2px solid #E28585;
      border-radius: 15px;
      padding: 2rem;
      width: 100%;
      max-width: 400px;
    }

    .form-group {
      margin-bottom: 1.5rem;
    }

    .form-label {
      display: block;
      color: #333;
      font-weight: 600;
      margin-bottom: 0.5rem;
    }

    .form-input {
      width: 100%;
      padding: 12px;
      border: 2px solid #E28585;
      border-radius: 8px;
      font-size: 1rem;
      color: #333;
      background: rgba(255, 255, 255, 0.9);
      transition: all 0.3s ease;
      box-sizing: border-box;
    }

    .form-input:focus {
      outline: none;
      border-color: #d16969;
      box-shadow: 0 0 0 3px rgba(226, 133, 133, 0.1);
    }

    .payment-link-container {
      background: rgba(226, 133, 133, 0.1);
      border: 2px solid #E28585;
      border-radius: 15px;
      padding: 2rem;
      width: 100%;
      max-width: 400px;
      text-align: center;
    }

    .payment-link-info {
      margin-bottom: 2rem;
    }

    .payment-link-info h3 {
      color: #333;
      margin-bottom: 1rem;
    }

    .payment-link-info p {
      color: #666;
      line-height: 1.5;
    }

    .payment-link-button {
      background: linear-gradient(135deg, #00AA13, #00D91A);
      color: white;
      border: none;
      border-radius: 15px;
      padding: 15px 30px;
      font-weight: 700;
      font-size: 1.1rem;
      cursor: pointer;
      transition: all 0.3s ease;
      width: 100%;
      margin-bottom: 1rem;
      text-decoration: none;
      display: inline-block;
      box-shadow: 0 4px 15px rgba(0, 170, 19, 0.3);
    }

    .payment-link-button:hover {
      background: linear-gradient(135deg, #009612, #00C018);
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(0, 170, 19, 0.4);
    }

    .payment-link-url {
      background: rgba(255, 255, 255, 0.9);
      border: 2px solid #E28585;
      border-radius: 8px;
      padding: 10px;
      font-size: 0.8rem;
      color: #666;
      word-break: break-all;
      margin-bottom: 1rem;
    }

    .timer-info {
      background: rgba(255, 193, 7, 0.1);
      border: 2px solid #ffc107;
      border-radius: 8px;
      padding: 10px;
      color: #856404;
      font-weight: 600;
      margin-top: 1rem;
    }

    .error-container {
      margin-top: 1rem;
      padding: 1rem;
      background: rgba(220, 53, 69, 0.1);
      border-radius: 10px;
      border-left: 4px solid #dc3545;
      color: #dc3545;
      text-align: center;
      width: 100%;
      max-width: 400px;
    }

    .loading-container {
      text-align: center;
      padding: 2rem;
      width: 100%;
      max-width: 400px;
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
      width: 100%;
    }

    .start-btn:hover {
      background: #d16969;
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(226, 133, 133, 0.3);
    }

    .start-btn:disabled {
      background: #ccc;
      cursor: not-allowed;
      transform: none;
      box-shadow: none;
    }

    .copy-button {
      background: #17a2b8;
      color: white;
      border: none;
      border-radius: 5px;
      padding: 5px 10px;
      font-size: 0.8rem;
      cursor: pointer;
      margin-left: 10px;
    }

    .copy-button:hover {
      background: #138496;
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
          <h1 class="payment-title">Payment Link</h1>
          <div class="payment-instructions">
            <p>Silakan isi data Anda di bawah ini untuk mendapatkan link pembayaran:</p>
            <ol>
              <li>Masukkan nama lengkap Anda</li>
              <li>Masukkan nomor telepon yang valid</li>
              <li>Klik "Buat Link Pembayaran"</li>
              <li>Anda akan mendapat link yang bisa dibuka di browser manapun</li>
              <li>Pilih metode pembayaran dan selesaikan transaksi</li>
              <li>Setelah berhasil, kembali ke halaman ini</li>
            </ol>
          </div>
        </div>

        <!-- Kanan -->
        <div class="payment-right">
          <!-- Customer Form -->
          <div id="customer-form" class="customer-form">
            <div class="form-group">
              <label class="form-label" for="firstName">Nama Depan *</label>
              <input type="text" id="firstName" class="form-input" placeholder="Contoh: Budi" required>
            </div>
            
            <div class="form-group">
              <label class="form-label" for="lastName">Nama Belakang</label>
              <input type="text" id="lastName" class="form-input" placeholder="Contoh: Santoso">
            </div>
            
            <div class="form-group">
              <label class="form-label" for="phone">Nomor Telepon *</label>
              <input type="tel" id="phone" class="form-input" placeholder="Contoh: 08123456789" required>
            </div>
            
            <div class="form-group">
              <label class="form-label" for="email">Email (Opsional)</label>
              <input type="email" id="email" class="form-input" placeholder="Contoh: nama@email.com">
            </div>
            
            <button id="create-link-button" class="start-btn" onclick="createPaymentLink()">Buat Link Pembayaran - Rp 15.000</button>
          </div>
          
          <!-- Loading Container -->
          <div id="loading-container" class="loading-container" style="display: none;">
            <div class="loading-spinner"></div>
            <div class="loading-progress">
              <div class="progress-bar"></div>
            </div>
            <p>Membuat link pembayaran...</p>
          </div>
          
          <!-- Payment Link Container -->
          <div id="payment-link-container" class="payment-link-container" style="display: none;">
            <div class="payment-link-info">
              <h3>🔗 Link Pembayaran Berhasil Dibuat!</h3>
              <p>Klik tombol di bawah atau copy link untuk melakukan pembayaran</p>
            </div>
            
            <a id="payment-link-button" href="#" target="_blank" class="payment-link-button">
              💳 Bayar Sekarang - Rp 15.000
            </a>
            
            <div class="payment-link-url">
              <span id="payment-url-text"></span>
              <button class="copy-button" onclick="copyPaymentUrl()">Copy</button>
            </div>
            
            <div class="timer-info">
              ⏰ Link akan expired dalam <span id="countdown-timer">20:00</span>
            </div>
            
            <button class="start-btn" onclick="checkPaymentStatus()">🔄 Cek Status Pembayaran</button>
            <button class="start-btn" onclick="showCustomerForm()" style="background: #6c757d; margin-top: 10px;">Buat Ulang Link</button>
          </div>
          
          <!-- Error Container -->
          <div id="error-container" class="error-container" style="display: none;">
            <p id="error-message"></p>
            <button class="start-btn" onclick="showCustomerForm()">Coba Lagi</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    let currentOrderId = "";
    let paymentUrl = "";
    let countdownInterval;
    let statusCheckInterval;

    // Function untuk reset session dan kembali ke home
    function resetSessionAndGoHome() {
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
        window.location.href = '/index.php';
      })
      .catch(error => {
        console.error('Error resetting session:', error);
        window.location.href = '/index.php';
      });
    }

    // Clear all intervals
    function clearAllIntervals() {
      if (countdownInterval) clearInterval(countdownInterval);
      if (statusCheckInterval) clearInterval(statusCheckInterval);
    }

    // Function untuk show customer form
    function showCustomerForm() {
      clearAllIntervals();
      document.getElementById('customer-form').style.display = 'block';
      document.getElementById('loading-container').style.display = 'none';
      document.getElementById('payment-link-container').style.display = 'none';
      document.getElementById('error-container').style.display = 'none';
    }

    // Function untuk show loading
    function showLoading() {
      document.getElementById('customer-form').style.display = 'none';
      document.getElementById('loading-container').style.display = 'block';
      document.getElementById('payment-link-container').style.display = 'none';
      document.getElementById('error-container').style.display = 'none';
    }

    // Function untuk show payment link
    function showPaymentLink() {
      document.getElementById('customer-form').style.display = 'none';
      document.getElementById('loading-container').style.display = 'none';
      document.getElementById('payment-link-container').style.display = 'block';
      document.getElementById('error-container').style.display = 'none';
    }

    // Function untuk show error
    function showError(message) {
      clearAllIntervals();
      document.getElementById('customer-form').style.display = 'none';
      document.getElementById('loading-container').style.display = 'none';
      document.getElementById('payment-link-container').style.display = 'none';
      document.getElementById('error-container').style.display = 'block';
      document.getElementById('error-message').textContent = message;
    }

    // Validate form
    function validateForm() {
      const firstName = document.getElementById('firstName').value.trim();
      const phone = document.getElementById('phone').value.trim();
      
      if (!firstName) {
        alert('Nama depan harus diisi!');
        return false;
      }
      
      if (!phone) {
        alert('Nomor telepon harus diisi!');
        return false;
      }
      
      // Validate Indonesian phone number
      const phoneRegex = /^(\+62|62|0)8[1-9][0-9]{6,9}$/;
      if (!phoneRegex.test(phone)) {
        alert('Format nomor telepon tidak valid! Contoh: 08123456789');
        return false;
      }
      
      return true;
    }

    // Create payment link
    async function createPaymentLink() {
      if (!validateForm()) {
        return;
      }

      // Disable button
      const createButton = document.getElementById('create-link-button');
      createButton.disabled = true;
      createButton.textContent = 'Membuat Link...';

      // Show loading
      showLoading();

      try {
        // Get form data
        const firstName = document.getElementById('firstName').value.trim();
        const lastName = document.getElementById('lastName').value.trim();
        const phone = document.getElementById('phone').value.trim();
        const email = document.getElementById('email').value.trim();

        // Create Payment Link
        const response = await fetch('../api-fetch/create_payment_link.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            first_name: firstName,
            last_name: lastName,
            phone: phone,
            email: email
          })
        });

        const data = await response.json();

        if (data.success) {
          currentOrderId = data.order_id;
          paymentUrl = data.payment_url;
          
          // Set session order_id
          await fetch('../api-fetch/set_session.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify({ order_id: currentOrderId })
          });

          // Update UI with payment link
          document.getElementById('payment-link-button').href = paymentUrl;
          document.getElementById('payment-url-text').textContent = paymentUrl;

          // Show payment link
          showPaymentLink();

          // Start countdown timer (20 minutes)
          startCountdown(20 * 60);

          // Start checking payment status every 5 seconds
          startStatusCheck();

        } else {
          throw new Error(data.error || 'Terjadi kesalahan saat membuat payment link');
        }

      } catch (error) {
        console.error('Error:', error);
        showError('Terjadi kesalahan: ' + error.message);
      } finally {
        // Re-enable button
        createButton.disabled = false;
        createButton.textContent = 'Buat Link Pembayaran - Rp 15.000';
      }
    }

    // Start countdown timer
    function startCountdown(seconds) {
      const timerElement = document.getElementById('countdown-timer');
      
      countdownInterval = setInterval(() => {
        const minutes = Math.floor(seconds / 60);
        const remainingSeconds = seconds % 60;
        
        timerElement.textContent = `${minutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')}`;
        
        if (seconds <= 0) {
          clearInterval(countdownInterval);
          showError('Link pembayaran telah expired. Silakan buat link baru.');
        }
        
        seconds--;
      }, 1000);
    }

    // Start status checking
    function startStatusCheck() {
      statusCheckInterval = setInterval(() => {
        checkPaymentStatus(false);
      }, 5000);
    }

    // Check payment status
    async function checkPaymentStatus(showAlert = true) {
      if (!currentOrderId) return;

      try {
        const response = await fetch(`../api-fetch/check_status.php?order_id=${currentOrderId}`);
        const data = await response.json();

        if (data.transaction_status === 'settlement' || data.transaction_status === 'capture') {
          clearAllIntervals();
          
          // Complete payment
          await completePayment(data);
          
        } else if (data.transaction_status === 'pending') {
          if (showAlert) {
            alert('Pembayaran masih pending. Silakan selesaikan pembayaran Anda.');
          }
        } else if (data.transaction_status === 'expire' || data.transaction_status === 'cancel') {
          clearAllIntervals();
          showError('Pembayaran telah expired atau dibatalkan. Silakan buat link baru.');
        }

      } catch (error) {
        console.error('Error checking payment status:', error);
        if (showAlert) {
          alert('Gagal mengecek status pembayaran. Silakan coba lagi.');
        }
      }
    }

    // Complete payment
    async function completePayment(paymentResult) {
      try {
        const response = await fetch('../api-fetch/complete_payment.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({ 
            order_id: currentOrderId,
            payment_result: paymentResult
          })
        });

        const data = await response.json();

        if (data.success) {
          alert('Pembayaran berhasil! Anda akan diarahkan ke halaman pemilihan layout.');
          window.location.href = './selectlayout.php';
        } else {
          throw new Error(data.error || 'Gagal menyelesaikan pembayaran');
        }

      } catch (error) {
        console.error('Error completing payment:', error);
        showError('Pembayaran berhasil tetapi terjadi kesalahan sistem. Silakan hubungi support.');
      }
    }

    // Copy payment URL
    function copyPaymentUrl() {
      if (navigator.clipboard && paymentUrl) {
        navigator.clipboard.writeText(paymentUrl).then(() => {
          alert('Link pembayaran berhasil dicopy!');
        }).catch(() => {
          // Fallback for older browsers
          const textArea = document.createElement('textarea');
          textArea.value = paymentUrl;
          document.body.appendChild(textArea);
          textArea.select();
          document.execCommand('copy');
          document.body.removeChild(textArea);
          alert('Link pembayaran berhasil dicopy!');
        });
      }
    }

    // Form validation on input
    document.addEventListener('DOMContentLoaded', function() {
      const firstName = document.getElementById('firstName');
      const phone = document.getElementById('phone');
      
      // Real-time validation
      firstName.addEventListener('input', function() {
        if (this.value.trim()) {
          this.style.borderColor = '#28a745';
        } else {
          this.style.borderColor = '#E28585';
        }
      });
      
      phone.addEventListener('input', function() {
        const phoneRegex = /^(\+62|62|0)8[1-9][0-9]{6,9}$/;
        if (phoneRegex.test(this.value.trim())) {
          this.style.borderColor = '#28a745';
        } else {
          this.style.borderColor = '#E28585';
        }
      });
    });

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
