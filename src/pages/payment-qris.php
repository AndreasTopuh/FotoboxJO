<!DOCTYPE html>
<html>

<body>
    <h2>QRIS Gopay</h2>
    <p>Scan QR berikut atau copy URL QR-nya:</p>
    <p id="qr-url" style="word-break: break-all; font-size: 14px; color: blue;"></p>
    <img id="qris-img" src="" width="300" alt="QRIS Gopay">
    <p id="status">Status: Menunggu pembayaran...</p>
    <button id="next" style="display:none;" onclick="location.href='selectlayout.php'">Lanjutkan</button>

    <script>
        let orderId = "";

        fetch('../api-fetch/charge_qris.php')
            .then(res => res.json())
            .then(data => {
                orderId = data.order_id;

                // Tampilkan link QR Midtrans (yang akan di-embed ke generator QR)
                const qrUrl = data.qr_url;
                document.getElementById('qr-url').innerText = qrUrl;
                document.getElementById('qris-img').src = qrUrl; // <-- Tambahin ini

                pollStatus();
            });

        function pollStatus() {
            fetch(`../api-fetch/check_status.php?order_id=${orderId}`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('status').innerText = "Status: " + data.transaction_status;
                    if (data.transaction_status === "settlement") {
                        document.getElementById('next').style.display = "block";
                    } else {
                        setTimeout(pollStatus, 3000);
                    }
                });
        }
    </script>
</body>

</html>