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
        content="Take instant photobooth-style photos online with Layout 1 (2 photos). Perfect for photo strips and printing.">
    <meta name="keywords" content="photobooth, photo layout, photo strip, online photobooth, layout 1, 2 photos">
    <title>Photobooth | Layout 1 - 2 Photos</title>
    <link rel="canonical" href="https://www.gofotobox.online" />
    <meta property="og:title" content="Photobooth | Layout 1 - 2 Photos" />
    <meta property="og:description"
        content="Take instant photobooth-style photos online with Layout 1. Perfect for 2-photo strips." />
    <meta property="og:image" content="https://www.gofotobox.online/assets/home-mockup.png" />
    <meta property="og:url" content="https://www.gofotobox.online" />
    <meta property="og:type" content="website" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="Photobooth | Layout 1 - 2 Photos" />
    <meta name="twitter:description"
        content="Take instant photobooth-style photos online with Layout 1. Perfect for 2-photo strips.">
    <meta name="twitter:image" content="https://www.gofotobox.online/assets/home-mockup.png">
    <link rel="stylesheet" href="../../styles.css" />
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&family=Syne:wght@400..800&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Mukta+Mahee:wght@200;300;400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <link rel="icon" href="/src/assets/icons/photobooth-new-logo.png" />
    <style>
        body {
            /* text-align: center;  */
            font-family: Arial, sans-serif;
            /* margin: 0;  */
            padding: 20px;
        }

        #videoContainer {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            background: black;
            border: 1px solid black;
            border-radius: 20px;
        }

        video {
            width: 100%;
            max-width: 1000px;
            border: 2px solid black;
            border-radius: 20px;
            display: block;
        }

        /* Black screen before video loads */
        #blackScreen {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: black;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
            transition: opacity 1s ease-in-out;
        }

        /* Countdown Text */
        #countdownText {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 47px;
            font-weight: bold;
            color: white;
            background: #d9919136;
            padding: 20px;
            border-radius: 50%;
            padding: 20px 30px;
            display: none;
            z-index: 1;
            animation: bounceScale 0.3s ease-in-out;
        }

        /* Bounce Animation */
        @keyframes bounceScale {
            0% {
                transform: translate(-50%, -50%) scale(0.8);
                opacity: 0.8;
            }

            50% {
                transform: translate(-50%, -50%) scale(1.2);
                opacity: 1;
            }

            100% {
                transform: translate(-50%, -50%) scale(1);
                opacity: 1;
            }
        }

        .bounce {
            animation: bounceScale 0.5s ease-in-out;
        }

        #flash {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            background: white;
            opacity: 0;
            transition: opacity 0.2s;
            pointer-events: none;
        }

        #photoContainer {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            margin-top: 10px;
            width: 170px;
            /* transform: scaleX(-1); */
        }

        #progressCounter {
            font-size: 2.2rem;
        }

        .camera-container {
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .photo {
            width: 150px;
            max-width: 150px;
            border: 2px solid black;
            display: block;
            border-radius: 12px;
            height: 109.76px;
            object-fit: cover;
        }

        button {
            padding: 10px 20px;
            margin-top: 10px;
            font-size: 16px;
            cursor: pointer;
        }

        #downloadBtn {
            display: none;
        }

        /* video {
                transform: scaleX(-1);
            } */

        .credits-container {
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            bottom: -150px;
        }

        #fullscreenMessage {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 24px;
            font-weight: bold;
            color: white;
            background: rgba(0, 0, 0, 0.6);
            padding: 20px 25px;
            border-radius: 10px;
            opacity: 0;
            /* Initially hidden */
            transition: opacity 0.5s ease-in-out;
            /* Smooth fade effect */
            z-index: 2;
        }

        #filterMessage {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 24px;
            font-weight: normal;
            color: white;
            background: rgba(0, 0, 0, 0.2);
            padding: 10px 15px;
            border-radius: 10px;
            opacity: 0;
            /* Initially hidden */
            transition: opacity 0.5s ease-in-out;
            /* Smooth fade effect */
            z-index: 2;
        }

        .startBtn-container {
            display: flex;
            flex-direction: column-reverse;
            justify-content: center;
            align-items: center;
            gap: 0;
        }

        .start-done-btn {
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            gap: 20px;
        }

        @media only screen and (max-width: 768px) {

            /* canvas */
            .camera-container {
                display: flex;
                flex-direction: column;
                justify-content: center;
                gap: 20px;
            }

            .photo {
                width: 100px;
                height: 73.17px;
                display: flex;
                flex-direction: row;
                object-fit: cover;
            }

            #photoContainer {
                display: flex;
                flex-direction: row;
                align-items: center;
                justify-content: center;
                width: 100%;
            }
        }

        @media only screen and (max-width: 540px) {

            /* canvas */
            .camera-container {
                display: flex;
                flex-direction: column;
                justify-content: center;
                gap: 20px;
            }

            .photo {
                width: 100px;
                height: 73.17px;
                display: flex;
                flex-direction: row;
                object-fit: cover;
            }

            #photoContainer {
                display: flex;
                flex-direction: row;
                align-items: center;
                justify-content: center;
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <main id="main-section">
        <nav id="new-navbar">
            <a href="selectlayout.php" class="canvas-logo">
                <h2 class="side-logo">gofotobox</h2>
            </a>
            <div id="nav-links">
                <ul>
                    <li><a href="selectlayout.php">Layouts</a></li>
                    <li><a href="canvasLayout1.php" class="current-nav">Layout 1</a></li>
                </ul>
            </div>
            <div>
                <button id="darkModeToggle">
                    <img class="toggleImage" src="/src/assets/sun-icon.png" alt="Toggle Dark Mode" />
                </button>
            </div>
        </nav>
        <section id="choose-layout">
            <h1 class="custom-heading layout-heading">Layout 1 - 2 Photos</h1>
            <p class="layout-subtext">Perfect photo strip layout with 2 photos!</p>
            <p class="layout-subtext">NOTE: you have 3 seconds for each shot - 2 photos total</p>
            <div class="custom-main">
                <div id="videoContainer">
                    <video id="video" autoplay muted></video>
                    <div id="blackScreen">
                        <div id="countdownText">3</div>
                    </div>
                    <div id="flash"></div>
                    <div id="fullscreenMessage">Press SPACE to Start</div>
                    <div id="filterMessage"></div>
                    <div id="add-ons-container">
                        <button id="fullscreenBtn" class="uploadBtnStyling">
                            <img class="fullScreenSize" src="/src/assets/fullScreen3.png" alt="Full Screen" />
                        </button>
                        <button id="invertBtn">
                            <img class="s-icons-size" src="/src/assets/mirror-icon.svg" alt="Invert Camera" />
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
                            <img class="s-icons-size" src="/src/assets/upload-icon.png" alt="Upload" />
                            Upload
                        </button>
                        <input type="file" id="uploadInput" accept="image/*" style="display: none;" multiple />
                    </div>
                </div>
                <div class="customization-container">
                    <div id="progressCounter">0/2</div>
                    <div id="photoContainer"></div>
                    <div class="startBtn-container">
                        <div class="filter-container">
                            <button id="normalFilterId" class="filterBtn"></button>
                            <button id="sepiaFilterId" class="filterBtn"></button>
                            <button id="bnwFilterId" class="filterBtn"></button>
                            <button id="vintageFilterId" class="filterBtn"></button>
                            <button id="grayFilterId" class="filterBtn"></button>
                            <button id="smoothFilterId" class="filterBtn"></button>
                        </div>
                        <div>
                            <h3 class="options-label">Choose a filter</h3>
                        </div>
                        <div class="start-done-btn">
                            <button id="startBtn" class="main-button">Start</button>
                            <button id="doneBtn" class="sub-button">Customize</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <script src="canvasLayout1.js"></script>
</body>

</html>