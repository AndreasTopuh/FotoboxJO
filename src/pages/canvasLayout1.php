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
    <link rel="stylesheet" href="/styles.css?v=<?php echo time(); ?>" />
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
        /* Import dari home-styles.css dan konsistensi styling */
        * {
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
            background-color: #fff;
            font-family: 'Poppins', sans-serif;
        }

        /* Container utama dengan glassmorphism */
        .canvas-centered {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        /* Main glassmorphism card */
        .main-content-card {
            width: 100%;
            max-width: 1000px;
            padding: 2.5rem;
            background: rgba(255, 255, 255, 0.13);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 
                0 8px 32px rgba(0, 0, 0, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.5),
                inset 0 -1px 0 rgba(255, 255, 255, 0.1);
            position: relative;
            overflow: hidden;
        }

        .main-content-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.8), transparent);
        }

        /* Timer Box Styling - konsisten dengan theme */
        .timer-box {
            position: fixed;
            top: 20px;
            right: 20px;
            background: rgba(226, 133, 133, 0.9);
            color: white;
            padding: 15px 20px;
            border-radius: 15px;
            font-weight: 600;
            z-index: 1000;
            text-align: center;
            box-shadow: 0 8px 25px rgba(226, 133, 133, 0.3);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            font-family: 'Poppins', sans-serif;
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

        /* Back Button Styling */
        .back-button {
            background: rgba(226, 133, 133, 0.1);
            color: #E28585;
            border: 2px solid #E28585;
            border-radius: 25px;
            padding: 10px 20px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            margin-bottom: 1.5rem;
            font-family: 'Poppins', sans-serif;
            font-size: 0.9rem;
        }

        .back-button:hover {
            background: #E28585;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(226, 133, 133, 0.3);
        }

        /* Canvas Title Section */
        .canvas-title-section {
            text-align: center;
            margin-bottom: 2rem;
        }

        .canvas-title {
            color: #333;
            font-size: 2rem;
            font-weight: 700;
            margin: 0 0 0.5rem 0;
            font-family: 'Poppins', sans-serif;
        }

        .canvas-subtitle {
            color: #666;
            font-size: 1rem;
            margin: 0;
            line-height: 1.5;
            font-family: 'Poppins', sans-serif;
        }

        /* Progress Counter Styling */
        #progressCounter {
            font-size: 2.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 1.5rem;
            text-align: center;
            font-family: 'Poppins', sans-serif;
        }

        /* Add-ons container untuk upload dan timer */
        #add-ons-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        /* Upload Button dengan glassmorphism */
        .uploadBtnStyling {
            background: rgba(76, 175, 80, 0.9);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
            backdrop-filter: blur(5px);
            font-family: 'Poppins', sans-serif;
        }

        .uploadBtnStyling:hover {
            background: rgba(69, 160, 73, 0.95);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(76, 175, 80, 0.4);
        }

        /* Custom Select Styling */
        .custom-select {
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid rgba(226, 133, 133, 0.3);
            border-radius: 12px;
            padding: 10px 15px;
            font-size: 14px;
            font-weight: 600;
            color: #333;
            cursor: pointer;
            outline: none;
            transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
            backdrop-filter: blur(5px);
        }

        .custom-select:hover, .custom-select:focus {
            border-color: #E28585;
            box-shadow: 0 4px 15px rgba(226, 133, 133, 0.2);
        }

        .icons-size {
            width: 16px;
            height: 16px;
        }

        /* Video Container dengan glassmorphism */
        #videoContainer {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            max-width: 800px;
            height: auto;
            background: rgba(0, 0, 0, 0.8);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            border: 2px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 2rem;
        }

        video {
            width: 100%;
            max-width: 800px;
            border-radius: 18px;
            display: block;
        }

        /* Black screen before video loads */
        #blackScreen {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
            transition: opacity 1s ease-in-out;
            font-family: 'Poppins', sans-serif;
            backdrop-filter: blur(5px);
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
            background: rgba(226, 133, 133, 0.8);
            padding: 20px 30px;
            border-radius: 50%;
            display: none;
            z-index: 10;
            animation: bounceScale 0.3s ease-in-out;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            font-family: 'Poppins', sans-serif;
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

        /* Photo Container dan Camera Container */
        .camera-container {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            gap: 30px;
            width: 100%;
            max-width: 1000px;
        }

        #photoContainer {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
            min-width: 170px;
        }

        .photo {
            width: 150px;
            max-width: 150px;
            border: 3px solid rgba(226, 133, 133, 0.5);
            border-radius: 15px;
            height: 109.76px;
            object-fit: cover;
            cursor: pointer;
            position: relative;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .photo:hover {
            transform: scale(1.05);
            border-color: #E28585;
            box-shadow: 0 8px 25px rgba(226, 133, 133, 0.3);
        }

        .retake-btn {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 32px;
            height: 32px;
            background: rgba(255, 68, 68, 0.9);
            border: none;
            border-radius: 50%;
            cursor: pointer;
            z-index: 10;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
            box-shadow: 0 2px 8px rgba(255, 68, 68, 0.3);
        }

        .retake-btn:hover {
            background: rgba(255, 68, 68, 1);
            transform: scale(1.1);
        }

        .retake-btn img {
            width: 18px;
            height: 18px;
            display: block;
        }

        /* Modal Styling dengan glassmorphism */
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
            backdrop-filter: blur(5px);
        }

        .modal-content {
            background: rgba(255, 255, 255, 0.95);
            padding: 2.5rem;
            border-radius: 20px;
            text-align: center;
            max-width: 400px;
            margin: 0 1rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            font-family: 'Poppins', sans-serif;
        }

        .modal-btn {
            background: #E28585;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 600;
            font-size: 1rem;
            margin-top: 1rem;
            transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
        }

        .modal-btn:hover {
            background: #d67373;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(226, 133, 133, 0.3);
        }

        /* Fullscreen Button */
        #fullscreenBtn {
            position: absolute;
            bottom: 15px;
            right: 15px;
            background: rgba(226, 133, 133, 0.9);
            border: none;
            border-radius: 12px;
            padding: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
            box-shadow: 0 4px 15px rgba(226, 133, 133, 0.3);
        }

        #fullscreenBtn:hover {
            background: rgba(226, 133, 133, 1);
            transform: scale(1.1);
        }

        .fullScreenSize {
            width: 24px;
            height: 24px;
        }

        /* Messages styling */
        #fullscreenMessage, #filterMessage {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 24px;
            font-weight: 600;
            color: white;
            background: rgba(0, 0, 0, 0.8);
            padding: 15px 25px;
            border-radius: 15px;
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
            z-index: 15;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            font-family: 'Poppins', sans-serif;
        }

        /* Filter dan Control Buttons */
        .filter-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .filterBtn, #invertBtn, #gridToggleBtn {
            width: 50px;
            height: 50px;
            border: 3px solid rgba(226, 133, 133, 0.5);
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(5px);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .filterBtn:hover, #invertBtn:hover {
            border-color: #E28585;
            transform: scale(1.1);
            box-shadow: 0 4px 15px rgba(226, 133, 133, 0.3);
        }

        #gridToggleBtn {
            width: auto;
            padding: 10px 15px;
            background: rgba(226, 133, 133, 0.1);
            color: #E28585;
            border: 2px solid #E28585;
            font-weight: 600;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
        }

        #gridToggleBtn:hover {
            background: #E28585;
            color: white;
            transform: translateY(-2px);
        }

        /* Main Action Buttons */
        .start-done-btn {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            margin-top: 2rem;
        }

        #startBtn, #doneBtn {
            background: linear-gradient(135deg, #E28585, #FF6B9D);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 25px;
            font-weight: 700;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
            box-shadow: 0 4px 15px rgba(226, 133, 133, 0.3);
            min-width: 120px;
        }

        #startBtn:hover, #doneBtn:hover {
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 8px 25px rgba(226, 133, 133, 0.4);
        }

        #doneBtn {
            display: none;
        }

        /* Options Label */
        .options-label {
            color: #333;
            font-size: 1.2rem;
            font-weight: 600;
            margin: 1rem 0;
            text-align: center;
            font-family: 'Poppins', sans-serif;
        }

        /* Startbtn Container */
        .startBtn-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 0;
            width: 100%;
        }

        /* Grid Overlay Styling */
        .grid-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-template-rows: repeat(3, 1fr);
            pointer-events: none;
            z-index: 5;
            opacity: 0.7;
        }

        .grid-overlay::before,
        .grid-overlay::after {
            content: '';
            position: absolute;
            background: rgba(255, 255, 255, 0.4);
        }

        .grid-overlay::before {
            width: 100%;
            height: 2px;
            top: 33.33%;
        }

        .grid-overlay::after {
            width: 100%;
            height: 2px;
            top: 66.67%;
        }

        .grid-overlay>div {
            border-right: 2px solid rgba(255, 255, 255, 0.4);
        }

        .grid-overlay>div:nth-child(3n) {
            border-right: none;
        }

        /* Filter backgrounds untuk preview */
        #vintageFilterId { background: linear-gradient(45deg, #DAA520, #CD853F); }
        #grayFilterId { background: linear-gradient(45deg, #696969, #A9A9A9); }
        #smoothFilterId { background: linear-gradient(45deg, #FFB6C1, #FFC0CB); }
        #bnwFilterId { background: linear-gradient(45deg, #000000, #333333); }
        #sepiaFilterId { background: linear-gradient(45deg, #D2691E, #8B4513); }
        #normalFilterId { background: linear-gradient(45deg, #6495ED, #87CEEB); }

        /* Carousel Styling dengan Glassmorphism */
        .carousel-container {
            position: relative;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 2rem;
            max-width: 90vw;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .carousel-close-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(255, 68, 68, 0.9);
            color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            font-size: 1.5rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
            z-index: 10;
        }

        .carousel-close-btn:hover {
            background: rgba(255, 68, 68, 1);
            transform: scale(1.1);
        }

        .carousel-image-container {
            position: relative;
            max-width: 70vw;
            max-height: 70vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 60px;
        }

        .carousel-image {
            max-width: 100%;
            max-height: 100%;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            object-fit: contain;
        }

        .carousel-nav-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(226, 133, 133, 0.9);
            color: white;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            font-size: 1.5rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
            z-index: 5;
        }

        .carousel-nav-btn:hover {
            background: rgba(226, 133, 133, 1);
            transform: translateY(-50%) scale(1.1);
        }

        .carousel-nav-btn:disabled {
            background: rgba(128, 128, 128, 0.5);
            cursor: not-allowed;
            transform: translateY(-50%) scale(0.9);
        }

        .prev-btn {
            left: 10px;
        }

        .next-btn {
            right: 10px;
        }

        .carousel-retake-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(255, 68, 68, 0.9);
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
        }

        .carousel-retake-btn:hover {
            background: rgba(255, 68, 68, 1);
            transform: scale(1.1);
        }

        .carousel-retake-btn img {
            width: 20px;
            height: 20px;
        }

        .carousel-indicators {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }

        .carousel-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .carousel-indicator.active {
            background: #E28585;
            transform: scale(1.2);
        }

        /* Responsive Design */
        @media only screen and (max-width: 768px) {
            .canvas-centered {
                padding: 1rem;
            }

            .main-content-card {
                padding: 1.5rem;
            }

            .camera-container {
                flex-direction: column;
                gap: 20px;
                align-items: center;
            }

            #videoContainer {
                max-width: 100%;
            }

            #photoContainer {
                flex-direction: row;
                justify-content: center;
                min-width: auto;
                width: 100%;
            }

            .photo {
                width: 100px;
                height: 73.17px;
            }

            .retake-btn {
                width: 25px;
                height: 25px;
            }

            .retake-btn img {
                width: 15px;
                height: 15px;
            }

            #progressCounter {
                font-size: 2rem;
            }

            .filter-container {
                gap: 10px;
            }

            .filterBtn, #invertBtn {
                width: 40px;
                height: 40px;
            }

            #add-ons-container {
                flex-direction: column;
                gap: 15px;
            }

            .start-done-btn {
                flex-direction: column;
                gap: 15px;
            }

            #startBtn, #doneBtn {
                width: 100%;
                max-width: 200px;
            }

            .canvas-title {
                font-size: 1.6rem;
            }

            .canvas-subtitle {
                font-size: 0.9rem;
            }

            .carousel-container {
                padding: 1.5rem;
                max-width: 90vw;
            }

            .carousel-image-container {
                margin: 0 50px;
                max-width: 75vw;
                max-height: 65vh;
            }
        }

        @media only screen and (max-width: 540px) {
            .main-content-card {
                padding: 1rem;
            }

            #progressCounter {
                font-size: 1.8rem;
            }

            .timer-box {
                top: 10px;
                right: 10px;
                padding: 10px 15px;
            }

            .timer-box #timer-display {
                font-size: 1.2rem;
            }

            .canvas-title {
                font-size: 1.4rem;
            }

            .canvas-subtitle {
                font-size: 0.85rem;
            }

            .canvas-title-section {
                margin-bottom: 1.5rem;
            }

            .carousel-container {
                padding: 1rem;
                max-width: 95vw;
                max-height: 95vh;
            }

            .carousel-image-container {
                margin: 0 40px;
                max-width: 80vw;
                max-height: 60vh;
            }

            .carousel-nav-btn {
                width: 40px;
                height: 40px;
                font-size: 1.2rem;
            }

            .prev-btn {
                left: 5px;
            }

            .next-btn {
                right: 5px;
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
        <!-- <div class="gradientBgCanvas"></div> -->
        <div class="canvas-centered">
            <div class="main-content-card">
                <!-- Back Button untuk konsistensi -->
                <a href="selectlayout.php" class="back-button">← Kembali ke Layout</a>
                
                <!-- Title Section -->
                <div class="canvas-title-section">
                    <h1 class="canvas-title">Layout 1 - Photo Session</h1>
                    <p class="canvas-subtitle">Ambil 2 foto dengan style yang Anda inginkan</p>
                </div>
                
                <p id="progressCounter">0/2</p>
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
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
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