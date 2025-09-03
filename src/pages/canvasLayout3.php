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
        content="Take instant photobooth-style photos online with Layout 3 (6 photos). Perfect for photo grids and printing." />
    <meta name="keywords"
        content="photobooth, photo layout, photo grid, online photobooth, layout 3, 6 photos" />
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
        * {
            overflow: hidden;
        }

        /* Adjustments to fit content within 1280x1024 viewport */
        #videoContainer {

            height: 700px;

        }

        .photo-preview-container {

            padding: 0.5rem 0;
        }

        /* Override .main-content-card styles for this page only */
        .main-content-card {
            width: unset !important;
            min-width: unset !important;
            max-width: unset !important;
            height: calc(100vh - 1rem) !important;
            background: unset !important;
            backdrop-filter: unset !important;
            -webkit-backdrop-filter: unset !important;
            border-radius: unset !important;
            box-shadow: unset !important;
            padding: unset !important;
            position: unset !important;
            overflow: unset !important;
            display: unset !important;
            flex-direction: unset !important;
            touch-action: unset !important;
            overflow-x: unset !important;
            overscroll-behavior-x: unset !important;
        }
    </style>
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
        * {
            overflow: hidden;
        }

        /* Adjustments to fit content within 1280x1024 viewport */
        #videoContainer {

            height: 700px;

        }

        .photo-preview-container {

            padding: 0.5rem 0;
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
                                var photoCount = window.photoCount || 6; // Layout 3 has 6 photos
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
                            <div id="progressCounter">0/6</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

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