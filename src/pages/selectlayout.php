<?php
session_start();

// Validasi session payment
if (!isset($_SESSION['payment_expired_time']) || time() > $_SESSION['payment_expired_time']) {
    // Session payment expired atau tidak ada
    header("Location: selectpayment.php");
    exit();
}

if (!isset($_SESSION['payment_completed']) || $_SESSION['payment_completed'] !== true) {
    // Payment belum selesai
    header("Location: payment-qris.php");
    exit();
}
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
            <h1 class="custom-heading layout-heading">choose your layout</h1>
            <p class="layout-subtext">NOTE: you have 3 seconds for each shot</p>
            <div class="gradientBgCanvas"></div>
            <div id="layout-settings">

                <div class="layout-contents">
                    <button class="layout-holder" id="layout1Btn">
                        <img src="../assets/layouts/layout1.png" class="layout-img" alt="layout 1" loading="eager">
                    </button>
                    <h2 class="layout-label">Layout 1</h2>
                    <div>
                        <p class="layout-description">Photo Strip</p>
                        <p class="layout-description">(2 Photos)</p>
                    </div>
                </div>
                <div class="layout-contents">
                    <button class="layout-holder" id="layout2Btn">
                        <img src="../assets/layouts/layout2.png" class="layout-img" alt="layout 2" loading="eager">
                    </button>
                    <h2 class="layout-label">Layout 2</h2>
                    <div>
                        <p class="layout-description">Photo Grid</p>
                        <p class="layout-description">(4 Photos)</p>
                    </div>
                </div>
                <div class="layout-contents">
                    <button class="layout-holder" id="layout3Btn">
                        <img src="../assets/layouts/layout3.png" class="layout-img" alt="layout 3" loading="eager">
                    </button>
                    <h2 class="layout-label">Layout 3</h2>
                    <div>
                        <p class="layout-description">Photo Grid</p>
                        <p class="layout-description">(6 Photos)</p>
                    </div>
                </div>
                <div class="layout-contents">
                    <button class="layout-holder" id="layout4Btn">
                        <img src="../assets/layouts/layout4.png" class="layout-img" alt="layout 4" loading="eager">
                    </button>
                    <h2 class="layout-label">Layout 4</h2>
                    <div>
                        <p class="layout-description">Photo Grid</p>
                        <p class="layout-description">(8 Photos)</p>
                    </div>
                </div>
                <div class="layout-contents">
                    <button class="layout-holder" id="layout5Btn">
                        <img src="../assets/layouts/layout5.png" class="layout-img" alt="layout 5" loading="eager">
                    </button>
                    <h2 class="layout-label">Layout 5</h2>
                    <div>
                        <p class="layout-description">Photo Grid</p>
                        <p class="layout-description">(6 Photos)</p>
                    </div>
                </div>
                <div class="layout-contents">
                    <button class="layout-holder" id="layout6Btn">
                        <img src="../assets/layouts/layout6.png" class="layout-img" alt="layout 6" loading="eager">
                    </button>
                    <h2 class="layout-label">Layout 6</h2>
                    <div>
                        <p class="layout-description">Photo Grid</p>
                        <p class="layout-description">(4 Photos)</p>
                    </div>
                </div>

                <!-- Original Canvas Options -->
                <div class="layout-contents">
                    <button class="layout-holder" id="canvasBtn">
                        <img src="../assets/layouts/thinBorders1.2.png" class="layout-img" alt="canvas" loading="eager">
                    </button>
                    <h2 class="layout-label">Canvas</h2>
                    <div>
                        <p class="layout-description">Original Frame</p>
                        <p class="layout-description">(1 Photo)</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Popup Confirmation -->
    <div id="layoutPopup" class="popup-overlay" style="display: none;">
        <div class="popup-content">
            <button id="closeBtn" class="close-btn">&times;</button>
            <h3>Konfirmasi Layout</h3>
            <p>Anda akan mulai sesi foto dengan layout yang dipilih.</p>
            <div class="session-info">
                <p><strong>⏱️ Sesi Foto: 7 Menit</strong></p>
                <p><strong>✏️ Sesi Edit Foto: 3 Menit</strong></p>
            </div>
            <div class="popup-buttons">
                <button id="confirmBtn" class="btn-primary">Lanjutkan</button>
            </div>
        </div>
    </div>

    <script src="./layout.js"></script>
    <script>
        // Popup functionality
        let selectedLayout = '';
        
        // Add click event listeners to all layout buttons
        document.querySelectorAll('.layout-holder').forEach(button => {
            button.addEventListener('click', function() {
                selectedLayout = this.id.replace('Btn', '');
                document.getElementById('layoutPopup').style.display = 'flex';
            });
        });
        
        // Cancel button
        document.getElementById('closeBtn').addEventListener('click', function() {
            document.getElementById('layoutPopup').style.display = 'none';
        });
        
        // Confirm button - create new session and navigate
        document.getElementById('confirmBtn').addEventListener('click', function() {
            // Create photo session
            fetch('../api-fetch/create_photo_session.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ layout: selectedLayout })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Navigate to appropriate canvas page
                    let targetPage = '';
                    switch(selectedLayout) {
                        case 'layout1': targetPage = 'canvasLayout1.php'; break;
                        case 'layout2': targetPage = 'canvasLayout2.php'; break;
                        case 'layout3': targetPage = 'canvasLayout3.php'; break;
                        case 'layout4': targetPage = 'canvasLayout4.php'; break;
                        case 'layout5': targetPage = 'canvasLayout5.php'; break;
                        case 'layout6': targetPage = 'canvasLayout6.php'; break;
                        case 'canvas': targetPage = 'canvas.php'; break;
                        case 'canvas2': targetPage = 'canvas2.php'; break;
                        case 'canvas4': targetPage = 'canvas4.php'; break;
                        case 'canvas6': targetPage = 'canvas6.php'; break;
                        default: targetPage = 'canvas.php'; break;
                    }
                    window.location.href = targetPage;
                } else {
                    alert('Error: ' + data.error);
                }
            });
        });
    </script>
    
    <style>
        .popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        
        .popup-content {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            text-align: center;
            max-width: 450px;
            margin: 0 1rem;
            position: relative;
        }
        
        .close-btn {
            position: absolute;
            top: 10px;
            right: 15px;
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #999;
        }
        
        .close-btn:hover {
            color: #333;
        }
        
        .session-info {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            margin: 1rem 0;
            border-left: 4px solid #007bff;
        }
        
        .session-info p {
            margin: 0.5rem 0;
            color: #495057;
        }
        
        .popup-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
            justify-content: center;
        }
        
        .btn-primary, .btn-secondary {
            padding: 0.75rem 2rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            font-size: 1rem;
        }
        
        .btn-primary {
            background: #007bff;
            color: white;
        }
        
        .btn-primary:hover {
            background: #0056b3;
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #545b62;
        }
    </style>
</body>

</html>