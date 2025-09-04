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
    <meta name="description" content="Take instant photobooth-style photos online. Customize with over 100 frame colors, add stickers and frames, and download high-quality photo strips instantly.">
    <meta name="keywords" content="photobooth, photobooth website, photobooth website tiktok, online photobooth, tiktok photobooth, webcamtoy, tiktok viral photobooth, picapica, photobooth-io">
    <?php PWAHelper::addPWAHeaders(); ?>
    <title>Photobooth | Choose Your Photo Layout</title>
    <link rel="canonical" href="https://www.photobooth-io.cc">
    <meta property="og:title" content="Photobooth | Free Online Photobooth Anytime, Anywhere">
    <meta property="og:description" content="Take instant photobooth-style photos online. Customize with over 100 frame colors, add stickers and frames, and download high-quality photo strips instantly.">
    <meta property="og:image" content="https://www.photobooth-io.cc/assets/home-mockup.png">
    <meta property="og:url" content="https://www.photobooth-io.cc">
    <meta property="og:type" content="website">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Photobooth | Free Online Photobooth Anytime, Anywhere">
    <meta name="twitter:description" content="Take instant photobooth-style photos online. Customize with over 100 frame colors, add stickers and frames, and download high-quality photo strips instantly.">
    <meta name="twitter:image" content="https://www.photobooth-io.cc/assets/home-mockup.png">
    <link rel="stylesheet" href="../../static/css/main.css?v=<?php echo time(); ?>" />
    <link rel="stylesheet" href="../../static/css/responsive.css?v=<?php echo time(); ?>" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="/src/assets/icons/photobooth-new-logo.png" />
    <style>
        * {
            overflow: hidden;
        }
    </style>
</head>

