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
  // Layout already selected, redirect to layout page
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
      background: #fff;
      color: var(--pink-primary);
      border: 2px solid var(--pink-secondary);
      border-radius: 25px;
      padding: 10px 20px;
      font-weight: 600;
      text-decoration: none;
      display: inline-block;
      transition: all 0.3s ease;
      margin-bottom: 2rem;
    }

    .back-button:hover {
      background: var(--pink-primary);
      color: #fff;
      border: 2px solid #fff;
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
      background: #fff;
      border: 2px solid var(--pink-secondary);
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
      color: var(--pink-primary);
      font-weight: 600;
      font-size: 1.1rem;
    }

    .payment-option:hover {
      background: var(--pink-primary);
      color: #fff;
      border: 2px solid #fff;
      box-shadow: 0 8px 25px rgba(233, 30, 99, 0.3);
      transform: translateY(-2px);
    }

    .payment-option img {
      width: 50px;
      height: 50px;
      object-fit: contain;
    }

    /* Developer Button - tersembunyi tapi bisa diakses */
    .developer-access {
      position: fixed;
      bottom: 20px;
      right: 20px;
      width: 40px;
      height: 40px;
      background: transparent;
      border: none;
      cursor: pointer;
      opacity: 0.1;
      transition: opacity 0.3s ease;
      z-index: 1000;
    }

    .developer-access:hover {
      opacity: 0.5;
    }

    /* Modal Styles */
    .modal-overlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.8);
      z-index: 2000;
      backdrop-filter: blur(5px);
    }

    .modal-content {
      max-width: 400px;
      margin: 250px auto;
    }

    .modal-title {
      color: #333;
      font-size: 1.5rem;
      font-weight: 700;
      margin-bottom: 1rem;
      text-align: center;
    }

    .modal-input {
      width: 100%;
      padding: 12px;
      border: 2px solid #E28585;
      border-radius: 8px;
      font-size: 1rem;
      margin-bottom: 1rem;
      text-align: center;
      letter-spacing: 2px;
    }

    .modal-buttons {
      display: flex;
      gap: 1rem;
      justify-content: center;
    }

    .modal-btn {
      padding: 10px 20px;
      border: none;
      border-radius: 8px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .modal-btn-primary {
      background: #E28585;
      color: white;
    }

    .modal-btn-primary:hover {
      background: #d67373;
    }

    .modal-btn-secondary {
      background: #f0f0f0;
      color: #333;
    }

    .modal-btn-secondary:hover {
      background: #e0e0e0;
    }

    /* Virtual Keyboard Styles */
    .virtual-keyboard {
      margin-top: 1rem;
      padding: 1rem;
      background: #f8f8f8;
      border-radius: 10px;
      border: 1px solid #ddd;
    }

    .keyboard-row {
      display: flex;
      justify-content: center;
      gap: 8px;
      margin-bottom: 8px;
    }

    .keyboard-key {
      background: white;
      border: 2px solid #E28585;
      border-radius: 8px;
      padding: 12px 16px;
      font-size: 1.1rem;
      font-weight: 600;
      color: #333;
      cursor: pointer;
      transition: all 0.2s ease;
      user-select: none;
      min-width: 50px;
      text-align: center;
    }

    .keyboard-key:hover {
      background: rgba(226, 133, 133, 0.1);
      transform: translateY(-1px);
    }

    .keyboard-key:active {
      background: rgba(226, 133, 133, 0.2);
      transform: translateY(0);
    }

    .keyboard-key.wide {
      min-width: 80px;
      background: rgba(226, 133, 133, 0.1);
    }

    .keyboard-key.wide:hover {
      background: rgba(226, 133, 133, 0.2);
    }

    .keyboard-display {
      background: #fff;
      border: 2px solid #E28585;
      border-radius: 8px;
      padding: 8px;
      margin-bottom: 10px;
      text-align: center;
      font-size: 1.2rem;
      font-weight: 600;
      letter-spacing: 4px;
      min-height: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
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
          QRIS
          <img src="../assets/qris.png" alt="QRIS Logo">
        </a>

        <!-- BCA -->
        <a href="#" onclick="startPaymentSession('bank'); return false;" class="payment-option">
          Virtual Bank (BCA)
          <img src="../assets/bca.png" alt="BCA Logo">
        </a>

        <!-- Cash Payment -->
        <a href="#" onclick="showCashModal(); return false;" class="payment-option">
          Cash Payment
          <img src="../assets/icons/cash-icon.png" alt="Cash" style="width: 50px; height: 50px; background: #4CAF50; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 20px;">💰</img>
        </a>
      </div>
    </div>
  </div>

  <!-- Developer Access Button (tersembunyi) -->
  <button class="developer-access" onclick="showDeveloperModal()" title="Developer Access">
    <div style="width: 100%; height: 100%; background: rgba(128, 73, 73, 0.3); border-radius: 50%;"></div>
  </button>

  <!-- Cash Payment Modal -->
  <div id="cashModal" class="modal-overlay">
    <div class="modal-content">
      <h3 class="modal-title">Cash Payment</h3>
      <!-- Virtual Keyboard -->
      <div class="virtual-keyboard">
        <input
          type="password"
          id="cashCode"
          class="modal-input"
          placeholder="Masukkan kode cash dari admin"
          maxlength="5"
          readonly />

        <div class="keyboard-row">
          <button class="keyboard-key" onclick="addCashDigit('1')">1</button>
          <button class="keyboard-key" onclick="addCashDigit('2')">2</button>
          <button class="keyboard-key" onclick="addCashDigit('3')">3</button>
        </div>

        <div class="keyboard-row">
          <button class="keyboard-key" onclick="addCashDigit('4')">4</button>
          <button class="keyboard-key" onclick="addCashDigit('5')">5</button>
          <button class="keyboard-key" onclick="addCashDigit('6')">6</button>
        </div>

        <div class="keyboard-row">
          <button class="keyboard-key" onclick="addCashDigit('7')">7</button>
          <button class="keyboard-key" onclick="addCashDigit('8')">8</button>
          <button class="keyboard-key" onclick="addCashDigit('9')">9</button>
        </div>

        <div class="keyboard-row">
          <button class="keyboard-key wide" onclick="clearCashInput()">Clear</button>
          <button class="keyboard-key" onclick="addCashDigit('0')">0</button>
          <button class="keyboard-key wide" onclick="deleteCashDigit()">⌫</button>
        </div>
      </div>

      <div class="modal-buttons">
        <button class="modal-btn modal-btn-secondary" onclick="closeCashModal()">Batal</button>
        <button class="modal-btn modal-btn-primary" onclick="verifyCashCode()">Verifikasi</button>
      </div>
    </div>
  </div>

  <!-- Developer Modal -->
  <div id="developerModal" class="modal-overlay">
    <div class="modal-content">
      <h3 class="modal-title">Developer Access</h3>
      <!-- Virtual Keyboard -->
      <div class="virtual-keyboard">
        <input
          type="password"
          id="developerCode"
          class="modal-input"
          placeholder="Masukkan kode akses"
          maxlength="5"
          readonly />

        <div class="keyboard-row">
          <button class="keyboard-key" onclick="addDigit('1')">1</button>
          <button class="keyboard-key" onclick="addDigit('2')">2</button>
          <button class="keyboard-key" onclick="addDigit('3')">3</button>
        </div>

        <div class="keyboard-row">
          <button class="keyboard-key" onclick="addDigit('4')">4</button>
          <button class="keyboard-key" onclick="addDigit('5')">5</button>
          <button class="keyboard-key" onclick="addDigit('6')">6</button>
        </div>

        <div class="keyboard-row">
          <button class="keyboard-key" onclick="addDigit('7')">7</button>
          <button class="keyboard-key" onclick="addDigit('8')">8</button>
          <button class="keyboard-key" onclick="addDigit('9')">9</button>
        </div>

        <div class="keyboard-row">
          <button class="keyboard-key wide" onclick="clearInput()">Clear</button>
          <button class="keyboard-key" onclick="addDigit('0')">0</button>
          <button class="keyboard-key wide" onclick="deleteDigit()">⌫</button>
        </div>
      </div>

      <div class="modal-buttons">
        <button class="modal-btn modal-btn-secondary" onclick="closeDeveloperModal()">Batal</button>
        <button class="modal-btn modal-btn-primary" onclick="verifyDeveloperCode()">Masuk</button>
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

    // Cash Modal Functions
    function showCashModal() {
      document.getElementById('cashModal').style.display = 'block';
    }

    function closeCashModal() {
      document.getElementById('cashModal').style.display = 'none';
      document.getElementById('cashCode').value = '';
    }

    // Cash Virtual Keyboard Functions
    function addCashDigit(digit) {
      const input = document.getElementById('cashCode');
      if (input.value.length < 5) {
        input.value += digit;
      }
    }

    function deleteCashDigit() {
      const input = document.getElementById('cashCode');
      input.value = input.value.slice(0, -1);
    }

    function clearCashInput() {
      document.getElementById('cashCode').value = '';
    }

    async function verifyCashCode() {
      const code = document.getElementById('cashCode').value;

      if (code.length < 5) {
        alert('Silakan masukkan 5 digit kode cash!');
        return;
      }

      try {
        // Verify cash code with API
        const response = await fetch('../api-fetch/verify_cash_code.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            code: code
          })
        });

        const result = await response.json();

        if (result.success) {
          // Start cash payment session
          const sessionResponse = await fetch('../api-fetch/set_session.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify({
              action: 'start_cash_session'
            })
          });

          const sessionResult = await sessionResponse.json();

          if (sessionResult.success) {
            // Redirect to select layout
            window.location.href = 'selectlayout.php';
          } else {
            alert('Gagal memulai sesi cash. Silakan coba lagi.');
          }
        } else {
          alert(result.message || 'Kode cash tidak valid!');
          clearCashInput();
        }
      } catch (error) {
        console.error('Error verifying cash code:', error);
        alert('Terjadi kesalahan. Silakan coba lagi.');
      }
    }

    // Developer Modal Functions
    function showDeveloperModal() {
      document.getElementById('developerModal').style.display = 'block';
      updateKeyboardDisplay();
    }

    function closeDeveloperModal() {
      document.getElementById('developerModal').style.display = 'none';
      document.getElementById('developerCode').value = '';
      updateKeyboardDisplay();
    }

    // Virtual Keyboard Functions
    function addDigit(digit) {
      const input = document.getElementById('developerCode');
      if (input.value.length < 5) {
        input.value += digit;
        updateKeyboardDisplay();
      }
    }

    function deleteDigit() {
      const input = document.getElementById('developerCode');
      input.value = input.value.slice(0, -1);
      updateKeyboardDisplay();
    }

    function clearInput() {
      document.getElementById('developerCode').value = '';
      updateKeyboardDisplay();
    }

    function updateKeyboardDisplay() {
      const input = document.getElementById('developerCode');
      const display = document.getElementById('keyboardDisplay');
      const value = input.value;
      const masked = '●'.repeat(value.length) + '_'.repeat(5 - value.length);
      display.textContent = masked;
    }

    async function verifyDeveloperCode() {
      const code = document.getElementById('developerCode').value;

      if (code.length < 5) {
        alert('Silakan masukkan 5 digit kode akses!');
        return;
      }

      if (code === '54321') {
        try {
          // Start developer session dengan timer 20 menit tapi skip payment
          const response = await fetch('../api-fetch/set_session.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify({
              action: 'start_developer_session'
            })
          });

          const result = await response.json();

          if (result.success) {
            // Langsung redirect ke select layout
            window.location.href = 'selectlayout.php';
          } else {
            alert('Gagal memulai sesi developer. Silakan coba lagi.');
          }
        } catch (error) {
          console.error('Error starting developer session:', error);
          alert('Terjadi kesalahan. Silakan coba lagi.');
        }
      } else {
        alert('Kode akses salah!');
        clearInput();
      }
    }

    // Event listener untuk Enter key di modal
    document.addEventListener('DOMContentLoaded', function() {
      // Cash modal event listeners
      document.getElementById('cashCode').addEventListener('input', function(e) {
        // Hanya izinkan angka
        e.target.value = e.target.value.replace(/[^0-9]/g, '');
      });

      document.getElementById('cashCode').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
          verifyCashCode();
        }
      });

      // Close cash modal ketika klik overlay
      document.getElementById('cashModal').addEventListener('click', function(e) {
        if (e.target === this) {
          closeCashModal();
        }
      });

      // Developer modal event listeners
      // Physical keyboard support (masih bisa digunakan jika ada)
      document.getElementById('developerCode').addEventListener('input', function(e) {
        // Hanya izinkan angka
        e.target.value = e.target.value.replace(/[^0-9]/g, '');
        updateKeyboardDisplay();
      });

      document.getElementById('developerCode').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
          verifyDeveloperCode();
        }
      });

      // Close developer modal ketika klik overlay
      document.getElementById('developerModal').addEventListener('click', function(e) {
        if (e.target === this) {
          closeDeveloperModal();
        }
      });

      // Prevent context menu on virtual keyboard
      document.querySelectorAll('.keyboard-key').forEach(key => {
        key.addEventListener('contextmenu', function(e) {
          e.preventDefault();
        });
      });
    });
  </script>

  <?php PWAHelper::addPWAScript(); ?>
</body>

</html>