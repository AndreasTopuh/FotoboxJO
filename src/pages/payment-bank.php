<!DOCTYPE html>
<html>

<body>
    <h2>Transfer Bank (BCA)</h2>
    <div id="va"></div>
    <p id="status">Menunggu pembayaran...</p>
    <button id="next" style="display:none;" onclick="location.href='selectlayout.php'">Lanjutkan</button>

    <script>
        let orderId = "";

        fetch('../api-fetch/charge_bank.php')
            .then(res => res.json())
            .then(data => {
                orderId = data.order_id;
                document.getElementById('va').innerText = "No. Virtual Account BCA: " + data.va_number;
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