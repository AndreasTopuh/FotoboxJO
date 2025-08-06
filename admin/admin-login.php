<?php
session_start();

// Handle logout
if (isset($_GET['logout'])) {
    unset($_SESSION['admin_access']);
    session_destroy();
    header('Location: admin-login.php');
    exit();
}

// Check if already logged in
if (isset($_SESSION['admin_access']) && $_SESSION['admin_access'] === true) {
    header('Location: admin.php');
    exit();
}

// Handle login attempt
if ($_POST['code'] ?? '' !== '') {
    $code = $_POST['code'];

    if ($code === '11000') {
        $_SESSION['admin_access'] = true;
        header('Location: admin.php');
        exit();
    } else {
        $error = 'Kode akses salah!';
    }
}

// Include PWA helper
require_once '../src/includes/pwa-helper.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Login - GoBooth</title>

    <?php PWAHelper::addPWAHeaders(); ?>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />
    <style>
        :root {
            --text-dark: #333;
            --text-light: #666;
            --btn-color: #ff4e68;
            --btn-hover: rgba(255, 94, 145, 0.4);
            --pink-primary: #E91E63;
            --pink-secondary: #C2185B;
            --pink-light: #F48FB1;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
            background: #fff;
            height: 100%;
            overflow-x: hidden;
            justify-content: center;
        }

        body {
            overflow-y: auto;
        }

        .container-login {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 1rem;
            max-width: 100%;
            min-height: 100vh;
            background: linear-gradient(135deg, #f0f0f0, #ffffff);
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 1rem;
            max-width: 100%;
            min-height: 100vh;
            background: linear-gradient(135deg, #f0f0f0, #ffffff);
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 16px;
            padding: 1.5rem;
            width: 100%;
            max-width: 400px;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .login-title {
            color: var(--text-dark);
            font-size: 1.4rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 0.75rem;
        }

        .login-subtitle {
            color: var(--text-light);
            font-size: 0.9rem;
            text-align: center;
            margin-bottom: 1.5rem;
            line-height: 1.4;
        }

        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            text-align: center;
        }

        .virtual-keyboard {
            margin-top: 1rem;
            padding: 0.75rem;
            background: #f8f8f8;
            border-radius: 10px;
            border: 1px solid #ddd;
            overflow-x: auto;
        }

        .keyboard-display {
            background: #fff;
            border: 2px solid #E28585;
            border-radius: 6px;
            padding: 10px;
            margin-bottom: 12px;
            text-align: center;
            font-size: 1.1rem;
            font-weight: 600;
            letter-spacing: 4px;
            min-height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Courier New', monospace;
            word-break: break-all;
        }

        .keyboard-row {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 6px;
            margin-bottom: 6px;
        }

        .keyboard-key {
            background: white;
            border: 2px solid #E28585;
            border-radius: 6px;
            padding: 8px 10px;
            font-size: 0.9rem;
            font-weight: 600;
            min-width: 36px;
            text-align: center;
            color: #333;
            cursor: pointer;
            user-select: none;
            transition: all 0.2s ease;
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
            min-width: 56px;
            background: rgba(226, 133, 133, 0.08);
        }

        .keyboard-key.wide:hover {
            background: rgba(226, 133, 133, 0.15);
        }

        .login-buttons {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-top: 1.5rem;
        }

        .login-btn {
            padding: 12px;
            font-size: 1rem;
            font-weight: 600;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s ease;
        }

        .login-btn-primary {
            background: var(--pink-primary);
            color: white;
        }

        .login-btn-primary:hover {
            background: var(--pink-secondary);
            box-shadow: 0 6px 20px rgba(233, 30, 99, 0.25);
        }

        .login-btn-secondary {
            background: #f0f0f0;
            color: #333;
        }

        .login-btn-secondary:hover {
            background: #e0e0e0;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.95rem;
            color: var(--pink-primary);
            font-weight: 600;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 360px) {
            .keyboard-key {
                min-width: 30px;
                font-size: 0.8rem;
                padding: 6px 8px;
            }

            .keyboard-key.wide {
                min-width: 48px;
            }

            .keyboard-display {
                font-size: 1rem;
                letter-spacing: 3px;
            }

            .login-title {
                font-size: 1.2rem;
            }

            .login-subtitle {
                font-size: 0.85rem;
            }
        }
    </style>


</head>

<body>
    <div class="gradientBgCanvas"></div>

    <div class="container-login">
        <div class="login-card">
            <h1 class="login-title">Admin Login</h1>
            <p class="login-subtitle">Masukkan kode akses admin untuk melanjutkan</p>

            <?php if (isset($error)): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST" id="loginForm">
                <!-- Virtual Keyboard -->
                <div class="virtual-keyboard">
                    <div class="keyboard-display" id="codeDisplay">_____</div>

                    <input type="hidden" name="code" id="codeInput" value="">

                    <div class="keyboard-row">
                        <button type="button" class="keyboard-key" onclick="addDigit('1')">1</button>
                        <button type="button" class="keyboard-key" onclick="addDigit('2')">2</button>
                        <button type="button" class="keyboard-key" onclick="addDigit('3')">3</button>
                    </div>

                    <div class="keyboard-row">
                        <button type="button" class="keyboard-key" onclick="addDigit('4')">4</button>
                        <button type="button" class="keyboard-key" onclick="addDigit('5')">5</button>
                        <button type="button" class="keyboard-key" onclick="addDigit('6')">6</button>
                    </div>

                    <div class="keyboard-row">
                        <button type="button" class="keyboard-key" onclick="addDigit('7')">7</button>
                        <button type="button" class="keyboard-key" onclick="addDigit('8')">8</button>
                        <button type="button" class="keyboard-key" onclick="addDigit('9')">9</button>
                    </div>

                    <div class="keyboard-row">
                        <button type="button" class="keyboard-key wide" onclick="clearInput()">Clear</button>
                        <button type="button" class="keyboard-key" onclick="addDigit('0')">0</button>
                        <button type="button" class="keyboard-key wide" onclick="deleteDigit()">⌫</button>
                    </div>
                </div>

                <div class="login-buttons">
                    <button type="button" class="login-btn login-btn-secondary" onclick="clearInput()">Reset</button>
                    <button type="submit" class="login-btn login-btn-primary">Login</button>
                </div>
            </form>

            <!-- <a href="../index.php" class="back-link">← Kembali ke Home</a> -->
        </div>
    </div>

    <script>
        let currentCode = '';

        function addDigit(digit) {
            if (currentCode.length < 5) {
                currentCode += digit;
                updateDisplay();
            }
        }

        function deleteDigit() {
            currentCode = currentCode.slice(0, -1);
            updateDisplay();
        }

        function clearInput() {
            currentCode = '';
            updateDisplay();
        }

        function updateDisplay() {
            const display = document.getElementById('codeDisplay');
            const input = document.getElementById('codeInput');

            // Update display with dots for entered digits and underscores for remaining
            const displayText = currentCode.padEnd(5, '_');
            display.textContent = displayText;

            // Update hidden input
            input.value = currentCode;
        }

        // Handle form submission
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            if (currentCode.length !== 5) {
                e.preventDefault();
                alert('Silakan masukkan 5 digit kode akses!');
            }
        });

        // Prevent context menu on virtual keyboard
        document.querySelectorAll('.keyboard-key').forEach(key => {
            key.addEventListener('contextmenu', function(e) {
                e.preventDefault();
            });
        });
    </script>

    <?php PWAHelper::addPWAScript(); ?>
</body>

</html>