<?php
// Include session manager dan PWA helper
require_once '../includes/session-manager.php';
require_once '../includes/pwa-helper.php';

session_start();

// Debug: Tampilkan session untuk debugging
if (isset($_GET['debug'])) {
    echo "<pre>";
    echo "=== SESSION DEBUG INFO ===\n";
    echo "Current State: " . SessionManager::getSessionState() . "\n";
    echo "State Display: " . SessionManager::getStateDisplayName() . "\n";
    echo "Session Info:\n";
    print_r(SessionManager::getSessionInfo());
    echo "\nRaw Session:\n";
    print_r($_SESSION);
    echo "</pre>";
    exit;
}

// Validasi bahwa user sudah menyelesaikan pembayaran
SessionManager::requirePayment();
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

    <?php PWAHelper::addPWAHeaders(); ?>
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
            <div class="gradientBgCanvas"></div>
            <div class="content-container">
                <?php if (isset($_SESSION['is_developer_session']) && $_SESSION['is_developer_session']): ?>
                    <div style="position: fixed; top: 10px; right: 10px; background: rgba(226, 133, 133, 0.9); color: white; padding: 5px 15px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; z-index: 1000;">
                        Developer Mode
                    </div>
                <?php endif; ?>

                <h1 class="custom-heading layout-heading">choose your layout</h1>
                <p class="layout-subtext">NOTE: you have 3 seconds for each shot</p>

                <!-- Carousel Container -->
                <div class="carousel-container">
                    <div class="card-carousel" id="layout-carousel">
                        <button class="layout-card my-card" id="layout1Btn" data-layout="layout1">
                            <img src="../assets/layouts/layout1.png" class="layout-img" alt="layout 1" loading="eager">
                            <div class="layout-info">
                                <h2 class="layout-description">Layout 1</h2>
                            </div>
                        </button>

                        <button class="layout-card my-card" id="layout2Btn" data-layout="layout2">
                            <img src="../assets/layouts/layout2.png" class="layout-img" alt="layout 2" loading="eager">
                            <div class="layout-info">
                                <h2 class="layout-label">Layout 2</h2>
                                <p class="layout-description">Photo Grid</p>
                                <p class="layout-description">(4 Photos)</p>
                            </div>
                        </button>

                        <button class="layout-card my-card" id="layout3Btn" data-layout="layout3">
                            <img src="../assets/layouts/layout3.png" class="layout-img" alt="layout 3" loading="eager">
                            <div class="layout-info">
                                <h2 class="layout-label">Layout 3</h2>
                                <p class="layout-description">Photo Grid</p>
                                <p class="layout-description">(6 Photos)</p>
                            </div>
                        </button>

                        <button class="layout-card my-card" id="layout4Btn" data-layout="layout4">
                            <img src="../assets/layouts/layout4.png" class="layout-img" alt="layout 4" loading="eager">
                            <div class="layout-info">
                                <h2 class="layout-label">Layout 4</h2>
                                <p class="layout-description">Photo Grid</p>
                                <p class="layout-description">(8 Photos)</p>
                            </div>
                        </button>

                        <button class="layout-card my-card" id="layout5Btn" data-layout="layout5">
                            <img src="../assets/layouts/layout5.png" class="layout-img" alt="layout 5" loading="eager">
                            <div class="layout-info">
                                <h2 class="layout-label">Layout 5</h2>
                                <p class="layout-description">Photo Grid</p>
                                <p class="layout-description">(6 Photos)</p>
                            </div>
                        </button>

                        <button class="layout-card my-card" id="layout6Btn" data-layout="layout6">
                            <img src="../assets/layouts/layout6.png" class="layout-img" alt="layout 6" loading="eager">
                            <div class="layout-info">
                                <h2 class="layout-label">Layout 6</h2>
                                <p class="layout-description">Photo Grid</p>
                                <p class="layout-description">(4 Photos)</p>
                            </div>
                        </button>
                    </div>

                    <!-- Navigation indicators -->
                    <div class="carousel-nav">
                        <button class="nav-btn prev-btn" id="prevBtn">‹</button>
                        <div class="carousel-dots">
                            <span class="dot active" data-index="0"></span>
                            <span class="dot" data-index="1"></span>
                            <span class="dot" data-index="2"></span>
                            <span class="dot" data-index="3"></span>
                            <span class="dot" data-index="4"></span>
                            <span class="dot" data-index="5"></span>
                        </div>
                        <button class="nav-btn next-btn" id="nextBtn">›</button>
                    </div>
                </div>
        </section>
    </main>

    <!-- Popup Confirmation -->
    <div id="layoutPopup" class="popup-overlay" style="display: none;">
        <div class="popup-content">
            <button id="closeBtn" class="close-btn">&times;</button>
            <h3>Konfirmasi Layout</h3>
            <p>Apakah Anda yakin memilih layout ini?</p>

            <!-- Layout Preview Container -->
            <div class="layout-preview-container">
                <div class="preview-image-wrapper">
                    <img id="previewLayoutImage" src="" alt="Layout Preview" class="preview-layout-img">
                </div>
                <div class="layout-info">
                    <h4 id="previewLayoutTitle">Layout 1</h4>
                    <p id="previewLayoutDesc">Photo Strip (2 Photos)</p>
                    <div class="layout-specs">
                        <span id="previewPhotoCount">📸 2 foto</span>
                    </div>
                </div>
            </div>

            <div class="popup-buttons">
                <button id="cancelBtn" class="btn-secondary">Batal</button>
                <button id="confirmBtn" class="btn-primary">Lanjutkan</button>
            </div>
        </div>
    </div>

    <!-- Script untuk layout.js di-comment untuk mencegah konflik dengan inline script -->
    <!-- <script src="./layout.js"></script> -->

    <!-- Add jQuery for carousel functionality -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // Carousel functionality - Non-infinite
        $(document).ready(function() {
            // Initialize carousel
            let currentIndex = 1; // Start with Layout 1 (index 0)
            const $cards = $('.my-card');
            const totalCards = $cards.length;

            function initCarousel() {
                // Set Layout 1 as default (index 0)
                currentIndex = 1;
                updateCarousel();
                updateNavButtons();
            }

            function updateCarousel() {
                // Remove all classes
                $cards.removeClass('active prev next');

                // Set active card (current index)
                $cards.eq(currentIndex).addClass('active');

                // Set previous card (if exists)
                if (currentIndex > 0) {
                    $cards.eq(currentIndex - 1).addClass('prev');
                }

                // Set next card (if exists)
                if (currentIndex < totalCards - 1) {
                    $cards.eq(currentIndex + 1).addClass('next');
                }

                // Update dots
                $('.dot').removeClass('active');
                $('.dot').eq(currentIndex).addClass('active');
            }

            function updateNavButtons() {
                // Disable/enable navigation buttons based on position
                $('#prevBtn').prop('disabled', currentIndex === 0);
                $('#nextBtn').prop('disabled', currentIndex === totalCards - 1);
            }

            function goToSlide(index) {
                if (index >= 0 && index < totalCards) {
                    currentIndex = index;
                    updateCarousel();
                    updateNavButtons();
                }
            }

            function nextSlide() {
                if (currentIndex < totalCards - 1) {
                    currentIndex++;
                    updateCarousel();
                    updateNavButtons();
                }
            }

            function prevSlide() {
                if (currentIndex > 0) {
                    currentIndex--;
                    updateCarousel();
                    updateNavButtons();
                }
            }

            // Card click events
            $('.my-card').click(function() {
                const clickedIndex = $(this).index();

                if ($(this).hasClass('active')) {
                    // If clicking active card, show popup
                    selectedLayout = $(this).data('layout');
                    showLayoutPreview(selectedLayout);
                } else if ($(this).hasClass('next') && currentIndex < totalCards - 1) {
                    nextSlide();
                } else if ($(this).hasClass('prev') && currentIndex > 0) {
                    prevSlide();
                }
            });

            // Navigation button events
            $('#nextBtn').click(function() {
                if (!$(this).prop('disabled')) {
                    nextSlide();
                }
            });

            $('#prevBtn').click(function() {
                if (!$(this).prop('disabled')) {
                    prevSlide();
                }
            });

            // Dot navigation
            $('.dot').click(function() {
                const index = $(this).data('index');
                goToSlide(index);
            });

            // Keyboard navigation
            $(document).keydown(function(e) {
                if (e.keyCode === 37 && currentIndex > 0) { // Left arrow
                    prevSlide();
                } else if (e.keyCode === 39 && currentIndex < totalCards - 1) { // Right arrow
                    nextSlide();
                } else if (e.keyCode === 13) { // Enter
                    $('.my-card.active').trigger('click');
                }
            });

            // Touch/swipe support for mobile (non-infinite)
            let startX = 0;
            let endX = 0;

            $('.card-carousel').on('touchstart', function(e) {
                startX = e.originalEvent.touches[0].clientX;
            });

            $('.card-carousel').on('touchend', function(e) {
                endX = e.originalEvent.changedTouches[0].clientX;
                handleSwipe();
            });

            function handleSwipe() {
                const threshold = 50;
                const diff = startX - endX;

                if (Math.abs(diff) > threshold) {
                    if (diff > 0 && currentIndex < totalCards - 1) {
                        nextSlide(); // Swipe left (next)
                    } else if (diff < 0 && currentIndex > 0) {
                        prevSlide(); // Swipe right (prev)
                    }
                }
            }

            // Start carousel with Layout 1 as default
            initCarousel();
        });

        // Popup functionality dengan preview layout
        let selectedLayout = '';

        // Data layout untuk preview
        const layoutData = {
            'layout1': {
                title: 'Layout 1',
                description: 'Photo Strip (2 Photos)',
                photoCount: '📸 2 foto',
                image: '../assets/layouts/layout1.png'
            },
            'layout2': {
                title: 'Layout 2',
                description: 'Photo Grid (4 Photos)',
                photoCount: '📸 4 foto',
                image: '../assets/layouts/layout2.png'
            },
            'layout3': {
                title: 'Layout 3',
                description: 'Photo Grid (6 Photos)',
                photoCount: '📸 6 foto',
                image: '../assets/layouts/layout3.png'
            },
            'layout4': {
                title: 'Layout 4',
                description: 'Photo Grid (8 Photos)',
                photoCount: '📸 8 foto',
                image: '../assets/layouts/layout4.png'
            },
            'layout5': {
                title: 'Layout 5',
                description: 'Photo Grid (6 Photos)',
                photoCount: '📸 6 foto',
                image: '../assets/layouts/layout5.png'
            },
            'layout6': {
                title: 'Layout 6',
                description: 'Photo Grid (4 Photos)',
                photoCount: '📸 4 foto',
                image: '../assets/layouts/layout6.png'
            }
        };
        // Function to show layout preview in popup
        function showLayoutPreview(layoutKey) {
            const layout = layoutData[layoutKey];
            if (!layout) {
                console.error('Layout data not found for:', layoutKey);
                return;
            }

            // Update preview content
            document.getElementById('previewLayoutImage').src = layout.image;
            document.getElementById('previewLayoutTitle').textContent = layout.title;
            document.getElementById('previewLayoutDesc').textContent = layout.description;
            document.getElementById('previewPhotoCount').textContent = layout.photoCount;

            // Show popup
            document.getElementById('layoutPopup').style.display = 'flex';
        }

        // Close button handlers
        document.getElementById('closeBtn').addEventListener('click', function() {
            document.getElementById('layoutPopup').style.display = 'none';
        });

        document.getElementById('cancelBtn').addEventListener('click', function() {
            document.getElementById('layoutPopup').style.display = 'none';
        });

        // Close popup when clicking outside
        document.getElementById('layoutPopup').addEventListener('click', function(e) {
            if (e.target === this) {
                this.style.display = 'none';
            }
        });

        // Confirm button - select layout and transition state
        document.getElementById('confirmBtn').addEventListener('click', function() {
            // Show loading state
            this.innerHTML = '<span style="margin-right: 8px;">⏳</span> Memproses...';
            this.disabled = true;

            // First, select layout and update session state
            fetch('../api-fetch/select_layout.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        layout: selectedLayout
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Create photo session
                        return fetch('../api-fetch/create_photo_session.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                layout: selectedLayout
                            })
                        });
                    } else {
                        throw new Error('Failed to select layout: ' + data.error);
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Navigate to appropriate canvas page
                        let targetPage = '';
                        switch (selectedLayout) {
                            case 'layout1':
                                targetPage = 'canvasLayout1.php';
                                break;
                            case 'layout2':
                                targetPage = 'canvasLayout2.php';
                                break;
                            case 'layout3':
                                targetPage = 'canvasLayout3.php';
                                break;
                            case 'layout4':
                                targetPage = 'canvasLayout4.php';
                                break;
                            case 'layout5':
                                targetPage = 'canvasLayout5.php';
                                break;
                            case 'layout6':
                                targetPage = 'canvasLayout6.php';
                                break;
                            default:
                                targetPage = 'canvasLayout2.php';
                                break;
                        }
                        window.location.href = targetPage;
                    } else {
                        alert('Error creating photo session: ' + data.error);
                        // Reset button state
                        document.getElementById('confirmBtn').innerHTML = 'Lanjutkan';
                        document.getElementById('confirmBtn').disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error: ' + error.message);
                    // Reset button state
                    document.getElementById('confirmBtn').innerHTML = 'Lanjutkan';
                    document.getElementById('confirmBtn').disabled = false;
                });
        });
    </script>

    <style>
        /* Konsistensi dengan halaman lain */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(90deg, #f598a8, #f6edb2);
            min-height: 100vh;
            overflow-x: hidden;
        }

        .gradientBgCanvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, #f598a8, #f6edb2);
            z-index: -1;
        }

        .content-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem 1rem;
            position: relative;
            z-index: 1;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        #choose-layout {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        #layout-settings {
            display: flex;
            flex-direction: row;
            gap: 1.5rem;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            overflow-x: auto;
            padding: 1rem 0;
        }

        /* Carousel Container */
        .carousel-container {
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            position: relative;
            padding: 3rem 0;
        }

        /* Card Carousel Styles - 3 Cards Display */
        .card-carousel {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            height: 500px;
            overflow: hidden;
            padding: 0 4rem;
            gap: 10px;
            /* Margin 10px antar cards */
        }

        .card-carousel .my-card {
            height: 380px;
            width: 300px;
            position: relative;
            /* Changed from absolute */
            z-index: 1;
            transform: scale(0.85);
            opacity: 0.4;
            cursor: pointer;
            pointer-events: auto;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            border: 1px solid rgba(255, 255, 255, 0.2);
            display: none;
            /* Hide by default */
            flex-direction: column;
            align-items: center;
            gap: 1rem;
            overflow: hidden;
            margin: 0 10px;
            /* Margin 10px */
        }

        .card-carousel .my-card::after {
            display: none;
        }

        /* Show only 3 cards - prev, active, next */
        .card-carousel .my-card.prev,
        .card-carousel .my-card.active,
        .card-carousel .my-card.next {
            display: flex;
            /* Show these cards */
        }

        /* Active card (center) - Layout 1 by default */
        .card-carousel .my-card.active {
            z-index: 3;
            height: 420px;
            width: 340px;
            transform: scale(1);
            opacity: 1;
            background: rgba(255, 255, 255, 0.98);
            /* border: 2px solid rgba(0, 123, 255, 0.3); */
            border-radius: 25px;
            padding: 2.5rem;
            gap: 2rem;
        }

        /* Previous and next cards - visible with lower opacity */
        .card-carousel .my-card.prev,
        .card-carousel .my-card.next {
            z-index: 2;
            transform: scale(0.85);
            opacity: 0.4;
        }

        /* Navigation - Updated for non-infinite scroll */
        .carousel-nav {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 4rem;
            margin-top: 4rem;
        }

        .nav-btn {
            background: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            font-size: 1.8rem;
            color: #007bff;
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .nav-btn:hover {
            background: rgba(255, 255, 255, 1);
            transform: scale(1.15);
        }

        .nav-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: scale(1);
        }

        .carousel-dots {
            display: flex;
            gap: 1rem;
        }

        .dot {
            width: 15px;
            height: 15px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .dot.active {
            background: white;
            transform: scale(1.3);
        }

        .layout-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border-radius: 25px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            border: 2px solid rgba(255, 255, 255, 0.3);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1.5rem;
            min-width: 250px;
            flex-shrink: 0;
            cursor: pointer;
        }

        .layout-card:hover {
            transform: translateY(-10px);
            background: rgba(255, 255, 255, 1);
            /* border-color: rgba(0, 123, 255, 0.5); */
        }

        .layout-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.6s ease;
        }

        .layout-card:hover::before {
            left: 100%;
        }

        .custom-heading {
            color: white;
            font-size: 3.2rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 0.8rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            text-transform: uppercase;
            letter-spacing: 3px;
        }

        .layout-subtext {
            color: white;
            font-size: 1.2rem;
            text-align: center;
            margin-bottom: 4rem;
            font-weight: 500;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
        }

        .layout-img {
            width: 180px;
            height: 140px;
            object-fit: contain;
            border-radius: 15px;
            transition: all 0.3s ease;
            background: white;
            padding: 0.8rem;
        }

        .my-card.active .layout-img {
            width: 220px;
            height: 180px;
        }

        .layout-info {
            width: 100%;
            text-align: center;
        }

        .layout-label {
            font-size: 1.2rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 0.5rem;
            opacity: 0.8;
        }

        .my-card.active .layout-label {
            font-size: 1.8rem;
            color: #007bff;
            opacity: 1;
        }

        .layout-description {
            color: #666;
            font-size: 0.9rem;
            margin: 0.2rem 0;
            font-weight: 500;
            opacity: 0.7;
            line-height: 1.4;
            text-align: center;
        }

        .my-card.active .layout-description {
            font-size: 1.1rem;
            color: #333;
            opacity: 1;
        }

        /* Layout preview images */
        .card-carousel .my-card img {
            width: 80%;
            height: auto;
            border-radius: 12px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            opacity: 0.8;
            margin: 1rem 0;
        }

        .card-carousel .my-card.active img {
            width: 90%;
            opacity: 1;
            /* border: 2px solid rgba(0, 123, 255, 0.3); */
        }

        /* Popup styling improvements */
        .popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            backdrop-filter: blur(5px);
        }

        .popup-content {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            padding: 3rem;
            border-radius: 25px;
            text-align: center;
            max-width: 600px;
            margin: 0 1rem;
            position: relative;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .popup-content h3 {
            color: #333;
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 1.2rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .popup-content p {
            color: #666;
            font-size: 1.2rem;
            margin-bottom: 2rem;
        }

        .close-btn {
            position: absolute;
            top: 25px;
            right: 30px;
            background: none;
            border: none;
            font-size: 35px;
            cursor: pointer;
            color: #999;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .close-btn:hover {
            color: #333;
            transform: scale(1.1);
        }

        /* Layout Preview Styles */
        .layout-preview-container {
            display: flex;
            align-items: center;
            gap: 2rem;
            margin: 2rem 0;
            padding: 2rem;
            background: rgba(248, 249, 250, 0.8);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            border: 2px solid rgba(233, 236, 239, 0.5);
        }

        .preview-image-wrapper {
            flex-shrink: 0;
        }

        .preview-layout-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 15px;
            border: 2px solid rgba(222, 226, 230, 0.5);
        }

        .layout-info {
            flex: 1;
            text-align: left;
        }

        .layout-info h4 {
            margin: 0 0 0.8rem 0;
            color: #495057;
            font-size: 1.6rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .layout-info p {
            margin: 0 0 1.2rem 0;
            color: #6c757d;
            font-size: 1.2rem;
        }

        .layout-specs {
            display: flex;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        .layout-specs span {
            background: linear-gradient(135deg, #e7f3ff 0%, #d4edff 100%);
            color: #0056b3;
            padding: 0.6rem 1.5rem;
            border-radius: 25px;
            font-size: 1.1rem;
            font-weight: 600;
        }

        .popup-buttons {
            display: flex;
            gap: 1.5rem;
            margin-top: 2.5rem;
            justify-content: center;
        }

        .btn-primary,
        .btn-secondary {
            padding: 1.2rem 3rem;
            border: none;
            border-radius: 15px;
            cursor: pointer;
            font-weight: 600;
            font-size: 1.2rem;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #0056b3 0%, #004085 100%);
            transform: translateY(-3px);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #6c757d 0%, #545b62 100%);
            color: white;
        }

        .btn-secondary:hover {
            background: linear-gradient(135deg, #545b62 0%, #495057 100%);
            transform: translateY(-3px);
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .content-container {
                padding: 1rem 0.5rem;
                height: auto;
                min-height: 100vh;
                max-width: 100%;
            }

            .custom-heading {
                font-size: 2.5rem;
                margin-bottom: 0.5rem;
                letter-spacing: 2px;
            }

            .layout-subtext {
                font-size: 1.1rem;
                margin-bottom: 2.5rem;
            }

            .carousel-container {
                max-width: 100%;
                padding: 2rem 0;
            }

            .card-carousel {
                height: 350px;
                padding: 0 2rem;
                gap: 5px;
                /* Smaller gap on mobile */
            }

            .card-carousel .my-card {
                height: 300px;
                width: 240px;
                padding: 1.5rem;
                margin: 0 5px;
                /* Smaller margin on mobile */
            }

            .card-carousel .my-card.active {
                height: 320px;
                width: 260px;
            }

            .layout-img {
                width: 140px;
                height: 110px;
            }

            .my-card.active .layout-img {
                width: 170px;
                height: 140px;
            }

            .nav-btn {
                width: 50px;
                height: 50px;
                font-size: 1.5rem;
            }

            .carousel-nav {
                gap: 2.5rem;
                margin-top: 3rem;
            }

            .dot {
                width: 12px;
                height: 12px;
            }

            #layout-settings {
                flex-direction: column;
                gap: 1rem;
                max-width: 400px;
            }

            .layout-card {
                min-width: auto;
                width: 100%;
                padding: 1.2rem;
            }

            .layout-label {
                font-size: 1.1rem;
            }

            .layout-description {
                font-size: 0.85rem;
            }

            .popup-content {
                max-width: 95%;
                padding: 2rem;
            }

            .layout-preview-container {
                flex-direction: column;
                text-align: center;
                gap: 1.5rem;
                padding: 1.5rem;
            }

            .popup-buttons {
                flex-direction: column;
                gap: 1rem;
            }

            .btn-primary,
            .btn-secondary {
                width: 100%;
                padding: 1rem;
                font-size: 1.1rem;
            }
        }

        @media (max-width: 480px) {
            .content-container {
                padding: 1rem 0.5rem;
            }

            .custom-heading {
                font-size: 2rem;
                letter-spacing: 1px;
            }

            .layout-subtext {
                font-size: 1rem;
                margin-bottom: 2rem;
            }

            .card-carousel {
                height: 300px;
                padding: 0 1rem;
                gap: 3px;
            }

            .card-carousel .my-card {
                height: 260px;
                width: 200px;
                padding: 1.2rem;
                gap: 1rem;
                margin: 0 3px;
            }

            .card-carousel .my-card.active {
                height: 280px;
                width: 220px;
            }

            .layout-img {
                width: 120px;
                height: 90px;
            }

            .my-card.active .layout-img {
                width: 150px;
                height: 120px;
            }

            .layout-label {
                font-size: 1.1rem;
            }

            .layout-description {
                font-size: 0.9rem;
            }

            .nav-btn {
                width: 45px;
                height: 45px;
                font-size: 1.3rem;
            }

            .carousel-nav {
                gap: 2rem;
                margin-top: 2.5rem;
            }

            #layout-settings {
                max-width: 300px;
            }

            .layout-card {
                padding: 1rem;
            }
        }
    </style>

    <!-- Session Timer Script -->
    <script src="../includes/session-timer.js"></script>

    <script>
        // Handle layout selection with session management
        document.addEventListener('DOMContentLoaded', function() {
            // Custom timer expired handler for layout page
            if (window.sessionTimer) {
                window.sessionTimer.onExpired = function(page) {
                    // Close popup if open
                    document.getElementById('layoutPopup').style.display = 'none';

                    // Disable all layout buttons
                    const layoutButtons = document.querySelectorAll('.layout-card');
                    layoutButtons.forEach(button => {
                        button.style.pointerEvents = 'none';
                        button.style.opacity = '0.5';
                    });

                    // Show popup with reset option
                    alert('Sesi Anda telah berakhir. Silakan mulai ulang dari pembayaran.');
                    window.location.href = '/';
                };
            }
        });
    </script>

    <?php PWAHelper::addPWAScript(); ?>
</body>

</html>