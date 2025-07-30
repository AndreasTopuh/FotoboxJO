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
    <link rel="stylesheet" href="/styles.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
        }
        .modal-content {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            text-align: center;
            max-width: 400px;
            margin: 0 1rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
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

    <!-- Email Modal -->
    <div id="emailModal" class="modal" style="display: none;">
        <div class="modal-content">
            <button id="closeEmailModal" class="modal-btn" style="position: absolute; top: 10px; right: 15px; background: transparent; color: #666; font-size: 24px; padding: 0; width: 30px; height: 30px; border-radius: 50%;">&times;</button>
            <h3>Masukan Email Anda</h3>
            <input type="email" id="emailInput" placeholder="contoh@email.com" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 5px; margin: 1rem 0; box-sizing: border-box;">
            <div style="margin-top: 1rem;">
                <button id="sendEmailBtn" class="modal-btn">Kirim</button>
            </div>
        </div>
    </div>

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