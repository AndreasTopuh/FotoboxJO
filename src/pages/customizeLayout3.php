<?php
session_start();
// Initialize customize session if not set or expired, but preserve photo data
if (!isset($_SESSION['customize_expired_time']) || time() > $_SESSION['customize_expired_time']) {
    $_SESSION['customize_start_time'] = time();
    $_SESSION['customize_expired_time'] = time() + (10 * 60);
    $_SESSION['session_type'] = 'customize';
    // Don't clear captured_photos here - keep them for the customize session
}
require_once '../includes/pwa-helper.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Customize your Layout 1 photobooth photos with frames, stickers, and text.">
    <meta name="twitter:image" content="https://www.gofotobox.online/assets/home-mockup.png">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Photobooth | Customize Layout 3</title>
    <link rel="icon" href="/src/assets/icons/photobooth-new-logo.png">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="../../static/css/main.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../../static/css/customize.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../../static/css/layout-variables.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../../static/css/responsive.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Syne:wght@400;700&family=Poppins:wght@400;600;700&family=Mukta+Mahee:wght@200;300;400;500;600;700;800&display=swap">

    <?php PWAHelper::addPWAHeaders(); ?>

    <style>
        /* Override main layout untuk customizeLayout1 */
        .customize-content-wrapper {
            display: flex;
            flex: 1;
            align-items: stretch;
        }

        /* Left Section: Customization Options (Enhanced Pink & White Theme) */
        .customize-left-section {
            width: auto;
            max-width: 450px;
            backdrop-filter: unset;
            border: 0;
            flex-shrink: 0;
            background: linear-gradient(135deg, #fff 0%, #ffe6ec 50%, #fff0f3 100%);
            border-radius: 1.2rem;
            overflow-y: auto;
            position: relative;
        }

        .customize-left-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 60px;
            background: linear-gradient(180deg, rgba(233, 30, 99, 0.08) 0%, transparent 100%);
            pointer-events: none;
        }

        .customize-left-section::-webkit-scrollbar {
            width: 20px;
        }

        .customize-left-section::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.5);
            border-radius: 10px;
            border: 1px solid rgba(233, 30, 99, 0.1);
        }

        .customize-left-section::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, var(--pink-primary), var(--pink-hover));
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .customize-left-section::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, var(--pink-hover), var(--pink-primary));
            transform: scale(1.05);
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.3);
        }

        .customize-title {
            color: var(--pink-primary);
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            text-align: center;
            text-shadow: 0 2px 4px rgba(233, 30, 99, 0.1);
            position: relative;
        }

        .customize-title::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: linear-gradient(to right, var(--pink-primary), var(--pink-light));
            border-radius: 2px;
        }

        /* Right Section: Photo Preview */
        .customize-right-section {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #ffe6ec 0%, #fff0f3 100%);
            border-radius: 1.2rem;
            position: relative;
        }

        .customize-right-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at center, rgba(255, 255, 255, 0.3) 0%, transparent 70%);
            border-radius: inherit;
            pointer-events: none;
        }

        .customize-photo-preview {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .customize-photo-preview canvas,
        .customize-photo-preview img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            display: block;
        }

        /* Customization Options Groups */
        .customize-options-group {
            margin-bottom: 1.8rem;
            padding: 1.2rem;
            background: rgba(255, 255, 255, 0.6);
            border-radius: 0.8rem;
            border: 1px solid rgba(233, 30, 99, 0.15);
            box-shadow: 0 2px 8px rgba(233, 30, 99, 0.08);
            backdrop-filter: blur(5px);
            transition: all 0.3s ease;
        }

        .customize-options-group:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(233, 30, 99, 0.15);
            border-color: rgba(233, 30, 99, 0.3);
        }

        .customize-options-label {
            color: var(--pink-primary);
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 1rem;
            display: block;
            text-align: center;
            position: relative;
        }

        .customize-options-label::before {
            content: '';
            position: absolute;
            left: 0;
            bottom: -5px;
            width: 100%;
            height: 2px;
            background: linear-gradient(to right, transparent, var(--pink-primary), transparent);
            border-radius: 1px;
        }

        /* Buttons Grid */
        .customize-buttons-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 1rem;
            margin-top: 1rem;
            padding: 0.8rem;
            background: rgba(255, 255, 255, 0.4);
            border-radius: 0.6rem;
            border: 1px solid rgba(233, 30, 99, 0.1);
            justify-items: center;
        }

        .customize-buttons-grid.frame-color-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 0.3rem;
            padding: 0.4rem;
        }

        /* Frame Color Section - More Compact */
        .frame-color-section {
            margin-bottom: 1.2rem;
            padding: 0.8rem;
        }

        .frame-color-section .customize-options-group {
            margin-bottom: 1rem;
            padding: 0.8rem;
        }

        .customize-buttons-grid.shape-buttons {
            grid-template-columns: repeat(2, 1fr);
        }

        /* Frame Color Buttons - Smaller and Centered */
        .buttonFrames {
            width: 40px;
            height: 40px;
            border: 2px solid var(--pink-primary);
            border-radius: 0.6rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 3px 10px rgba(255, 105, 135, 0.15);
            position: relative;
            overflow: hidden;
        }

        .buttonFrames::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.3) 0%, transparent 50%);
            transform: scale(0);
            transition: transform 0.3s ease;
        }

        .buttonFrames:hover::before {
            transform: scale(1);
        }

        .buttonFrames:hover {
            transform: scale(1.08) translateY(-3px);
            box-shadow: 0 6px 20px rgba(255, 105, 135, 0.25);
            border-width: 3px;
        }

        .buttonFrames.active {
            border-width: 3px;
            box-shadow: 0 0 0 3px rgba(233, 30, 99, 0.3);
            transform: scale(1.05);
        }

        /* Frame color styling */
        .frame-color-pink {
            background: #ff748d;
        }

        .frame-color-blue {
            background: #64b5f6;
        }

        .frame-color-yellow {
            background: #ffeb3b;
        }

        .frame-color-matcha {
            background: #81c784;
        }

        .frame-color-purple {
            background: #ba68c8;
        }

        .frame-color-brown {
            background: #8d6e63;
        }

        .frame-color-red {
            background: #e57373;
        }

        .frame-color-white {
            background: #ffffff;
            border-color: var(--pink-primary);
        }

        .frame-color-black {
            background: #424242;
        }

        /* Color Buttons - Khusus untuk tombol warna yang kecil dan bulat */
        .color-buttons-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 0.8rem;
            justify-content: center;
            max-width: 280px;
            margin: 0 auto;
        }

        .buttonColorFrames {
            width: 25px;
            height: 25px;
            border: 3px solid rgba(255, 255, 255, 0.8);
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            position: relative;
            overflow: hidden;
        }

        .buttonColorFrames::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .buttonColorFrames:hover::before {
            width: 100%;
            height: 100%;
        }

        .buttonColorFrames:hover {
            border-color: var(--pink-primary);
            transform: scale(1.15);
            box-shadow: 0 4px 15px rgba(233, 30, 99, 0.3);
        }

        .buttonColorFrames.active {
            border-color: var(--pink-primary);
            border-width: 5px;
            transform: scale(1.1);
            box-shadow: 0 0 0 2px rgba(233, 30, 99, 0.2), 0 4px 15px rgba(233, 30, 99, 0.4);
        }

        /* Frame Color Section - Compact styling */
        .frame-color-section .customize-options-group {
            padding: 1rem;
        }

        .frame-color-section .customize-options-label {
            margin-bottom: 1rem;
            font-size: 1.1rem;
        }

        /* Shape Buttons */
        .buttonShapes {
            width: 55px;
            height: 55px;
            border: 2px solid var(--pink-primary);
            border-radius: 0.8rem;
            cursor: pointer;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #fff 0%, #ffe6ec 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 3px 10px rgba(255, 105, 135, 0.15);
            position: relative;
            overflow: hidden;
        }

        .buttonShapes::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg, var(--pink-primary), var(--pink-light), var(--pink-primary));
            border-radius: 0.8rem;
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: -1;
        }

        .buttonShapes:hover::before,
        .buttonShapes.active::before {
            opacity: 1;
        }

        .buttonShapes:hover {
            background: linear-gradient(135deg, var(--pink-primary), var(--pink-light));
            color: #fff;
            transform: scale(1.08) translateY(-3px);
            box-shadow: 0 6px 20px rgba(255, 105, 135, 0.25);
        }

        .buttonShapes.active {
            background: linear-gradient(135deg, var(--pink-primary), var(--pink-light));
            color: #fff;
            box-shadow: 0 0 0 3px rgba(233, 30, 99, 0.3);
            transform: scale(1.05);
        }

        .buttonShapes img.shape-icon {
            width: 24px;
            height: 24px;
            object-fit: contain;
        }

        /* Frame & Sticker Combo Buttons - Larger for Better Visibility */
        .buttonFrameStickers {
            width: 120px;
            height: 90px;
            border: 2px solid rgba(233, 30, 99, 0.4);
            border-radius: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #fff 0%, #ffe6ec 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(255, 105, 135, 0.15);
            position: relative;
            margin: 0 auto;
        }

        .buttonFrameStickers::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0);
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(233, 30, 99, 0.2) 0%, transparent 70%);
            border-radius: 50%;
            transition: transform 0.3s ease;
        }

        .buttonFrameStickers:hover::after,
        .buttonFrameStickers.active::after {
            transform: translate(-50%, -50%) scale(1);
        }

        .buttonFrameStickers:hover {
            border-color: var(--pink-primary);
            transform: scale(1.05) translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 105, 135, 0.25);
        }

        .buttonFrameStickers.active {
            border-color: var(--pink-primary);
            background: linear-gradient(135deg, rgba(233, 30, 99, 0.1), rgba(233, 30, 99, 0.2));
            box-shadow: 0 0 0 3px rgba(233, 30, 99, 0.3);
            transform: scale(1.03);
        }

        .buttonFrameStickers img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 6px;
        }

        /* Special styling for "None" buttons */
        .frame-sticker-none {
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .frame-sticker-none img {
            width: 32px;
            height: 32px;
            opacity: 0.6;
            object-fit: contain;
        }

        .frame-sticker-none span {
            font-size: 0.8rem;
            color: var(--pink-primary);
            font-weight: 600;
            text-align: center;
        }

        /* Logo Buttons */
        .customize-logo-buttons {
            display: flex;
            gap: 0.7rem;
            margin-top: 1rem;
            padding: 0.5rem;
            background: rgba(255, 255, 255, 0.4);
            border-radius: 0.6rem;
            border: 1px solid rgba(233, 30, 99, 0.1);
        }

        .logoCustomBtn {
            flex: 1;
            padding: 0.8rem 1.4rem;
            background: linear-gradient(135deg, #fff 0%, #ffe6ec 100%);
            color: var(--pink-primary);
            border: 2px solid var(--pink-primary);
            border-radius: 0.8rem;
            font-family: inherit;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 3px 10px rgba(255, 105, 135, 0.15);
            position: relative;
            overflow: hidden;
        }

        .logoCustomBtn::before {
            content: '';
            position: absolute;
            top: -100%;
            left: -100%;
            width: 300%;
            height: 300%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: transform 0.5s ease;
            transform: translateX(-100%);
        }

        .logoCustomBtn:hover::before {
            transform: translateX(100%);
        }

        .logoCustomBtn:hover {
            background: linear-gradient(135deg, var(--pink-primary), var(--pink-light));
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(255, 105, 135, 0.25);
        }

        .logoCustomBtn.active {
            background: linear-gradient(135deg, var(--pink-primary), var(--pink-light));
            color: #fff;
            box-shadow: 0 0 0 3px rgba(233, 30, 99, 0.3);
        }

        /* Brightness Controls */
        .brightness-controls {
            display: flex;
            flex-direction: column;
            gap: 15px;
            padding: 1.2rem;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.7) 0%, rgba(255, 230, 236, 0.5) 100%);
            border-radius: 0.8rem;
            border: 1px solid rgba(233, 30, 99, 0.2);
            margin-top: 1rem;
            box-shadow: 0 2px 8px rgba(233, 30, 99, 0.08);
        }

        .brightness-slider-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .brightness-label {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--pink-primary);
            font-size: 14px;
            font-weight: 600;
            min-width: 140px;
        }

        .brightness-slider {
            flex: 1;
            height: 6px;
            background: linear-gradient(to right, #333, #fff);
            border-radius: 3px;
            outline: none;
            -webkit-appearance: none;
            appearance: none;
        }

        .brightness-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 20px;
            height: 20px;
            background: var(--pink-primary);
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
            transition: all 0.2s ease;
        }

        .brightness-slider::-webkit-slider-thumb:hover {
            background: var(--pink-hover);
            transform: scale(1.1);
        }

        .brightness-slider::-moz-range-thumb {
            width: 20px;
            height: 20px;
            background: var(--pink-primary);
            border-radius: 50%;
            cursor: pointer;
            border: none;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
            transition: all 0.2s ease;
        }

        .brightness-value {
            min-width: 50px;
            text-align: center;
        }

        .brightness-value span {
            color: var(--pink-primary);
            font-weight: 600;
            font-size: 14px;
        }

        /* Action Buttons Footer */
        .customize-action-buttons {
            height: unset;
            display: flex;
            backdrop-filter: blur(12px);
            justify-content: center;
            outline: unset;
            border-top: unset;
            background: unset;
        }

        .customize-action-btn {
            padding: 1rem 2rem;
            border-radius: 0.8rem;
            border: 2px solid var(--pink-primary);
            font-family: inherit;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(255, 105, 135, 0.20);
            position: relative;
            overflow: hidden;
        }

        .customize-action-btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0);
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.3) 0%, transparent 70%);
            transition: transform 0.3s ease;
            border-radius: inherit;
        }

        .customize-action-btn:hover::before {
            transform: translate(-50%, -50%) scale(1);
        }

        .email-btn {
            background: linear-gradient(135deg, #fff 0%, #ffe6ec 100%);
            color: var(--pink-primary);
        }

        .email-btn:hover {
            background: linear-gradient(135deg, var(--pink-primary), var(--pink-light));
            color: #fff;
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(255, 105, 135, 0.30);
        }

        .print-btn {
            background: linear-gradient(135deg, #fff 0%, #ffe6ec 100%);
            color: var(--pink-primary);
        }

        .print-btn:hover {
            background: linear-gradient(135deg, var(--pink-primary), var(--pink-light));
            color: #fff;
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(255, 105, 135, 0.30);
        }

        .continue-btn {
            background: linear-gradient(135deg, var(--pink-primary), var(--pink-light));
            color: #fff;
            border-color: var(--pink-light);
        }

        .continue-btn:hover {
            background: linear-gradient(135deg, var(--pink-hover), var(--pink-primary));
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(194, 24, 91, 0.35);
        }

        /* Loading placeholder styles */
        .loading-placeholder {
            padding: 20px;
            text-align: center;
            color: var(--pink-primary);
            font-style: italic;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.6) 0%, rgba(255, 230, 236, 0.4) 100%);
            border-radius: 0.6rem;
            border: 1px dashed rgba(233, 30, 99, 0.3);
            animation: pulse-loading 1.5s ease-in-out infinite;
        }

        @keyframes pulse-loading {

            0%,
            100% {
                opacity: 0.7;
                transform: scale(1);
            }

            50% {
                opacity: 1;
                transform: scale(1.02);
            }
        }

        /* Frame & sticker combo container styling */
        #dynamicFrameStickerContainer {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-top: 1rem;
            padding: 0.8rem;
            background: rgba(255, 255, 255, 0.4);
            border-radius: 0.6rem;
            border: 1px solid rgba(233, 30, 99, 0.1);
            justify-items: center;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(8px);
        }

        .modal-content {
            background: linear-gradient(135deg, #fff 0%, #ffe6ec 50%, #fff0f3 100%);
            border: 2px solid var(--pink-primary);
            margin: 3% auto;
            padding: 2.5rem;
            border-radius: 1.5rem;
            width: 90%;
            max-width: 550px;
            position: relative;
            box-shadow: 0 10px 40px rgba(255, 105, 135, 0.25);
        }

        .close-btn {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.8), rgba(255, 230, 236, 0.6));
            border: 1px solid var(--pink-primary);
            font-size: 1.5rem;
            color: var(--pink-primary);
            cursor: pointer;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.3s ease;
            font-weight: bold;
        }

        .close-btn:hover {
            background: linear-gradient(135deg, var(--pink-primary), var(--pink-light));
            color: #fff;
            transform: scale(1.1);
        }

        .modal-title {
            color: var(--pink-primary);
            font-weight: 700;
            font-size: 1.3rem;
            margin-bottom: 0.5rem;
            text-align: center;
        }

        .modal-subtitle {
            color: var(--pink-primary);
            margin-bottom: 1.5rem;
            text-align: center;
            opacity: 0.8;
        }

        .email-input-container {
            margin-bottom: 1.5rem;
        }

        .email-input {
            width: 100%;
            padding: 1rem 1.2rem;
            border: 2px solid rgba(233, 30, 99, 0.3);
            border-radius: 0.8rem;
            font-size: 1rem;
            background: rgba(255, 255, 255, 0.9);
            color: var(--pink-primary);
            transition: all 0.3s ease;
        }

        .email-input:focus {
            outline: none;
            border-color: var(--pink-primary);
            box-shadow: 0 0 0 3px rgba(233, 30, 99, 0.2);
            background: #fff;
        }

        .input-validation {
            margin-top: 0.5rem;
            height: 20px;
        }

        #validation-message {
            color: #e74c3c;
            font-size: 0.9rem;
            display: none;
        }

        /* Virtual Keyboard */
        .virtual-keyboard {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .keyboard-row {
            display: flex;
            gap: 0.3rem;
            justify-content: center;
        }

        .key-btn {
            padding: 0.6rem;
            min-width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #fff 0%, #ffe6ec 100%);
            border: 1px solid var(--pink-primary);
            border-radius: 0.6rem;
            color: var(--pink-primary);
            cursor: pointer;
            transition: all 0.2s ease;
            font-weight: 600;
            box-shadow: 0 2px 5px rgba(255, 105, 135, 0.10);
        }

        .key-btn:hover {
            background: linear-gradient(135deg, var(--pink-primary), var(--pink-light));
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(255, 105, 135, 0.20);
        }

        .key-caps,
        .key-backspace {
            min-width: 70px;
        }

        .modal-actions {
            display: flex;
            gap: 1.2rem;
            justify-content: center;
        }

        .btn-secondary {
            padding: 1rem 2rem;
            background: linear-gradient(135deg, #fff 0%, #ffe6ec 100%);
            color: var(--pink-primary);
            border: 2px solid var(--pink-primary);
            border-radius: 0.8rem;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            box-shadow: 0 3px 10px rgba(255, 105, 135, 0.15);
        }

        .btn-secondary:hover {
            background: linear-gradient(135deg, var(--pink-primary), var(--pink-light));
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(255, 105, 135, 0.25);
        }

        .btn-primary {
            padding: 1rem 2rem;
            background: linear-gradient(135deg, var(--pink-primary), var(--pink-light));
            color: #fff;
            border: 2px solid var(--pink-light);
            border-radius: 0.8rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.7rem;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(255, 105, 135, 0.25);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--pink-hover), var(--pink-primary));
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(194, 24, 91, 0.35);
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .customize-content-wrapper {
                flex-direction: column;
                height: auto;
                padding: 0.5rem;
                gap: 1rem;
            }

            .customize-left-section {
                max-width: 100%;
                width: 100%;
                max-height: 350px;
                order: 2;
            }

            .customize-right-section {
                min-height: 450px;
                order: 1;
            }

            .customize-action-buttons {
                order: 3;
                position: sticky;
                bottom: 0;
                background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 230, 236, 0.8) 100%);
                backdrop-filter: blur(15px);
                z-index: 100;
            }
        }

        @media (max-width: 768px) {
            .customize-content-wrapper {
                padding: 0.3rem;
                gap: 0.8rem;
            }

            .customize-left-section {
                padding: 1.2rem;
                max-height: 280px;
            }

            .customize-title {
                font-size: 1.3rem;
                margin-bottom: 1.2rem;
            }

            .customize-options-label {
                font-size: 1rem;
            }

            .customize-buttons-grid {
                gap: 0.6rem;
            }

            .customize-buttons-grid.frame-color-grid {
                gap: 0.2rem;
            }

            .color-buttons-grid {
                gap: 0.6rem;
                grid-template-columns: repeat(5, 1fr);
                max-width: 240px;
            }

            .buttonColorFrames {
                width: 25px;
                height: 25px;
                border-width: 2px;
            }

            .buttonColorFrames.active {
                border-width: 4px;
            }

            .buttonFrames {
                width: 35px;
                height: 35px;
            }

            .buttonShapes {
                width: 45px;
                height: 45px;
            }

            .buttonFrameStickers {
                width: 100px;
                height: 75px;
            }

            .customize-action-buttons {
                padding: 1rem;
                gap: 0.6rem;
            }

            .customize-action-btn {
                padding: 0.8rem 1.2rem;
                font-size: 0.9rem;
            }

            .modal-content {
                width: 95%;
                padding: 1.8rem;
                margin: 8% auto;
            }

            .key-btn {
                min-width: 38px;
                height: 38px;
                padding: 0.4rem;
            }
        }

        /* Additional enhancements */
        .customize-options-group {
            position: relative;
        }

        /* Enhanced hover effects */
        .customize-left-section {
            transition: all 0.3s ease;
        }

        .customize-right-section {
            transition: all 0.3s ease;
        }

        /* Animation untuk loading state */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .customize-options-group {
            animation: fadeInUp 0.6s ease forwards;
        }

        .customize-options-group:nth-child(2) {
            animation-delay: 0.1s;
        }

        .customize-options-group:nth-child(3) {
            animation-delay: 0.2s;
        }

        .customize-options-group:nth-child(4) {
            animation-delay: 0.3s;
        }

        .customize-options-group:nth-child(5) {
            animation-delay: 0.4s;
        }

        /* Print styles for 4R paper */
        @page {
            size: 4in 6in;
            margin: 0;
        }

        @media print {
            * {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                margin: 0;
                padding: 0;
                border: none;
                box-sizing: border-box;
            }

            html,
            body {
                width: 4in;
                height: 6in;
                margin: 0;
                padding: 0;
                overflow: hidden;
                background: none;
            }

            .print-container {
                width: 4in;
                height: 6in;
                position: absolute;
                top: 0;
                left: 0;
            }

            .print-image {
                width: 4in;
                height: 6in;
                object-fit: cover;
                object-position: center;
                position: absolute;
                top: 0;
                left: 0;
                display: block;
            }
        }
    </style>
