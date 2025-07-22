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
        content="Take instant photobooth-style photos online with Layout 5 (6 photos). Perfect for photo grids and printing.">
    <meta name="keywords"
        content="photobooth, photo layout, photo grid, online photobooth, layout 5, 6 photos">
    <title>Photobooth | Layout 5 - 6 Photos</title>
    <link rel="canonical" href="https://www.gofotobox.online">
    <meta property="og:title" content="Photobooth | Layout 5 - 6 Photos">
    <meta property="og:description"
        content="Take instant photobooth-style photos online with Layout 5. Perfect for 6-photo grids.">
    <meta property="og:image" content="https://www.gofotobox.online/assets/home-mockup.png">
    <meta property="og:url" content="https://www.gofotobox.online">
    <meta property="og:type" content="website">
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Photobooth | Layout 5 - 6 Photos">
    <meta name="twitter:description"
        content="Take instant photobooth-style photos online with Layout 5. Perfect for 6-photo grids.">
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
            <h1 class="custom-heading layout-heading">Layout 5 - 6 Photos</h1>
            <p class="layout-subtext">Perfect photo grid layout with 6 photos!</p>
            <p class="layout-subtext">NOTE: you have 3 seconds for each shot - 6 photos total</p>

            <div id="layout-settings">

                <nav id="new-navbar">
                    <a href="selectlayout.php" class="canvas-logo">
                        <h2 class="side-logo">gofotobox</h2>
                    </a>

                    <div id="nav-links">
                        <ul>
                            <li><a href="selectlayout.php">Layouts</a></li>
                            <li><a href="canvasLayout5.php" class="current-nav">Layout 5</a></li>
                        </ul>
                    </div>

                    <div>
                        <button id="darkModeToggle">
                            <img class="toggleImage" src="../assets/sun-icon.png" alt="Toggle Dark Mode" />
                        </button>
                    </div>
                </nav>

                <div class="custom-main">
                    <div id="videoContainer">

                        <video id="video" autoplay muted></video>

                        <div id="blackScreen">
                            <div id="countdownText">3</div>
                        </div>

                        <div id="flash"></div>

                        <div id="fullscreenMessage">
                            entered fullscreen
                        </div>

                        <div id="filterMessage">
                            none
                        </div>

                        <div id="add-ons-container">
                            <button id="fullscreenBtn" class="uploadBtnStyling">
                                <img class="fullScreenSize" src="../assets/fullScreen3.png" alt="Full Screen" />
                            </button>

                            <button id="invertBtn">
                                <img class="s-icons-size" src="../assets/mirror-icon.svg" alt="Invert Camera" />
                            </button>

                            <select id="timerOptions" class="custom-select">
                                <option value="" disabled selected>Timer</option>
                                <option value="1">1 sec</option>
                                <option value="2">2 sec</option>
                                <option value="3">3 sec</option>
                                <option value="5">5 sec</option>
                                <option value="10">10 sec</option>
                            </select>

                            <button id="uploadBtn" class="uploadBtnStyling">
                                <img class="s-icons-size" src="../assets/upload-icon.png" alt="Upload" />
                                Upload
                            </button>
                            <input type="file" id="uploadInput" accept="image/*" style="display: none;" />

                        </div>

                        <div class="filter-container">
                            <button id="normalFilterId" class="filterBtn"></button>
                            <button id="sepiaFilterId" class="filterBtn"></button>
                            <button id="bnwFilterId" class="filterBtn"></button>
                            <button id="vintageFilterId" class="filterBtn"></button>
                            <button id="grayFilterId" class="filterBtn"></button>
                            <button id="smoothFilterId" class="filterBtn"></button>
                        </div>

                    </div>

                    <div class="customization-container">
                        <div id="progressCounter">0/6</div>

                        <div id="photoContainer"></div>

                        <div class="startBtn-container">
                            <button id="startBtn" class="main-button">
                                Start
                            </button>

                            <button id="doneBtn" class="sub-button">
                                Customize
                            </button>
                        </div>
                    </div>
                </div>

            </div>

        </section>

    </main>

    <script src="canvasLayout5.js"></script>
</body>

</html>
