document.addEventListener('DOMContentLoaded', () => {
    // 🚀 COMPRESSION CONFIGURATION - 3-Level Quality System for Layout 2 (4 Photos)
    const COMPRESSION_CONFIG = {
        // Untuk session storage (temporary) - FAST SAVE
        SESSION_QUALITY: 0.5,        // 50% untuk performa cepat
        SESSION_MAX_WIDTH: 1200,     // Resize untuk storage
        SESSION_MAX_HEIGHT: 800,
        
        // Untuk download/print (high quality) - BEST QUALITY  
DOWNLOAD_QUALITY: 1.05,      // 95% - hampir lossless
        DOWNLOAD_MAX_WIDTH: 2400,    // Full resolution
        DOWNLOAD_MAX_HEIGHT: 1600,
        
        // Untuk preview thumbnail - FAST PREVIEW
        THUMB_QUALITY: 0.6,          // 60% - kecil untuk preview
        THUMB_MAX_WIDTH: 300,
        THUMB_MAX_HEIGHT: 200
    };

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
                    // Resize logic
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
                    // Output JPEG untuk kompresi lebih kecil
                    resolve(canvas.toDataURL('image/jpeg', quality));
                };
                img.onerror = () => reject(new Error('Failed to compress image'));
                img.src = imageData;
            } catch (error) {
                reject(error);
            }
        });
    }

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

    const bnwFilter = document.getElementById('bnwFilterId');
    const sepiaFilter = document.getElementById('sepiaFilterId');
    const smoothFilter = document.getElementById('smoothFilterId');
    const grayFilter = document.getElementById('grayFilterId');
    const vintageFilter = document.getElementById('vintageFilterId');
    const normalFilter = document.getElementById('normalFilterId');

    const fullscreenBtn = document.getElementById('fullscreenBtn');
    const videoContainer = document.getElementById("videoContainer");
    const fullscreenMessage = document.getElementById("fullscreenMessage");
    const filterMessage = document.getElementById("filterMessage");
    const fullscreenImg = fullscreenBtn && fullscreenBtn.querySelector("img");

    const uploadInput = document.getElementById('uploadInput');
    const uploadBtn = document.getElementById('uploadBtn');

    const timerOptions = document.getElementById("timerOptions");
    if (timerOptions) {
        timerOptions.addEventListener("change", updateCountdown);
    }

    window.addEventListener("beforeunload", () => {
        let stream = document.querySelector("video")?.srcObject;
        if (stream) {
            stream.getTracks().forEach((track) => track.stop());
        }
    });

    // 🎠 CAROUSEL MODAL FUNCTIONALITY
    const carouselModal = document.getElementById('carousel-modal');
    const carouselImage = document.getElementById('carousel-image');
    const carouselCloseBtn = document.getElementById('carousel-close-btn');
    const carouselPrevBtn = document.getElementById('carousel-prev-btn');
    const carouselNextBtn = document.getElementById('carousel-next-btn');
    const carouselRetakeBtn = document.getElementById('carousel-retake-btn');
    const carouselIndicators = document.getElementById('carousel-indicators');
    let currentImageIndex = 0;

    function openCarousel(index) {
        console.log('Opening carousel at index:', index, 'Images available:', images.length);
        
        if (!carouselModal || !carouselImage || images.length === 0) {
            console.log('Cannot open carousel - missing elements or no images');
            return;
        }

        // Pastikan index valid
        if (index < 0 || index >= images.length || !images[index]) {
            console.log('Invalid index or no image at index:', index);
            return;
        }
        
        currentImageIndex = index;
        updateCarousel();
        carouselModal.style.display = 'flex';
        carouselModal.classList.remove('fade-out');
        carouselModal.classList.add('fade-in');
        document.body.style.overflow = 'hidden';
    }

    function closeCarousel() {
        if (!carouselModal) return;
        
        carouselModal.classList.remove('fade-in');
        carouselModal.classList.add('fade-out');
        document.body.style.overflow = 'auto';
        
        setTimeout(() => {
            carouselModal.style.display = 'none';
            carouselModal.classList.remove('fade-out');
        }, 300);
    }

    function updateCarousel() {
        if (!carouselImage) return;

        // Update image with smooth transition
        carouselImage.style.opacity = '0';
        carouselImage.style.transform = 'scale(0.95)';
        
        setTimeout(() => {
            if (images[currentImageIndex]) {
                carouselImage.src = images[currentImageIndex];
                carouselImage.alt = `Photo ${currentImageIndex + 1}`;
                
                carouselImage.onload = () => {
                    carouselImage.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                    carouselImage.style.opacity = '1';
                    carouselImage.style.transform = 'scale(1)';
                };
            }
        }, 200);

        // Update indicators
        carouselIndicators.innerHTML = '';
        images.forEach((_, i) => {
            const indicator = document.createElement('div');
            indicator.classList.add('carousel-indicator');
            if (i === currentImageIndex) indicator.classList.add('active');
            
            indicator.addEventListener('click', () => {
                if (i !== currentImageIndex) {
                    currentImageIndex = i;
                    updateCarousel();
                }
            });
            
            carouselIndicators.appendChild(indicator);
        });

        // Update navigation buttons
        if (carouselPrevBtn) carouselPrevBtn.disabled = currentImageIndex === 0;
        if (carouselNextBtn) carouselNextBtn.disabled = currentImageIndex === images.length - 1;
        
        // Update retake button
        if (carouselRetakeBtn) {
            carouselRetakeBtn.style.display = images[currentImageIndex] ? 'flex' : 'none';
            carouselRetakeBtn.onclick = (e) => {
                e.stopPropagation();
                console.log('Retaking photo at index:', currentImageIndex);
                closeCarousel();
                setTimeout(() => retakeSinglePhoto(currentImageIndex), 300);
            };
        }
    }

    // Navigation event listeners
    if (carouselPrevBtn) {
        carouselPrevBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            if (currentImageIndex > 0) {
                currentImageIndex--;
                updateCarousel();
            }
        });
    }

    if (carouselNextBtn) {
        carouselNextBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            if (currentImageIndex < images.length - 1) {
                currentImageIndex++;
                updateCarousel();
            }
        });
    }

    // Close button event listener
    if (carouselCloseBtn) {
        carouselCloseBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            closeCarousel();
        });
    }

    // Modal backdrop click to close
    if (carouselModal) {
        carouselModal.addEventListener('click', (e) => {
            if (e.target === carouselModal) {
                closeCarousel();
            }
        });
    }

    // Keyboard navigation
    document.addEventListener('keydown', (e) => {
        if (!carouselModal || carouselModal.style.display === 'none') return;
        
        e.preventDefault();
        switch(e.key) {
            case 'Escape':
                closeCarousel();
                break;
            case 'ArrowLeft':
                if (currentImageIndex > 0) {
                    currentImageIndex--;
                    updateCarousel();
                }
                break;
            case 'ArrowRight':
                if (currentImageIndex < images.length - 1) {
                    currentImageIndex++;
                    updateCarousel();
                }
                break;
        }
    });

    // Global functions for modal
    window.openCarousel = openCarousel;
    window.closeCarousel = closeCarousel;

    // Add enhanced CSS styles for modal and animations (same as Layout 1)
    const style = document.createElement('style');
    style.textContent = `
        /* Modal Animations */
        .modal.fade-in {
            animation: modalFadeIn 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }
        .modal.fade-out {
            animation: modalFadeOut 0.3s cubic-bezier(0.55, 0.06, 0.68, 0.19);
        }
        
        @keyframes modalFadeIn {
            from { 
                opacity: 0; 
                transform: scale(0.9);
                backdrop-filter: blur(0px);
            }
            to { 
                opacity: 1; 
                transform: scale(1);
                backdrop-filter: blur(10px);
            }
        }
        
        @keyframes modalFadeOut {
            from { 
                opacity: 1; 
                transform: scale(1);
                backdrop-filter: blur(10px);
            }
            to { 
                opacity: 0; 
                transform: scale(0.9);
                backdrop-filter: blur(0px);
            }
        }
        
        /* Enhanced Photo Preview Slots */
        .photo-preview-slot {
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            position: relative;
            overflow: hidden;
        }
        
        .photo-preview-slot:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 8px 25px rgba(233, 30, 99, 0.3);
        }
        
        .photo-preview-slot.filled:hover {
            transform: translateY(-6px) scale(1.05);
            box-shadow: 0 12px 35px rgba(233, 30, 99, 0.4);
        }
        
        .photo-preview-slot::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 1;
            pointer-events: none;
        }
        
        .photo-preview-slot:hover::before {
            opacity: 1;
        }
        
        .photo-preview-slot img {
            transition: transform 0.3s ease;
        }
        
        .photo-preview-slot:hover img {
            transform: scale(1.05);
        }
        
        /* Enhanced Carousel Image */
        .carousel-image {
            max-width: 90%;
            max-height: 75vh;
            object-fit: contain;
            transition: opacity 0.4s ease, transform 0.4s ease;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
        }
        
        /* Enhanced Modal Styling */
        .modal {
            backdrop-filter: blur(10px);
            background: rgba(0, 0, 0, 0.8);
        }
        
        .carousel-container {
            position: relative;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        /* Enhanced Navigation Buttons */
        .carousel-nav-btn {
            background: rgba(233, 30, 99, 0.9);
            border: none;
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 15px rgba(233, 30, 99, 0.3);
        }
        
        .carousel-nav-btn:hover:not(:disabled) {
            background: rgba(233, 30, 99, 1);
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(233, 30, 99, 0.4);
        }
        
        .carousel-nav-btn:disabled {
            background: rgba(150, 150, 150, 0.5);
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
        
        /* Enhanced Close Button */
        .carousel-close-btn {
            background: rgba(255, 59, 48, 0.9);
            border: none;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            font-size: 20px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            top: 15px;
            right: 15px;
            z-index: 10;
            backdrop-filter: blur(10px);
        }
        
        .carousel-close-btn:hover {
            background: rgba(255, 59, 48, 1);
            transform: scale(1.1);
            box-shadow: 0 4px 15px rgba(255, 59, 48, 0.4);
        }
        
        /* Enhanced Retake Button */
        .carousel-retake-btn {
            background: linear-gradient(135deg, #FF6B35, #FF8E53);
            border: none;
            color: white;
            padding: 12px 20px;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 14px;
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            box-shadow: 0 4px 15px rgba(255, 107, 53, 0.3);
        }
        
        .carousel-retake-btn:hover {
            transform: translateX(-50%) translateY(-2px) scale(1.05);
            box-shadow: 0 8px 25px rgba(255, 107, 53, 0.4);
        }
        
        .carousel-retake-btn img {
            width: 16px;
            height: 16px;
            filter: brightness(0) invert(1);
        }
        
        /* Enhanced Indicators */
        .carousel-indicators {
            display: flex;
            gap: 8px;
            justify-content: center;
            margin-top: 20px;
            position: absolute;
            bottom: 70px;
            left: 50%;
            transform: translateX(-50%);
        }
        
        .carousel-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(150, 150, 150, 0.5);
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        
        .carousel-indicator:hover {
            background: rgba(233, 30, 99, 0.7);
            transform: scale(1.2);
        }
        
        .carousel-indicator.active {
            background: rgba(233, 30, 99, 1);
            transform: scale(1.3);
            border-color: rgba(255, 255, 255, 0.8);
            box-shadow: 0 2px 8px rgba(233, 30, 99, 0.4);
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .carousel-container {
                margin: 10px;
                padding: 15px;
            }
            
            .carousel-image {
                max-height: 60vh;
            }
            
            .carousel-nav-btn {
                width: 40px;
                height: 40px;
                font-size: 16px;
            }
            
            .carousel-retake-btn {
                padding: 10px 16px;
                font-size: 13px;
            }
        }
    `;
    document.head.appendChild(style);

    // Update countdown text based on selected timer option
    function toggleFullscreen() {
        const videoContainer = document.getElementById('videoContainer');
        const fullscreenMessage = document.getElementById('fullscreenMessage');
        const fullscreenImg = document.querySelector('#fullscreenBtn img');
        let fullscreenBtnElement = document.getElementById('startBtnFullscreen');

        if (
            !document.fullscreenElement &&
            !document.mozFullScreenElement &&
            !document.webkitFullscreenElement &&
            !document.msFullscreenElement
        ) {
            // === ENTER FULLSCREEN ===
            if (videoContainer.requestFullscreen) {
                videoContainer.requestFullscreen();
            } else if (videoContainer.mozRequestFullScreen) {
                videoContainer.mozRequestFullScreen();
            } else if (videoContainer.webkitRequestFullscreen) {
                videoContainer.webkitRequestFullscreen();
            } else if (videoContainer.msRequestFullscreen) {
                videoContainer.msRequestFullscreen();
            }

            if (fullscreenMessage) fullscreenMessage.style.opacity = "1";
            if (fullscreenImg) fullscreenImg.src = "/src/assets/fullScreen2.png";

            setTimeout(() => {
                if (fullscreenMessage) fullscreenMessage.style.opacity = "0";
            }, 1000);

            // === Tambahkan tombol START CAPTURE ===
            if (!fullscreenBtnElement) {
                fullscreenBtnElement = document.createElement('button');
                fullscreenBtnElement.id = 'startBtnFullscreen';
                fullscreenBtnElement.textContent = 'START CAPTURE';
                fullscreenBtnElement.style.position = 'absolute';
                fullscreenBtnElement.style.bottom = '20px';
                fullscreenBtnElement.style.left = '50%';
                fullscreenBtnElement.style.transform = 'translateX(-50%)';
                fullscreenBtnElement.style.zIndex = '9999';
                fullscreenBtnElement.style.padding = '12px 24px';
                fullscreenBtnElement.style.backgroundColor = '#ff4081';
                fullscreenBtnElement.style.color = 'white';
                fullscreenBtnElement.style.border = 'none';
                fullscreenBtnElement.style.borderRadius = '8px';
                fullscreenBtnElement.style.fontSize = '16px';
                fullscreenBtnElement.style.cursor = 'pointer';

                // Klik tombol ini akan trigger tombol asli (startBtn)
                fullscreenBtnElement.addEventListener('click', () => {
                    document.getElementById('startBtn').click();
                });

                videoContainer.appendChild(fullscreenBtnElement);
            }

        } else {
            // === EXIT FULLSCREEN ===
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            }

            if (fullscreenMessage) fullscreenMessage.style.opacity = "0";
            if (fullscreenImg) fullscreenImg.src = "/src/assets/fullScreen3.png";

            // Hapus tombol START CAPTURE ketika keluar fullscreen
            if (fullscreenBtnElement) {
                fullscreenBtnElement.remove();
            }
        }
    }
    // Add event listener to fullscreen button
    if (fullscreenBtn) {
        fullscreenBtn.addEventListener("click", toggleFullscreen);
    }

    if(bnwFilter) {
        bnwFilter.addEventListener('click', () => {
            applyFilter("grayscale");
            filterText("BNW")
        });
    }

    if(sepiaFilter) {
        sepiaFilter.addEventListener('click', () => {
            applyFilter("sepia");
            filterText("cyber")
        });
    }

    if(smoothFilter) {
        smoothFilter.addEventListener('click', () => {
            applyFilter("smooth");
            filterText("smooth")
        });
    }

    if(grayFilter) {
        grayFilter.addEventListener('click', () => {
            applyFilter("gray");
            filterText("grayscale")
        });
    }

    if(vintageFilter) {
        vintageFilter.addEventListener('click', () => {
            applyFilter("vintage");
            filterText("vintage")
        });
    }

    if(normalFilter) {
        normalFilter.addEventListener('click', () => {
            applyFilter("none");
            filterText("none")
        });
    }

    function applyFilter(filterClass) {
        if (!video) return;
        
        // Remove existing filters
        video.classList.remove("sepia", "grayscale","smooth","gray","vintage");

        // Apply new filter if not 'none'
        if (filterClass !== "none") {
            video.classList.add(filterClass);
        }
    }

    function filterText(chosenFilter) {
        if (!filterMessage) return;
        
        filterMessage.style.opacity = "1";
        filterMessage.innerHTML = chosenFilter;
            
        setTimeout(() => {
            filterMessage.style.opacity = "0"; // Fade out
        }, 1000);
    }

    let images = [];
    let currentPhotoIndex = 0;
    const expectedPhotos = 8; // Layout 4 memiliki 8 foto
    let invertBtnState = false;

    // Update UI state
    function updateUI() {
        const progressCounter = document.getElementById('progressCounter');
        const startBtn = document.getElementById('startBtn');
        const retakeAllBtn = document.getElementById('retakeAllBtn');
        const doneBtn = document.getElementById('doneBtn');
        
        if (progressCounter) {
            progressCounter.textContent = `${images.length}/${expectedPhotos}`;
        }
        
        // Enable/disable retake all button
        if (retakeAllBtn) {
            retakeAllBtn.disabled = images.length === 0;
        }
        
        // Show done button when all photos are taken
        if (images.length === expectedPhotos) {
            if (startBtn) startBtn.style.display = 'none';
            if (doneBtn) doneBtn.style.display = 'block';
        } else {
            if (startBtn) startBtn.style.display = 'block';
            if (doneBtn) doneBtn.style.display = 'none';
        }
        
        // Update start button text
        if (startBtn && images.length < expectedPhotos) {
            startBtn.textContent = `AMBIL FOTO ${images.length + 1}`;
        }
    }

    // Update photo preview slots
    function updatePhotoPreview(index, imageData) {
        const photoSlot = document.querySelector(`.photo-preview-slot[data-index="${index}"]`);
        if (!photoSlot) return;
        
        // Clear existing content
        photoSlot.innerHTML = '';
        
        // Add image
        const img = document.createElement('img');
        img.src = imageData;
        img.alt = `Photo ${index + 1}`;
        img.style.cursor = 'pointer';
        
        // Add click event to open carousel modal
        img.addEventListener('click', (e) => {
            e.stopPropagation();
            console.log('Image clicked, opening carousel for index:', index);
            openCarousel(index);
        });
        
        photoSlot.appendChild(img);
        
        // Add retake button
        const retakeBtn = document.createElement('button');
        retakeBtn.className = 'retake-photo-btn';
        retakeBtn.innerHTML = '↻';
        retakeBtn.title = `Retake Photo ${index + 1}`;
        retakeBtn.onclick = (e) => {
            e.stopPropagation();
            retakeSinglePhoto(index);
        };
        photoSlot.appendChild(retakeBtn);
        
        // Add filled class and make entire slot clickable
        photoSlot.classList.add('filled');
        photoSlot.style.cursor = 'pointer';
        
        // Add slot click event as backup
        photoSlot.addEventListener('click', (e) => {
            // Only trigger if clicking on the slot itself, not buttons
            if (e.target === photoSlot || e.target === img) {
                e.stopPropagation();
                console.log('Photo slot clicked, opening carousel for index:', index);
                openCarousel(index);
            }
        });
    }

    // Clear photo preview slot
    function clearPhotoPreview(index) {
        const photoSlot = document.querySelector(`.photo-preview-slot[data-index="${index}"]`);
        if (!photoSlot) return;
        
        photoSlot.innerHTML = `<div class="photo-placeholder">Photo ${index + 1}</div>`;
        photoSlot.classList.remove('filled');
    }

    // Single photo capture function
    async function capturePhoto() {
        if (images.length >= expectedPhotos) {
            return;
        }
        
        const startBtn = document.getElementById('startBtn');
        const uploadBtn = document.getElementById('uploadBtn');
        
        if (startBtn) {
            startBtn.disabled = true;
            startBtn.textContent = 'Capturing...';
        }
        if (uploadBtn) uploadBtn.disabled = true;
        
        try {
            const timerOptions = document.getElementById("timerOptions");
            const selectedValue = parseInt(timerOptions?.value) || 3;
            
            await showCountdown(selectedValue);
            
            if (flash) {
                flash.style.opacity = 1;
                setTimeout(() => flash.style.opacity = 0, 200);
            }
            
            if (!video || video.videoWidth === 0 || video.videoHeight === 0) {
                throw new Error("Camera not ready. Please try again.");
            }
            
            if (!canvas) {
                throw new Error("Canvas element not found. Please refresh the page.");
            }
            
            const ctx = canvas.getContext('2d');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            
            ctx.filter = getComputedStyle(video).filter;
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            let imageData = canvas.toDataURL('image/png');
            if (invertBtnState) {
                imageData = await applyMirrorEffect(imageData);
            }
            
            const compressedImage = await compressImage(imageData, 'session'); // Compress for preview
            images.push(compressedImage);
            
            updatePhotoPreview(images.length - 1, compressedImage);
            updateUI();
            
        } catch (error) {
            console.error('Error capturing photo:', error);
            alert(error.message || 'Error capturing photo. Please try again.');
        } finally {
            if (startBtn) {
                startBtn.disabled = false;
                if (images.length < expectedPhotos) {
                    startBtn.textContent = `AMBIL FOTO ${images.length + 1}`;
                }
            }
            if (uploadBtn) uploadBtn.disabled = false;
        }
    }

    // 🚀 AMBIL BERSAMAAN PHOTOS FUNCTION - Auto capture dengan interval untuk Layout 2
    async function captureAllPhotos() {
        if (images.length >= expectedPhotos) {
            return;
        }

        try {
            // Disable buttons during capture all process
            const captureAllBtn = document.getElementById('captureAllBtn');
            const startBtn = document.getElementById('startBtn');
            const retakeAllBtn = document.getElementById('retakeAllBtn');
            const uploadBtn = document.getElementById('uploadBtn');
            
            if (captureAllBtn) {
                captureAllBtn.disabled = true;
                captureAllBtn.textContent = 'CAPTURING...';
            }
            if (startBtn) startBtn.disabled = true;
            if (retakeAllBtn) retakeAllBtn.disabled = true;
            if (uploadBtn) uploadBtn.disabled = true;

            const remainingPhotos = expectedPhotos - images.length;
            const timerOptions = document.getElementById("timerOptions");
            const selectedValue = parseInt(timerOptions?.value) || 3;
            
            for (let i = 0; i < remainingPhotos; i++) {
                // Show which photo we're taking
                if (progressCounter) {
                    progressCounter.textContent = `Sedang Mengambil Foto ${images.length + 1}/${expectedPhotos}`;
                    progressCounter.style.fontSize = '18px';
                    progressCounter.style.color = '#ff4081';
                }

                // Countdown for each photo
                await showCountdown(selectedValue);
                
                // Flash effect
                if (flash) {
                    flash.style.opacity = 1;
                    setTimeout(() => flash.style.opacity = 0, 200);
                }
                
                // Ensure video dimensions are loaded
                if (!video || video.videoWidth === 0 || video.videoHeight === 0) {
                    throw new Error("Camera not ready. Please try again.");
                }
                
                if (!canvas) {
                    throw new Error("Canvas element not found. Please refresh the page.");
                }
                
                const ctx = canvas.getContext('2d');
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                
                ctx.filter = getComputedStyle(video).filter;
                ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                
                let imageData = canvas.toDataURL('image/png');
                if (invertBtnState) {
                    imageData = await applyMirrorEffect(imageData);
                }
                
                const compressedImage = await compressImage(imageData, 'session');
                images.push(compressedImage);
                
                updatePhotoPreview(images.length - 1, compressedImage);
                updateUI();
                
                console.log(`Auto Photo ${images.length}/${expectedPhotos} captured successfully`);
                
                // Wait 2 seconds before next photo (except for last photo)
                if (i < remainingPhotos - 1) {
                    if (progressCounter) {
                        progressCounter.textContent = `Foto berikutnya dalam 2 detik...`;
                    }
                    await new Promise(resolve => setTimeout(resolve, 2000));
                }
            }
            
            // Reset progress counter style
            if (progressCounter) {
                progressCounter.style.fontSize = '';
                progressCounter.style.color = '';
            }
            
            
        } catch (error) {
            console.error('Error in capture all:', error);
            alert(error.message || 'Gagal mengambil semua foto. Silakan coba lagi.');
        } finally {
            // Re-enable buttons
            const captureAllBtn = document.getElementById('captureAllBtn');
            const startBtn = document.getElementById('startBtn');
            const retakeAllBtn = document.getElementById('retakeAllBtn');
            const uploadBtn = document.getElementById('uploadBtn');
            
            if (captureAllBtn) {
                captureAllBtn.disabled = false;
                captureAllBtn.textContent = 'AMBIL BERSAMAAN';
            }
            if (startBtn) startBtn.disabled = false;
            if (retakeAllBtn) retakeAllBtn.disabled = false;
            if (uploadBtn) uploadBtn.disabled = false;
        }
    }

    // Retake single photo function
    async function retakeSinglePhoto(index) {
        if (index < 0 || index >= images.length) return;
        
        const startBtn = document.getElementById('startBtn');
        const uploadBtn = document.getElementById('uploadBtn');
        const doneBtn = document.getElementById('doneBtn');
        
        // Disable buttons during retake
        if (startBtn) startBtn.disabled = true;
        if (uploadBtn) uploadBtn.disabled = true;
        if (doneBtn) doneBtn.disabled = true;
        
        try {
            // Get the selected timer value
            const timerOptions = document.getElementById("timerOptions");
            const selectedValue = parseInt(timerOptions?.value) || 3;
            
            // Countdown
            await showCountdown(selectedValue);
            
            // Flash Effect
            if (flash) {
                flash.style.opacity = 1;
                setTimeout(() => flash.style.opacity = 0, 200);
            }
            
            // Ensure video dimensions are loaded
            if (!video || video.videoWidth === 0 || video.videoHeight === 0) {
                throw new Error("Camera not ready. Please try again.");
            }
            
            // Ensure canvas exists
            if (!canvas) {
                throw new Error("Canvas element not found. Please refresh the page.");
            }
            
            // Capture Image with Filter Applied
            const ctx = canvas.getContext('2d');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            
            // Apply current video filter to the canvas
            ctx.filter = getComputedStyle(video).filter;
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            // Apply mirror effect if enabled
            let imageData = canvas.toDataURL('image/png');
            if (invertBtnState) {
                imageData = await applyMirrorEffect(imageData);
            }
            
            // Update images array
            images[index] = imageData;
            console.log(`Retake Photo ${index + 1}: `, imageData);
            
            // Update photo preview
            updatePhotoPreview(index, imageData);
            
            // Update UI2
            updateUI();
            
        } catch (error) {
            console.error('Error retaking photo:', error);
            alert(error.message || 'Gagal mengambil ulang foto. Silakan coba lagi.');
        } finally {
            // Re-enable buttons
            if (startBtn) startBtn.disabled = false;
            if (uploadBtn) uploadBtn.disabled = false;
            if (doneBtn) doneBtn.disabled = false;
        }
    }

        // Retake all photos function
    function retakeAllPhotos() {
        const confirmReset = confirm("Are you sure you want to retake all photos?");
        if (!confirmReset) return;
        
        // Clear all images
        images = [];
        
        // Clear all photo previews
        for (let i = 0; i < expectedPhotos; i++) {
            clearPhotoPreview(i);
        }
        
        // Update UI
        updateUI();
        
        console.log('All photos cleared for retake');
    }

    // Global function for HTML onclick
    window.retakeSinglePhoto = retakeSinglePhoto;

    if(invertBtn) {
        invertBtn.addEventListener('click', () => {
            invertBtnState = !invertBtnState;
            cameraInvertSwitch();
            filterText("invert");
        });
    }

    function cameraInvertSwitch() {
        if (invertBtnState == true) {
            if (photoContainer) photoContainer.style.transform = 'scaleX(-1)';
            if (video) video.style.transform = 'scaleX(-1)';
        } else {
            if (photoContainer) photoContainer.style.transform = 'scaleX(1)';
            if (video) video.style.transform = 'scaleX(1)';
        }
    }

    // 📹 CAMERA INITIALIZATION
    async function startCamera() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({
                video: {
                    width: { ideal: 1920 },
                    height: { ideal: 1080 },
                    facingMode: 'user'
                }
            });
            
            if (video) {
                video.srcObject = stream;
                video.addEventListener('loadedmetadata', () => {
                    if (blackScreen) {
                        blackScreen.style.opacity = '0';
                        setTimeout(() => {
                            if (blackScreen) blackScreen.style.display = 'none';
                        }, 1000);
                    }
                });
            }
        } catch (error) {
            console.error('Camera access error:', error);
            if (blackScreen) {
                blackScreen.textContent = 'Camera access denied. Please allow camera access and refresh the page.';
            }
        }
    }
    
    function stopCameraStream() {
        if (video && video.srcObject) {
            video.srcObject.getTracks().forEach(track => track.stop());
            video.srcObject = null;
        }
        // Hide grid when camera stops
        if (gridOverlay) {
            gridOverlay.style.display = 'none';
            if (gridToggleBtn) gridToggleBtn.textContent = 'Tampilkan Grid';
        }
    }

    // Start camera if on canvasLayout4.php
    if (window.location.pathname.endsWith("canvasLayout4.php") || window.location.pathname === "canvasLayout4.php") {
        startCamera();
    }

    // Toggle grid visibility
    if (gridToggleBtn) {
        gridToggleBtn.addEventListener('click', () => {
            if (gridOverlay) {
                gridOverlay.style.display = gridOverlay.style.display === 'grid' ? 'none' : 'grid';
                gridToggleBtn.textContent = gridOverlay.style.display === 'grid' ? 'Sembunyikan Grid' : 'Tampilkan Grid';
            }
        });
    }

    // Legacy function - not used in new single-capture system
    // Keeping for backward compatibility only
    async function startPhotobooth() {
        console.log('Legacy startPhotobooth called - redirecting to single capture');
        await capturePhoto();
    }

    // Legacy function - already implemented above in new system

    async function showCountdown(selectedValue) {
        if (!countdownText) return;
        
        // Hide grid during countdown
        if (gridOverlay) {
            gridOverlay.style.display = 'none';
        }
        
        countdownText.style.display = "flex";
        for (let countdown = selectedValue; countdown > 0; countdown--) {
            countdownText.textContent = countdown;
            countdownText.classList.remove("bounce");
            void countdownText.offsetWidth; // Trigger reflow for animation
            countdownText.classList.add("bounce");
            await new Promise(res => setTimeout(res, 1000));
        }
        countdownText.style.display = "none";
        
        // Show grid again after countdown
        if (gridOverlay) {
            gridOverlay.style.display = 'grid';
            if (gridToggleBtn) gridToggleBtn.textContent = 'Sembunyikan Grid';
        }
    }
    
    // Automatically trigger the countdown when option changes
    function updateCountdown() {
        showCountdown();
    }

    // Update Image Upload for Users to choose multiple images at once
    function handleImageUpload(event) {
        const photoCount = 8; // Layout 4 has 8 photos
        const files = Array.from(event.target.files);

        if (files.length === 0) {
            alert("Please upload a valid image file.");
            return;
        }

        for (const file of files) {
            if (!file.type.startsWith("image/")) continue;

            if (images.length >= photoCount) {
                const confirmReplace = confirm(`You already have ${photoCount} pictures. Uploading new images will replace all current pictures. Do you want to proceed?`);
                if (!confirmReplace) {
                    event.target.value = "";
                    return;
                }

                images = [];
                if (photoContainer) photoContainer.innerHTML = '';
                if (progressCounter) progressCounter.textContent = `0/${photoCount}`;
                if (startBtn) startBtn.innerHTML = 'Capturing...';
                if (doneBtn) doneBtn.style.display = 'none';
            }

            if (startBtn) startBtn.innerHTML = 'Capturing...';

            const reader = new FileReader();
            reader.onload = async function(e) {
                const imageData = e.target.result;
                const compressedImage = await compressImage(imageData, 'session'); // Compress for preview

                images.push(compressedImage);

                if (photoContainer) {
                    const container = document.createElement('div');
                    container.style.position = 'relative';
                    const imgElement = document.createElement('img');
                    imgElement.src = compressedImage;
                    imgElement.classList.add('photo');
                    imgElement.addEventListener('click', () => openCarousel(images.length - 1));
                    const retakeBtn = document.createElement('button');
                    retakeBtn.classList.add('retake-btn');
                    retakeBtn.innerHTML = `<img src="/src/assets/retake.png" alt="retake icon">`;
                    retakeBtn.addEventListener('click', (e) => {
                        e.stopPropagation();
                        retakeSinglePhoto(images.length - 1);
                    });
                    container.appendChild(imgElement);
                    container.appendChild(retakeBtn);
                    photoContainer.appendChild(container);
                }

                if (progressCounter) progressCounter.textContent = `${images.length}/${photoCount}`;

                if (images.length === photoCount) {
                    if (startBtn) startBtn.innerHTML = 'Retake All';
                    if (doneBtn) doneBtn.style.display = 'block';
                }
            };

            reader.readAsDataURL(file);
        }

        event.target.value = "";

    }


    // 🚀 OPTIMIZED STORE IMAGE FUNCTION - 3x FASTER!
    async function storeImageArray() {
        const photoCount = 8; // Layout 4 has 8 photos
        const doneBtn = document.getElementById('doneBtn');
        const startTime = Date.now(); // Add timing
        
        try {
            console.log('⚡ Starting FAST photo compression...');
            
            // Show progress
            if (doneBtn) {
                doneBtn.textContent = 'Compressing...';
                doneBtn.disabled = true;
            }
            
            const sessionPhotos = [];  // For session (80% quality - FAST)
            const originalPhotos = []; // Backup original (100% quality - DOWNLOAD)
            
            // Process each image
            for (let index = 0; index < images.length; index++) {
                const imgData = images[index];
                if (!imgData) continue;
                
                console.log(`📸 Processing image ${index + 1}/${photoCount}...`);
                
                // Update progress
                if (doneBtn) {
                    const progress = Math.round(((index + 1) / photoCount) * 50); // 50% for compression
                    doneBtn.textContent = `Compressing ${progress}%`;
                }
                
                let processedImage = imgData;
                
                // Apply mirror if needed
                if (typeof invertBtnState !== 'undefined' && invertBtnState) {
                    processedImage = await applyMirrorEffect(imgData);
                }
                
                // Compress for session (FAST SAVE)
                const compressedImage = await compressImage(processedImage, 'session');
                sessionPhotos[index] = compressedImage;
                
                // Keep original for download (HIGH QUALITY)
                originalPhotos[index] = processedImage;
                
                console.log(`✅ Image ${index + 1} compressed: ${Math.round(compressedImage.length / 1024)}KB`);
            }
            
            // Update progress
            if (doneBtn) {
                doneBtn.textContent = 'Menyimpan foto...';
            }
            
            console.log('💾 Saving compressed photos to server session...');
            
            // Save compressed photos to session (SUPER FAST)
            const response = await fetch('../api-fetch/save_photos.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ photos: sessionPhotos }),
                signal: AbortSignal.timeout(15000) // Reduced to 15s because smaller size
            });
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            
            const data = await response.json();
            
            if (data.success) {
                console.log(`🎉 SUCCESS! Photos saved in ${Date.now() - startTime}ms`);
                console.log(`📊 Compressed size: ${data.data_size || 'unknown'}`);
                
                // Save originals to localStorage for download
                try {
                    localStorage.setItem('fotobox_originals', JSON.stringify(originalPhotos));
                    localStorage.setItem('fotobox_timestamp', Date.now().toString());
                    console.log('💾 Original photos backed up for download');
                } catch (e) {
                    console.warn('⚠️ Could not backup originals:', e.message);
                }
                
                // Create customize session
                const sessionResponse = await fetch('../api-fetch/create_customize_session.php', {
                    method: 'POST',
                    signal: AbortSignal.timeout(5000)
                });
                
                if (sessionResponse.ok) {
                    const sessionData = await sessionResponse.json();
                    if (sessionData.success) {
                        console.log('✅ Customize session created');
                        window.location.href = 'customizeLayout4.php';
                        return;
                    }
                }
                
                // Fallback redirect even if session creation fails
                console.log('⚠️ Session creation failed, but proceeding...');
                window.location.href = 'customizeLayout4.php';

            } else {
                throw new Error(data.error || 'Failed to save photos');
            }
            
        } catch (error) {
            console.error('❌ Error in storeImageArray:', error);
            
            // Reset button state
            if (doneBtn) {
                doneBtn.textContent = 'Done';
                doneBtn.disabled = false;
            }
            
            let errorMessage = 'Error saving photos: ';
            if (error.name === 'TimeoutError') {
                errorMessage += 'Request timeout. Please try again.';
            } else if (error.message.includes('Failed to fetch')) {
                errorMessage += 'Network error. Check your connection.';
            } else {
                errorMessage += error.message;
            }
            
            alert(errorMessage);
        }
    }

        // 🪞 HELPER FUNCTION - Apply Mirror Effect
    function applyMirrorEffect(imgData) {
        return new Promise((resolve, reject) => {
            const img = new Image();
            img.onload = function() {
                const tempCanvas = document.createElement('canvas');
                const tempCtx = tempCanvas.getContext('2d');
                
                tempCanvas.width = img.width;
                tempCanvas.height = img.height;
                
                // Apply mirroring
                tempCtx.translate(img.width, 0);
                tempCtx.scale(-1, 1);
                tempCtx.drawImage(img, 0, 0, img.width, img.height);
                
                resolve(tempCanvas.toDataURL('image/png'));
            };
            img.onerror = () => reject(new Error('Failed to apply mirror effect'));
            img.src = imgData;
        });
    }

    // Event Listeners
    if(startBtn) {
        startBtn.addEventListener('click', () => capturePhoto());
    }

    // Add capture all button listener
    const captureAllBtn = document.getElementById('captureAllBtn');
    if (captureAllBtn) {
        captureAllBtn.addEventListener('click', () => captureAllPhotos());
    }

    // Add retake all button listener
    const retakeAllBtn = document.getElementById('retakeAllBtn');
    if (retakeAllBtn) {
        retakeAllBtn.addEventListener('click', () => retakeAllPhotos());
    }



    if (doneBtn) {
        doneBtn.addEventListener('click', () => storeImageArray());
    }

    // Initialize UI
    updateUI();
    
    // Add photo container event listener as fallback
    document.addEventListener('DOMContentLoaded', () => {
        const photoContainer = document.getElementById('photoContainer');
        if (photoContainer) {
            console.log('Adding fallback photo container event listener');
            photoContainer.addEventListener('click', (e) => {
                const slot = e.target.closest('.photo-preview-slot');
                const img = e.target.closest('img');
                
                console.log('Photo container clicked:', {
                    slot: slot,
                    img: img,
                    target: e.target,
                    dataIndex: slot ? slot.dataset.index : 'none'
                });
                
                if (slot && slot.dataset.index !== undefined && !e.target.classList.contains('retake-photo-btn')) {
                    const index = parseInt(slot.dataset.index);
                    console.log('Opening carousel for slot index:', index);
                    
                    if (images[index]) {
                        e.stopPropagation();
                        openCarousel(index);
                    } else {
                        console.log('No image available at index:', index);
                    }
                }
            });
        } else {
            console.log('Photo container not found');
        }
    });

    if (uploadBtn) {
        uploadBtn.addEventListener('click', () => {
            alert("Note: Please make sure your total photo size does not exceed 15MB.\nLarge images may cause saving issues.");
            if (uploadInput) uploadInput.click();
        });
    }
    
    if(uploadInput) {
        uploadInput.addEventListener('change', handleImageUpload);
    }
});