</head>

<body>
    <div class="gradientBgCanvas"></div>

    <main class="customize-content-wrapper">
        <!-- Customization Options -->
        <section class="customize-left-section">
            <h1 class="customize-title">Customize Your Photo</h1>

            <!-- Photo Brightness -->
            <div class="customize-options-group">
                <h3 class="customize-options-label">Brightness / Kecerahan</h3>
                <div class="brightness-controls">
                    <div class="brightness-slider-container">
                        <label for="brightnessSlider" class="brightness-label">
                            <i class="fas fa-sun"></i> Adjust Brightness
                        </label>
                        <input type="range" id="brightnessSlider" min="0.3" max="3.0" step="0.1" value="1.0" class="brightness-slider">
                        <div class="brightness-value">
                            <span id="brightnessValue">100%</span>
                        </div>
                    </div>
                    <!-- <div class="brightness-preset-buttons">
                        <button id="darkerBtn" class="brightness-btn darker-btn">
                            <i class="fas fa-moon"></i> Darker
                        </button>
                        <button id="normalBtn" class="brightness-btn normal-btn active">
                            <i class="fas fa-adjust"></i> Normal
                        </button>
                        <button id="brighterBtn" class="brightness-btn brighter-btn">
                            <i class="fas fa-sun"></i> Brighter
                        </button>
                        <!-- <button id="superBrightBtn" class="brightness-btn super-bright-btn">
                            <i class="fas fa-fire"></i> Super Bright
                        </button> -->
                </div>
            </div>
            <!-- Frame Color -->
            <div class="customize-options-group frame-color-section">
                <h3 class="customize-options-label">Frame Color</h3>
                <div class="customize-buttons-grid color-buttons-grid">
                    <button id="pinkBtnFrame" class="buttonColorFrames frame-color-pink" data-color="pink"></button>
                    <button id="blueBtnFrame" class="buttonColorFrames frame-color-blue" data-color="blue"></button>
                    <button id="yellowBtnFrame" class="buttonColorFrames frame-color-yellow" data-color="yellow"></button>
                    <button id="matchaBtnFrame" class="buttonColorFrames frame-color-matcha" data-color="matcha"></button>
                    <button id="purpleBtnFrame" class="buttonColorFrames frame-color-purple" data-color="purple"></button>
                    <button id="brownBtnFrame" class="buttonColorFrames frame-color-brown" data-color="brown"></button>
                    <button id="redBtnFrame" class="buttonColorFrames frame-color-red" data-color="red"></button>
                    <button id="whiteBtnFrame" class="buttonColorFrames frame-color-white" data-color="white"></button>
                    <button id="blackBtnFrame" class="buttonColorFrames frame-color-black" data-color="black"></button>
                </div>
            </div>
            <!-- Frame & Sticker Combo (Dynamic from Database - Layout 3 specific) -->
            <div class="customize-options-group">
                <h3 class="customize-options-label">Frame & Sticker</h3>
                <div id="dynamicFrameStickerContainer" class="customize-buttons-grid stickers-grid">
                    <button id="noneFrameSticker" class="buttonFrameStickers frame-sticker-none">
                        <img src="../assets/block (1).png" alt="None" class="shape-icon">
                    </button>
                    <div class="loading-placeholder">Loading layout 3 frame & sticker combos...</div>
                </div>
            </div>

            <!-- Background Frames (Dynamic from Database) -->
            <!-- <div class="customize-options-group">
                <h3 class="customize-options-label">Background Frames</h3>
                <div id="dynamicFramesContainer" class="customize-buttons-grid">
                    <div class="loading-placeholder">Loading frames...</div>
                </div>
            </div> -->
            <!-- Stickers (Dynamic from Database - Layout 1 specific) -->
            <!-- <div class="customize-options-group">
                <h3 class="customize-options-label">Stickers</h3>
                <div id="dynamicStickersContainer" class="customize-buttons-grid stickers-grid">
                    <button type="button" id="noneSticker" class="buttonStickers sticker-none">
                        <img src="../assets/block (1).png" alt="None" class="shape-icon">
                    </button>
                    <div class="loading-placeholder">Loading layout 1 stickers...</div>
                </div>
            </div> -->
            <!-- Photo Shape
            <div class="customize-options-group">
                <h3 class="customize-options-label">Photo Shape</h3>
                <div class="customize-buttons-grid shape-buttons">
                    <button id="noneFrameShape" class="buttonShapes">
                        <img src="../assets/block (1).png" alt="None" class="shape-icon">
                    </button>
                    <button id="softFrameShape" class="buttonShapes">
                        <img src="../assets/corners.png" alt="Soft Edge Frame" class="shape-icon">
                    </button>
                </div>
            </div> -->
            <!-- Logo 
            <div class="customize-options-group">
                <h3 class="customize-options-label">Logo</h3>
                <div class="customize-logo-buttons">
                    <button id="nonLogo" class="logoCustomBtn">None</button>
                    <button id="engLogo" class="logoCustomBtn">Use</button>
                </div>
            </div> -->
        </section>

        <!-- Photo Preview -->
        <section class="customize-right-section">
            <div id="photoPreview" class="customize-photo-preview"></div>
        </section>
    </main>

    <!-- Action Buttons -->
    <footer class="customize-action-buttons">
        <button class="customize-action-btn email-btn" id="emailBtn">
            <i class="fas fa-envelope"></i> Kirim ke Email
        </button>
        <button class="customize-action-btn print-btn" id="printBtn">
            <i class="fas fa-print"></i> Print
        </button>
        <button class="customize-action-btn continue-btn" id="continueBtn">
            <i class="fas fa-arrow-right"></i> Lanjutkan
        </button>
    </footer>

    <!-- Email Modal -->
    <div id="emailModal" class="modal">
        <div class="modal-content email-modal-content">
            <button id="closeEmailModal" class="close-btn">&times;</button>
            <div class="modal-header">
                <h3 class="modal-title">Masukan Email Anda</h3>
                <p class="modal-subtitle">Foto akan dikirim ke alamat email yang Anda masukkan</p>
            </div>
            <div class="email-input-container">
                <input type="email" id="emailInput" placeholder="contoh@email.com" class="email-input">
                <div class="input-validation">
                    <span id="validation-message">Format email tidak valid</span>
                </div>
            </div>
            <div id="virtualKeyboard" class="virtual-keyboard">
                <div class="keyboard-row">
                    <button class="key-btn" data-key="1">1</button>
                    <button class="key-btn" data-key="2">2</button>
                    <button class="key-btn" data-key="3">3</button>
                    <button class="key-btn" data-key="4">4</button>
                    <button class="key-btn" data-key="5">5</button>
                    <button class="key-btn" data-key="6">6</button>
                    <button class="key-btn" data-key="7">7</button>
                    <button class="key-btn" data-key="8">8</button>
                    <button class="key-btn" data-key="9">9</button>
                    <button class="key-btn" data-key="0">0</button>
                </div>
                <div class="keyboard-row">
                    <button class="key-btn" data-key="q">Q</button>
                    <button class="key-btn" data-key="w">W</button>
                    <button class="key-btn" data-key="e">E</button>
                    <button class="key-btn" data-key="r">R</button>
                    <button class="key-btn" data-key="t">T</button>
                    <button class="key-btn" data-key="y">Y</button>
                    <button class="key-btn" data-key="u">U</button>
                    <button class="key-btn" data-key="i">I</button>
                    <button class="key-btn" data-key="o">O</button>
                    <button class="key-btn" data-key="p">P</button>
                </div>
                <div class="keyboard-row">
                    <button class="key-btn" data-key="a">A</button>
                    <button class="key-btn" data-key="s">S</button>
                    <button class="key-btn" data-key="d">D</button>
                    <button class="key-btn" data-key="f">F</button>
                    <button class="key-btn" data-key="g">G</button>
                    <button class="key-btn" data-key="h">H</button>
                    <button class="key-btn" data-key="j">J</button>
                    <button class="key-btn" data-key="k">K</button>
                    <button class="key-btn" data-key="l">L</button>
                    <button class="key-btn" data-key="@">@</button>
                </div>
                <div class="keyboard-row">
                    <button class="key-btn key-caps" data-key="caps">CAPS</button>
                    <button class="key-btn" data-key="z">Z</button>
                    <button class="key-btn" data-key="x">X</button>
                    <button class="key-btn" data-key="c">C</button>
                    <button class="key-btn" data-key="v">V</button>
                    <button class="key-btn" data-key="b">B</button>
                    <button class="key-btn" data-key="n">N</button>
                    <button class="key-btn" data-key="m">M</button>
                    <button class="key-btn key-backspace" data-key="backspace">⌫</button>
                </div>
                <div class="keyboard-row">
                    <button class="key-btn" data-key=".">.</button>
                    <button class="key-btn" data-key="-">-</button>
                    <button class="key-btn" data-key="_">_</button>
                </div>
            </div>
            <div class="modal-actions">
                <button id="cancelEmailBtn" class="btn-secondary">Batal</button>
                <button id="sendEmailBtn" class="btn-primary">
                    <i class="fas fa-paper-plane"></i> Kirim
                </button>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js"></script>
    <!-- Enhanced EmailJS Helper -->
    <script src="../assets/js/emailjs-helper.js"></script>
    <script>
        (function() {
            emailjs.init({
                publicKey: "9SDzOfKjxuULQ5ZW8"
            });

            // Initialize enhanced EmailJS helper
            if (typeof window.emailJSHelper !== 'undefined') {
                window.emailJSHelper.init('9SDzOfKjxuULQ5ZW8');
                console.log('✅ Enhanced EmailJS Helper loaded');
            }
        })();
    </script>
    <script src="../assets/js/assets-manager.js"></script>
    <script src="customizeLayout3.js"></script>
    <script src="../includes/session-timer.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.sessionTimer) {
                window.sessionTimer.onExpired = () => {
                    window.location.href = 'thankyou.php';
                };
            }
        });
    </script>
    <?php PWAHelper::addPWAScript(); ?>
</body>

</html>