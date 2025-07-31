<?php
session_start();
// Initialize session if not set or expired
if (!isset($_SESSION['customize_expired_time']) || time() > $_SESSION['customize_expired_time']) {
    $_SESSION['customize_start_time'] = time();
    $_SESSION['customize_expired_time'] = time() + (15 * 60);
    $_SESSION['session_type'] = 'customize';
}
require_once '../includes/pwa-helper.php';
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <?php PWAHelper::addPWAHeaders(); ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Customize your Layout 1 photobooth photos with frames, stickers, and text.">
    <title>Photobooth | Customize Layout 1</title>
    <link rel="stylesheet" href="home-styles.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Syne:wght@400;700&display=swap" rel="stylesheet">
    <link rel="icon" href="/src/assets/icons/photobooth-new-logo.png" />
    <style>
        /* Timer Box Styling */
        .timer-box {
            position: fixed;
            top: 20px;
            right: 20px;
            background: rgba(255, 68, 68, 0.9);
            color: white;
            padding: 15px 20px;
            border-radius: 12px;
            font-weight: bold;
            z-index: 1000;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
        }
        .timer-box #timer-display {
            font-size: 1.5rem;
            font-weight: 700;
            display: block;
            margin-bottom: 5px;
        }
        .timer-box p {
            margin: 0;
            font-size: 0.9rem;
            opacity: 0.9;
        }
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 2000;
            backdrop-filter: blur(5px);
        }
        
        .modal-content {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            text-align: center;
            max-width: 500px;
            margin: 0 1rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.3);
            position: relative;
            max-height: 90vh;
            overflow-y: auto;
        }
        
        .email-modal-content {
            max-width: 650px;
            padding: 2.5rem;
        }
        
        .close-btn {
            position: absolute;
            top: 20px;
            right: 25px;
            background: none;
            border: none;
            font-size: 30px;
            cursor: pointer;
            color: #999;
            font-weight: bold;
            transition: all 0.3s ease;
            z-index: 10;
        }
        
        .close-btn:hover {
            color: #333;
            transform: scale(1.1);
        }
        
        .modal-header {
            margin-bottom: 2rem;
        }
        
        .modal-title {
            color: #333;
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .modal-subtitle {
            color: #666;
            font-size: 1rem;
            margin: 0;
            line-height: 1.5;
        }
        
        .email-input-container {
            margin-bottom: 2rem;
        }
        
        .email-input {
            width: 100%;
            padding: 1rem 1.5rem;
            border: 2px solid rgba(222, 226, 230, 0.5);
            border-radius: 12px;
            font-size: 1.1rem;
            font-family: 'Poppins', sans-serif;
            background: rgba(248, 249, 250, 0.8);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            box-sizing: border-box;
            caret-color: #007bff;
            text-align: left;
        }
        
        .email-input:focus {
            outline: none;
            border-color: #007bff;
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
        }
        
        .input-validation {
            margin-top: 0.5rem;
            text-align: left;
        }
        
        .input-validation span {
            color: #dc3545;
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        .input-validation span.success {
            color: #28a745;
        }
        
        /* Virtual Keyboard Styles */
        .virtual-keyboard {
            background: rgba(248, 249, 250, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 1.5rem;
            margin: 2rem 0;
            border: 2px solid rgba(233, 236, 239, 0.5);
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
        }
        
        .keyboard-row {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }
        
        .keyboard-row:last-child {
            margin-bottom: 0;
        }
        
        .key-btn {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border: 2px solid rgba(222, 226, 230, 0.8);
            border-radius: 8px;
            padding: 0.75rem;
            min-width: 45px;
            min-height: 45px;
            font-size: 1rem;
            font-weight: 600;
            color: #495057;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: 'Poppins', sans-serif;
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            outline: none;
        }
        
        .key-btn:hover {
            background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
            border-color: #007bff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.2);
        }
        
        .key-btn:active {
            transform: translateY(0);
            box-shadow: 0 2px 6px rgba(0, 123, 255, 0.3);
        }
        
        .key-space {
            flex: 1;
            max-width: 200px;
        }
        
        .key-backspace, .key-caps {
            min-width: 60px;
            font-size: 0.9rem;
        }
        
        .key-caps.active {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            border-color: #0056b3;
        }
        
        .modal-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            justify-content: center;
        }
        
        .btn-primary, .btn-secondary {
            padding: 1rem 2.5rem;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-family: 'Poppins', sans-serif;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #0056b3 0%, #004085 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 123, 255, 0.4);
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, #6c757d 0%, #545b62 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
        }
        
        .btn-secondary:hover {
            background: linear-gradient(135deg, #545b62 0%, #495057 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(108, 117, 125, 0.4);
        }
        
        .modal-content h2 {
            color: #ff4444;
            margin-bottom: 1rem;
        }
        .modal-btn {
            background: #ff4444;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 1rem;
            margin-top: 1rem;
            transition: background 0.3s ease;
        }
        .modal-btn:hover {
            background: #e03e3e;
        }
        .main-button {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 12px;
            font-weight: 600;
            text-transform: none;
            letter-spacing: 0.5px;
            padding: 14px 24px;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 52px;
        }
        .main-button:hover {
            transform: translateY(-2px) scale(1.02);
            filter: brightness(1.1);
        }
        .main-button:active {
            transform: translateY(0) scale(0.98);
            transition: all 0.1s;
        }
        .email-button:hover {
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4) !important;
        }
        .continue-button:hover {
            box-shadow: 0 8px 25px rgba(0, 123, 255, 0.4) !important;
        }
        .main-button.loading {
            pointer-events: none;
            opacity: 0.7;
        }
        .main-button.loading::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            border: 2px solid transparent;
            border-top: 2px solid currentColor;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-left: 8px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
       @media print {
        body {
            margin: 0 !important;
            padding: 0 !important;
        }
        img, .print-img {
            width: 100vw !important;
            height: 100vh !important;
            max-width: 100vw !important;
            max-height: 100vh !important;
            object-fit: contain !important;
            page-break-inside: avoid !important;
            break-inside: avoid !important;
            display: block;
            margin: 0 auto !important;
        }
        @page {
            size: 4in 6in;
            margin: 0;
        }
    }
    
    /* Mobile Responsive untuk Virtual Keyboard */
    @media (max-width: 768px) {
        .email-modal-content {
            max-width: 95%;
            padding: 2rem;
            max-height: 95vh;
        }
        
        .modal-title {
            font-size: 1.5rem;
        }
        
        .modal-subtitle {
            font-size: 0.9rem;
        }
        
        .virtual-keyboard {
            padding: 1rem;
            margin: 1.5rem 0;
        }
        
        .keyboard-row {
            gap: 0.3rem;
            margin-bottom: 0.3rem;
        }
        
        .key-btn {
            min-width: 35px;
            min-height: 35px;
            padding: 0.5rem;
            font-size: 0.9rem;
        }
        
        .key-backspace, .key-caps {
            min-width: 50px;
            font-size: 0.8rem;
        }
        
        .modal-actions {
            flex-direction: column;
            gap: 0.75rem;
        }
        
        .btn-primary, .btn-secondary {
            width: 100%;
            padding: 1rem;
            font-size: 1rem;
        }
    }
    
    @media (max-width: 480px) {
        .email-modal-content {
            padding: 1.5rem;
        }
        
        .key-btn {
            min-width: 30px;
            min-height: 30px;
            padding: 0.4rem;
            font-size: 0.8rem;
        }
        
        .keyboard-row {
            gap: 0.2rem;
        }
        
        .virtual-keyboard {
            padding: 0.75rem;
        }
    }
    </style>
</head>

<body>
    <div id="session-timer-container" class="timer-box">
        <span id="session-timer-display">20:00</span>
        <p>Sisa waktu sesi</p>
    </div>
    <main id="main-section">
        <div class="gradientBgCanvas"></div>
        <section class="custom-main">
            <div id="photoPreview"></div>
            <div class="customization-container">
                <h1 class="custom-heading">customize your photo</h1>
                <div>
                    <div class="custom-options-container">
                        <h3 class="options-label">Frame Color</h3>
                        <div class="custom-buttons-container">
                            <button id="pinkBtnFrame" class="buttonFrames"></button>
                            <button id="blueBtnFrame" class="buttonFrames"></button>
                            <button id="yellowBtnFrame" class="buttonFrames"></button>
                            <button id="matchaBtnFrame" class="buttonFrames"></button>
                            <button id="purpleBtnFrame" class="buttonFrames"></button>
                            <button id="brownBtnFrame" class="buttonFrames"></button>
                            <button id="redBtnFrame" class="buttonFrames"></button>
                            <button id="whiteBtnFrame" class="buttonFrames"></button>
                            <button id="blackBtnFrame" class="buttonFrames"></button>
                            <!-- FRAMES -->
                            <button id="matcha" class="buttonBgFrames"></button>
                            <button id="blackStar" class="buttonBgFrames"></button>
                            <button id="blueStripe" class="buttonBgFrames"></button>
                        </div>
                        <div class="">
                            <h3 class="options-label">Photo Shape:</h3>
                            <div class="custom-buttons-container">
                                <button id="noneFrameShape" class="buttonShapes"><img
                                    src="assets/frame-shapes/noneShape.png" alt="None"
                                    class="btnShapeSize"></button>
                                <button id="softFrameShape" class="buttonShapes"><img
                                    src="assets/frame-shapes/squareShape.png" alt="Soft Edge Frame"
                                    class="btnShapeSize"></button>
                                 </div>
                        </div>
                        <h3 class="options-label">Stickers</h3>
                        <div class="custom-buttons-container stickers-container">
                            <button id="noneSticker" class="buttonStickers">
                                <span style="font-size: 12px;">None</span>
                            </button>
                            <!-- <button id="bunnySticker" class="buttonStickers">
                                <img src="/src/assets/stickers/bunny1.png" alt="bunny" class="stickerIconSize" />
                            </button> -->
                            <button id="bintang1" class="buttonStickers">
                                <img src="/src/assets/stickers/bintang1.png" alt="bintang1" class="stickerIconSize" />
                            </button>
                            <!-- Add more stickers here as needed, using the correct path -->
                        </div>
                        <div class="custom-logo-holder">
                            <h3 class="options-label">Logo:</h3>
                            <div class="logo-container">
                                <button id="engLogo" class="logoCustomBtn">ENG</button>
                                <button id="korLogo" class="logoCustomBtn">KOR</button>
                                <button id="cnLogo" class="logoCustomBtn">CN</button>
                            </div>
                        </div>
                        <div class="date-overlay">
                            <input type="checkbox" id="dateCheckbox">
                            <label for="dateCheckbox" id="addDateLabel">Add Date</label>
                            <input type="checkbox" id="dateTimeCheckbox">
                            <label for="dateTimeCheckbox" id="addDateTimeLabel">Add Time</label>
                        </div>
                        <div class="custom-buttons-holder">
                            <button class="main-button email-button" id="emailBtn" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; border: none; box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);">
                                <i class="fas fa-envelope"></i> 📧 Kirim ke Email
                            </button>
                            <button class="main-button print-button" id="printBtn" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3); position: relative; overflow: hidden;">
                                <i class="fas fa-print"></i> Print
                            </button>
                            <button class="main-button continue-button" id="continueBtn" style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); color: white; border: none; box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);">
                                <i class="fas fa-arrow-right"></i> ➡️ Lanjutkan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Email Modal dengan Virtual Keyboard -->
    <div id="emailModal" class="modal" style="display: none;">
        <div class="modal-content email-modal-content">
            <button id="closeEmailModal" class="close-btn">&times;</button>
            <div class="modal-header">
                <h3 class="modal-title">Masukan Email Anda</h3>
                <p class="modal-subtitle">Foto akan dikirim ke alamat email yang Anda masukkan</p>
            </div>
            <div class="email-input-container">
                <input type="email" id="emailInput" placeholder="contoh@email.com" class="email-input">
                <div class="input-validation" style="display: none;">
                    <span id="validation-message">Format email tidak valid</span>
                </div>
            </div>
            
            <!-- Virtual Keyboard -->
            <div id="virtualKeyboard" class="virtual-keyboard">
                <div class="keyboard-row">
                    <button class="key-btn" data-key="1">1</button>
                    <button class="key-btn" data-key="2">2</button>
                    <button class="key-btn" data-key="3">3</button>
                    <button class="key-btn" data-key="4">4</button>
                    <button class="key-btn" data-key="5">5</button>
                    <button class="key-btn" data-key="6">6</button>
                    <button class="key-btn" data-key="7">7</button>
                    <button class="key-btn" data-key="8">8</button>
                    <button class="key-btn" data-key="9">9</button>
                    <button class="key-btn" data-key="0">0</button>
                </div>
                <div class="keyboard-row">
                    <button class="key-btn" data-key="q">Q</button>
                    <button class="key-btn" data-key="w">W</button>
                    <button class="key-btn" data-key="e">E</button>
                    <button class="key-btn" data-key="r">R</button>
                    <button class="key-btn" data-key="t">T</button>
                    <button class="key-btn" data-key="y">Y</button>
                    <button class="key-btn" data-key="u">U</button>
                    <button class="key-btn" data-key="i">I</button>
                    <button class="key-btn" data-key="o">O</button>
                    <button class="key-btn" data-key="p">P</button>
                </div>
                <div class="keyboard-row">
                    <button class="key-btn" data-key="a">A</button>
                    <button class="key-btn" data-key="s">S</button>
                    <button class="key-btn" data-key="d">D</button>
                    <button class="key-btn" data-key="f">F</button>
                    <button class="key-btn" data-key="g">G</button>
                    <button class="key-btn" data-key="h">H</button>
                    <button class="key-btn" data-key="j">J</button>
                    <button class="key-btn" data-key="k">K</button>
                    <button class="key-btn" data-key="l">L</button>
                    <button class="key-btn" data-key="@">@</button>
                </div>
                <div class="keyboard-row">
                    <button class="key-btn key-caps" data-key="caps">CAPS</button>
                    <button class="key-btn" data-key="z">Z</button>
                    <button class="key-btn" data-key="x">X</button>
                    <button class="key-btn" data-key="c">C</button>
                    <button class="key-btn" data-key="v">V</button>
                    <button class="key-btn" data-key="b">B</button>
                    <button class="key-btn" data-key="n">N</button>
                    <button class="key-btn" data-key="m">M</button>
                    <button class="key-btn key-backspace" data-key="backspace">⌫</button>
                </div>
                <div class="keyboard-row">
                    <button class="key-btn key-space" data-key="space">SPACE</button>
                    <button class="key-btn" data-key=".">.</button>
                    <button class="key-btn" data-key="-">-</button>
                    <button class="key-btn" data-key="_">_</button>
                </div>
            </div>
            
            <div class="modal-actions">
                <button id="cancelEmailBtn" class="btn-secondary">Batal</button>
                <button id="sendEmailBtn" class="btn-primary">
                    <i class="fas fa-paper-plane"></i> Kirim
                </button>
            </div>
        </div>
    </div>

    <!-- EmailJS Scripts -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js"></script>
    <script type="text/javascript">
        (function(){
            emailjs.init({
                publicKey: "9SDzOfKjxuULQ5ZW8",
            });
        })();
    </script>

    <script src="customizeLayout1.js"></script>
    
    <!-- Session Timer Script -->
    <script src="../includes/session-timer.js"></script>
    
    <script>
        // Custom timer expired handler for customize page
        document.addEventListener('DOMContentLoaded', function() {
            if (window.sessionTimer) {
                window.sessionTimer.onExpired = function(page) {
                    // From customize page, go directly to thank you
                    window.location.href = 'thankyou.php';
                };
            }
        });
    </script>
    
    <?php PWAHelper::addPWAScript(); ?>
</body>

</html>