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
  <title>Pembayaran Digital - GoBooth</title>
  
  <?php PWAHelper::addPWAHeaders(); ?>
  
  <!-- Midtrans Snap.js -->
  <script type="text/javascript" 
    src="https://app.midtrans.com/snap/snap.js" 
    data-client-key="Mid-client-glZIkKBqFretZ-Td"></script>
  
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

    .snap-container {
      background: rgba(226, 133, 133, 0.1);
      border: 2px solid #E28585;
      border-radius: 15px;
      padding: 1rem;
      width: 100%;
      max-width: 400px;
      min-height: 400px;
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
          <h1 class="payment-title">Pembayaran Digital</h1>
          <div class="payment-instructions">
            <p>Silakan isi data Anda di bawah ini untuk melanjutkan pembayaran:</p>
            <ol>
              <li>Masukkan nama lengkap Anda</li>
              <li>Masukkan nomor telepon yang valid</li>
              <li>Klik "Bayar Sekarang" untuk membuka payment gateway</li>
              <li>Pilih metode pembayaran yang tersedia (GoPay, QRIS, ShopeePay)</li>
              <li>Selesaikan pembayaran sesuai instruksi</li>
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
            
            <button id="pay-button" class="start-btn" onclick="processPayment()">Bayar Sekarang - Rp 15.000</button>
          </div>
          
          <!-- Loading Container -->
          <div id="loading-container" class="loading-container" style="display: none;">
            <div class="loading-spinner"></div>
            <div class="loading-progress">
              <div class="progress-bar"></div>
            </div>
            <p>Memproses pembayaran...</p>
          </div>
          
          <!-- Snap Container (Hidden saat loading) -->
          <div id="snap-container" class="snap-container" style="display: none;"></div>
          
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

    // Function untuk reset session dan kembali ke home
    function resetSessionAndGoHome() {
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

    // Function untuk show customer form
    function showCustomerForm() {
      document.getElementById('customer-form').style.display = 'block';
      document.getElementById('loading-container').style.display = 'none';
      document.getElementById('snap-container').style.display = 'none';
      document.getElementById('error-container').style.display = 'none';
    }

    // Function untuk show loading
    function showLoading() {
      document.getElementById('customer-form').style.display = 'none';
      document.getElementById('loading-container').style.display = 'block';
      document.getElementById('snap-container').style.display = 'none';
      document.getElementById('error-container').style.display = 'none';
    }

    // Function untuk show snap
    function showSnap() {
      document.getElementById('customer-form').style.display = 'none';
      document.getElementById('loading-container').style.display = 'none';
      document.getElementById('snap-container').style.display = 'block';
      document.getElementById('error-container').style.display = 'none';
    }

    // Function untuk show error
    function showError(message) {
      document.getElementById('customer-form').style.display = 'none';
      document.getElementById('loading-container').style.display = 'none';
      document.getElementById('snap-container').style.display = 'none';
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

    // Process payment
    async function processPayment() {
      if (!validateForm()) {
        return;
      }

      // Disable button
      const payButton = document.getElementById('pay-button');
      payButton.disabled = true;
      payButton.textContent = 'Memproses...';

      // Show loading
      showLoading();

      try {
        // Get form data
        const firstName = document.getElementById('firstName').value.trim();
        const lastName = document.getElementById('lastName').value.trim();
        const phone = document.getElementById('phone').value.trim();
        const email = document.getElementById('email').value.trim();

        // Create Snap token
        const response = await fetch('../api-fetch/create_snap_token.php', {
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
          
          // Set session order_id
          await fetch('../api-fetch/set_session.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify({ order_id: currentOrderId })
          });

          // Show Snap
          showSnap();

          // Embed Snap
          window.snap.embed(data.snap_token, {
            embedId: 'snap-container',
            onSuccess: function (result) {
              console.log('Payment success:', result);
              completePayment(result);
            },
            onPending: function (result) {
              console.log('Payment pending:', result);
              alert('Pembayaran sedang diproses. Silakan selesaikan pembayaran Anda.');
            },
            onError: function (result) {
              console.log('Payment error:', result);
              showError('Pembayaran gagal. Silakan coba lagi.');
            },
            onClose: function () {
              console.log('Payment closed');
              showCustomerForm();
              // Re-enable button
              payButton.disabled = false;
              payButton.textContent = 'Bayar Sekarang - Rp 15.000';
            }
          });

        } else {
          throw new Error(data.error || 'Terjadi kesalahan saat membuat token pembayaran');
        }

      } catch (error) {
        console.error('Error:', error);
        showError('Terjadi kesalahan: ' + error.message);
        
        // Re-enable button
        payButton.disabled = false;
        payButton.textContent = 'Bayar Sekarang - Rp 15.000';
      }
    }

    // Complete payment
    async function completePayment(result) {
      try {
        const response = await fetch('../api-fetch/complete_payment.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({ 
            order_id: currentOrderId,
            payment_result: result
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
  </script>

  <!-- Session Timer Script -->
  <script src="../includes/session-timer.js"></script>
  
  <?php PWAHelper::addPWAScript(); ?>
</body>

</html>
