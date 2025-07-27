<?php
session_start();

// Set default session if not exists (untuk PWA compatibility)
if (!isset($_SESSION['session_type'])) {
    $_SESSION['session_type'] = 'photo';
    $_SESSION['photo_start_time'] = time();
    $_SESSION['photo_expired_time'] = time() + (10 * 60); // 10 menit
}

// Extend session if expired untuk better UX
if (isset($_SESSION['photo_expired_time']) && time() > $_SESSION['photo_expired_time']) {
    $_SESSION['photo_expired_time'] = time() + (10 * 60); // Extend 10 menit
}

// Hitung waktu tersisa
$timeLeft = $_SESSION['photo_expired_time'] - time();

// Include PWA helper
require_once '../includes/pwa-helper.php';
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description"
        content="Take instant photobooth-style photos online. Customize with over 100 frame colors, add stickers and frames, and download high-quality photo strips instantly.">
    
    <?php PWAHelper::addPWAHeaders(); ?>
    <meta name="keywords"
        content="photobooth, photobooth website, photobooth website tiktok, online photobooth, tiktok photobooth, webcamtoy, tiktok viral photobooth, picapica, digibooth, photobooth-io">
    <title>Photobooth | Take Photos</title>
    <!-- Canonical URL -->
    <link rel="canonical" href="https://www.photobooth-io.cc">
    <!-- Open Graph (Facebook, LinkedIn) -->
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
        <!-- Timer Session -->
        <div id="session-timer" style="position: fixed; top: 20px; right: 20px; background: rgba(255, 68, 68, 0.9); color: white; padding: 10px 15px; border-radius: 8px; font-weight: bold; z-index: 1000;">
            <div>Waktu Tersisa: <span id="timer"><?php echo sprintf('%02d:%02d', floor($timeLeft / 60), $timeLeft % 60); ?></span></div>
        </div>
        
        <div class="canvas-centered">
            <div class="gradientBgCanvas"></div>
            <p id="progressCounter">0/3</p>
            <input type="file" id="uploadInput" accept="image/*" multiple style="display: none;">
            <div id="add-ons-container">
                <button id="uploadBtn" class="uploadBtnStyling">
                    <img src="/src/assets/upload-icon.png" class="icons-size" alt="upload image icon">
                    Upload Image
                </button>
                <div>
                    <select name="timerOptions" id="timerOptions" class="custom-select">
                        <option value="3">3s</option>
                        <option value="5">5s</option>
                        <option value="10">10s</option>
                    </select>
                </div>
            </div>
            <section class="camera-container">
                <div id="videoContainer">
                    <video id="video" autoplay playsinline></video>
                    <div id="flash"></div>
                    <div id="fullscreenMessage">Press SPACE to Start</div>
                    <div id="filterMessage"></div>
                    <div id="blackScreen">Waiting for camera access...</div>
                    <div id="countdownText">3</div>
                    <button id="fullscreenBtn">
                        <img src="/src/assets/fullScreen3.png" class="fullScreenSize" alt="full screen button">
                    </button>
                </div>
                <div id="photoContainer"></div>
            </section>
            <div class="startBtn-container">
                <div class="filter-container">
                    <button id="vintageFilterId" class="filterBtn"></button>
                    <button id="grayFilterId" class="filterBtn"></button>
                    <button id="smoothFilterId" class="filterBtn"></button>
                    <button id="bnwFilterId" class="filterBtn"></button>
                    <button id="sepiaFilterId" class="filterBtn"></button>
                    <button id="normalFilterId" class="filterBtn"></button>
                    <button id="invertBtn"><img src="/src/assets/mirror-icon.svg" alt="mirror icon"
                            id="mirror-icon"></button>
                </div>
                <div>
                    <h3 class="options-label">Choose a filter </h3>
                </div>
                <div class="start-done-btn">
                    <button id="startBtn">START</button>
                    <button id="doneBtn">DONE</button>
                </div>
            </div>
            <div id="photoPreview"></div>
            <!-- <div id="flash"></div> -->
        </div>
    </main>

    <script>
        // Session timer
        let timeLeft = <?php echo $timeLeft; ?>;
        let timerInterval;

        function startSessionTimer() {
            timerInterval = setInterval(() => {
                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;
                
                document.getElementById('timer').textContent = 
                    `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                
                if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                    alert('Waktu sesi foto habis! Melanjutkan ke halaman customize.');
                    window.location.href = 'customize.php';
                }
                
                timeLeft--;
            }, 1000);
        }

        // Mulai timer ketika halaman load
        window.addEventListener('load', function() {
            startSessionTimer();
        });
    </script>
    <script src="canvas.js"></script>
    <script src="debug-camera.js"></script>
    <!-- <script src="main.js"></script> -->
    
    <?php PWAHelper::addPWAScript(); ?>
</body>

</html>