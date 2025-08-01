// TEMPLATE FOR ALL CANVAS LAYOUTS
// This template can be used for Layout 3, 4, 5, 6 with minimal changes

document.addEventListener('DOMContentLoaded', () => {
    // 🚀 COMPRESSION CONFIGURATION - 3-Level Quality System
    const COMPRESSION_CONFIG = {
        SESSION_QUALITY: 0.5,
        SESSION_MAX_WIDTH: 1200,
        SESSION_MAX_HEIGHT: 800,
        DOWNLOAD_QUALITY: 0.95,
        DOWNLOAD_MAX_WIDTH: 2400,
        DOWNLOAD_MAX_HEIGHT: 1600,
        THUMB_QUALITY: 0.6,
        THUMB_MAX_WIDTH: 300,
        THUMB_MAX_HEIGHT: 200
    };

    // 🎯 CONFIGURATION - CHANGE THIS FOR EACH LAYOUT
    // Layout 1: 2, Layout 2: 4, Layout 3: 6, Layout 4: 8, Layout 5: 6, Layout 6: 4
    const expectedPhotos = 6; // Replace 6 with the actual expected number of photos or assign dynamically
    const layoutName = '{{LAYOUT_NAME}}'; // e.g., 'canvasLayout3'
    
    let images = [];
    let invertBtnState = false;
    let currentImageIndex = 0;

    // 🚀 FAST COMPRESSION FUNCTION
    function compressImage(imageData, mode = 'session') {
        return new Promise((resolve, reject) => {
            try {
                const img = new Image();
                img.onload = function() {
                    let maxWidth, maxHeight, quality;
                    if (mode === 'session') {
                        maxWidth = COMPRESSION_CONFIG.SESSION_MAX_WIDTH;
                        maxHeight = COMPRESSION_CONFIG.SESSION_MAX_HEIGHT;
                        quality = COMPRESSION_CONFIG.SESSION_QUALITY;
                    } else if (mode === 'download') {
                        maxWidth = COMPRESSION_CONFIG.DOWNLOAD_MAX_WIDTH;
                        maxHeight = COMPRESSION_CONFIG.DOWNLOAD_MAX_HEIGHT;
                        quality = COMPRESSION_CONFIG.DOWNLOAD_QUALITY;
                    } else {
                        maxWidth = COMPRESSION_CONFIG.THUMB_MAX_WIDTH;
                        maxHeight = COMPRESSION_CONFIG.THUMB_MAX_HEIGHT;
                        quality = COMPRESSION_CONFIG.THUMB_QUALITY;
                    }
                    let width = img.width;
                    let height = img.height;
                    if (width > maxWidth) {
                        height = Math.round(height * (maxWidth / width));
                        width = maxWidth;
                    }
                    if (height > maxHeight) {
                        width = Math.round(width * (maxHeight / height));
                        height = maxHeight;
                    }
                    const canvas = document.createElement('canvas');
                    canvas.width = width;
                    canvas.height = height;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, width, height);
                    resolve(canvas.toDataURL('image/jpeg', quality));
                };
                img.onerror = () => reject(new Error('Failed to compress image'));
                img.src = imageData;
            } catch (error) {
                reject(error);
            }
        });
    }

    // DOM ELEMENTS
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const blackScreen = document.getElementById('blackScreen');
    const countdownText = document.getElementById('countdownText');
    const progressCounter = document.getElementById('progressCounter');
    const startBtn = document.getElementById('startBtn');
    const invertBtn = document.getElementById('invertBtn');
    const doneBtn = document.getElementById('doneBtn');
    const flash = document.getElementById('flash');
    const photoContainer = document.getElementById('photoContainer');
    const gridOverlay = document.getElementById('gridOverlay');
    const gridToggleBtn = document.getElementById('gridToggleBtn');
    const fullscreenBtn = document.getElementById('fullscreenBtn');
    const videoContainer = document.getElementById("videoContainer");
    const fullscreenMessage = document.getElementById("fullscreenMessage");
    const filterMessage = document.getElementById("filterMessage");
    const uploadInput = document.getElementById('uploadInput');
    const uploadBtn = document.getElementById('uploadBtn');
    const timerOptions = document.getElementById("timerOptions");

    // Filter buttons
    const bnwFilter = document.getElementById('bnwFilterId');
    const sepiaFilter = document.getElementById('sepiaFilterId');
    const smoothFilter = document.getElementById('smoothFilterId');
    const grayFilter = document.getElementById('grayFilterId');
    const vintageFilter = document.getElementById('vintageFilterId');
    const normalFilter = document.getElementById('normalFilterId');

    // Event listeners
    if (timerOptions) {
        timerOptions.addEventListener("change", updateCountdown);
    }

    window.addEventListener("beforeunload", () => {
        let stream = document.querySelector("video")?.srcObject;
        if (stream) {
            stream.getTracks().forEach((track) => track.stop());
        }
    });

    // ALL THE FUNCTIONS FROM LAYOUT 1...
    // (Copy all functions from canvasLayout1.js but replace storage keys and URLs)
    
    console.log(`🎯 ${layoutName} (${expectedPhotos} photos) initialized successfully!`);
});
