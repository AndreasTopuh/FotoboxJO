<?php
session_start();

// Reset session sebelumnya jika ada
session_unset();
session_destroy();
session_start();

// Set session payment dengan waktu expired 3 menit
$_SESSION['payment_start_time'] = time();
$_SESSION['payment_expired_time'] = time() + (3 * 60); // 3 menit
$_SESSION['session_type'] = 'payment';

// Include PWA helper
require_once '../includes/pwa-helper.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pembayaran Virtual Bank BCA - GoFotobox</title>
  
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
          <h1 class="hero-title" style="font-size: 2rem; margin-bottom: 1rem;">Pembayaran Virtual Bank BCA</h1>
          <div class="hero-subtitle payment-bank-instructions" style="text-align: left; margin-top: 0;">
            <p style="margin-bottom: 0.5rem;"><strong>Cara Pembayaran:</strong></p>
            <ol style="margin: 0; padding-left: 20px; line-height: 1.4;">
              <li style="margin-bottom: 0.3rem;">Buka aplikasi mobile banking BCA atau ATM BCA</li>
              <li style="margin-bottom: 0.3rem;">Pilih menu "Transfer" atau "Virtual Account"</li>
              <li style="margin-bottom: 0.3rem;">Masukkan nomor Virtual Account di sebelah kanan</li>
              <li style="margin-bottom: 0.3rem;">Konfirmasi nominal pembayaran</li>
              <li style="margin-bottom: 0;">Selesaikan transaksi pembayaran</li>
            </ol>
          </div>
        </div>

        <!-- Divider -->
        <div class="select-payment-divider"></div>

        <!-- Kanan -->
        <div class="select-payment-right">
          <!-- Timer -->
          <div class="timer-container" style="text-align: center; margin-bottom: 1rem;">
            <h3 style="color: #ff4444; margin: 0;">Waktu Tersisa:</h3>
            <div id="timer" style="font-size: 2rem; font-weight: bold; color: #ff4444;">03:00</div>
          </div>
          
          <div class="va-container" style="text-align: center; margin-bottom: 1rem;">
            <h4 style="margin: 0.5rem 0;">Nomor Virtual Account BCA:</h4>
            <div id="va" style="font-size: 1.5rem; font-weight: bold; color: #007bff; padding: 1rem; background: #f8f9fa; border-radius: 8px; border: 2px dashed #007bff;">Loading...</div>
          </div>
          
          <div class="status-container">
            <p id="status" style="margin: 0; color: #333; font-weight: 600;">Status: Menunggu pembayaran...</p>
          </div>
          
          <button id="next" class="start-btn" style="display:none;" onclick="proceedToLayout()">Lanjutkan</button>
        </div>

      </div>
    </div>
  </div>

    <script>
        let orderId = "";
        let timeLeft = 180; // 3 menit dalam detik
        let timerInterval;
        let statusInterval;

        // Timer countdown
        function startTimer() {
            timerInterval = setInterval(() => {
                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;
                
                document.getElementById('timer').textContent = 
                    `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                
                if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                    clearInterval(statusInterval);
                    
                    // Tampilkan popup modal
                    showTimeoutModal();
                }
                
                timeLeft--;
            }, 1000);
        }

        // Fetch Virtual Account dan mulai timer
        fetch('../api-fetch/charge_bank.php')
            .then(res => res.json())
            .then(data => {
                if (data.error) {
                    alert('Error: ' + data.error);
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

                // Tampilkan Virtual Account
                document.getElementById('va').textContent = data.va_number;

                // Mulai timer dan polling status
                startTimer();
                pollStatus();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memuat nomor Virtual Account');
            });

        function pollStatus() {
            statusInterval = setInterval(() => {
                fetch(`../api-fetch/check_status.php?order_id=${orderId}`)
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById('status').innerText = "Status: " + data.transaction_status;
                        if (data.transaction_status === "settlement") {
                            clearInterval(timerInterval);
                            clearInterval(statusInterval);
                            document.getElementById('timer').textContent = "LUNAS";
                            document.getElementById('timer').style.color = "#28a745";
                            document.getElementById('status').style.color = "#28a745";
                            document.getElementById('next').style.display = "block";
                            
                            // Set session payment completed untuk PWA
                            fetch('../api-fetch/set_session.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify({ 
                                    payment_completed: true,
                                    payment_expired_time: Math.floor(Date.now() / 1000) + (15 * 60) // 15 menit
                                })
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error checking status:', error);
                    });
            }, 3000);
        }

        // Function untuk menampilkan modal timeout
        function showTimeoutModal() {
            const modal = document.getElementById('timeoutModal');
            modal.style.display = 'flex';
        }

        // Function untuk reset session dan kembali ke index
        function continueAfterTimeout() {
            fetch('../api-fetch/reset_session.php', {
                method: 'POST'
            })
            .then(() => {
                window.location.href = '../../index.html';
            });
        }

        // Function untuk proceed ke layout selection
        function proceedToLayout() {
            console.log('proceedToLayout called from Bank payment');
            
            // Set session for layout selection via API
            fetch('../api-fetch/set_session.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    payment_completed: true,
                    session_type: 'layout_selection',
                    payment_expired_time: Math.floor(Date.now() / 1000) + (15 * 60) // 15 minutes
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Session set result:', data);
                if (data.success) {
                    // Add small delay to ensure session is set
                    setTimeout(() => {
                        window.location.href = 'selectlayout.php';
                    }, 500);
                } else {
                    console.error('Failed to set session:', data);
                    // Fallback: try direct navigation
                    window.location.href = 'selectlayout.php';
                }
            })
            .catch(error => {
                console.error('Error setting session:', error);
                // Fallback: try direct navigation
                window.location.href = 'selectlayout.php';
            });
        }
    </script>

    <!-- Modal Timeout -->
    <div id="timeoutModal" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <h3>Waktu Pembayaran Habis</h3>
            <p>Waktu pembayaran telah berakhir. Silakan coba lagi.</p>
            <button onclick="continueAfterTimeout()" class="continue-btn">Lanjutkan</button>
        </div>
    </div>

    <style>
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        
        .modal-content {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            text-align: center;
            max-width: 400px;
            margin: 0 1rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }
        
        .modal-content h3 {
            color: #ff4444;
            margin-bottom: 1rem;
        }
        
        .continue-btn {
            background: #007bff;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            margin-top: 1rem;
        }
        
        .continue-btn:hover {
            background: #0056b3;
        }
    </style>
    
    <?php PWAHelper::addPWAScript(); ?>
</body>

</html>