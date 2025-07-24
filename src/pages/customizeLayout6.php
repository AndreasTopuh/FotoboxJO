<?php
session_start();

// Auto create customize session jika belum ada atau expired
if (!isset($_SESSION['customize_expired_time']) || time() > $_SESSION['customize_expired_time']) {
    // Create new customize session
    $_SESSION['customize_start_time'] = time();
    $_SESSION['customize_expired_time'] = time() + (3 * 60); // 3 menit
    $_SESSION['session_type'] = 'customize';
}

// Hitung waktu tersisa
$timeLeft = $_SESSION['customize_expired_time'] - time();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description"
        content="Customize your Layout 6 photobooth photos. Add frames, stickers, and text to your 4-photo strip.">
    <meta name="keywords"
        content="photobooth customization, layout 6 editing, photo frames, photo stickers, photo strip editing">
    <title>Photobooth | Customize Layout 6</title>
    <link rel="canonical" href="https://www.gofotobox.online">
    <meta property="og:title" content="Photobooth | Customize Layout 6">
    <meta property="og:description"
        content="Customize your Layout 6 photobooth photos. Add frames, stickers, and text to your 4-photo strip.">
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
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&family=Syne:wght@400..800&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Mukta+Mahee:wght@200;300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="icon" href="/src/assets/icons/photobooth-new-logo.png" />

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
                                                        <div id="colorPickerBtn" class="buttonFrames"></div>
                                                        <button id="pinkBtnFrame" class="buttonFrames"></button>
                                                        <button id="blueBtnFrame" class="buttonFrames"></button>
                                                        <button id="yellowBtnFrame" class="buttonFrames"></button>
                                                        <button id="matchaBtnFrame" class="buttonFrames"></button>
                                                        <button id="purpleBtnFrame" class="buttonFrames"></button>
                                                        <button id="brownBtnFrame" class="buttonFrames"></button>
                                                        <button id="redBtnFrame" class="buttonFrames"></button>
                                                        <!-- <button id="ribbonBtnFrame" class="buttonFrames"></button> -->
                                                        <button id="whiteBtnFrame" class="buttonFrames"></button>
                                                        <button id="blackBtnFrame" class="buttonFrames"></button>
                                                        <!-- FRAMES -->
                                                        <button id="pinkGlitter" class="buttonBgFrames"></button>
                                                        <button id="pinkPlaid" class="buttonBgFrames"></button>
                                                        <button id="bluePlaid" class="buttonBgFrames"></button>
                                                        <button id="black-cq" class="buttonBgFrames"></button>
                                                        <button id="white-cq" class="buttonBgFrames"></button>
                                                        <button id="pinkLeather" class="buttonBgFrames"></button>
                                                        <button id="brownKnittedFrame" class="buttonBgFrames"></button>
                                                        <button id="hotPinkKnittedFrame" class="buttonBgFrames"></button>
                                                        <button id="redKnittedFrame" class="buttonBgFrames"></button>
                                                        <button id="pinkKnittedFrame" class="buttonBgFrames"></button>
                                                        <button id="redStripesFrame" class="buttonBgFrames"></button>
                                                        <button id="greenStripesFrame" class="buttonBgFrames"></button>
                                                        <button id="blueStripesFrame" class="buttonBgFrames"></button>
                                                        <button id="vsPinkFrame" class="buttonBgFrames"></button>
                                                        <button id="vsYellowFrame" class="buttonBgFrames"></button>
                                                        <button id="blueYellowSquares" class="buttonBgFrames"></button>
                                                        <button id="blueWhiteSquares" class="buttonBgFrames"></button>
                                                        <button id="brownLeopard" class="buttonBgFrames"></button>
                                                        <button id="cowPrint" class="buttonBgFrames"></button>
                                                        <button id="redLeather" class="buttonBgFrames"></button>
                                                        <button id="pinkGumamela" class="buttonBgFrames"></button>
                                                        <button id="pinkLiliesFrame" class="buttonBgFrames"></button>
                                                        <button id="whiteKnitted" class="buttonBgFrames"></button>
                                                        <button id="ribbonSweaterFrame" class="buttonBgFrames"></button>
                                                        <button id="ribbonDenim" class="buttonBgFrames"></button>
                                                        <button id="blackPinkRibbon" class="buttonBgFrames"></button>
                                                        <button id="fourLockers" class="buttonBgFrames"></button>
                                                        <button id="gridPaperFrame" class="buttonBgFrames"></button>
                                                        <button id="crumpledPaper" class="buttonBgFrames"></button>
                                                        <button id="roughTextureFrame" class="buttonBgFrames"></button>
                                                        <button id="blueBackdrop" class="buttonBgFrames"></button>
                                                        <button id="greenHills" class="buttonBgFrames"></button>
                                                        <button id="sandShells" class="buttonBgFrames"></button>
                                                        <button id="waterBeach" class="buttonBgFrames"></button>
                                                        <button id="cocoTrees" class="buttonBgFrames"></button>
                                                        <button id="stardustFrame" class="buttonBgFrames"></button>
                                                        <button id="roseCardFrame" class="buttonBgFrames"></button>
                                                        <button id="princessVintageFrame" class="buttonBgFrames"></button>
                                                        <button id="redRosesPaintFrame" class="buttonBgFrames"></button>
                                                        <button id="grayTrashFrame" class="buttonBgFrames"></button>
                                                        <button id="blackTrashFrame" class="buttonBgFrames"></button>
                                                        <button id="whiteTrashFrame" class="buttonBgFrames"></button>
                                                        <button id="partyDrapeFrame" class="buttonBgFrames"></button>
                                                        <button id="partyDotsFrame" class="buttonBgFrames"></button>
                                                        <button id="blingDenimFrame" class="buttonBgFrames"></button>
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
                                                                <button id="circleFrameShape" class="buttonShapes"><img
                                                                                src="assets/frame-shapes/circleShape.png" alt="Circle Frame"
                                                                                class="btnShapeSize"></button>
                                                                <button id="heartFrameShape" class="buttonShapes"><img
                                                                                src="assets/frame-shapes/heartShape.png" alt="Heart Frame"
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
                                                        <button class="main-button email-button" id="emailBtn" style="background: #28a745;">📧 Kirim ke Email</button>
                                                        <button class="main-button print-button" id="printBtn" style="background: #ffc107; color: #000;">🖨️ Print</button>
                                                        <button class="main-button continue-button" id="continueBtn" style="background: #007bff;">➡️ Lanjutkan</button>
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

    <script src="https://cdn.jsdelivr.net/npm/vanilla-picker@2.12.1/dist/vanilla-picker.min.js"></script>
    <script src="customizeLayout6.js"></script>
</body>

</html>