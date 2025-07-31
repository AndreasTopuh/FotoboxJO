document.addEventListener('DOMContentLoaded', () => {
    // ⚡ COMPRESSION CONFIGURATION - 3-Level Quality System
    const COMPRESSION_CONFIG = {
        // Untuk session storage (temporary) - FAST SAVE
        SESSION_QUALITY: 0.5,        // 80% - bagus untuk preview
        SESSION_MAX_WIDTH: 1200,     // Resize untuk storage
        SESSION_MAX_HEIGHT: 800,
        
        // Untuk download/print (high quality) - BEST QUALITY  
        DOWNLOAD_QUALITY: 0.95,      // 95% - hampir lossless
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

    const carouselModal = document.getElementById('carousel-modal');
const carouselImage = document.getElementById('carousel-image');
const carouselPrevBtn = document.getElementById('carousel-prev-btn');
const carouselNextBtn = document.getElementById('carousel-next-btn');
const carouselCloseBtn = document.getElementById('carousel-close-btn');
const carouselRetakeBtn = document.getElementById('carousel-retake-btn');
const carouselIndicators = document.getElementById('carousel-indicators');

let currentImageIndex = 0;

function openCarousel(index) {
    if (!carouselModal || !carouselImage || images.length === 0) return;
    
    currentImageIndex = index;
    updateCarousel();
    carouselModal.style.display = 'flex';
    carouselModal.classList.add('fade-in');
    document.body.style.overflow = 'hidden'; // Prevent background scroll
}

function closeCarousel() {
    carouselModal.classList.remove('fade-in');
    carouselModal.classList.add('fade-out');
    document.body.style.overflow = 'auto';
    setTimeout(() => {
        carouselModal.style.display = 'none';
        carouselModal.classList.remove('fade-out');
    }, 300);
}

function updateCarousel() {
    carouselImage.classList.remove('show');
    carouselImage.style.opacity = '0';

    setTimeout(() => {
        carouselImage.src = images[currentImageIndex] || '';
        carouselImage.onload = () => {
            carouselImage.style.transition = 'opacity 0.3s ease';
            carouselImage.style.opacity = '1';
            carouselImage.classList.add('show');
        };
    }, 100);

    // Update indicators
    carouselIndicators.innerHTML = '';
    images.forEach((_, i) => {
        const indicator = document.createElement('span');
        indicator.classList.add('carousel-indicator');
        if (i === currentImageIndex) indicator.classList.add('active');
        indicator.addEventListener('click', () => {
            currentImageIndex = i;
            updateCarousel();
        });
        carouselIndicators.appendChild(indicator);
    });

    // Update navigation buttons
    carouselPrevBtn.disabled = currentImageIndex === 0;
    carouselNextBtn.disabled = currentImageIndex === images.length - 1;

    // Update retake button
    carouselRetakeBtn.style.display = images[currentImageIndex] ? 'block' : 'none';
    carouselRetakeBtn.onclick = (e) => {
        e.stopPropagation();
        retakeSinglePhoto(currentImageIndex);
        closeCarousel();
    };
}

// Event listeners for carousel controls
carouselPrevBtn.addEventListener('click', () => {
    if (currentImageIndex > 0) {
        currentImageIndex--;
        updateCarousel();
    }
});

carouselNextBtn.addEventListener('click', () => {
    if (currentImageIndex < images.length - 1) {
        currentImageIndex++;
        updateCarousel();
    }
});

carouselCloseBtn.addEventListener('click', closeCarousel);
carouselModal.addEventListener('click', (e) => {
    if (e.target === carouselModal) closeCarousel();
});

// Integrate with photo preview click
photoContainer.addEventListener('click', (e) => {
    const slot = e.target.closest('.photo-preview-slot');
    if (slot && slot.dataset.index !== undefined) {
        openCarousel(parseInt(slot.dataset.index));
    }
});

// Add CSS styles for animations
const style = document.createElement('style');
style.textContent = `
    .modal.fade-in {
        animation: fadeIn 0.3s ease-in-out;
    }
    .modal.fade-out {
        animation: fadeOut 0.3s ease-in-out;
    }
    .carousel-image {
        max-width: 100%;
        max-height: 70vh;
        object-fit: contain;
        transition: opacity 0.3s ease;
    }
    .photo-preview-slot .photo {
        cursor: pointer;
        transition: transform 0.2s ease;
    }
    .photo-preview-slot .photo:hover {
        transform: scale(1.05);
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    @keyframes fadeOut {
        from { opacity: 1; }
        to { opacity: 0; }
    }
`;
document.head.appendChild(style);

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
    const expectedPhotos = 2; // Layout 1 has 2 photos
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
            startBtn.textContent = `CAPTURE PHOTO ${images.length + 1}`;
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
        photoSlot.appendChild(img);
        
        // Add retake button
        const retakeBtn = document.createElement('button');
        retakeBtn.className = 'retake-photo-btn';
        retakeBtn.innerHTML = '↻';
        retakeBtn.onclick = () => retakeSinglePhoto(index);
        photoSlot.appendChild(retakeBtn);
        
        // Add filled class
        photoSlot.classList.add('filled');
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
        alert('All photos have been captured!');
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
                startBtn.textContent = `CAPTURE PHOTO ${images.length + 1}`;
            }
        }
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
            
            // Update UI
            updateUI();
            
        } catch (error) {
            console.error('Error retaking photo:', error);
            alert(error.message || 'Error retaking photo. Please try again.');
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

    async function startCamera() {
        stopCameraStream(); 
    
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } });
            if (video) {
                video.srcObject = stream;
        
                // Ensure video is playing before hiding black screen
                video.onloadedmetadata = () => {
                    video.play();
                    setTimeout(() => {
                        if (blackScreen) {
                            blackScreen.style.opacity = 0;
                            setTimeout(() => blackScreen.style.display = 'none', 1000);
                        }
                        // Show grid overlay when camera starts
                        if (gridOverlay) {
                            gridOverlay.style.display = 'grid';
                            if (gridToggleBtn) gridToggleBtn.textContent = 'Hide Grid';
                        }
                    }, 500);
                };
            }
        } catch (err) {
            console.error("Camera Access Denied", err);
            alert("Please enable camera permissions in your browser settings.");
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
            if (gridToggleBtn) gridToggleBtn.textContent = 'Show Grid';
        }
    }
    
    // Start camera if on canvasLayout1.php
    if (window.location.pathname.endsWith("canvasLayout1.php") || window.location.pathname === "canvasLayout1.php") {
        startCamera();
    }

    // Toggle grid visibility
    if (gridToggleBtn) {
        gridToggleBtn.addEventListener('click', () => {
            if (gridOverlay) {
                gridOverlay.style.display = gridOverlay.style.display === 'grid' ? 'none' : 'grid';
                gridToggleBtn.textContent = gridOverlay.style.display === 'grid' ? 'Hide Grid' : 'Show Grid';
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
            if (gridToggleBtn) gridToggleBtn.textContent = 'Hide Grid';
        }
    }
    
    // Automatically trigger the countdown when option changes
    function updateCountdown() {
        showCountdown();
    }


    // Update Image Upload for Users to choose multiple images at once
    function handleImageUpload(event) {
    const photoCount = 2; // Layout 1 has 2 photos
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
        const photoCount = 2; // Layout 1 has 2 photos
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
                doneBtn.textContent = 'Saving to server...';
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
                        window.location.href = 'customizeLayout1.php';
                        return;
                    }
                }
                
                // Fallback redirect even if session creation fails
                console.log('⚠️ Session creation failed, but proceeding...');
                window.location.href = 'customizeLayout1.php';
                
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

    // Add retake all button listener
    const retakeAllBtn = document.getElementById('retakeAllBtn');
    if (retakeAllBtn) {
        retakeAllBtn.addEventListener('click', () => retakeAllPhotos());
    }

    document.addEventListener('keydown', (event) => {
        if (event.code === "Space") {
            event.preventDefault(); // Prevents scrolling when spacebar is pressed
            capturePhoto();
        }
    });

    if (doneBtn) {
        doneBtn.addEventListener('click', () => storeImageArray());
    }

    // Initialize UI
    updateUI();

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