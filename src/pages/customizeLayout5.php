<?php
session_start();
// Comment out payment check untuk testing
/*
if (!isset($_SESSION['has_paid']) || $_SESSION['has_paid'] !== true) {
    header("Location: /FotoboxJO/index.php"); // tendang balik ke landing
    exit();
}
*/
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
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
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&family=Syne:wght@400..800&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Mukta+Mahee:wght@200;300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="icon" href="/src/assets/icons/photobooth-new-logo.png" />

</head>

<body>
    <main id="main-section">
        <section id="choose-layout">
            <h1 class="custom-heading layout-heading">Customize Your 4R Photo</h1>
            <p class="layout-subtext">4R size: 10.2cm x 15.2cm (4" x 6") - Perfect for printing!</p>

            <div id="layout-settings">

                <nav id="new-navbar">
                    <a href="selectlayout.php" class="canvas-logo">
                        <h2 class="side-logo">gofotobox</h2>
                    </a>

                    <div id="nav-links">
                        <ul>
                            <li><a href="selectlayout.php">Layouts</a></li>
                            <li><a href="canvas4R.php">4R Photo</a></li>
                            <li><a href="customize4R.php" class="current-nav">Customize</a></li>
                        </ul>
                    </div>

                    <div>
                        <button id="darkModeToggle">
                            <img class="toggleImage" src="../assets/sun-icon.png" alt="Toggle Dark Mode" />
                        </button>
                    </div>
                </nav>

                <div class="custom-main">
                    
                    <div class="customization-container">
                        
                        <!-- Frame Colors -->
                        <div class="options-label">Frame Colors</div>
                        <div class="custom-buttons-container">
                            <button id="pinkBtnFrame" class="buttonFrames"></button>
                            <button id="blueBtnFrame" class="buttonFrames"></button>
                            <button id="yellowBtnFrame" class="buttonFrames"></button>
                            <button id="brownBtnFrame" class="buttonFrames"></button>
                            <button id="redBtnFrame" class="buttonFrames"></button>
                            <button id="matchaBtnFrame" class="buttonFrames"></button>
                            <button id="purpleBtnFrame" class="buttonFrames"></button>
                            <button id="whiteBtnFrame" class="buttonFrames"></button>
                            <button id="blackBtnFrame" class="buttonFrames"></button>
                        </div>

                        <!-- Frame Shapes -->
                        <div class="options-label">Frame Shapes</div>
                        <div class="custom-buttons-container">
                            <button id="noneFrameShape" class="buttonShapes">
                                <img class="btnShapeSize" src="../assets/frame-shapes/none-frame.png" alt="Normal" />
                            </button>
                            <button id="softFrameShape" class="buttonShapes">
                                <img class="btnShapeSize" src="../assets/frame-shapes/soft-frame.png" alt="Rounded" />
                            </button>
                            <button id="circleFrameShape" class="buttonShapes">
                                <img class="btnShapeSize" src="../assets/frame-shapes/circle-frame.png" alt="Circle" />
                            </button>
                            <button id="heartFrameShape" class="buttonShapes">
                                <img class="btnShapeSize" src="../assets/frame-shapes/heart-frame.png" alt="Heart" />
                            </button>
                        </div>

                        <!-- Stickers -->
                        <div class="options-label">Stickers</div>
                        <div class="custom-buttons-container">
                            <button id="noneSticker" class="buttonStickers clearSticker">
                                <img class="stickerIconSize" src="../assets/stickers/none-sticker.png" alt="None" />
                            </button>
                            <button id="kissSticker" class="buttonStickers">
                                <img class="stickerIconSize" src="../assets/stickers/kiss-sticker.png" alt="Kiss" />
                            </button>
                            <button id="sweetSticker" class="buttonStickers">
                                <img class="stickerIconSize" src="../assets/stickers/sweet-sticker.png" alt="Sweet" />
                            </button>
                            <button id="ribbonSticker" class="buttonStickers">
                                <img class="stickerIconSize" src="../assets/stickers/ribbon-sticker.png" alt="Ribbon" />
                            </button>
                            <button id="sparkleSticker" class="buttonStickers">
                                <img class="stickerIconSize" src="../assets/stickers/sparkle-sticker.png" alt="Sparkle" />
                            </button>
                            <button id="pearlSticker" class="buttonStickers">
                                <img class="stickerIconSize" src="../assets/stickers/pearl-sticker.png" alt="Pearl" />
                            </button>
                            <button id="softSticker" class="buttonStickers">
                                <img class="stickerIconSize" src="../assets/stickers/soft-sticker.png" alt="Soft" />
                            </button>
                            <button id="bunnySticker" class="buttonStickers">
                                <img class="stickerIconSize" src="../assets/stickers/bunny-sticker.png" alt="Bunny" />
                            </button>
                            <button id="classicSticker" class="buttonStickers classicStickers">
                                <img class="stickerIconSize" src="../assets/stickers/classic-sticker.png" alt="Classic" />
                            </button>
                            <button id="luckySticker" class="buttonStickers">
                                <img class="stickerIconSize" src="../assets/stickers/lucky-sticker.png" alt="Lucky" />
                            </button>
                        </div>

                        <!-- Logo Options -->
                        <div class="options-label">Logo</div>
                        <div class="custom-buttons-container">
                            <button id="engLogo" class="logoCustomBtn">
                                <img class="stickerIconSize" src="../assets/icons/photobooth-new-logo.png" alt="English" />
                            </button>
                            <button id="korLogo" class="logoCustomBtn">
                                <img class="stickerIconSize" src="../assets/icons/photobooth-new-logo.png" alt="Korean" />
                            </button>
                            <button id="cnLogo" class="logoCustomBtn">
                                <img class="stickerIconSize" src="../assets/icons/photobooth-new-logo.png" alt="Chinese" />
                            </button>
                        </div>

                        <div class="custom-buttons-holder">
                            <button id="customBack" class="sub-button customBtn retake-button-design">
                                Back
                            </button>
                            <button id="downloadCopyBtn" class="main-button customBtn download-button-design">
                                Download 4R
                            </button>
                        </div>

                    </div>

                    <div id="photoPreview" class="canvas-centered">
                        <!-- Preview will be rendered here -->
                    </div>
                    
                </div>

            </div>

        </section>

    </main>

    <script src="customizeLayout5.js"></script>
</body>

</html>
