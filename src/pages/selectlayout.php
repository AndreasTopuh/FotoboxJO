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
                
                <div id="layout-settings">

                <button class="layout-card" id="layout1Btn">
                    <img src="../assets/layouts/layout1.png" class="layout-img" alt="layout 1" loading="eager">
                    <div class="layout-info">
                        <h2 class="layout-label">Layout 1</h2>
                        <p class="layout-description">Photo Strip</p>
                        <p class="layout-description">(2 Photos)</p>
                    </div>
                </button>
                <button class="layout-card" id="layout2Btn">
                    <img src="../assets/layouts/layout2.png" class="layout-img" alt="layout 2" loading="eager">
                    <div class="layout-info">
                        <h2 class="layout-label">Layout 2</h2>
                        <p class="layout-description">Photo Grid</p>
                        <p class="layout-description">(4 Photos)</p>
                    </div>
                </button>
                <button class="layout-card" id="layout3Btn">
                    <img src="../assets/layouts/layout3.png" class="layout-img" alt="layout 3" loading="eager">
                    <div class="layout-info">
                        <h2 class="layout-label">Layout 3</h2>
                        <p class="layout-description">Photo Grid</p>
                        <p class="layout-description">(6 Photos)</p>
                    </div>
                </button>
                <button class="layout-card" id="layout4Btn">
                    <img src="../assets/layouts/layout4.png" class="layout-img" alt="layout 4" loading="eager">
                    <div class="layout-info">
                        <h2 class="layout-label">Layout 4</h2>
                        <p class="layout-description">Photo Grid</p>
                        <p class="layout-description">(8 Photos)</p>
                    </div>
                </button>
                <button class="layout-card" id="layout5Btn">
                    <img src="../assets/layouts/layout5.png" class="layout-img" alt="layout 5" loading="eager">
                    <div class="layout-info">
                        <h2 class="layout-label">Layout 5</h2>
                        <p class="layout-description">Photo Grid</p>
                        <p class="layout-description">(6 Photos)</p>
                    </div>
                </button>
                <button class="layout-card" id="layout6Btn">
                    <img src="../assets/layouts/layout6.png" class="layout-img" alt="layout 6" loading="eager">
                    <div class="layout-info">
                        <h2 class="layout-label">Layout 6</h2>
                        <p class="layout-description">Photo Grid</p>
                        <p class="layout-description">(4 Photos)</p>
                    </div>
                </button>
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
    <script>
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
        
        // Add click event listeners to all layout buttons
        document.querySelectorAll('.layout-card').forEach(button => {
            button.addEventListener('click', function() {
                selectedLayout = this.id.replace('Btn', '');
                showLayoutPreview(selectedLayout);
            });
        });
        
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
                body: JSON.stringify({ layout: selectedLayout })
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
                        body: JSON.stringify({ layout: selectedLayout })
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
                    switch(selectedLayout) {
                        case 'layout1': targetPage = 'canvasLayout1.php'; break;
                        case 'layout2': targetPage = 'canvasLayout2.php'; break;
                        case 'layout3': targetPage = 'canvasLayout3.php'; break;
                        case 'layout4': targetPage = 'canvasLayout4.php'; break;
                        case 'layout5': targetPage = 'canvasLayout5.php'; break;
                        case 'layout6': targetPage = 'canvasLayout6.php'; break;
                        default: targetPage = 'canvasLayout2.php'; break;
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
            max-width: 1200px;
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

        .layout-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
            border: 2px solid rgba(255, 255, 255, 0.3);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
            min-width: 200px;
            flex-shrink: 0;
            cursor: pointer;
        }

        .layout-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25);
            background: rgba(255, 255, 255, 1);
            border-color: rgba(0, 123, 255, 0.5);
        }

        .layout-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            transition: left 0.6s ease;
        }

        .layout-card:hover::before {
            left: 100%;
        }

        .custom-heading {
            color: white;
            font-size: 3rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 0.5rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .layout-subtext {
            color: white;
            font-size: 1.1rem;
            text-align: center;
            margin-bottom: 3rem;
            font-weight: 500;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
        }

        .layout-img {
            width: 150px;
            height: 120px;
            object-fit: contain;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            background: white;
            padding: 0.5rem;
        }

        .layout-info {
            width: 100%;
            text-align: center;
        }

        .layout-label {
            font-size: 1.2rem;
            font-weight: 700;
            color: #333;
            margin: 0 0 0.5rem 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .layout-description {
            color: #666;
            font-size: 0.9rem;
            margin: 0.1rem 0;
            font-weight: 500;
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
            padding: 2.5rem;
            border-radius: 20px;
            text-align: center;
            max-width: 550px;
            margin: 0 1rem;
            position: relative;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .popup-content h3 {
            color: #333;
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .popup-content p {
            color: #666;
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
        }
        
        .close-btn {
            position: absolute;
            top: 20px;
            right: 25px;
            background: none;
            border: none;
            font-size: 30px;
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
            gap: 1.5rem;
            margin: 1.5rem 0;
            padding: 1.5rem;
            background: rgba(248, 249, 250, 0.8);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            border: 2px solid rgba(233, 236, 239, 0.5);
        }
        
        .preview-image-wrapper {
            flex-shrink: 0;
        }
        
        .preview-layout-img {
            width: 130px;
            height: 130px;
            object-fit: cover;
            border-radius: 12px;
            border: 2px solid rgba(222, 226, 230, 0.5);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .layout-info {
            flex: 1;
            text-align: left;
        }
        
        .layout-info h4 {
            margin: 0 0 0.5rem 0;
            color: #495057;
            font-size: 1.4rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .layout-info p {
            margin: 0 0 1rem 0;
            color: #6c757d;
            font-size: 1.1rem;
        }
        
        .layout-specs {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .layout-specs span {
            background: linear-gradient(135deg, #e7f3ff 0%, #d4edff 100%);
            color: #0056b3;
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.95rem;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(0, 86, 179, 0.2);
        }
        
        .popup-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            justify-content: center;
        }
        
        .btn-primary, .btn-secondary {
            padding: 1rem 2.5rem;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #0056b3 0%, #004085 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 123, 255, 0.4);
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, #6c757d 0%, #545b62 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
        }
        
        .btn-secondary:hover {
            background: linear-gradient(135deg, #545b62 0%, #495057 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(108, 117, 125, 0.4);
        }
        
        /* Mobile Responsive */
        @media (max-width: 768px) {
            .content-container {
                padding: 1rem 0.5rem;
                height: auto;
                min-height: 100vh;
            }

            .custom-heading {
                font-size: 2.2rem;
                margin-bottom: 0.3rem;
            }

            .layout-subtext {
                font-size: 1rem;
                margin-bottom: 2rem;
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

            .layout-img {
                width: 120px;
                height: 100px;
            }

            .layout-label {
                font-size: 1.1rem;
            }

            .layout-description {
                font-size: 0.85rem;
            }

            .popup-content {
                max-width: 90%;
                padding: 2rem;
            }
            
            .layout-preview-container {
                flex-direction: column;
                text-align: center;
            }
            
            .popup-buttons {
                flex-direction: column;
            }
            
            .btn-primary, .btn-secondary {
                width: 100%;
                padding: 1rem;
            }
        }

        @media (max-width: 480px) {
            .content-container {
                padding: 1rem 0.5rem;
            }

            .custom-heading {
                font-size: 1.8rem;
            }

            .layout-subtext {
                font-size: 0.9rem;
                margin-bottom: 1.5rem;
            }

            #layout-settings {
                max-width: 300px;
            }

            .layout-card {
                padding: 1rem;
            }

            .layout-img {
                width: 100px;
                height: 80px;
            }

            .layout-label {
                font-size: 1rem;
            }

            .layout-description {
                font-size: 0.8rem;
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