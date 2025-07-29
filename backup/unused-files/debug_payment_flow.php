<?php
session_start();

?>
<!DOCTYPE html>
<html>
<head>
    <title>Debug Payment Flow</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; background: #f9f9f9; }
        .step { margin: 10px 0; padding: 10px; background: #e9e9e9; }
        button { padding: 10px 20px; margin: 10px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #0056b3; }
        pre { background: #f0f0f0; padding: 10px; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>Debug Payment Flow - PWA Session Test</h1>
    
    <div class="section">
        <h2>Current Session Info</h2>
        <pre><?php print_r($_SESSION); ?></pre>
        <p><strong>Session ID:</strong> <?php echo session_id(); ?></p>
        <p><strong>Current Time:</strong> <?php echo date('Y-m-d H:i:s', time()); ?></p>
        
        <?php if (isset($_SESSION['payment_expired_time'])): ?>
        <p><strong>Payment Expires:</strong> <?php echo date('Y-m-d H:i:s', $_SESSION['payment_expired_time']); ?></p>
        <p><strong>Time Left:</strong> <?php echo $_SESSION['payment_expired_time'] - time(); ?> seconds</p>
        <?php endif; ?>
    </div>

    <div class="section">
        <h2>Test Payment Flow</h2>
        
        <div class="step">
            <h3>Step 1: Start Payment Session</h3>
            <button onclick="startPaymentSession()">Start Payment QRIS</button>
            <button onclick="startPaymentBank()">Start Payment Bank</button>
        </div>
        
        <div class="step">
            <h3>Step 2: Simulate Payment Success</h3>
            <button onclick="simulatePaymentSuccess()">Simulate Payment Success</button>
        </div>
        
        <div class="step">
            <h3>Step 3: Go to Layout Selection</h3>
            <button onclick="goToSelectLayout()">Go to Select Layout</button>
        </div>
        
        <div class="step">
            <h3>Step 4: Test Layout Pages</h3>
            <button onclick="testCanvasLayout1()">Test Canvas Layout 1</button>
            <button onclick="testCustomizeLayout1()">Test Customize Layout 1</button>
        </div>
    </div>

    <div class="section">
        <h2>Session Management API Test</h2>
        <button onclick="testSetSession()">Test Set Session API</button>
        <button onclick="testCreateSession()">Test Create Session API</button>
        <div id="apiResult"></div>
    </div>

    <script>
        function startPaymentSession() {
            window.location.href = 'src/pages/payment-qris.php';
        }
        
        function startPaymentBank() {
            window.location.href = 'src/pages/payment-bank.php';
        }
        
        function simulatePaymentSuccess() {
            // Simulate successful payment
            fetch('src/api-fetch/set_session.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    session_type: 'layout_selection',
                    payment_success: true
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Payment simulation result:', data);
                document.getElementById('apiResult').innerHTML = '<pre>' + JSON.stringify(data, null, 2) + '</pre>';
                setTimeout(() => location.reload(), 1000);
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('apiResult').innerHTML = '<pre>Error: ' + error + '</pre>';
            });
        }
        
        function goToSelectLayout() {
            window.location.href = 'src/pages/selectlayout.php';
        }
        
        function testCanvasLayout1() {
            window.location.href = 'src/pages/canvasLayout1.php';
        }
        
        function testCustomizeLayout1() {
            window.location.href = 'src/pages/customizeLayout1.php';
        }
        
        function testSetSession() {
            fetch('src/api-fetch/set_session.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    session_type: 'test',
                    test_data: 'PWA session test'
                })
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('apiResult').innerHTML = '<h4>Set Session Result:</h4><pre>' + JSON.stringify(data, null, 2) + '</pre>';
            })
            .catch(error => {
                document.getElementById('apiResult').innerHTML = '<h4>Set Session Error:</h4><pre>' + error + '</pre>';
            });
        }
        
        function testCreateSession() {
            fetch('src/api-fetch/create_photo_session.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    layout: 'layout1'
                })
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('apiResult').innerHTML = '<h4>Create Session Result:</h4><pre>' + JSON.stringify(data, null, 2) + '</pre>';
            })
            .catch(error => {
                document.getElementById('apiResult').innerHTML = '<h4>Create Session Error:</h4><pre>' + error + '</pre>';
            });
        }
        
        // Auto refresh every 5 seconds to show session changes
        setTimeout(() => {
            location.reload();
        }, 30000);
    </script>
</body>
</html>
