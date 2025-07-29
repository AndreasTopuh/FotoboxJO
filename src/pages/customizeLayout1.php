<?php
session_start();

// Auto create customize session jika belum ada atau expired (PWA-friendly)
if (!isset($_SESSION['customize_expired_time']) || time() > $_SESSION['customize_expired_time']) {
    // Create new customize session
    $_SESSION['customize_start_time'] = time();
    $_SESSION['customize_expired_time'] = time() + (15 * 60);
    $_SESSION['session_type'] = 'customize';
}

// Hitung waktu tersisa
$timeLeft = $_SESSION['customize_expired_time'] - time();

// Include PWA helper
require_once '../includes/pwa-helper.php';
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    
    <?php PWAHelper::addPWAHeaders(); ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description"
        content="Customize your Layout 1 photobooth photos. Add frames, stickers, and text to your 2-photo strip.">
    <meta name="keywords"
        content="photobooth customization, layout 1 editing, photo frames, photo stickers, photo strip editing">
    <title>Photobooth | Customize Layout 1</title>
    <link rel="canonical" href="https://www.gofotobox.online">
    <meta property="og:title" content="Photobooth | Customize Layout 1">
    <meta property="og:description"
        content="Customize your Layout 1 photobooth photos. Add frames, stickers, and text to your 2-photo strip.">
    <meta property="og:image" content="https://www.gofotobox.online/assets/home-mockup.png">
    <meta property="og:url" content="https://www.gofotobox.online">
    <meta property="og:type" content="website">
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Photobooth | Customize 4R Photo">
    <meta name="twitter:description"
        content="Customize your 4R photobooth photos. Add frames, stickers, and text.">
    <meta name="twitter:image" content="https://www.gofotobox.online/assets/home-mockup.png">
    <link rel="stylesheet" href="/styles.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&family=Syne:wght@400..800&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Mukta+Mahee:wght@200;300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="icon" href="/src/assets/icons/photobooth-new-logo.png" />

    <style>
        /* Enhanced button styles for all main buttons */
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

        /* Button loading state */
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
    <!-- Timer Box -->
    <div id="timer-box" class="timer-box">
        <span id="timer-display">03:00</span>
        <p>Sisa waktu untuk customize</p>
    </div>

    <!-- Timeout Modal -->
    <div id="timeout-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <h2>Waktu Habis!</h2>
            <p>Sesi customize Anda telah berakhir. Anda akan diarahkan ke halaman utama.</p>
            <button id="timeout-ok-btn" class="modal-btn">OK</button>
        </div>
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
                            <button id="bunnySticker" class="buttonStickers">
                                <img src="/src/assets/stickers/bunny1.png" alt="bunny" class="stickerIconSize" />
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
    <div id="emailModal" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <button id="closeEmailModal" class="close-btn">&times;</button>
            <h3>Masukan Email Anda</h3>
            <input type="email" id="emailInput" placeholder="contoh@email.com" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 5px; margin: 1rem 0;">
            <div class="modal-buttons">
                <button id="sendEmailBtn" class="btn-primary">Kirim</button>
            </div>
        </div>
    </div>

    <script src="customizeLayout1.js"></script>
    
    <?php PWAHelper::addPWAScript(); ?>
</body>

</html>