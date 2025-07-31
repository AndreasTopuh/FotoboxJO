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
        /* 🎨 HORIZONTAL CANVAS LAYOUT 1 - CLEAN WHITE DESIGN WITH PINK THEME */
        
        /* Simple gradient background - same as index.php */
        .gradientBgCanvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: linear-gradient(90deg, #f598a8, #f6edb2);
            z-index: -1;
        }

        /* Main Container - Full Width Horizontal Layout */
        .canvas-centered {
            max-width: 100vw;
            margin: 0;
            padding: 0.5rem;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .main-content-card {
            width: 100%;
            min-width: 92vw;
            max-width: 96vw;
            height: calc(100vh - 0.5rem);
            background: rgba(255, 255, 255, 0.95) !important;
            border-radius: 8px !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1) !important;
            padding: 0.5rem !important;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        /* Back Button - Positioned Top Left */
        .back-button {
            position: absolute;
            top: 0.5rem;
            left: 0.5rem;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: #E91E63;
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.75rem;
            transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
            z-index: 10;
        }

        .back-button:hover {
            background: #C2185B;
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(233, 30, 99, 0.3);
        }

        /* Direction Section - Top Center */
        .direction-section {
            text-align: center;
            margin: 0.3rem 0 0.5rem 0;
            position: relative;
        }

        .direction-arrows {
            font-size: 1.2rem;
            color: #E91E63;
            margin-bottom: 0.2rem;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-10px);
            }
            60% {
                transform: translateY(-5px);
            }
        }

        .direction-heading {
            font-size: 1rem;
            font-weight: 700;
            color: #E91E63;
            margin: 0;
            font-family: 'Poppins', sans-serif;
        }

        /* Main Horizontal Layout */
        .horizontal-layout {
            display: flex;
            gap: 1rem;
            flex: 1;
            min-height: 0;
            height: calc(100vh - 5rem);
        }

        /* Left Section - Photo Preview & Camera */
        .left-section {
            flex: 1;
            display: flex;
            gap: 1rem;
            height: 100%;
            justify-content: center;
            align-items: center;
        }

        /* Photo Preview Container */
        .preview-container {
            width: 160px;
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
            flex-shrink: 0;
        }

        .preview-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: #E91E63;
            text-align: center;
            margin-bottom: 0.3rem;
        }

        .photo-preview-grid {
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
        }

        .photo-preview-slot {
            width: 150px;
            height: 100px;
            border: 2px dashed #E91E63;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(233, 30, 99, 0.05);
            position: relative;
            transition: all 0.3s ease;
        }

        .photo-preview-slot.filled {
            border: 2px solid #E91E63;
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
            color: #E91E63;
            font-size: 0.8rem;
            opacity: 0.7;
            text-align: center;
        }

        .retake-photo-btn {
            position: absolute;
            top: -8px;
            right: -8px;
            width: 24px;
            height: 24px;
            background: #E91E63;
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            font-size: 12px;
            font-weight: bold;
            display: none;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .photo-preview-slot.filled .retake-photo-btn {
            display: flex;
        }

        .retake-photo-btn:hover {
            background: #C2185B;
            transform: scale(1.1);
        }

        /* Camera Container */
        .camera-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100%;
        }

        .camera-title {
            font-size: 0.8rem;
            font-weight: 600;
            color: #E91E63;
            text-align: center;
            margin-bottom: 0.5rem;
        }

        #videoContainer {
            width: 640px;
            height: 480px;
            background: rgba(0, 0, 0, 0.9);
            border-radius: 16px;
            overflow: hidden;
            position: relative;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
            flex-shrink: 0;
            border: 3px solid rgba(255, 255, 255, 0.1);
        }

        video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 13px;
        }

        /* Progress Counter */
        .progress-display {
            text-align: center;
            margin-top: 0.5rem;
        }

        #progressCounter {
            font-size: 1.1rem;
            font-weight: 700;
            color: #E91E63;
            margin: 0;
        }

        /* Right Section - Controls */
        .right-section {
            width: 280px;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            flex-shrink: 0;
            height: 100%;
            overflow-y: auto;
        }

        /* Camera Settings */
        .camera-settings {
            background: rgba(233, 30, 99, 0.05);
            padding: 1rem;
            border-radius: 12px;
            border: 1px solid rgba(233, 30, 99, 0.2);
        }

        .settings-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: #E91E63;
            margin-bottom: 0.8rem;
            text-align: center;
        }

        .setting-group {
            margin-bottom: 1rem;
        }

        .setting-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #666;
            margin-bottom: 0.5rem;
            display: block;
        }

        .timer-selector {
            position: relative;
            width: 100%;
        }

        .custom-select {
            width: 100%;
            background: white;
            border: 2px solid #E91E63;
            border-radius: 8px;
            padding: 8px 12px;
            font-weight: 600;
            color: #333;
            font-size: 0.85rem;
            cursor: pointer;
            outline: none;
            transition: all 0.3s ease;
        }

        .custom-select:focus {
            border-color: #C2185B;
            box-shadow: 0 0 0 2px rgba(233, 30, 99, 0.2);
        }

        .invert-toggle {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            margin-top: 0.8rem;
        }

        #invertBtn {
            width: 32px;
            height: 32px;
            background: white;
            border: 2px solid #E91E63;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        #invertBtn:hover, #invertBtn.active {
            background: #E91E63;
        }

        #invertBtn img {
            width: 20px;
            height: 20px;
            filter: brightness(0) saturate(100%) invert(24%) sepia(85%) saturate(5755%) hue-rotate(322deg) brightness(91%) contrast(89%);
        }

        #invertBtn:hover img, #invertBtn.active img {
            filter: brightness(0) saturate(100%) invert(100%);
        }

        /* Filter Section */
        .filter-section {
            background: rgba(233, 30, 99, 0.05);
            padding: 1rem;
            border-radius: 12px;
            border: 1px solid rgba(233, 30, 99, 0.2);
        }

        .filter-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: #E91E63;
            margin-bottom: 0.8rem;
            text-align: center;
        }

        .filter-buttons-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            margin-bottom: 1rem;
            width: 100%;
            max-width: 100%;
        }

        .filterBtn {
            width: 100%;
            height: 55px;
            border: 2px solid rgba(233, 30, 99, 0.3);
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .filterBtn:hover, .filterBtn.active {
            border-color: #E91E63;
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(233, 30, 99, 0.3);
        }

        .grid-toggle {
            display: flex;
            justify-content: center;
            margin-top: 0.8rem;
        }

        #gridToggleBtn {
            background: white;
            color: #E91E63;
            border: 2px solid #E91E63;
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.75rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        #gridToggleBtn:hover, #gridToggleBtn.active {
            background: #E91E63;
            color: white;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
        }

        #startBtn {
            background: linear-gradient(135deg, #E91E63, #F48FB1);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        #startBtn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        #startBtn:hover::before {
            left: 100%;
        }

        #startBtn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(233, 30, 99, 0.4);
        }

        #startBtn:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        #retakeAllBtn {
            background: white;
            color: #E91E63;
            border: 2px solid #E91E63;
            padding: 6px 16px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        #retakeAllBtn:hover:not(:disabled) {
            background: #E91E63;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(233, 30, 99, 0.3);
        }

        #retakeAllBtn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        #doneBtn {
            background: linear-gradient(135deg, #4CAF50, #81C784);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            display: none;
        }

        #doneBtn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(76, 175, 80, 0.4);
        }

        /* Upload Button */
        .upload-section {
            margin-top: 1rem;
        }

        .uploadBtnStyling {
            width: 100%;
            background: linear-gradient(135deg, #FF9800, #FFB74D);
            color: white;
            border: none;
            padding: 12px 18px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .uploadBtnStyling:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 152, 0, 0.3);
        }

        /* Filter backgrounds untuk preview */
        #vintageFilterId { background: linear-gradient(45deg, #DAA520, #CD853F); }
        #grayFilterId { background: linear-gradient(45deg, #696969, #A9A9A9); }
        #smoothFilterId { background: linear-gradient(45deg, #FFB6C1, #FFC0CB); }
        #bnwFilterId { background: linear-gradient(45deg, #000000, #333333); }
        #sepiaFilterId { background: linear-gradient(45deg, #D2691E, #8B4513); }
        #normalFilterId { background: linear-gradient(45deg, #6495ED, #87CEEB); }

        /* Canvas specific filter effects */
        .sepia { filter: sepia(100%) hue-rotate(30deg) saturate(1.2); }
        .grayscale { filter: grayscale(100%); }
        .smooth { filter: blur(0.5px) brightness(1.1) contrast(1.1); }
        .gray { filter: grayscale(50%) contrast(1.2); }
        .vintage { filter: sepia(60%) contrast(1.3) brightness(0.9) saturate(1.4); }

        /* Messages and Overlays */
        #fullscreenMessage, #filterMessage {
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 12px 20px;
            font-size: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
        }

        #countdownText {
            background: linear-gradient(135deg, #E91E63, #F48FB1);
            border: 2px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            font-size: 36px;
            padding: 12px 20px;
            color: white;
            font-weight: bold;
        }

        #blackScreen {
            background: rgba(0, 0, 0, 0.9);
            color: white;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border-radius: 16px;
        }

        #fullscreenBtn {
            position: absolute;
            bottom: 15px;
            right: 15px;
            background: rgba(233, 30, 99, 0.9);
            border: none;
            border-radius: 10px;
            padding: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 10;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        #fullscreenBtn:hover {
            background: rgba(233, 30, 99, 1);
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(233, 30, 99, 0.4);
        }

        .fullScreenSize {
            width: 24px;
            height: 24px;
            filter: brightness(0) saturate(100%) invert(100%);
        }

        /* Grid Overlay - Simple & Perfect 3x3 Grid */
        .grid-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 5;
            display: none;
            background-image: 
                /* Horizontal lines */
                linear-gradient(to bottom, transparent calc(33.33% - 0.5px), rgba(255, 255, 255, 0.6) calc(33.33% - 0.5px), rgba(255, 255, 255, 0.6) calc(33.33% + 0.5px), transparent calc(33.33% + 0.5px)),
                linear-gradient(to bottom, transparent calc(66.66% - 0.5px), rgba(255, 255, 255, 0.6) calc(66.66% - 0.5px), rgba(255, 255, 255, 0.6) calc(66.66% + 0.5px), transparent calc(66.66% + 0.5px)),
                /* Vertical lines */
                linear-gradient(to right, transparent calc(33.33% - 0.5px), rgba(255, 255, 255, 0.6) calc(33.33% - 0.5px), rgba(255, 255, 255, 0.6) calc(33.33% + 0.5px), transparent calc(33.33% + 0.5px)),
                linear-gradient(to right, transparent calc(66.66% - 0.5px), rgba(255, 255, 255, 0.6) calc(66.66% - 0.5px), rgba(255, 255, 255, 0.6) calc(66.66% + 0.5px), transparent calc(66.66% + 0.5px));
            background-size: 100% 100%, 100% 100%, 100% 100%, 100% 100%;
        }

        .grid-overlay::before,
        .grid-overlay::after {
            display: none;
        }

        /* Fullscreen Mode */
        #videoContainer:fullscreen {
            width: 100vw !important;
            height: 100vh !important;
            max-width: none !important;
            max-height: none !important;
            border-radius: 0 !important;
            border: none !important;
            box-shadow: none !important;
            background: black !important;
        }

        #videoContainer:fullscreen video {
            width: 100vw !important;
            height: 100vh !important;
            object-fit: cover !important;
            border-radius: 0 !important;
        }

        #videoContainer:-webkit-full-screen {
            width: 100vw !important;
            height: 100vh !important;
            max-width: none !important;
            max-height: none !important;
            border-radius: 0 !important;
            border: none !important;
            box-shadow: none !important;
            background: black !important;
        }

        #videoContainer:-webkit-full-screen video {
            width: 100vw !important;
            height: 100vh !important;
            object-fit: cover !important;
            border-radius: 0 !important;
        }

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

        #videoContainer:-moz-full-screen video {
            width: 100vw !important;
            height: 100vh !important;
            object-fit: cover !important;
            border-radius: 0 !important;
        }

        /* Mobile Responsive */
        @media (max-width: 1200px) {
            .horizontal-layout {
                gap: 0.8rem;
            }
            
            .right-section {
                width: 200px;
            }
            
            #videoContainer {
                width: 580px;
                height: 435px;
            }
        }

        @media (max-width: 1024px) {
            .horizontal-layout {
                flex-direction: column;
                gap: 1rem;
                height: auto;
            }
            
            .left-section {
                flex-direction: row;
                align-items: flex-start;
                gap: 1rem;
                justify-content: center;
            }
            
            .preview-container {
                width: 180px;
                flex-shrink: 0;
            }
            
            .photo-preview-grid {
                flex-direction: column;
                gap: 0.8rem;
            }
            
            .photo-preview-slot {
                width: 160px;
                height: 110px;
            }
            
            #videoContainer {
                width: 560px;
                height: 420px;
            }
            
            .right-section {
                width: 100%;
                max-width: 100%;
                flex-direction: row;
                flex-wrap: wrap;
                gap: 1rem;
                justify-content: center;
            }
            
            .camera-settings,
            .filter-section {
                flex: 1;
                min-width: 280px;
            }
            
            .action-buttons,
            .upload-section {
                flex: 1;
                min-width: 200px;
            }
        }

        @media (max-width: 768px) {
            .canvas-centered {
                padding: 0.3rem;
            }
            
            .main-content-card {
                padding: 0.8rem !important;
                height: calc(100vh - 0.6rem);
            }
            
            .direction-heading {
                font-size: 1rem;
            }
            
            .left-section {
                flex-direction: column;
                align-items: center;
            }
            
            .preview-container {
                width: 100%;
                max-width: 100%;
            }
            
            .photo-preview-grid {
                flex-direction: row;
                justify-content: center;
                gap: 1rem;
            }
            
            .photo-preview-slot {
                width: 140px;
                height: 95px;
            }
            
            #videoContainer {
                width: 480px;
                height: 360px;
            }
            
            .right-section {
                flex-direction: column;
                max-width: 100%;
            }
            
            .camera-settings,
            .filter-section,
            .action-buttons,
            .upload-section {
                min-width: 100%;
            }
            
            .filter-buttons-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 8px;
            }
            
            .filterBtn {
                max-width: none;
                height: 45px;
            }
        }

        @media (max-width: 480px) {
            .main-content-card {
                padding: 0.5rem !important;
            }
            
            .direction-heading {
                font-size: 0.9rem;
            }
            
            .photo-preview-grid {
                gap: 0.8rem;
            }
            
            .photo-preview-slot {
                width: 120px;
                height: 80px;
            }
            
            #videoContainer {
                width: 320px;
                height: 240px;
            }
            
            .filter-buttons-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 6px;
            }
            
            .right-section {
                gap: 0.8rem;
            }
        }

        /* Compact Back Button */
        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 255, 255, 0.2);
            color: #333;
            padding: 8px 16px;
            border-radius: 40px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.85rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
            margin-bottom: 1.5rem;
            font-family: 'Poppins', sans-serif;
        }

        .back-button:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        /* Compact Title Section */
        .canvas-title-section {
            text-align: center;
            margin-bottom: 1.5rem;
            position: relative;
        }

        .canvas-title {
            font-size: 1.8rem !important;
            font-weight: 700 !important;
            background: linear-gradient(135deg, #E28585, #F598A8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem !important;
            letter-spacing: -0.3px;
        }

        .canvas-subtitle {
            color: rgba(51, 51, 51, 0.8) !important;
            font-size: 0.95rem !important;
            font-weight: 500;
            margin: 0 !important;
        }

        /* Compact Progress Counter */
        #progressCounter {
            text-align: center;
            font-size: 2.2rem !important;
            font-weight: 800 !important;
            background: linear-gradient(135deg, #E28585, #F598A8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin: 1.2rem 0 !important;
            position: relative;
        }

        #progressCounter::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 40px;
            height: 2px;
            background: linear-gradient(135deg, #E28585, #F598A8);
            border-radius: 1px;
        }

        /* Compact Add-ons Container */
        #add-ons-container {
            background: rgba(255, 255, 255, 0.1);
            padding: 1rem;
            border-radius: 16px;
            margin-bottom: 1.5rem !important;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        /* Compact Upload Button */
        .uploadBtnStyling {
            background: linear-gradient(135deg, #E28585, #F598A8) !important;
            color: white !important;
            border: none !important;
            padding: 10px 18px !important;
            border-radius: 12px !important;
            font-weight: 600 !important;
            font-size: 0.85rem !important;
            box-shadow: 0 4px 15px rgba(229, 133, 133, 0.25) !important;
            transition: all 0.3s ease !important;
        }

        .uploadBtnStyling:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 6px 20px rgba(229, 133, 133, 0.3) !important;
        }

        /* Compact Custom Select */
        .custom-select {
            background: rgba(255, 255, 255, 0.2) !important;
            border: 1px solid rgba(255, 255, 255, 0.3) !important;
            border-radius: 12px !important;
            padding: 10px 14px !important;
            font-weight: 600 !important;
            color: #333 !important;
            backdrop-filter: blur(10px) !important;
            transition: all 0.3s ease !important;
            font-size: 0.85rem !important;
        }

        .custom-select:focus {
            border-color: #E28585 !important;
            box-shadow: 0 0 0 2px rgba(226, 133, 133, 0.2) !important;
            outline: none !important;
        }

        /* Compact Camera Container */
        .camera-container {
            gap: 25px !important;
            margin-bottom: 1.5rem;
        }

        #videoContainer {
            border-radius: 18px !important;
            border: 2px solid rgba(255, 255, 255, 0.2) !important;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
            overflow: hidden;
            position: relative;
            max-width: 600px !important;
        }

        #videoContainer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            z-index: 2;
        }

        video {
            border-radius: 16px !important;
        }

        /* Compact Photo Container */
        #photoContainer {
            min-width: 140px !important;
            gap: 12px !important;
        }

        .photo {
            width: 120px !important;
            max-width: 120px !important;
            height: 88px !important;
            border: 2px solid rgba(255, 255, 255, 0.3) !important;
            border-radius: 12px !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
            transition: all 0.3s ease !important;
        }

        .photo:hover {
            transform: scale(1.05) !important;
            border-color: #E28585 !important;
            box-shadow: 0 6px 20px rgba(226, 133, 133, 0.25) !important;
        }

        /* Compact Filter Container */
        .filter-container {
            background: rgba(255, 255, 255, 0.1);
            padding: 1rem;
            border-radius: 16px;
            margin-bottom: 1rem !important;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Compact Filter Buttons */
        .filterBtn, #invertBtn {
            width: 40px !important;
            height: 40px !important;
            border: 2px solid rgba(255, 255, 255, 0.3) !important;
            border-radius: 12px !important;
            backdrop-filter: blur(10px) !important;
            transition: all 0.3s ease !important;
            position: relative;
            overflow: hidden;
        }

        .filterBtn:hover, #invertBtn:hover {
            transform: scale(1.1) !important;
            border-color: rgba(255, 255, 255, 0.5) !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15) !important;
        }

        #gridToggleBtn {
            background: rgba(255, 255, 255, 0.15) !important;
            color: #333 !important;
            border: 2px solid rgba(255, 255, 255, 0.3) !important;
            padding: 8px 14px !important;
            border-radius: 12px !important;
            backdrop-filter: blur(10px) !important;
            font-weight: 600 !important;
            font-size: 0.8rem !important;
            transition: all 0.3s ease !important;
        }

        #gridToggleBtn:hover {
            background: rgba(226, 133, 133, 0.2) !important;
            border-color: #E28585 !important;
            color: #E28585 !important;
        }

        /* Compact Options Label */
        .options-label {
            color: rgba(51, 51, 51, 0.8) !important;
            font-size: 0.9rem !important;
            font-weight: 600 !important;
            margin: 0.8rem 0 !important;
            text-align: center;
        }

        /* Compact Start/Done Buttons */
        .start-done-btn {
            display: flex;
            gap: 15px;
            justify-content: center;
            align-items: center;
        }

        #startBtn {
            background: linear-gradient(135deg, #E28585, #F598A8) !important;
            color: white !important;
            border: none !important;
            padding: 12px 30px !important;
            border-radius: 20px !important;
            font-size: 1.1rem !important;
            font-weight: 700 !important;
            box-shadow: 0 4px 15px rgba(226, 133, 133, 0.25) !important;
            transition: all 0.3s ease !important;
            position: relative;
            overflow: hidden;
        }

        #startBtn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        #startBtn:hover::before {
            left: 100%;
        }

        #startBtn:hover {
            transform: translateY(-2px) scale(1.02) !important;
            box-shadow: 0 8px 25px rgba(226, 133, 133, 0.3) !important;
        }

        #doneBtn {
            background: rgba(255, 255, 255, 0.2) !important;
            color: #333 !important;
            border: 2px solid rgba(255, 255, 255, 0.3) !important;
            backdrop-filter: blur(10px) !important;
            transition: all 0.3s ease !important;
            padding: 12px 30px !important;
            border-radius: 20px !important;
            font-size: 1.1rem !important;
            font-weight: 700 !important;
        }

        #doneBtn:hover {
            background: linear-gradient(135deg, #43e97b, #38f9d7) !important;
            color: white !important;
            border-color: transparent !important;
            transform: translateY(-2px) scale(1.02) !important;
            box-shadow: 0 8px 25px rgba(67, 233, 123, 0.3) !important;
        }

        /* Compact Messages */
        #fullscreenMessage, #filterMessage {
            background: rgba(0, 0, 0, 0.8) !important;
            backdrop-filter: blur(10px) !important;
            border-radius: 16px !important;
            padding: 15px 20px !important;
            font-size: 1rem !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
        }

        #countdownText {
            background: linear-gradient(135deg, #E28585, #F598A8) !important;
            border: 2px solid rgba(255, 255, 255, 0.3) !important;
            backdrop-filter: blur(10px) !important;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2) !important;
            font-size: 40px !important;
            padding: 15px 25px !important;
        }

        /* Compact Controls Section */
        .controls-section {
            margin-top: 1.5rem;
        }

        .filter-buttons-grid {
            /* display: grid;
            grid-template-columns: repeat(6, 1fr); */
            gap: 10px;
            margin-bottom: 0.8rem;
            justify-items: center;
        }

        .special-controls {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 0.8rem;
        }

        .options-section {
            text-align: center;
            margin: 1rem 0;
        }

        .timer-selector {
            position: relative;
        }

        .timer-selector::before {
            content: '⏱️';
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 0.9rem;
            z-index: 1;
        }

        .timer-selector .custom-select {
            padding-left: 35px !important;
        }

        /* Compact Upload Button */
        .uploadBtnStyling {
            display: flex !important;
            align-items: center !important;
            gap: 8px !important;
            position: relative;
            overflow: hidden;
        }

        .uploadBtnStyling::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .uploadBtnStyling:hover::before {
            left: 100%;
        }

        /* Canvas specific filter effects */
        .sepia { filter: sepia(100%) hue-rotate(30deg) saturate(1.2); }
        .grayscale { filter: grayscale(100%); }
        .smooth { filter: blur(0.5px) brightness(1.1) contrast(1.1); }
        .gray { filter: grayscale(50%) contrast(1.2); }
        .vintage { filter: sepia(60%) contrast(1.3) brightness(0.9) saturate(1.4); }

        /* Mobile Responsive Enhancements */
        @media (max-width: 768px) {
            .main-content-card {
                padding: 1.5rem !important;
                margin: 1rem !important;
            }
            
            .canvas-title {
                font-size: 1.5rem !important;
            }
            
            #progressCounter {
                font-size: 1.8rem !important;
            }
            
            .camera-container {
                flex-direction: column !important;
                gap: 20px !important;
            }
            
            .filter-container {
                padding: 0.8rem !important;
            }
            
            .filterBtn, #invertBtn {
                width: 35px !important;
                height: 35px !important;
            }
            
            .filter-buttons-grid {
                grid-template-columns: repeat(3, 1fr) !important;
                gap: 8px !important;
            }
            
            #add-ons-container {
                flex-direction: column !important;
                gap: 12px !important;
            }
            
            .start-done-btn {
                flex-direction: column !important;
                gap: 12px !important;
            }
            
            #startBtn, #doneBtn {
                width: 100% !important;
                max-width: 200px !important;
                padding: 10px 25px !important;
                font-size: 1rem !important;
            }
        }

        @media (max-width: 480px) {
            .main-content-card {
                padding: 1.2rem !important;
                margin: 0.5rem !important;
            }
            
            .canvas-title {
                font-size: 1.3rem !important;
            }
            
            .canvas-subtitle {
                font-size: 0.85rem !important;
            }
            
            #progressCounter {
                font-size: 1.6rem !important;
            }
            
            .filter-buttons-grid {
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 6px !important;
            }
            
            .filterBtn, #invertBtn {
                width: 32px !important;
                height: 32px !important;
            }
            
            .special-controls {
                flex-direction: column !important;
                gap: 8px !important;
            }
        }
    </style>
