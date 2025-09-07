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
            justify-content: center;
            padding: 2rem;
            max-width: 100%;
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            max-width: 100%;
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .login-card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 24px;
            padding: 3rem;
            width: 100%;
            max-width: 500px;
            backdrop-filter: blur(20px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.4);
            transform: translateY(-10px);
            transition: all 0.3s ease;
            position: relative;
            z-index: 2;
        }

        .login-card:hover {
            transform: translateY(-15px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
        }

        .login-title {
            color: var(--text-dark);
            font-size: 2.2rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 1rem;
            background: linear-gradient(45deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .login-subtitle {
            color: var(--text-light);
            font-size: 1.1rem;
            text-align: center;
            margin-bottom: 2rem;
            line-height: 1.5;
            font-weight: 400;
        }

        .error-message {
            background: linear-gradient(135deg, #ff6b6b, #ee5a52);
            color: white;
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            font-size: 1rem;
            text-align: center;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3);
            border: none;
        }

        .virtual-keyboard {
            margin-top: 2rem;
            padding: 2rem;
            background: linear-gradient(135deg, #f8f9ff, #e3f2fd);
            border-radius: 20px;
            border: 2px solid rgba(102, 126, 234, 0.2);
            overflow-x: auto;
            box-shadow: inset 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .keyboard-display {
            background: linear-gradient(135deg, #fff, #f8f9ff);
            border: 3px solid #667eea;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: 10px;
            min-height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Courier New', monospace;
            word-break: break-all;
            color: #667eea;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.15);
        }

        .keyboard-row {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 15px;
        }

        .keyboard-key {
            background: linear-gradient(135deg, #fff, #f0f4ff);
            border: 2px solid #667eea;
            border-radius: 12px;
            padding: 18px 22px;
            font-size: 1.3rem;
            font-weight: 700;
            min-width: 65px;
            min-height: 65px;
            text-align: center;
            color: #667eea;
            cursor: pointer;
            user-select: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .keyboard-key:hover {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .keyboard-key:active {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
        }

        .keyboard-key.wide {
            min-width: 110px;
            background: linear-gradient(135deg, #764ba2, #667eea);
            color: white;
            font-size: 1.1rem;
            font-weight: 600;
        }

        .keyboard-key.wide:hover {
            background: linear-gradient(135deg, #5a4a9f, #5666d9);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(118, 75, 162, 0.4);
        }

        .login-buttons {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-top: 2rem;
        }

        .login-btn {
            padding: 18px 24px;
            font-size: 1.2rem;
            font-weight: 700;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            overflow: hidden;
        }

        .login-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .login-btn:hover::before {
            left: 100%;
        }

        .login-btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);
        }

        .login-btn-primary:hover {
            background: linear-gradient(135deg, #5666d9, #6a4c93);
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        }

        .login-btn-secondary {
            background: linear-gradient(135deg, #f0f0f0, #e0e0e0);
            color: #333;
            border: 2px solid #667eea;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .login-btn-secondary:hover {
            background: linear-gradient(135deg, #e0e0e0, #d0d0d0);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        /* Floating shapes animation */
        .floating-shapes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }

        .shape {
            position: absolute;
            background: linear-gradient(45deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .shape-1 {
            width: 80px;
            height: 80px;
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .shape-2 {
            width: 60px;
            height: 60px;
            top: 20%;
            right: 15%;
            animation-delay: 2s;
        }

        .shape-3 {
            width: 100px;
            height: 100px;
            bottom: 15%;
            left: 20%;
            animation-delay: 4s;
        }

        .shape-4 {
            width: 40px;
            height: 40px;
            bottom: 25%;
            right: 25%;
            animation-delay: 1s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(180deg);
            }
        }

        .login-header {
            text-align: center;
            margin-bottom: 1rem;
        }

        .admin-icon {
            margin-bottom: 1.5rem;
            display: inline-block;
            padding: 25px;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.15), rgba(118, 75, 162, 0.15));
            border-radius: 50%;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
            transition: all 0.3s ease;
        }

        .admin-icon:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.4);
        }

        @media (max-width: 480px) {
            .container-login {
                padding: 1rem;
            }
            
            .login-card {
                margin: 0.5rem;
                padding: 2rem 1.5rem;
                max-width: calc(100% - 1rem);
            }

            .login-title {
                font-size: 1.8rem;
            }

            .login-subtitle {
                font-size: 1rem;
            }

            .virtual-keyboard {
                padding: 1.5rem;
                margin-top: 1.5rem;
            }

            .keyboard-key {
                min-width: 55px;
                min-height: 55px;
                font-size: 1.2rem;
                padding: 14px 18px;
            }

            .keyboard-key.wide {
                min-width: 90px;
                font-size: 1rem;
            }

            .keyboard-display {
                font-size: 1.6rem;
                letter-spacing: 8px;
                padding: 18px;
                min-height: 60px;
            }

            .login-btn {
                padding: 16px 20px;
                font-size: 1.1rem;
            }

            .keyboard-row {
                gap: 10px;
                margin-bottom: 10px;
            }
        }

        @media (max-width: 360px) {
            .container-login {
                padding: 0.5rem;
            }
            
            .login-card {
                margin: 0.25rem;
                padding: 1.5rem 1rem;
                max-width: calc(100% - 0.5rem);
            }

            .virtual-keyboard {
                padding: 1rem;
                margin-top: 1rem;
            }

            .keyboard-key {
                min-width: 50px;
                min-height: 50px;
                font-size: 1.1rem;
                padding: 12px 16px;
            }

            .keyboard-key.wide {
                min-width: 80px;
                font-size: 0.95rem;
            }

            .keyboard-display {
                font-size: 1.4rem;
                letter-spacing: 6px;
                padding: 15px;
                min-height: 55px;
            }

            .login-title {
                font-size: 1.6rem;
            }

            .login-subtitle {
                font-size: 0.95rem;
            }

            .login-btn {
                padding: 14px 18px;
                font-size: 1rem;
            }

            .keyboard-row {
                gap: 8px;
                margin-bottom: 8px;
            }

            .admin-icon {
                padding: 18px;
                margin-bottom: 1rem;
            }

            .admin-icon svg {
                width: 50px;
                height: 50px;
            }
        }
    </style> 

</head>

<body>
    <div class="gradientBgCanvas"></div>

    <div class="container-login">
        <!-- Decorative elements -->
        <div class="floating-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
            <div class="shape shape-4"></div>
        </div>

        <div class="login-card">
            <div class="login-header">
                <div class="admin-icon">
                    <svg width="60" height="60" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 12C14.7614 12 17 9.76142 17 7C17 4.23858 14.7614 2 12 2C9.23858 2 7 4.23858 7 7C7 9.76142 9.23858 12 12 12Z" fill="url(#gradient1)" />
                        <path d="M12 14C7.58172 14 4 17.5817 4 22H20C20 17.5817 16.4183 14 12 14Z" fill="url(#gradient1)" />
                        <defs>
                            <linearGradient id="gradient1" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" style="stop-color:#667eea;stop-opacity:1" />
                                <stop offset="100%" style="stop-color:#764ba2;stop-opacity:1" />
                            </linearGradient>
                        </defs>
                    </svg>
                </div>
                <h1 class="login-title">Admin Login</h1>
                <p class="login-subtitle">Masukkan kode akses admin untuk melanjutkan</p>
            </div>

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