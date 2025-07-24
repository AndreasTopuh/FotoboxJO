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
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pembayaran QRIS</title>
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
          <h1 class="hero-title" style="font-size: 2rem; margin-bottom: 1rem;">Pembayaran QRIS</h1>
          <div class="hero-subtitle payment-qris-instructions" style="text-align: left; margin-top: 0;">
            <p style="margin-bottom: 0.5rem;"><strong>Cara Pembayaran:</strong></p>
            <ol style="margin: 0; padding-left: 20px; line-height: 1.4;">
              <li style="margin-bottom: 0.3rem;">Buka aplikasi e-wallet (GoPay, Dana, OVO, dll)</li>
              <li style="margin-bottom: 0.3rem;">Pilih fitur "Scan QR" atau "Bayar"</li>
              <li style="margin-bottom: 0.3rem;">Arahkan kamera ke QR code di sebelah kanan</li>
              <li style="margin-bottom: 0.3rem;">Konfirmasi pembayaran di aplikasi Anda</li>
              <li style="margin-bottom: 0;">Tunggu konfirmasi pembayaran berhasil</li>
            </ol>
          </div>
        </div>

        <!-- Divider -->
        <div class="select-payment-divider"></div>

        <!-- Kanan -->
        <div class="select-payment-right">
          <!-- Timer (Hidden saat loading) -->
          <div id="timer-container" class="timer-container" style="text-align: center; margin-bottom: 1rem; display: none;">
            <h3 style="color: #ff4444; margin: 0;">Waktu Tersisa:</h3>
            <div id="timer" style="font-size: 2rem; font-weight: bold; color: #ff4444;">03:00</div>
          </div>
          
          <!-- Loading Spinner -->
          <div id="loading-container" class="loading-container" style="text-align: center; margin-bottom: 1rem;">
            <div class="loading-spinner"></div>
            <p style="margin-top: 1rem; color: #333; font-weight: 600;">Memuat QR Code...</p>
            <div class="loading-progress">
              <div class="progress-bar"></div>
            </div>
          </div>
          
          <!-- QR Container (Hidden saat loading) -->
          <div id="qr-container" class="qr-container" style="display: none;">
            <img id="qris-img" src="" style="width: 180px; height: 180px; object-fit: contain;" alt="QRIS Code">
          </div>
          
          <!-- Status Container (Hidden saat loading) -->
          <div id="status-container" class="status-container" style="display: none;">
            <p id="status" style="margin: 0; color: #333; font-weight: 600;">Status: Menunggu pembayaran...</p>
          </div>
          
          <button id="next" class="start-btn" style="display:none;" onclick="location.href='./selectlayout.php'">Lanjutkan</button>
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

        // Function untuk hide loading dan show QR
        function showQRCode() {
            // Hide loading
            document.getElementById('loading-container').style.display = 'none';
            
            // Show QR dan timer
            document.getElementById('timer-container').style.display = 'block';
            document.getElementById('qr-container').style.display = 'block';
            document.getElementById('status-container').style.display = 'block';
        }

        // Fetch QR code dengan loading
        fetch('../api-fetch/charge_qris.php')
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

                // Tampilkan QR code di image
                const qrUrl = data.qr_url;
                document.getElementById('qris-img').src = qrUrl;

                // Simulasi loading delay (2 detik) untuk UX yang lebih baik
                setTimeout(() => {
                    showQRCode();
                    
                    // Mulai timer dan polling status setelah QR muncul
                    startTimer();
                    pollStatus();
                }, 2000);
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memuat QR code');
                
                // Hide loading jika error
                document.getElementById('loading-container').style.display = 'none';
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

        /* Loading Spinner Styles */
        .loading-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 250px;
        }

        .loading-spinner {
            width: 60px;
            height: 60px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #007bff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Loading Progress Bar */
        .loading-progress {
            width: 200px;
            height: 6px;
            background-color: #f3f3f3;
            border-radius: 3px;
            overflow: hidden;
            margin-top: 1rem;
        }

        .progress-bar {
            width: 0%;
            height: 100%;
            background: linear-gradient(90deg, #007bff, #00d4ff);
            border-radius: 3px;
            animation: loadProgress 2s ease-out forwards;
        }

        @keyframes loadProgress {
            0% { width: 0%; }
            50% { width: 70%; }
            100% { width: 100%; }
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .loading-spinner {
                width: 50px;
                height: 50px;
            }
            
            .loading-progress {
                width: 150px;
            }
        }
    </style>
</body>

</html>