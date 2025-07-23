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
        content="Take instant photobooth-style photos online. Customize with over 100 frame colors, add stickers and frames, and download high-quality photo strips instantly.">
    <meta name="keywords"
        content="photobooth, photobooth website, photobooth website tiktok, online photobooth, tiktok photobooth, webcamtoy, tiktok viral photobooth, picapica, photobooth-io">
    <title>Photobooth | Choose Your Photo Layout</title>
    <link rel="canonical" href="https://www.photobooth-io.cc">
    <meta property="og:title" content="Photobooth | Free Online Photobooth Anytime, Anywhere">
    <meta property="og:description"
        content="Take instant photobooth-style photos online. Customize with over 100 frame colors, add stickers and frames, and download high-quality photo strips instantly.">
    <meta property="og:image" content="https://www.photobooth-io.cc/assets/home-mockup.png">
    <meta property="og:url" content="https://www.photobooth-io.cc">
    <meta property="og:type" content="website">
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Photobooth | Free Online Photobooth Anytime, Anywhere">
    <meta name="twitter:description"
        content="Take instant photobooth-style photos online. Customize with over 100 frame colors, add stickers and frames, and download high-quality photo strips instantly.">
    <meta name="twitter:image" content="https://www.photobooth-io.cc/assets/home-mockup.png">
    <link rel="stylesheet" href="../../styles.css" />
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
            <h1 class="custom-heading layout-heading">choose your layout</h1>
            <p class="layout-subtext">NOTE: you have 3 seconds for each shot</p>
            <div class="gradientBgCanvas"></div>
            <div id="layout-settings">

                <div class="layout-contents">
                    <button class="layout-holder" id="layout1Btn">
                        <img src="../assets/layouts/layout1.png" class="layout-img" alt="layout 1" loading="eager">
                    </button>
                    <h2 class="layout-label">Layout 1</h2>
                    <div>
                        <p class="layout-description">Photo Strip</p>
                        <p class="layout-description">(2 Photos)</p>
                    </div>
                </div>
                <div class="layout-contents">
                    <button class="layout-holder" id="layout2Btn">
                        <img src="../assets/layouts/layout2.png" class="layout-img" alt="layout 2" loading="eager">
                    </button>
                    <h2 class="layout-label">Layout 2</h2>
                    <div>
                        <p class="layout-description">Photo Grid</p>
                        <p class="layout-description">(4 Photos)</p>
                    </div>
                </div>
                <div class="layout-contents">
                    <button class="layout-holder" id="layout3Btn">
                        <img src="../assets/layouts/layout3.png" class="layout-img" alt="layout 3" loading="eager">
                    </button>
                    <h2 class="layout-label">Layout 3</h2>
                    <div>
                        <p class="layout-description">Photo Grid</p>
                        <p class="layout-description">(6 Photos)</p>
                    </div>
                </div>
                <div class="layout-contents">
                    <button class="layout-holder" id="layout4Btn">
                        <img src="../assets/layouts/layout4.png" class="layout-img" alt="layout 4" loading="eager">
                    </button>
                    <h2 class="layout-label">Layout 4</h2>
                    <div>
                        <p class="layout-description">Photo Grid</p>
                        <p class="layout-description">(8 Photos)</p>
                    </div>
                </div>
                <div class="layout-contents">
                    <button class="layout-holder" id="layout5Btn">
                        <img src="../assets/layouts/layout5.png" class="layout-img" alt="layout 5" loading="eager">
                    </button>
                    <h2 class="layout-label">Layout 5</h2>
                    <div>
                        <p class="layout-description">Photo Grid</p>
                        <p class="layout-description">(6 Photos)</p>
                    </div>
                </div>
                <div class="layout-contents">
                    <button class="layout-holder" id="layout6Btn">
                        <img src="../assets/layouts/layout6.png" class="layout-img" alt="layout 6" loading="eager">
                    </button>
                    <h2 class="layout-label">Layout 6</h2>
                    <div>
                        <p class="layout-description">Photo Grid</p>
                        <p class="layout-description">(4 Photos)</p>
                    </div>
                </div>

                <!-- Original Canvas Options -->
                <div class="layout-contents">
                    <button class="layout-holder" id="canvasBtn">
                        <img src="../assets/layouts/thinBorders1.2.png" class="layout-img" alt="canvas" loading="eager">
                    </button>
                    <h2 class="layout-label">Canvas</h2>
                    <div>
                        <p class="layout-description">Original Frame</p>
                        <p class="layout-description">(1 Photo)</p>
                    </div>
                </div>
                <div class="layout-contents">
                    <button class="layout-holder" id="canvas2Btn">
                        <img src="../assets/layouts/thinBorders2.2.png" class="layout-img" alt="canvas 2"
                            loading="eager">
                    </button>
                    <h2 class="layout-label">Canvas 2</h2>
                    <div>
                        <p class="layout-description">Original Frame</p>
                        <p class="layout-description">(2 Photos)</p>
                    </div>
                </div>
                <div class="layout-contents">
                    <button class="layout-holder" id="canvas4Btn">
                        <img src="../assets/layouts/thinBorders3.2.png" class="layout-img" alt="canvas 4"
                            loading="eager">
                    </button>
                    <h2 class="layout-label">Canvas 4</h2>
                    <div>
                        <p class="layout-description">Original Frame</p>
                        <p class="layout-description">(4 Photos)</p>
                    </div>
                </div>
                <div class="layout-contents">
                    <button class="layout-holder" id="canvas6Btn">
                        <img src="../assets/layouts/thinBorders4.1.png" class="layout-img" alt="canvas 6"
                            loading="eager">
                    </button>
                    <h2 class="layout-label">Canvas 6</h2>
                    <div>
                        <p class="layout-description">Original Frame</p>
                        <p class="layout-description">(6 Photos)</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script src="./layout.js"></script>
</body>

</html>