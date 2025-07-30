<?php
session_start();

// Include PWA helper
require_once '../includes/pwa-helper.php';

// Set default session if not exists (untuk PWA compatibility)
if (!isset($_SESSION["session_type"])) {
    $_SESSION["session_type"] = "photo";
    $_SESSION["photo_start_time"] = time();
    $_SESSION["photo_expired_time"] = time() + (7 * 60); // 7 menit untuk canvas
}

// Extend session if expired untuk better UX
if (isset($_SESSION["photo_expired_time"]) && time() > $_SESSION["photo_expired_time"]) {
    $_SESSION["photo_expired_time"] = time() + (7 * 60); // Extend 7 menit
}

// Hitung waktu tersisa
$timeLeft = $_SESSION['photo_expired_time'] - time();
?>

<!DOCTYPE html>
<html>
<head>
    <?php PWAHelper::addPWAHeaders(); ?>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description"
        content="Take instant photobooth-style photos online with Layout 3 (6 photos). Perfect for photo grids and printing." />
    <meta name="keywords" content="photobooth, photo layout, photo grid, online photobooth, layout 3, 6 photos" />
    <title>Photobooth | Layout 3 - 6 Photos</title>
    <link rel="canonical" href="https://www.gofotobox.online" />
    <meta property="og:title" content="Photobooth | Layout 3 - 6 Photos" />
    <meta property="og:description"
        content="Take instant photobooth-style photos online with Layout 3. Perfect for 6-photo grids." />
    <meta property="og:image" content="https://www.gofotobox.online/assets/home-mockup.png" />
    <meta property="og:url" content="https://www.gofotobox.online" />
    <meta property="og:type" content="website" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="Photobooth | Layout 3 - 6 Photos" />
    <meta name="twitter:description"
        content="Take instant photobooth-style photos online with Layout 3. Perfect for 6-photo grids." />
    <meta name="twitter:image" content="https://www.gofotobox.online/assets/home-mockup.png" />
    <link rel="stylesheet" href="/styles.css?v=<?php echo time(); ?>" />
    <link rel="stylesheet" href="/carousel.css?v=<?php echo time(); ?>" />
    <!-- Cache Control -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&family=Syne:wght@400..800&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Mukta+Mahee:wght@200;300;400;500;600;700;800&display=swap"
        rel="stylesheet" />
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

        /* Modal Styling */
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

        /* Upload Button Styling */
        .uploadBtnStyling {
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .uploadBtnStyling:hover {
            background: linear-gradient(135deg, #45a049, #3d8b40);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .icons-size {
            width: 16px;
            height: 16px;
        }

        body {
            font-family: Arial, sans-serif;
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
            transition: opacity 0.5s ease-in-out;
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
            transition: opacity 0.5s ease-in-out;
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

        /* Grid Overlay Styling */
        .grid-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: grid;
            grid-template-columns: repeat(3, 1fr); /* 3 columns */
            grid-template-rows: repeat(3, 1fr); /* 3 rows */
            pointer-events: none; /* Allow clicks to pass through */
            z-index: 1; /* Above video, below UI elements */
            opacity: 0.5; /* Semi-transparent */
        }

        .grid-overlay::before,
        .grid-overlay::after {
            content: '';
            position: absolute;
            background: rgba(255, 255, 255, 0.3); /* White lines with transparency */
        }

        .grid-overlay::before {
            width: 100%;
            height: 3px; /* Thicker horizontal line */
            top: 33.33%; /* Horizontal line at 1/3 */
        }

        .grid-overlay::after {
            width: 100%;
            height: 3px; /* Thicker horizontal line */
            top: 66.67%; /* Horizontal line at 2/3 */
        }

        .grid-overlay > div {
            border-right: 3px solid rgba(255, 255, 255, 0.3); /* Thicker vertical lines */
        }

        .grid-overlay > div:nth-child(3n) {
            border-right: none; /* Remove border on last column */
        }

        #gridToggleBtn {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            background: #ff4444;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            transition: background 0.3s ease;
        }

        #gridToggleBtn:hover {
            background: #e03e3e;
        }

        @media only screen and (max-width: 768px) {
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
    <!-- Timer Box -->
    <div id="timer-box" class="timer-box">
        <span id="timer-display">07:00</span>
        <p>Sisa waktu untuk mengambil foto</p>
    </div>

    <!-- Timeout Modal -->
    <div id="timeout-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <h2>Waktu Habis!</h2>
            <p id="timeout-message">Sesi foto Anda telah berakhir.</p>
            <button id="timeout-ok-btn" class="modal-btn">OK</button>
        </div>
    </div>

    <main id="main-section">
        <div class="canvas-centered">
            <div class="gradientBgCanvas"></div>
            <p id="progressCounter">0/6</p>
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
                    <div id="gridOverlay" class="grid-overlay" style="display: none;">
                        <div></div><div></div><div></div>
                        <div></div><div></div><div></div>
                        <div></div><div></div><div></div>
                    </div>
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
                    <button id="invertBtn"><img src="/src/assets/mirror-icon.svg" alt="mirror icon" id="mirror-icon"></button>
                    <button id="gridToggleBtn">Show Grid</button>
                </div>
                <div>
                    <h3 class="options-label">Choose a filter</h3>
                </div>
                <div class="start-done-btn">
                    <button id="startBtn">START</button>
                    <button id="doneBtn">DONE</button>
                </div>
            </div>
            <div id="photoPreview"></div>
        </div>
    </main>
    
    <!-- Carousel Modal -->
    <div id="carousel-modal" class="modal" style="display: none;">
        <div class="carousel-container">
            <button id="carousel-close-btn" class="carousel-close-btn">✕</button>
            <button id="carousel-prev-btn" class="carousel-nav-btn prev-btn">←</button>
            <div class="carousel-image-container">
                <img id="carousel-image" class="carousel-image" src="" alt="Photo Preview">
                <button id="carousel-retake-btn" class="carousel-retake-btn"><img src="/src/assets/retake.png" alt="retake icon"></button>
            </div>
            <button id="carousel-next-btn" class="carousel-nav-btn next-btn">→</button>
            <div id="carousel-indicators" class="carousel-indicators"></div>
        </div>
    </div>

    <script src="canvasLayout3.js"></script>
    <script src="debug-camera.js"></script>
    
    <!-- Session Timer Script -->
    <script src="../includes/session-timer.js"></script>
    
    <script>
        // Custom timer expired handler for canvas page
        document.addEventListener('DOMContentLoaded', function() {
            if (window.sessionTimer) {
                window.sessionTimer.onExpired = function(page) {
                    // From canvas page, check photo count first
                    const photoElements = document.querySelectorAll('#photoPreview img, #photoPreview canvas, .photo');
                    if (photoElements.length > 0) {
                        // Has photos, go to customize
                        window.location.href = 'customizeLayout3.php';
                    } else {
                        // No photos, reset and go to index
                        window.location.href = '/';
                    }
                };
            }
        });
    </script>
    
    <?php PWAHelper::addPWAScript(); ?>
</body>
</html>