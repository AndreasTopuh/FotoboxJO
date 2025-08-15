<?php
require_once '../includes/session-manager.php';
require_once '../includes/pwa-helper.php';

session_start();

if (!SessionManager::canAccessPaymentPage()) {
    header('Location: selectpayment.php');
    exit();
}

SessionManager::requireValidPaymentSession();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Custom QRIS Payment - GoBooth</title>
    <?php PWAHelper::addPWAHeaders(); ?>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="home-styles.css">
    <style>
        /* Same styling as payment-qris.php */
        .container { display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 100vh; padding: 2rem; max-width: 900px; margin: 0 auto; }
        .payment-card { background: rgba(255,255,255,0.9); border-radius: 20px; padding: 3rem; backdrop-filter: blur(10px); box-shadow: 0 8px 32px rgba(0,0,0,0.1); border: 1px solid rgba(255,255,255,0.3); width: 100%; max-width: 800px; }
        .back-button { background: rgba(226,133,133,0.1); color: #E28585; border: 2px solid #E28585; border-radius: 25px; padding: 10px 20px; font-weight: 600; text-decoration: none; display: inline-block; transition: all 0.3s ease; margin-bottom: 2rem; }
        .back-button:hover { background: #E28585; color: white; }
        .payment-content { display: flex; gap: 3rem; align-items: flex-start; }
        .payment-left { flex: 1; }
        .payment-right { flex: 1; display: flex; flex-direction: column; align-items: center; }
        .payment-title { color: #333; font-size: 2rem; font-weight: 700; margin-bottom: 1rem; }
        .payment-instructions { color: #666; line-height: 1.6; }
        .payment-instructions ol { margin: 0; padding-left: 20px; }
        .payment-instructions li { margin-bottom: 0.5rem; }
        .qr-container { background: rgba(226,133,133,0.1); border: 2px solid #E28585; border-radius: 15px; padding: 2rem; text-align: center; width: 100%; max-width: 400px; }
        .qr-container img { max-width: 250px; width: 100%; height: auto; border: 2px solid #E28585; border-radius: 8px; margin: 1rem 0; }
        .expiry-info { color: #333; font-weight: 600; margin-top: 1rem; }
        .error-container { margin-top: 1rem; padding: 1rem; background: rgba(220,53,69,0.1); border-radius: 10px; border-left: 4px solid #dc3545; color: #dc3545; text-align: center; }
        .loading-container { text-align: center; padding: 2rem; }
        .loading-spinner { border: 4px solid rgba(226,133,133,0.3); border-radius: 50%; border-top: 4px solid #E28585; width: 50px; height: 50px; animation: spin 1s linear infinite; margin: 0 auto 1rem; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>
</head>
<body>
    <div class="container">
        <div class="payment-card">
            <a href="selectpayment.php" class="back-button">Kembali</a>
            <div class="payment-content">
                <div class="payment-left">
                    <h1 class="payment-title">Pembayaran QRIS</h1>
                    <div class="payment-instructions">
                        <p>Cara Pembayaran:</p>
                        <ol>
                            <li>Buka aplikasi pembayaran QRIS Anda (GoPay, OVO, dll.)</li>
                            <li>Scan kode QR di samping</li>
                            <li>Verifikasi jumlah pembayaran: Rp 15.000</li>
                            <li>Konfirmasi pembayaran</li>
                            <li>Tunggu konfirmasi sukses</li>
                        </ol>
                    </div>
                </div>
                <div class="payment-right">
                    <div id="loading-container" class="loading-container" style="display: block;">
                        <div class="loading-spinner"></div>
                        <p>Memuat Kode QR...</p>
                    </div>
                    <div id="qr-container" class="qr-container" style="display: none;">
                        <img id="qris-img" src="" alt="QRIS Code">
                        <div id="expiry" class="expiry-info"></div>
                    </div>
                    <div id="status-container" style="display: none; margin-top: 1rem;">
                        <p id="status">Status: Menunggu Pembayaran</p>
                    </div>
                    <div id="error-container" class="error-container" style="display: none;">
                        <p id="error-message"></p>
                        <button onclick="retryPayment()" style="margin-top: 1rem; padding: 8px 16px; background: #E28585; color: white; border: none; border-radius: 15px; cursor: pointer;">Coba Lagi</button>
                    </div>
                    <button id="next" style="display: none; margin-top: 1rem; padding: 10px 20px; background: #28a745; color: white; border: none; border-radius: 25px; cursor: pointer;" onclick="proceedToLayout()">Lanjut ke Pemilihan Layout</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        let orderId = '';
        let statusInterval;

        function showError(message) {
            document.getElementById('loading-container').style.display = 'none';
            document.getElementById('error-container').style.display = 'block';
            document.getElementById('error-message').textContent = message;
        }

        function retryPayment() {
            document.getElementById('error-container').style.display = 'none';
            document.getElementById('loading-container').style.display = 'block';
            
            // Reset dan coba lagi
            setTimeout(() => {
                loadQRCode();
            }, 1000);
        }

        function loadQRCode() {
            fetch('../api-fetch/charge_qris.php')
                .then(res => {
                    if (!res.ok) {
                        throw new Error(`HTTP error! status: ${res.status}`);
                    }
                    return res.json();
                })
                .then(data => {
                    console.log('Response data:', data); // Untuk debugging
                    
                    if (data.error) {
                        showError(data.error);
                        return;
                    }
                    
                    if (!data.qr_url || !data.order_id) {
                        showError('Invalid response: missing QR URL or order ID');
                        return;
                    }
                    
                    orderId = data.order_id;
                    
                    // Set session order_id
                    fetch('../api-fetch/set_session.php', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/json'},
                        body: JSON.stringify({order_id: orderId})
                    });
                    
                    document.getElementById('qris-img').src = data.qr_url;
                    document.getElementById('expiry').textContent = 'Kedaluwarsa: ' + (data.expiry_time || 'Tidak tersedia');
                    
                    setTimeout(() => {
                        showQRCode();
                        pollStatus();
                    }, 2000);
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    showError('Error loading QR code: ' + error.message);
                });
        }

        function showQRCode() {
            document.getElementById('loading-container').style.display = 'none';
            document.getElementById('qr-container').style.display = 'block';
            document.getElementById('status-container').style.display = 'block';
        }

        // Load QR code when page loads
        loadQRCode();

        function pollStatus() {
            statusInterval = setInterval(() => {
                fetch(`../api-fetch/check_status.php?order_id=${orderId}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.error) return;
                        document.getElementById('status').innerText = "Status: " + data.transaction_status;
                        if (data.transaction_status === "settlement") {
                            clearInterval(statusInterval);
                            fetch('../api-fetch/complete_payment.php', {
                                method: 'POST',
                                headers: {'Content-Type': 'application/json'},
                                body: JSON.stringify({order_id: orderId})
                            }).then(res => res.json()).then(result => {
                                if (result.success) {
                                    document.getElementById('status').style.color = "#28a745";
                                    document.getElementById('next').style.display = "block";
                                }
                            });
                        } else if (data.transaction_status === "expire" || data.transaction_status === "cancel") {
                            clearInterval(statusInterval);
                            document.getElementById('status').style.color = "#dc3545";
                            showError('Transaksi kedaluwarsa/dibatalkan');
                        }
                    });
            }, 3000);
        }

        function proceedToLayout() {
            clearInterval(statusInterval);
            window.location.href = 'selectlayout.php';
        }

        window.addEventListener('beforeunload', () => clearInterval(statusInterval));
    </script>
    <script src="../includes/session-timer.js"></script>
    <?php PWAHelper::addPWAScript(); ?>
</body>
</html>