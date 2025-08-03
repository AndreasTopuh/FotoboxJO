<?php
// Include session protection - ini akan memastikan user sudah bayar
require_once '../includes/session-protection.php';

// Include PWA helper
require_once '../includes/pwa-helper.php';

// // Set photo session timing - 7 menit untuk foto
// if (!isset($_SESSION["photo_start_time"])) {
//     $_SESSION["photo_start_time"] = time();
//     $_SESSION["photo_expired_time"] = time() + (7 * 60); // 7 menit untuk foto
// }

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
        content="Take instant photobooth-style photos online with Layout 1 (2 photos). Perfect for photo strips and printing." />
    <meta name="keywords"
        content="photobooth, photo layout, photo strip, online photobooth, layout 1, 2 photos" />
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
        content="Take instant photobooth-style photos online with Layout 1. Perfect for 2-photo strips." />
    <meta name="twitter:image" content="https://www.gofotobox.online/assets/home-mockup.png" />
    <link rel="stylesheet" href="home-styles.css?v=<?php echo time(); ?>" />
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
        /* Adjustments for 1280x1024 viewport with three-section layout */
        .canvas-centered {
            padding: 0;
            min-height: 100vh;
            height: 100vh;
            margin: 0;
            display: flex;
            flex-direction: column;
            position: relative;
            width: 100vw;
            overflow: hidden;
        }

        .main-content-card {
            height: 100vh;
            padding: 0.5rem;
            min-width: 100vw;
            max-width: 100vw;
            margin: 0;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 0;
            box-shadow: none;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .horizontal-layout {
            display: flex;
            flex-direction: row;
            gap: 1rem;
            height: calc(100vh - 1rem);
            width: 100%;
            align-items: stretch;
            padding: 0.5rem;
        }

        /* Left Section: Photo Previews */
        .photo-preview-container {
            flex: 1;
            max-width: 300px;
            min-width: 200px;
            min-height: 260px;
            max-height: 300px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: var(--radius-lg);
            border: 1px solid rgba(233, 30, 99, 0.1);
            padding: 1rem;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: var(--pink-primary) transparent;
        }

        .photo-preview-container::-webkit-scrollbar {
            width: 4px;
        }

        .photo-preview-container::-webkit-scrollbar-track {
            background: transparent;
        }

        .photo-preview-container::-webkit-scrollbar-thumb {
            background: var(--pink-primary);
            border-radius: 2px;
        }

        .photo-preview-grid {
            display: flex;
            flex-direction: row;
            gap: 1rem;
            padding: 0.5rem;
            justify-content: center;
            align-items: center;
            width: 100%;
        }

        .photo-preview-slot {
            width: 120px;
            height: 120px;
            max-width: 120px;
            flex: none;
            aspect-ratio: 1 / 1;
            border: 2px dashed var(--pink-primary);
            border-radius: var(--radius-md);
            background: rgba(233, 30, 99, 0.05);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            position: relative;
        }

        .photo-preview-slot.filled {
            border: 2px solid var(--pink-primary);
            background: white;
            padding: 4px;
        }

        .photo-preview-slot img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 6px;
        }

        .photo-placeholder {
            color: var(--pink-primary);
            font-size: 0.9rem;
            opacity: 0.7;
            text-align: center;
        }

        .retake-photo-btn {
            position: absolute;
            top: -8px;
            right: -8px;
            width: 28px;
            height: 28px;
            background: var(--pink-primary);
            color: white;
            border: 2px solid white;
            border-radius: 50%;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            display: none;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(233, 30, 99, 0.4);
        }

        .photo-preview-slot.filled .retake-photo-btn {
            display: flex;
        }

        .retake-photo-btn:hover {
            background: var(--pink-hover);
            transform: scale(1.15);
            box-shadow: 0 6px 20px rgba(233, 30, 99, 0.5);
        }

        /* Middle Section: Camera View */
        .camera-container {
            flex: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            margin: 0;
            padding: 0.5rem;
        }

        #videoContainer {
            width: 100%;
            height: 450px;
            /* Standardized camera height for all layouts */
            max-height: 60vh;
            background: rgba(0, 0, 0, 0.9);
            border-radius: 18px;
            border: 2px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            position: relative;
            flex-shrink: 0;
        }

        video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 16px;
        }

        .camera-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--pink-primary);
            margin-bottom: 0.5rem;
        }

        #progressCounter {
            font-size: 2rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--pink-primary), var(--pink-light));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin: 1rem 0;
        }

        #progressCounter::after {
            width: 40px;
            height: 2px;
            bottom: -8px;
        }

        /* Right Section: Controls */
        .controls-container {
            flex: 1;
            max-width: 300px;
            min-width: 250px;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: var(--radius-lg);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: var(--pink-primary) transparent;
        }

        .controls-container::-webkit-scrollbar {
            width: 4px;
        }

        .controls-container::-webkit-scrollbar-track {
            background: transparent;
        }

        .controls-container::-webkit-scrollbar-thumb {
            background: var(--pink-primary);
            border-radius: 2px;
        }

        .camera-settings,
        .filter-section {
            padding: 1rem;
            border-radius: 12px;
            border: 1px solid rgba(233, 30, 99, 0.2);
            background: rgba(233, 30, 99, 0.05);
        }

        .settings-title,
        .filter-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--pink-primary);
            margin-bottom: 0.8rem;
        }

        .setting-group {
            margin-bottom: 1rem;
        }

        .setting-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-light);
            margin-bottom: 0.5rem;
        }

        .timer-selector::before {
            font-size: 0.9rem;
            left: 10px;
        }

        .custom-select {
            padding: 10px 14px;
            font-size: 0.85rem;
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 12px;
            cursor: pointer;
            outline: none;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .custom-select:focus {
            border-color: var(--pink-primary);
            box-shadow: 0 0 0 2px rgba(226, 133, 133, 0.2);
        }

        .filter-buttons-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            margin-bottom: 1rem;
        }

        .filterBtn {
            height: 55px;
            border: 2px solid rgba(233, 30, 99, 0.3);
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .filterBtn:hover,
        .filterBtn.active {
            border-color: var(--pink-primary);
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(233, 30, 99, 0.3);
        }

        #gridToggleBtn {
            padding: 8px 14px;
            font-size: 0.8rem;
            background: rgba(255, 255, 255, 0.15);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 12px;
            color: var(--text-dark);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        #gridToggleBtn:hover,
        #gridToggleBtn.active {
            background: rgba(226, 133, 133, 0.2);
            border-color: var(--pink-primary);
            color: var(--pink-primary);
        }

        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            align-items: center;
        }

        #startBtn,
        #captureAllBtn,
        #doneBtn {
            padding: 12px 30px;
            font-size: 1.1rem;
            border-radius: 20px;
            background: linear-gradient(135deg, var(--pink-primary), var(--pink-light));
            color: white;
            border: none;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(226, 133, 133, 0.25);
        }

        #startBtn:hover,
        #captureAllBtn:hover,
        #doneBtn:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 8px 25px rgba(226, 133, 133, 0.3);
        }

        #startBtn:disabled,
        #captureAllBtn:disabled,
        #doneBtn:disabled {
            background: #ccc;
            cursor: not-allowed;
            box-shadow: none;
        }

        #retakeAllBtn {
            padding: 6px 16px;
            font-size: 0.75rem;
            border-radius: 6px;
            background: white;
            color: var(--pink-primary);
            border: 2px solid var(--pink-primary);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        #retakeAllBtn:hover:not(:disabled) {
            background: var(--pink-primary);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(233, 30, 99, 0.3);
        }

        #retakeAllBtn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .timer-box {
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            background: rgba(226, 133, 133, 0.9);
            border-radius: var(--radius-lg);
            font-weight: 600;
            box-shadow: 0 8px 25px rgba(226, 133, 133, 0.3);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .timer-box #timer-display,
        .timer-box #session-timer-display {
            font-size: 1.5rem;
            margin-bottom: 5px;
        }

        .timer-box p {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        /* Specific adjustments for 1280x1024 resolution */
        @media (min-width: 1280px) and (max-height: 1024px) {
            .main-content-card {
                max-height: 1024px;
                padding: 0.5rem;
            }

            #videoContainer {
                height: 600px;
                max-height: 70vh;
            }

            .photo-preview-container {
                max-width: 250px;
            }

            .controls-container {
                max-width: 300px;
            }
        }

        @media (max-width: 1024px) {
            .horizontal-layout {
                flex-direction: column;
                height: auto;
                gap: 0.5rem;
            }

            .photo-preview-container,
            .camera-container,
            .controls-container {
                max-width: 100%;
                min-width: auto;
            }

            #videoContainer {
                height: 400px;
                max-height: 50vh;
            }

            .photo-preview-container {
                max-height: 200px;
                padding: 0.5rem;
            }

            .photo-preview-slot {
                width: 100px;
                height: 100px;
                max-width: 100px;
            }

            .controls-container {
                max-width: 100%;
                padding: 0.5rem;
            }
        }

        @media (max-width: 768px) {
            #videoContainer {
                height: 300px;
                max-height: 40vh;
            }

            .photo-preview-slot {
                width: 80px;
                height: 80px;
                max-width: 80px;
            }

            .timer-box {
                top: 10px;
                right: 10px;
                padding: 10px 15px;
            }

            .timer-box #timer-display,
            .timer-box #session-timer-display {
                font-size: 1.2rem;
            }

            .timer-box p {
                font-size: 0.7rem;
            }
        }

        /* Fullscreen styles */
        #videoContainer:fullscreen,
        #videoContainer:-webkit-full-screen,
        #videoContainer:-moz-full-screen {
            width: 100vw !important;
            height: 100vh !important;
            max-width: none !important;
            max-height: none !important;
            border-radius: 0 !important;
            border: none !important;
            box-shadow: none !important;
            background: black !important;
        }

        #videoContainer:fullscreen video,
        #videoContainer:-webkit-full-screen video,
        #videoContainer:-moz-full-screen video {
            width: 100vw !important;
            height: 100vh !important;
            object-fit: cover !important;
            border-radius: 0 !important;
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

                        <div class="photo-preview-container">
                            <div class="photo-preview-grid" id="photoContainer">
                                <!-- Slot photo akan di-generate oleh JS sesuai jumlah photo -->
                            </div>
                        </div>
                        <script>
                            // Enhanced photo container initialization with modal support
                            document.addEventListener('DOMContentLoaded', function() {
                                var photoCount = window.photoCount || 2; // fallback to 2 if not set
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

                                        var btn = document.createElement('div');
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
                        <!-- Camera Settings -->
                        <div class="camera-settings">
                            <h3 class="settings-title">Camera Settings</h3>
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
                            <h3 class="filter-title">Photo Filters</h3>
                            <div class="filter-buttons-grid">
                                <button id="normalFilterId" class="filterBtn" title="Normal"></button>
                                <button id="vintageFilterId" class="filterBtn" title="Vintage"></button>
                                <button id="grayFilterId" class="filterBtn" title="Gray"></button>
                                <button id="smoothFilterId" class="filterBtn" title="Smooth"></button>
                                <button id="bnwFilterId" class="filterBtn" title="Black & White"></button>
                                <button id="sepiaFilterId" class="filterBtn" title="Sepia"></button>
                            </div>
                            <div class="grid-toggle">
                                <button id="gridToggleBtn">Show Grid</button>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="action-buttons">
                            <button id="startBtn">START CAPTURE</button>
                            <button id="captureAllBtn">CAPTURE ALL</button>
                            <button id="retakeAllBtn" disabled>RETAKE ALL</button>
                            <button id="doneBtn" style="display: none;">COMPLETE SESSION</button>
                        </div>
                        <div class="progress-display">
                            <div id="progressCounter">0/2</div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="canvasLayout1.js"></script>
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
                        window.location.href = 'customizeLayout1.php';
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