<?php
// Include session protection - ini akan memastikan user sudah bayar
require_once '../includes/session-protection.php';

// Include PWA helper
require_once '../includes/pwa-helper.php';

// Set photo session timing - 7 menit untuk foto
if (!isset($_SESSION["photo_start_time"])) {
    $_SESSION["photo_start_time"] = time();
    $_SESSION["photo_expired_time"] = time() + (7 * 60); // 7 menit untuk foto
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
        content="Take instant photobooth-style photos online with Layout 2 (4 photos). Perfect for photo grids and printing." />
    <meta name="keywords"
        content="photobooth, photo layout, photo grid, online photobooth, layout 2, 4 photos" />
    <title>Photobooth | Layout 2 - 4 Photos</title>
    <link rel="canonical" href="https://www.gofotobox.online" />
    <meta property="og:title" content="Photobooth | Layout 2 - 4 Photos" />
    <meta property="og:description"
        content="Take instant photobooth-style photos online with Layout 2. Perfect for 4-photo grids." />
    <meta property="og:image" content="https://www.gofotobox.online/assets/home-mockup.png" />
    <meta property="og:url" content="https://www.gofotobox.online" />
    <meta property="og:type" content="website" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="Photobooth | Layout 2 - 4 Photos" />
    <meta name="twitter:description"
        content="Take instant photobooth-style photos online with Layout 2. Perfect for 4-photo grids." />
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
        </div>
    </div>

    <main id="main-section">
        <div class="gradientBgCanvas"></div>
        <div class="canvas-centered">
        </div>
    </main>

    <script src="canvasLayout2.js"></script>
    <script src="debug-camera.js"></script>

    <!-- Session Timer Script -->
    <script src="../includes/session-timer.js"></script>

    <script>
        // Custom timer expired handler for canvas page
        document.addEventListener('DOMContentLoaded', function() {
        });
    </script>

    <?php PWAHelper::addPWAScript(); ?>
</body>
</html>
