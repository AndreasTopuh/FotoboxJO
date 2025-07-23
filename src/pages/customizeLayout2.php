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
        <link rel="stylesheet" href="/styles.css?v=<?php echo time(); ?>" />
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&family=Syne:wght@400..800&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Mukta+Mahee:wght@200;300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="icon" href="/src/assets/icons/photobooth-new-logo.png" />

</head>

<body>
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
                                                        <button id="noneSticker" class="buttonStickers">
                                                                <!-- <img src="assets/clear.png" class="clearSticker" alt="clear"> -->
                                                                <img src="assets/frame-shapes/noneShape.png" alt="None" class="btnShapeSize">
                                                        </button>
                                                        <button id="bunnySticker" class="buttonStickers"><img src="assets/stickers/bunny1.png"
                                                                        alt="kiss" class="stickerIconSize" /></button>
                                                        <button id="luckySticker" class="buttonStickers new-sticker"><img
                                                                        src="assets/stickers/lucky1.png" alt="kiss" class="stickerIconSize" /></button>
                                                        <button id="kissSticker" class="buttonStickers"><img src="assets/stickers/kiss1.png"
                                                                        alt="kiss" class="stickerIconSize" /></button>
                                                        <button id="sweetSticker" class="buttonStickers"><img src="assets/stickers/sweet1.png"
                                                                        alt="sweet" class="stickerIconSize" /></button>
                                                        <button id="ribbonSticker" class="buttonStickers"><img src="assets/stickers/ribbon1.png"
                                                                        alt="ribbon" class="stickerIconSize" /></button>
                                                        <button id="sparkleSticker" class="buttonStickers"><img src="assets/stickers/sparkle2.png"
                                                                        alt="sparkle" class="stickerIconSize" /></button>
                                                        <button id="pearlSticker" class="buttonStickers"><img src="assets/stickers/pearl2.png"
                                                                        alt="kiss" class="stickerIconSize" /></button>
                                                        <button id="softSticker" class="buttonStickers"><img src="assets/stickers/soft5.png"
                                                                        alt="kiss" class="stickerIconSize" /></button>


                                                        <button id="confettiSticker" class="buttonStickers"><img
                                                                        src="assets/stickers/confetti/confetti.png" alt="confetti"
                                                                        class="stickerIconSize" /></button>
                                                        <button id="ribbonCoquetteSticker" class="buttonStickers"><img
                                                                        src="assets/stickers/ribboncq4.png" alt="ribbon coquette"
                                                                        class="stickerIconSize" /></button>
                                                        <button id="blueRibbonCoquetteSticker" class="buttonStickers"><img
                                                                        src="assets/stickers/blueRibbon2.png" alt="blue ribbon coquette"
                                                                        class="stickerIconSize" /></button>
                                                        <button id="blackStarSticker" class="buttonStickers"><img
                                                                        src="assets/stickers/blackStar5.png" alt="black stars"
                                                                        class="stickerIconSize" /></button>
                                                        <button id="yellowChickenSticker" class="buttonStickers"><img
                                                                        src="assets/stickers/yellowChicken1.png" alt="yellow baby chick"
                                                                        class="stickerIconSize" /></button>
                                                        <button id="brownBearSticker" class="buttonStickers"><img
                                                                        src="assets/stickers/brownyBear6.png" alt="brown bear"
                                                                        class="stickerIconSize" /></button>
                                                        <button id="lotsHeartSticker" class="buttonStickers"><img
                                                                        src="assets/stickers/lotsHeart8.png" alt="3d heart"
                                                                        class="stickerIconSize" /></button>
                                                        <button id="tabbyCatSticker" class="buttonStickers"><img src="assets/stickers/tabbyCat6.png"
                                                                        alt="yawning tabby cat" class="stickerIconSize" /></button>
                                                        <button id="ballerinaCappuccinoSticker" class="buttonStickers"><img
                                                                        src="assets/stickers/ballerinaCappuccino/balerinaCappuccino3.png"
                                                                        alt="cute white cat" class="stickerIconSize" /></button>
                                                        <button id="doggyWhiteSticker" class="buttonStickers"><img
                                                                        src="assets/stickers/doggyWhite/doggyWhite1.png" alt="cute white dog"
                                                                        class="stickerIconSize" /></button>
                                                        <button id="sakuraBlossomSticker" class="buttonStickers"><img
                                                                        src="assets/stickers/sakuraBlossom/sakuraBlossom6.png" alt="sakura"
                                                                        class="stickerIconSize" /></button>
                                                        <button id="myGirlsSticker" class="buttonStickers"><img
                                                                        src="assets/stickers/myGirls/myGirls12.png" alt="three girls"
                                                                        class="stickerIconSize" /></button>
                                                        <button id="classicSticker" class="buttonStickers classicStickers"><img
                                                                        src="assets/stickers/classic1.png" alt="classic black"
                                                                        class="stickerIconSize" /></button>
                                                        <button id="classicBSticker" class="buttonStickers classicStickers"><img
                                                                        src="assets/stickers/classic4.png" alt="classic white"
                                                                        class="stickerIconSize" /></button>
                                                        <!-- <button id="WISSticker" class="buttonStickers">Women In Stem</button> -->
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
                                                        <button class="sub-button customBtn retake-button-design" id="customBack">Retake</button>
                                                        <!-- <button class="main-button customBtn" id="customNext">NEXT</button> -->
                                                        <button class="main-button download-button-design" id="downloadCopyBtn">Download</button>
                                                </div>

                                        </div>
                                </div>
                        </div>

                </section>

    </main>>

    <script src="https://cdn.jsdelivr.net/npm/vanilla-picker@2.12.1/dist/vanilla-picker.min.js"></script>
    <script src="customizeLayout2.js"></script>
</body>

</html>
