<?php
// Include session protection - ini akan memastikan user sudah bayar
require_once '../includes/session-protection.php';

// Include PWA helper
require_once '../includes/pwa-helper.php';

// Set photo session timing - 10 menit untuk foto
if (!isset($_SESSION["photo_start_time"])) {
    $_SESSION["photo_start_time"] = time();
    $_SESSION["photo_expired_time"] = time() + (10 * 60); // 10 menit untuk foto
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
        content="Take instant photobooth-style photos online with Layout 6 (4 photos). Perfect for photo grids and printing." />
    <meta name="keywords"
        content="photobooth, photo layout, photo grid, online photobooth, layout 6, 4 photos" />
    <title>Photobooth | Layout 6 - 4 Photos</title>
    <link rel="canonical" href="https://www.gofotobox.online" />
    <meta property="og:title" content="Photobooth | Layout 6 - 4 Photos" />
    <meta property="og:description"
        content="Take instant photobooth-style photos online with Layout 6. Perfect for 4-photo grids." />
    <meta property="og:image" content="https://www.gofotobox.online/assets/home-mockup.png" />
    <meta property="og:url" content="https://www.gofotobox.online" />
    <meta property="og:type" content="website" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="Photobooth | Layout 6 - 4 Photos" />
    <meta name="twitter:description"
        content="Take instant photobooth-style photos online with Layout 6. Perfect for 4-photo grids." />
    <meta name="twitter:image" content="https://www.gofotobox.online/assets/home-mockup.png" />
    <link rel="stylesheet" href="../../static/css/main.css?v=<?php echo time(); ?>" />
    <link rel="stylesheet" href="../../static/css/canvas.css?v=<?php echo time(); ?>" />
    <link rel="stylesheet" href="../../static/css/responsive.css?v=<?php echo time(); ?>" />
    <!-- Cache Control -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&family=Syne:wght@400..800&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Mukta+Mahee:wght@200;300;400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <link rel="icon" href="/src/assets/icons/photobooth-new-logo.png" />
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', 'Roboto', 'Mukta Mahee', Arial, sans-serif;
            background: linear-gradient(135deg, #ffe6ec 0%, #ffb6c1 100%);
        }
        .gradientBgCanvas {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            z-index: -1;
            width: 100vw;
            height: 100vh;
            background: linear-gradient(135deg, #ffe6ec 0%, #ffb6c1 100%);
            opacity: 0.7;
        }
        .canvas-centered {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 0;
        }
        .main-content-card {
            display: flex;
            flex-direction: column;
            gap: 2rem;
            width: 100%;
            max-width: 1280px;
            margin: 0 auto;
            background: rgba(255,255,255,0.7);
            box-shadow: 0 8px 32px rgba(255,105,135,0.10);
            border-radius: 2rem;
            padding: 2rem 2rem 2rem 2rem;
        }
        .horizontal-layout {
            display: flex;
            flex-direction: row;
            gap: 2.5rem;
            align-items: flex-start;
            width: 100%;
        }
        @media (max-width: 900px) {
            .horizontal-layout {
                flex-direction: column;
                gap: 2rem;
            }
        }
        .camera-container {
            background: linear-gradient(120deg, #fff 80%, #ffe6ec 100%);
            border-radius: 2rem;
            box-shadow: 0 8px 32px rgba(255,105,135,0.13);
            padding: 2.5rem 2rem 2rem 2rem;
            display: flex;
            flex-direction: column;
            flex: 2 1 0;
            min-width: 0;
            align-items: center;
            position: relative;
            border: 1.5px solid #ffb6c1;
        }
        #videoContainer {
            background: #222;
            border-radius: 1.5rem;
            box-shadow: 0 4px 24px rgba(255,105,135,0.13);
            position: relative;
            overflow: hidden;
            outline: 4px solid #e91e63;
            width: 100%;
            max-width: 820px;
            height: 480px;
            margin: 0 auto 1.5rem auto;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        @media (max-width: 900px) {
            #videoContainer {
                max-width: 100%;
                height: 320px;
            }
        }
        #videoContainer video,
        #videoContainer canvas {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        #videoContainer #gridOverlay,
        #videoContainer #flash {
            border-radius: inherit;
        }
        #fullscreenMessage,
        #filterMessage,
        #blackScreen,
        #countdownText {
            color: #fff;
            text-shadow: 0 2px 8px rgba(0,0,0,0.6);
            font-weight: 700;
            font-size: 1.3rem;
            letter-spacing: 1px;
        }
        #blackScreen {
            background: linear-gradient(180deg, rgba(0,0,0,0.60), rgba(0,0,0,0.60));
        }
        #videoContainer #fullscreenBtn,
        #videoContainer .retake-all-btn {
            background: linear-gradient(135deg, #ffe6ec 0%, #ffb6c1 100%);
            border: 2px solid #e91e63;
            color: #e91e63;
            border-radius: 1rem;
            box-shadow: 0 2px 10px rgba(255,105,135,0.10);
            backdrop-filter: blur(2px);
            position: absolute;
            bottom: 1.2rem;
            left: 1.2rem;
            width: 52px;
            height: 52px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s, color 0.2s;
        }
        #videoContainer #fullscreenBtn {
            left: unset;
            right: 1.2rem;
        }
        #videoContainer #fullscreenBtn:hover,
        #videoContainer .retake-all-btn:hover {
            background: #e91e63;
            color: #fff;
        }
        .photo-preview-container {
            width: 100%;
            margin-top: 1.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .photo-preview-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            width: 100%;
            max-width: 820px;
        }
        @media (max-width: 900px) {
            .photo-preview-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        .photo-preview-slot {
            background: linear-gradient(120deg, #ffe6ec 80%, #fff 100%);
            border: 2.5px dashed #e91e63;
            border-radius: 1.2rem;
            position: relative;
            min-height: 120px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: box-shadow 0.2s, border-color 0.2s;
            box-shadow: 0 2px 12px rgba(255,105,135,0.10);
        }
        .photo-preview-slot:hover {
            box-shadow: 0 4px 16px rgba(255,105,135,0.18);
            border-color: #ffb6c1;
        }
        .photo-placeholder {
            color: #e91e63;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }
        .retake-photo-btn {
            background: linear-gradient(135deg, #ffe6ec 0%, #ffb6c1 100%);
            color: #e91e63;
            border: 2px solid #e91e63;
            border-radius: 999px;
            width: 36px;
            height: 36px;
            display: grid;
            place-items: center;
            font-weight: 800;
            font-size: 1.2rem;
            margin-top: 0.2rem;
            transition: background 0.2s, color 0.2s;
        }
        .retake-photo-btn:hover {
            background: #e91e63;
            color: #fff;
        }
        .controls-container {
            background: linear-gradient(120deg, #fff 80%, #ffe6ec 100%);
            border-radius: 2rem;
            box-shadow: 0 8px 32px rgba(255,105,135,0.13);
            padding: 2.5rem 2rem 2rem 2rem;
            display: flex;
            flex-direction: column;
            gap: 2.2rem;
            min-width: 340px;
            max-width: 420px;
            align-items: stretch;
            position: relative;
            border: 1.5px solid #ffb6c1;
        }
        .controls-container .settings-title,
        .controls-container .filter-title {
            color: #e91e63;
            font-weight: 700;
            font-size: 1.35rem;
            margin-bottom: 0.7rem;
            letter-spacing: 1px;
        }
        .controls-container .custom-select {
            background: #ffe6ec;
            border: 2px solid #e91e63;
            border-radius: 0.8rem;
            padding: 0.6rem 1.2rem;
            font-size: 1.1rem;
            color: #e91e63;
            font-weight: 600;
            margin-top: 0.3rem;
        }
        .controls-container .setting-label {
            color: #d16a8c;
            font-size: 1.05rem;
            font-weight: 500;
        }
        .controls-container .filter-buttons-grid {
            display: grid;
            grid-template-columns: repeat(3, 52px);
            grid-template-rows: repeat(2, 52px);
            gap: 0.9rem;
            justify-content: start;
            margin-bottom: 1.2rem;
        }
        .controls-container .filterBtn {
            background: linear-gradient(135deg, #ffe6ec 0%, #ffb6c1 100%);
            color: #e91e63;
            border: 2px solid #e91e63;
            border-radius: 999px;
            width: 52px;
            height: 52px;
            font-size: 1.2rem;
            transition: background 0.2s, color 0.2s;
        }
        .controls-container .filterBtn:hover,
        .controls-container .filterBtn.active {
            background: #e91e63;
            color: #fff;
        }
        .controls-container .grid-toggle {
            margin-top: 0.7rem;
        }
        .controls-container .grid-toggle #gridToggleBtn {
            background: linear-gradient(135deg, #ffe6ec 0%, #ffb6c1 100%);
            color: #e91e63;
            border: 2px solid #e91e63;
            border-radius: 0.8rem;
            padding: 0.6rem 1.2rem;
            font-size: 1.05rem;
            font-weight: 600;
            transition: background 0.2s, color 0.2s;
        }
        .controls-container .grid-toggle #gridToggleBtn:hover {
            background: #e91e63;
            color: #fff;
        }
        .controls-container .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 1.2rem;
            margin-top: 1.5rem;
        }
        .controls-container .action-buttons button {
            background: linear-gradient(135deg, #e91e63 0%, #ffb6c1 100%);
            color: #fff;
            border: none;
            border-radius: 1rem;
            font-size: 1.15rem;
            font-weight: 700;
            padding: 1rem 1.3rem;
            box-shadow: 0 2px 12px 0 rgba(255,105,135,0.10);
            transition: background 0.2s, color 0.2s;
        }
        .controls-container .action-buttons button:hover {
            background: #d16a8c;
        }
        .controls-container .action-buttons #retakeAllBtn {
            background: linear-gradient(135deg, #ffe6ec 0%, #ffb6c1 100%);
            color: #e91e63;
            border: 2px solid #e91e63;
        }
        .controls-container .action-buttons #retakeAllBtn:hover {
            background: #e91e63;
            color: #fff;
        }
        .controls-container .action-buttons #doneBtn {
            background: linear-gradient(135deg, #00b894 0%, #e0ffe6 100%);
            color: #fff;
        }
        .controls-container .action-buttons #doneBtn:hover {
            background: #00997a;
        }
        .controls-container .action-buttons button[disabled] {
            background: #f5f5f5;
            color: #bbb;
            border-color: #e0e0e0;
            cursor: not-allowed;
        }
        .controls-container .progress-display {
            margin-top: 1.5rem;
            text-align: center;
        }
        .controls-container .progress-display #progressCounter {
            font-size: 1.25rem;
            font-weight: 700;
            color: #e91e63;
            background: #ffe6ec;
            border: 2px solid #e91e63;
            border-radius: 0.8rem;
            padding: 0.5rem 1.3rem;
            display: inline-block;
        }
    </style>
</head>

<body>
    <!-- Enhanced Carousel Modal -->
    <div id="carousel-modal" class="modal" role="dialog" aria-modal="true" style="display: none;">
        <div class="carousel-container">
            <button id="carousel-close-btn" class="carousel-close-btn" aria-label="Close Carousel" title="Close (Esc)">✕</button>
            <button id="carousel-prev-btn" class="carousel-nav-btn prev-btn" aria-label="Previous Image" title="Previous (←)">←</button>
            <div class="carousel-image-container">
                <img id="carousel-image" class="carousel-image" src="" alt="Photo Preview">
            </div>
            <button id="carousel-next-btn" class="carousel-nav-btn next-btn" aria-label="Next Image" title="Next (→)">→</button>
            <button id="carousel-retake-btn" class="carousel-retake-btn" aria-label="Retake Photo" title="Retake this photo">
                <img src="/src/assets/retake.png" alt="Retake icon">
                <span>Retake Photo</span>
            </button>
            <div id="carousel-indicators" class="carousel-indicators"></div>
        </div>
    </div>

    <main id="main-section">
        <div class="gradientBgCanvas"></div>
        <div class="canvas-centered">
            <div class="main-content-card">

                <!-- Main Horizontal Layout -->
                <div class="horizontal-layout">
                    <!-- Center Section: Camera Preview -->
                    <div class="camera-container">
                        <!-- <h3 class="camera-title">Camera View</h3> -->
                        <div id="videoContainer">
                            <video id="video" autoplay playsinline></video>
                            <canvas id="canvas" style="display: none;"></canvas>
                            <div id="gridOverlay" class="grid-overlay" style="display: none;"></div>
                            <div id="flash"></div>
                            <div id="fullscreenMessage" style="opacity: 0;">Press SPACE to Start</div>
                            <div id="filterMessage" style="opacity: 0;"></div>
                            <div id="blackScreen">Waiting for camera access...</div>
                            <div id="countdownText" style="display: none;">3</div>
                            <button id="fullscreenBtn" title="Toggle Fullscreen">
                                <img src="/src/assets/fullScreen3.png" class="fullScreenSize" alt="fullscreen toggle">
                            </button>
                            <button id="retakeAllBtn2" class="retake-all-btn" title="Retake All Photos">
                                <img src="/src/assets/retake.png" alt="retake icon">
                            </button>
                        </div>

                        <?php

                        ?>
                        <div class="photo-preview-container">
                            <div class="photo-preview-grid" id="photoContainer">
                                <!-- Slot photo akan di-generate oleh JS sesuai jumlah photo -->
                            </div>
                        </div>
                        <script>
                            // Enhanced photo container initialization with modal support
                            document.addEventListener('DOMContentLoaded', function() {
                                var photoCount = window.photoCount || 4; // Layout 6 has 4 photos
                                var container = document.getElementById('photoContainer');

                                if (container) {
                                    container.innerHTML = '';

                                    for (let i = 0; i < photoCount; i++) {
                                        var slot = document.createElement('div');
                                        slot.className = 'photo-preview-slot';
                                        slot.setAttribute('data-index', i);
                                        slot.setAttribute('data-photo-slot', 'true');

                                        var placeholder = document.createElement('div');
                                        placeholder.className = 'photo-placeholder';
                                        placeholder.textContent = 'Photo ' + (i + 1);

                                        var btn = document.createElement('button');
                                        btn.className = 'retake-photo-btn';
                                        btn.innerHTML = '↻';
                                        btn.title = 'Retake Photo ' + (i + 1);
                                        btn.onclick = function(e) {
                                            e.stopPropagation();
                                            if (typeof retakeSinglePhoto === 'function') {
                                                retakeSinglePhoto(i);
                                            }
                                        };

                                        slot.appendChild(placeholder);
                                        slot.appendChild(btn);
                                        container.appendChild(slot);
                                    }

                                    console.log('Photo container initialized with', photoCount, 'slots');
                                }
                            });
                        </script>

                    </div>

                    <!-- Right Section: Controls (Filter, Settings, Buttons) -->
                    <div class="controls-container">
                        <!-- Pengaturan Timer -->
                        <div class="camera-settings">
                            <h3 class="settings-title">Pengaturan Timer</h3>
                            <div class="setting-group">
                                <label class="setting-label">Timer</label>
                                <div class="timer-selector">
                                    <select name="timerOptions" id="timerOptions" class="custom-select">
                                        <option value="3">3 Seconds</option>
                                        <option value="5">5 Seconds</option>
                                        <option value="10">10 Seconds</option>
                                    </select>
                                </div>
                            </div>

                        </div>

                        <!-- Filter Section -->
                        <div class="filter-section">
                            <h3 class="filter-title">Filter Foto</h3>
                            <div class="filter-buttons-grid">
                                <button id="normalFilterId" class="filterBtn" title="Normal"></button>
                                <button id="vintageFilterId" class="filterBtn" title="Vintage"></button>
                                <button id="grayFilterId" class="filterBtn" title="Gray"></button>
                                <button id="smoothFilterId" class="filterBtn" title="Smooth"></button>
                                <button id="bnwFilterId" class="filterBtn" title="Black & White"></button>
                                <button id="sepiaFilterId" class="filterBtn" title="Sepia"></button>
                            </div>
                            <div class="grid-toggle">
                                <button id="gridToggleBtn">Tampilkan Grid</button>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="action-buttons">
                            <button id="startBtn">MULAI AMBIL</button>
                            <button id="captureAllBtn">AMBIL BERSAMAAN</button>
                            <button id="retakeAllBtn" disabled>AMBIL ULANG SEMUA</button>
                            <button id="doneBtn" style="display: none;">LANJUT EDIT</button>
                        </div>
                        <div class="progress-display">
                            <div id="progressCounter">0/4</div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="canvasLayout6.js"></script>
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
                        window.location.href = 'customizeLayout6.php';
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