<body>
    <main id="main-section">
        <section id="choose-layout">
            <div class="gradientBgCanvas"></div>
            <div class="content-container">
                <?php if (isset($_SESSION['is_developer_session']) && $_SESSION['is_developer_session']): ?>
                    <div style="position: fixed; top: 10px; right: 10px; background: var(--pink-primary); color: white; padding: 5px 15px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; z-index: 1000;">
                    </div>
                <?php endif; ?>
                <h1 class="custom-heading layout-heading">Silahkan pilih layout</h1>
                <div class="carousel-containesr">
                    <div class="card-carousel" id="layout-carousel">
                        <button class="layout-card" id="layout1Btn" data-layout="layout1">
                            <img src="../assets/layouts/layout1.png" class="layout-img" alt="layout 1" loading="eager">
                            <div class="layout-info">
                                <h5 class="layout-label">Layout 1</h5>
                                <p class="layout-description">Photo Strip (2 Photos)</p>
                            </div>
                        </button>
                        <button class="layout-card" id="layout2Btn" data-layout="layout2">
                            <img src="../assets/layouts/layout2.png" class="layout-img" alt="layout 2" loading="eager">
                            <div class="layout-info">
                                <h5 class="layout-label">Layout 2</h5>
                                <p class="layout-description">Photo Grid (4 Photos)</p>
                            </div>
                        </button>
                        <button class="layout-card" id="layout3Btn" data-layout="layout3">
                            <img src="../assets/layouts/layout3.png" class="layout-img" alt="layout 3" loading="eager">
                            <div class="layout-info">
                                <h5 class="layout-label">Layout 3</h5>
                                <p class="layout-description">Photo Grid (6 Photos)</p>
                            </div>
                        </button>
                        <!-- <button class="layout-card" id="layout4Btn" data-layout="layout4">
                            <img src="../assets/layouts/layout4.png" class="layout-img" alt="layout 4" loading="eager">
                            <div class="layout-info">
                                <h5 class="layout-label">Layout 4</h5>
                                <p class="layout-description">Photo Grid (8 Photos)</p>
                            </div>
                        </button>
                        <button class="layout-card" id="layout5Btn" data-layout="layout5">
                            <img src="../assets/layouts/layout5.png" class="layout-img" alt="layout 5" loading="eager">
                            <div class="layout-info">
                                <h5 class="layout-label">Layout 5</h5>
                                <p class="layout-description">Photo Grid (6 Photos)</p>
                            </div>
                        </button> -->
                        <!-- <button class="layout-card" id="layout6Btn" data-layout="layout6">
                            <img src="../assets/layouts/layout6.png" class="layout-img" alt="layout 6" loading="eager">
                            <div class="layout-info">
                                <h5 class="layout-label">Layout 6</h5>
                                <p class="layout-description">Photo Grid (4 Photos)</p>
                            </div>
                        </button> -->
                    </div>
                    <div class="carousel-nav">
                        <button class="nav-btn prev-btn" id="prevBtn" style="padding: 0.7rem;">‹</button>
                        <div class="carousel-dots">
                            <span class="dot active" data-index="0"></span>
                            <span class="dot" data-index="1"></span>
                            <span class="dot" data-index="2"></span>
                            <span class="dot" data-index="3"></span>
                            <span class="dot" data-index="4"></span>
                            <span class="dot" data-index="5"></span>
                        </div>
                        <button class="nav-btn next-btn" id="nextBtn" style="padding: 0.7rem;">›</button>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <div id="layoutPopup" class="popup-overlay" style="display: none;">
        <div class="popup-content">
            <button id="closeBtn" class="close-btn" style="padding: 0rem;">&times;</button>
            <h3>Konfirmasi Layout</h3>
            <p>Apakah Anda yakin memilih layout ini?</p>
            <div class="layout-preview-container">
                <img id="previewLayoutImage" src="" alt="Layout Preview" class="preview-layout-img">
                <div class="layout-info">
                    <h4 id="previewLayoutTitle">Layout 1</h4>
                    <p id="previewLayoutDesc">Photo Strip (2 Photos)</p>
                    <div class="layout-specs">
                        <span id="previewPhotoCount">📸 2 foto</span>
                    </div>
                </div>
            </div>
            <div class="popup-buttons">
                <button id="cancelBtn" class="btn-secondary" style="padding: 0.7rem;">Batal</button>
                <button id="confirmBtn" class="btn-primary" style="padding: 0.7rem;">Lanjutkan</button>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            let currentIndex = 1;
            const $cards = $('.layout-card');
            const totalCards = $cards.length;

            function initCarousel() {
                currentIndex = 1;
                updateCarousel();
                updateNavButtons();
            }

            function updateCarousel() {
                $cards.removeClass('active prev next');
                $cards.eq(currentIndex).addClass('active');
                if (currentIndex > 0) {
                    $cards.eq(currentIndex - 1).addClass('prev');
                }
                if (currentIndex < totalCards - 1) {
                    $cards.eq(currentIndex + 1).addClass('next');
                }
                $('.dot').removeClass('active');
                $('.dot').eq(currentIndex).addClass('active');
            }

            function updateNavButtons() {
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

            $('.layout-card').click(function() {
                const clickedIndex = $(this).index();
                if ($(this).hasClass('active')) {
                    selectedLayout = $(this).data('layout');
                    showLayoutPreview(selectedLayout);
                } else if ($(this).hasClass('next') && currentIndex < totalCards - 1) {
                    nextSlide();
                } else if ($(this).hasClass('prev') && currentIndex > 0) {
                    prevSlide();
                }
            });

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

            $('.dot').click(function() {
                const index = $(this).data('index');
                goToSlide(index);
            });

            $(document).keydown(function(e) {
                if (e.keyCode === 37 && currentIndex > 0) {
                    prevSlide();
                } else if (e.keyCode === 39 && currentIndex < totalCards - 1) {
                    nextSlide();
                } else if (e.keyCode === 13) {
                    $('.layout-card.active').trigger('click');
                }
            });

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
                        nextSlide();
                    } else if (diff < 0 && currentIndex > 0) {
                        prevSlide();
                    }
                }
            }

            initCarousel();
        });

        let selectedLayout = '';
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

        function showLayoutPreview(layoutKey) {
            const layout = layoutData[layoutKey];
            if (!layout) {
                console.error('Layout data not found for:', layoutKey);
                return;
            }
            document.getElementById('previewLayoutImage').src = layout.image;
            document.getElementById('previewLayoutTitle').textContent = layout.title;
            document.getElementById('previewLayoutDesc').textContent = layout.description;
            document.getElementById('previewPhotoCount').textContent = layout.photoCount;
            document.getElementById('layoutPopup').style.display = 'flex';
        }

        document.getElementById('closeBtn').addEventListener('click', function() {
            document.getElementById('layoutPopup').style.display = 'none';
        });

        document.getElementById('cancelBtn').addEventListener('click', function() {
            document.getElementById('layoutPopup').style.display = 'none';
        });

        document.getElementById('layoutPopup').addEventListener('click', function(e) {
            if (e.target === this) {
                this.style.display = 'none';
            }
        });

        document.getElementById('confirmBtn').addEventListener('click', function() {
            this.innerHTML = '<span style="margin-right: 8px;">⏳</span> Memproses...';
            this.disabled = true;
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
                        document.getElementById('confirmBtn').innerHTML = 'Lanjutkan';
                        document.getElementById('confirmBtn').disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error: ' + error.message);
                    document.getElementById('confirmBtn').innerHTML = 'Lanjutkan';
                    document.getElementById('confirmBtn').disabled = false;
                });
        });
    </script>
    <style>
        button:not(.buttonFrames):not(.buttonBgFrames) {
            border: none;
            padding: 2rem;
            border-radius: var(--radius-md);
            cursor: pointer;
        }
        .content-container {
            max-width: 1200px; /* Reduced from 1400px */
            margin: 0 auto;
            position: relative;
            z-index: 1;
            height: calc(100vh); /* Constrain height */
            display: flex;
            flex-direction: column;
            justify-content: center;
            overflow-y: auto; /* Handle potential overflow */
        }

        #choose-layout {
            min-height: calc(100vh - 100px); /* Reduced to fit */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .card-carousel {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            height: 460px; /* Reduced from 520px */
            overflow: hidden;
            padding: 0 2rem; /* Reduced from 4rem */
            gap: 10px; /* Reduced from 15px */
        }

        .layout-card {
            height: 360px; /* Reduced from 400px */
            width: 280px; /* Reduced from 320px */
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            padding: 2rem; /* Reduced from 2rem */
            text-align: center;
            transition: all 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            border: 2px solid var(--pink-secondary);
            display: none;
            flex-direction: column;
            align-items: center;
            gap: 1rem; /* Reduced from 1.5rem */
            margin: 0 8px; /* Reduced from 10px */
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .layout-card.prev,
        .layout-card.active,
        .layout-card.next {
            display: flex;
        }

        .layout-card.active {
            height: 400px; /* Reduced from 440px */
            width: 320px; /* Reduced from 360px */
            transform: scale(1);
            opacity: 1;
            background: rgba(255, 255, 255, 0.98);
            border: 2px solid var(--pink-primary);
            border-radius: 25px;
            padding: 2rem; /* Reduced from 2.5rem */
            box-shadow: 0 8px 20px rgba(233, 30, 99, 0.2);
        }

        .layout-card.prev,
        .layout-card.next {
            transform: scale(0.9);
            opacity: 0.6;
        }

        .layout-card:hover {
            transform: translateY(-10px);
        }

        .carousel-nav {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 3rem; /* Reduced from 4rem */
            margin-top: 2rem; /* Reduced from 3rem */
        }

        .nav-btn {
            background: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 50%;
            width: 50px; /* Reduced from 60px */
            font-size: 1.5rem; /* Reduced from 1.8rem */
            color: var(--pink-primary);
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .nav-btn:hover {
            background: rgba(255, 255, 255, 1);
            transform: scale(1.15);
            box-shadow: 0 4px 15px rgba(233, 30, 99, 0.2);
        }

        .nav-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: scale(1);
        }

        .carousel-dots {
            display: flex;
            gap: 0.8rem; /* Reduced from 1rem */
        }

        .dot {
            width: 12px; /* Reduced from 15px */
            height: 12px; /* Reduced from 15px */
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .dot.active {
            background: var(--pink-primary);
            transform: scale(1.3);
        }

        .custom-heading {
            color: white;
            font-size: 3rem; /* Reduced from 3.5rem */
            font-weight: 700;
            text-align: center;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            text-transform: uppercase;
            letter-spacing: 2px; /* Reduced from 3px */
        }

        .layout-img {
            width: 200px; /* Reduced from 240px */
            height: 150px; /* Reduced from 180px */
            object-fit: contain;
            border-radius: 15px;
            background: white;
            padding: 0.6rem; /* Reduced from 0.8rem */
            border: 1px solid var(--pink-secondary);
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .layout-card.active .layout-img {
            width: 240px; /* Reduced from 280px */
            height: 240px; /* Reduced from 280px */
            object-fit: contain;
            border-radius: 18px;
            background: white;
            padding: 0.8rem; /* Reduced from 1.2rem */
            border: 2px solid var(--pink-primary);
            box-shadow: 0 8px 24px rgba(233, 30, 99, 0.15);
            transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .layout-info {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.15rem; /* Reduced from 0.2rem */
            margin-top: 0.4rem; /* Reduced from 0.5rem */
            padding: 0.4rem 0.6rem; /* Reduced from 0.5rem 0.8rem */
            background: transparent;
            border-radius: 12px;
            border: none;
            width: 100%;
            box-sizing: border-box;
        }

        .layout-label {
            font-size: 0.9rem; /* Reduced from 1rem */
            font-weight: 700;
            color: #333;
            margin-bottom: 0.1rem;
            opacity: 0.8;
            margin: 0;
        }

        .layout-card.active .layout-label {
            font-size: 1.1rem; /* Reduced from 1.2rem */
            color: var(--pink-primary);
            opacity: 1;
        }

        .layout-description {
            color: #666;
            font-size: 0.75rem; /* Reduced from 0.8rem */
            margin: 0;
            font-weight: 500;
            opacity: 0.7;
            line-height: 1.1;
            text-align: center;
        }

        .layout-card.active .layout-description {
            font-size: 0.85rem; /* Reduced from 0.9rem */
            color: #333;
            opacity: 1;
        }

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
            padding: 2rem; /* Reduced from 3rem */
            border-radius: 25px;
            text-align: center;
            max-width: 500px; /* Reduced from 600px */
            margin: 0 1rem;
            border: 2px solid var(--pink-secondary);
        }

        .popup-content h3 {
            color: var(--pink-primary);
            font-size: 1.8rem; /* Reduced from 2rem */
            font-weight: 600;
            margin-bottom: 1rem; /* Reduced from 1.2rem */
            text-transform: uppercase;
            letter-spacing: 1.2px; /* Reduced from 1.5px */
        }

        .popup-content p {
            color: #666;
            font-size: 1.1rem; /* Reduced from 1.2rem */
            margin-bottom: 1.5rem; /* Reduced from 2rem */
        }

        .close-btn {
            position: absolute;
            top: 20px; /* Reduced from 25px */
            right: 25px; /* Reduced from 30px */
            background: none;
            border: none;
            font-size: 30px; /* Reduced from 35px */
            cursor: pointer;
            color: #999;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .close-btn:hover {
            color: var(--pink-primary);
        }

        .layout-preview-container {
            display: flex;
            align-items: center;
            gap: 1.5rem; /* Reduced from 2rem */
            margin: 1.5rem 0; /* Reduced from 2rem */
            padding: 1.5rem; /* Reduced from 2rem */
            background: rgba(248, 249, 250, 0.8);
            border-radius: 20px;
            border: 2px solid var(--pink-secondary);
        }

        .preview-layout-img {
            width: 120px; /* Reduced from 150px */
            height: 160px; /* Reduced from 200px */
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            border-radius: 15px;
            box-sizing: border-box;
        }

        .layout-info {
            flex: 1;
        }

        .layout-info h2 {
            margin: 0 0 0.6rem 0; /* Reduced from 0.8rem */
            color: var(--pink-primary);
            font-size: 1.4rem; /* Reduced from 1.6rem */
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.2px; /* Reduced from 1.5px */
        }

        .layout-info p {
            margin: 0 0 1rem 0; /* Reduced from 1.2rem */
            color: #6c757d;
            font-size: 1.1rem; /* Reduced from 1.2rem */
        }

        .layout-specs span {
            background: var(--pink-secondary);
            color: var(--pink-primary);
            padding: 0.5rem 1.2rem; /* Reduced from 0.6rem 1.5rem */
            border-radius: 25px;
            font-size: 1rem; /* Reduced from 1.1rem */
            font-weight: 600;
        }

        .popup-buttons {
            display: flex;
            gap: 1.2rem; /* Reduced from 1.5rem */
            margin-top: 2rem; /* Reduced from 2.5rem */
            justify-content: center;
        }

        .btn-primary {
            padding: 1rem 2.5rem; /* Reduced from 1.2rem 3rem */
            border: none;
            border-radius: 15px;
            cursor: pointer;
            font-weight: 600;
            font-size: 1.1rem; /* Reduced from 1.2rem */
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1.2px; /* Reduced from 1.5px */
            background: var(--pink-primary);
            color: white;
        }

        .btn-primary:hover {
            background: #C2185B;
            transform: translateY(-3px);
            box-shadow: 0 4px 15px rgba(233, 30, 99, 0.3);
        }

        .btn-secondary {
            padding: 1rem 2.5rem; /* Reduced from 1.2rem 3rem */
            border: 2px solid var(--pink-secondary);
            border-radius: 15px;
            cursor: pointer;
            font-weight: 600;
            font-size: 1.1rem; /* Reduced from 1.2rem */
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1.2px; /* Reduced from 1.5px */
            background: white;
            color: var(--pink-primary);
        }

        .btn-secondary:hover {
            background: var(--pink-primary);
            border: 2px solid white;
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 4px 15px rgba(233, 30, 99, 0.3);
        }

        .session-expired-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 10000;
            backdrop-filter: blur(5px);
        }

        .session-expired-content {
            background: #4a4a4a;
            color: white;
            padding: 1.5rem 2rem; /* Reduced from 2rem 2.5rem */
            border-radius: 15px;
            text-align: left;
            max-width: 350px; /* Reduced from 400px */
            margin: 0 1rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .session-expired-content h3 {
            font-size: 1rem; /* Reduced from 1.1rem */
            font-weight: 600;
            margin-bottom: 1rem; /* Reduced from 1.2rem */
            color: #e0e0e0;
        }

        .session-expired-content p {
            font-size: 0.9rem; /* Reduced from 1rem */
            margin-bottom: 1.5rem; /* Reduced from 2rem */
            line-height: 1.3; /* Reduced from 1.4 */
            color: #d0d0d0;
        }

        .session-expired-btn {
            background: #5a9fd4;
            color: white;
            border: none;
            padding: 0.5rem 1.2rem; /* Reduced from 0.6rem 1.5rem */
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.85rem; /* Reduced from 0.9rem */
            font-weight: 500;
            float: right;
            transition: background 0.3s ease;
        }

        .session-expired-btn:hover {
            background: #4a8bc2;
        }

        @media (max-width: 1280px) and (max-height: 1024px) {
            .content-container {
                max-width: 1150px; /* Further reduced */
                height: calc(100vh - 80px); /* Tighter constraint */
                padding: 0 0.5rem;
            }

            #choose-layout {
                min-height: calc(100vh - 80px);
            }

            .custom-heading {
                font-size: 2.5rem; /* Further reduced */
                margin-bottom: 0.6rem;
                letter-spacing: 1.5px;
            }

            .card-carousel {
                height: 400px; /* Further reduced */
                padding: 0 1.5rem;
                gap: 8px;
            }

            .layout-card {
                height: 320px;
                width: 260px;
                padding: 1.2rem;
                margin: 0 6px;
            }

            .layout-card.active {
                height: 360px;
                width: 300px;
                padding: 1.5rem;
            }

            .layout-img {
                width: 180px;
                height: 130px;
                padding: 0.5rem;
            }

            .layout-card.active .layout-img {
                width: 220px;
                height: 220px;
                padding: 0.6rem;
            }

            .layout-info {
                gap: 0.1rem;
                margin-top: 0.3rem;
                padding: 0.3rem 0.5rem;
            }

            .layout-label {
                font-size: 0.85rem;
            }

            .layout-card.active .layout-label {
                font-size: 1rem;
            }

            .layout-description {
                font-size: 0.7rem;
            }

            .layout-card.active .layout-description {
                font-size: 0.8rem;
            }

            .carousel-nav {
                gap: 2.5rem;
                margin-top: 1.5rem;
            }

            .nav-btn {
                width: 45px;
                height: 45px;
                font-size: 1.3rem;
            }

            .dot {
                width: 10px;
                height: 10px;
            }

            .popup-content {
                max-width: 450px;
                padding: 1.5rem;
            }

            .popup-content h3 {
                font-size: 1.6rem;
                margin-bottom: 0.8rem;
                letter-spacing: 1px;
            }

            .popup-content p {
                font-size: 1rem;
                margin-bottom: 1.2rem;
            }

            .close-btn {
                top: 15px;
                right: 20px;
                font-size: 25px;
            }

            .layout-preview-container {
                gap: 1.2rem;
                margin: 1.2rem 0;
                padding: 1.2rem;
            }

            .preview-layout-img {
                width: 100px;
                height: 140px;
            }

            .layout-info h2 {
                font-size: 1.3rem;
                margin-bottom: 0.5rem;
                letter-spacing: 1px;
            }

            .layout-info p {
                font-size: 1rem;
                margin-bottom: 0.8rem;
            }

            .layout-specs span {
                padding: 0.4rem 1rem;
                font-size: 0.9rem;
            }

            .popup-buttons {
                gap: 1rem;
                margin-top: 1.5rem;
            }

            .btn-primary,
            .btn-secondary {
                padding: 0.8rem 2rem;
                font-size: 1rem;
                letter-spacing: 1px;
            }

            .session-expired-content {
                max-width: 320px;
                padding: 1.2rem 1.5rem;
            }

            .session-expired-content h3 {
                font-size: 0.9rem;
                margin-bottom: 0.8rem;
            }

            .session-expired-content p {
                font-size: 0.85rem;
                margin-bottom: 1.2rem;
                line-height: 1.2;
            }

            .session-expired-btn {
                padding: 0.4rem 1rem;
                font-size: 0.8rem;
            }
        }

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

            .card-carousel {
                height: 350px;
                padding: 0 2rem;
                gap: 5px;
            }

            .layout-card {
                height: 300px;
                width: 240px;
                padding: 1.5rem;
                margin: 0 5px;
            }

            .layout-card.active {
                height: 320px;
                width: 260px;
            }

            .layout-img {
                width: 140px;
                height: 110px;
            }

            .layout-card.active .layout-img {
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

            .preview-layout-img {
                width: 120px;
                height: 120px;
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

            .card-carousel {
                height: 300px;
                padding: 0 1rem;
                gap: 3px;
            }

            .layout-card {
                height: 260px;
                width: 200px;
                padding: 1.2rem;
                margin: 0 3px;
            }

            .layout-card.active {
                height: 280px;
                width: 220px;
            }

            .layout-img {
                width: 120px;
                height: 90px;
            }

            .layout-card.active .layout-img {
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

            .preview-layout-img {
                width: 100px;
                height: 100px;
            }
        }
    </style>
    <script src="../includes/session-timer.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.sessionTimer) {
                window.sessionTimer.onExpired = function(page) {
                    document.getElementById('layoutPopup').style.display = 'none';
                    const layoutButtons = document.querySelectorAll('.layout-card');
                    layoutButtons.forEach(button => {
                        button.style.pointerEvents = 'none';
                        button.style.opacity = '0.5';
                    });

                    // Gunakan custom popup seperti di canvas
                    showSessionExpiredModal();
                };
            }
        });

        // Tambahkan function untuk custom modal
        function showSessionExpiredModal() {
            // Buat modal dengan styling yang sama seperti di canvas
            const modal = document.createElement('div');
            modal.className = 'session-expired-modal';
            modal.innerHTML = `
                <div class="session-expired-content">
                    <h3>gofotobox.online says</h3>
                    <p>Sesi Anda telah berakhir. Silakan mulai ulang dari pembayaran.</p>
                    <button onclick="redirectToHome()" class="session-expired-btn">OK</button>
                </div>
            `;
            document.body.appendChild(modal);
        }

        function redirectToHome() {
            window.location.href = '/';
        }
    </script>
    <?php PWAHelper::addPWAScript(); ?>
</body>

</html>