</head>

<body>
    <!-- Session Timer Box -->
    <div id="session-timer-container" class="timer-box">
        <span id="session-timer-display">20:00</span>
        <p>Sisa waktu sesi</p>
    </div>

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

    <main id="main-section">
        <div class="gradientBgCanvas"></div>
        <div class="canvas-centered">
            <div class="main-content-card">
                <!-- Back Button -->
                <a href="selectlayout.php" class="back-button">
                    <span>←</span>
                    <span>Kembali</span>
                </a>
                
                <!-- Direction Section -->
                <div class="direction-section">
                    <div class="direction-arrows">↑ ↑ ↑</div>
                    <h1 class="direction-heading">LOOK OVER HERE</h1>
                </div>
                
                <!-- Main Horizontal Layout -->
                <div class="horizontal-layout">
                    <!-- Left Section: Photo Preview & Camera -->
                    <div class="left-section">
                        <!-- Photo Preview Container -->
                        <div class="preview-container">
                            <h3 class="preview-title">Photo Preview</h3>
                            <div class="photo-preview-grid" id="photoContainer">
                                <div class="photo-preview-slot" data-index="0">
                                    <div class="photo-placeholder">Photo 1</div>
                                    <button class="retake-photo-btn" onclick="retakeSinglePhoto(0)">↻</button>
                                </div>
                                <div class="photo-preview-slot" data-index="1">
                                    <div class="photo-placeholder">Photo 2</div>
                                    <button class="retake-photo-btn" onclick="retakeSinglePhoto(1)">↻</button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Camera Container -->
                        <div class="camera-container">
                            <h3 class="camera-title">Camera View</h3>
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
                            </div>
                            
                            <!-- Progress Display -->
                            <div class="progress-display">
                                <div id="progressCounter">0/2</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right Section: Controls -->
                    <div class="right-section">
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
                            
                            <div class="invert-toggle">
                                <button id="invertBtn" title="Mirror Camera">
                                    <img src="/src/assets/mirror-icon.svg" alt="mirror icon" id="mirror-icon">
                                </button>
                                <span class="setting-label">Mirror Mode</span>
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
                            <button id="retakeAllBtn" disabled>RETAKE ALL</button>
                            <button id="doneBtn" style="display: none;">COMPLETE SESSION</button>
                        </div>
                        
                        <!-- Upload Section -->
                        <div class="upload-section">
                            <button id="uploadBtn" class="uploadBtnStyling">
                                <img src="/src/assets/upload-icon.png" class="icons-size" alt="upload image icon">
                                <span>Upload Images</span>
                            </button>
                            <input type="file" id="uploadInput" accept="image/*" multiple style="display: none;">
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