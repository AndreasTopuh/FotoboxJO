document.addEventListener('DOMContentLoaded', () => {
    // 🚀 COMPRESSION CONFIGURATION - 3-Level Quality System for Layout 4 (8 Photos)
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

    // 🎯 LAYOUT 4 CONFIGURATION - 8 PHOTOS
    const expectedPhotos = 8; // Layout 4 memiliki 8 foto
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

    // 🎮 FULLSCREEN FUNCTIONALITY
    function toggleFullscreen() {
        if (!document.fullscreenElement && 
            !document.mozFullScreenElement && 
            !document.webkitFullscreenElement && 
            !document.msFullscreenElement) {
            
            if (videoContainer.requestFullscreen) {
                videoContainer.requestFullscreen();
            } else if (videoContainer.mozRequestFullScreen) {
                videoContainer.mozRequestFullScreen();
            } else if (videoContainer.webkitRequestFullscreen) {
                videoContainer.webkitRequestFullscreen();
            } else if (videoContainer.msRequestFullscreen) {
                videoContainer.msRequestFullscreen();
            }

            if (fullscreenImg) {
                fullscreenImg.src = "/src/assets/minimize3.png";
            }
            if (fullscreenMessage) {
                fullscreenMessage.style.opacity = "1";
                setTimeout(() => {
                    if (fullscreenMessage) fullscreenMessage.style.opacity = "0";
                }, 2000);
            }
        
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            }

            if (fullscreenImg) {
                fullscreenImg.src = "/src/assets/fullScreen3.png";
            }
        }
    }
    
    if (fullscreenBtn) {
        fullscreenBtn.addEventListener("click", toggleFullscreen);
    }

    // 🎨 FILTER FUNCTIONALITY
    if(bnwFilter) {
        bnwFilter.addEventListener('click', () => {
            applyFilter('bnw');
            filterText('Black & White Filter Applied');
        });
    }

    if(sepiaFilter) {
        sepiaFilter.addEventListener('click', () => {
            applyFilter('sepia');
            filterText('Sepia Filter Applied');
        });
    }

    if(smoothFilter) {
        smoothFilter.addEventListener('click', () => {
            applyFilter('smooth');
            filterText('Smooth Filter Applied');
        });
    }

    if(grayFilter) {
        grayFilter.addEventListener('click', () => {
            applyFilter('gray');
            filterText('Gray Filter Applied');
        });
    }

    if(vintageFilter) {
        vintageFilter.addEventListener('click', () => {
            applyFilter('vintage');
            filterText('Vintage Filter Applied');
        });
    }

    if(normalFilter) {
        normalFilter.addEventListener('click', () => {
            applyFilter('none');
            filterText('Normal Filter Applied');
        });
    }

    function applyFilter(filterClass) {
        if (!video) return;
        
        video.classList.remove("sepia", "grayscale", "smooth", "gray", "vintage", "bnw");
        
        if (filterClass !== "none") {
            video.classList.add(filterClass);
        }

        document.querySelectorAll('.filterBtn').forEach(btn => btn.classList.remove('active'));
        if (filterClass !== 'none') {
            const activeBtn = document.getElementById(filterClass + 'FilterId') || 
                             document.getElementById(filterClass.replace('bnw', 'bnw') + 'FilterId');
            if (activeBtn) activeBtn.classList.add('active');
        } else {
            const normalBtn = document.getElementById('normalFilterId');
            if (normalBtn) normalBtn.classList.add('active');
        }
    }

    function filterText(chosenFilter) {
        if (filterMessage) {
            filterMessage.textContent = chosenFilter;
            filterMessage.style.opacity = "1";
            setTimeout(() => {
                if (filterMessage) filterMessage.style.opacity = "0";
            }, 3000);
        }
    }

    // 🪞 INVERT/MIRROR FUNCTIONALITY
    if(invertBtn) {
        invertBtn.addEventListener('click', cameraInvertSwitch);
    }

    function cameraInvertSwitch() {
        invertBtnState = !invertBtnState;
        if (video) {
            video.style.transform = invertBtnState ? 'scaleX(-1)' : 'scaleX(1)';
        }
        
        if (invertBtn) {
            invertBtn.classList.toggle('active', invertBtnState);
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
            const stream = video.srcObject;
            const tracks = stream.getTracks();
            tracks.forEach(track => track.stop());
            video.srcObject = null;
        }
    }
    
    // Start camera if on canvasLayout4.php
    if (window.location.pathname.includes("canvasLayout4.php")) {
        startCamera();
    }

    // 🎯 GRID OVERLAY TOGGLE
    if (gridToggleBtn) {
        gridToggleBtn.addEventListener('click', () => {
            if (gridOverlay) {
                const isVisible = gridOverlay.style.display === 'grid';
                gridOverlay.style.display = isVisible ? 'none' : 'grid';
                gridToggleBtn.textContent = isVisible ? 'Show Grid' : 'Hide Grid';
                gridToggleBtn.classList.toggle('active', !isVisible);
            }
        });
    }

    // 📸 PHOTO CAPTURE FUNCTIONALITY
    async function startPhotobooth() {
        if (!video) return;
        
        try {
            const selectedValue = parseInt(timerOptions?.value || '3');
            await showCountdown(selectedValue);
            
            if (!canvas) {
                console.error('Canvas element not found');
                return;
            }

            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const ctx = canvas.getContext('2d');
            
            if (invertBtnState) {
                ctx.scale(-1, 1);
                ctx.translate(-canvas.width, 0);
            }
            
            ctx.drawImage(video, 0, 0);
            
            if (flash) {
                flash.style.opacity = '1';
                setTimeout(() => {
                    if (flash) flash.style.opacity = '0';
                }, 200);
            }
            
            const imageData = canvas.toDataURL('image/png');
            const compressedImage = await compressImage(imageData, 'session');
            images.push(compressedImage);
            
            const originalKey = `canvasLayout4_original_${images.length - 1}`;
            const downloadImage = await compressImage(imageData, 'download');
            localStorage.setItem(originalKey, downloadImage);
            
            updatePhotoPreview(images.length - 1, compressedImage);
            updateUI();
            
        } catch (error) {
            console.error('Error capturing photo:', error);
            alert(error.message || 'Error capturing photo. Please try again.');
        }
    }

    // ⏱️ COUNTDOWN FUNCTIONALITY
    async function showCountdown(selectedValue) {
        if (!countdownText) return;
        
        countdownText.style.display = 'block';
        
        for (let countdown = selectedValue; countdown > 0; countdown--) {
            countdownText.textContent = countdown;
            countdownText.classList.remove("bounce");
            void countdownText.offsetWidth;
            countdownText.classList.add("bounce");
            
            await new Promise(resolve => setTimeout(resolve, 1000));
        }
        
        countdownText.style.display = 'none';
    }
    
    function updateCountdown() {
        if (timerOptions) {
            const selectedValue = parseInt(timerOptions.value);
            console.log(`Timer set to ${selectedValue} seconds`);
        }
    }

    // 📁 IMAGE UPLOAD FUNCTIONALITY
    function handleImageUpload(event) {
        const files = Array.from(event.target.files);
        if (files.length === 0) return;

        files.slice(0, expectedPhotos - images.length).forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = async function(e) {
                    try {
                        const compressedImage = await compressImage(e.target.result, 'session');
                        images.push(compressedImage);
                        
                        const originalKey = `canvasLayout4_original_${images.length - 1}`;
                        const downloadImage = await compressImage(e.target.result, 'download');
                        localStorage.setItem(originalKey, downloadImage);
                        
                        updatePhotoPreview(images.length - 1, compressedImage);
                        updateUI();
                    } catch (error) {
                        console.error('Error processing uploaded image:', error);
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // 🖼️ PHOTO PREVIEW MANAGEMENT
    function updatePhotoPreview(index, imageData) {
        const photoSlot = document.querySelector(`.photo-preview-slot[data-index="${index}"]`);
        if (!photoSlot) return;

        const placeholder = photoSlot.querySelector('.photo-placeholder');
        if (placeholder) placeholder.remove();

        let img = photoSlot.querySelector('img');
        if (!img) {
            img = document.createElement('img');
            img.addEventListener('click', () => openCarousel(index));
            photoSlot.appendChild(img);
        }
        
        img.src = imageData;
        img.alt = `Photo ${index + 1}`;
        
        photoSlot.classList.add('filled');
        
        const retakeBtn = photoSlot.querySelector('.retake-photo-btn');
        if (retakeBtn) {
            retakeBtn.style.display = 'flex';
        }
    }

    // 🔄 RETAKE FUNCTIONALITY
    window.retakeSinglePhoto = function(index) {
        if (index >= 0 && index < images.length) {
            images.splice(index, 1);
            
            const originalKey = `canvasLayout4_original_${index}`;
            localStorage.removeItem(originalKey);
            
            for (let i = index; i < expectedPhotos - 1; i++) {
                const oldKey = `canvasLayout4_original_${i + 1}`;
                const newKey = `canvasLayout4_original_${i}`;
                const data = localStorage.getItem(oldKey);
                if (data) {
                    localStorage.setItem(newKey, data);
                    localStorage.removeItem(oldKey);
                }
            }
            
            const slots = document.querySelectorAll('.photo-preview-slot');
            slots.forEach((slot, i) => {
                const img = slot.querySelector('img');
                const placeholder = slot.querySelector('.photo-placeholder');
                const retakeBtn = slot.querySelector('.retake-photo-btn');
                
                if (i < images.length) {
                    if (img) img.src = images[i];
                    if (placeholder) placeholder.style.display = 'none';
                    if (retakeBtn) retakeBtn.style.display = 'flex';
                    slot.classList.add('filled');
                } else {
                    if (img) img.remove();
                    if (placeholder) {
                        placeholder.style.display = 'block';
                        placeholder.textContent = `Photo ${i + 1}`;
                    } else {
                        const newPlaceholder = document.createElement('div');
                        newPlaceholder.className = 'photo-placeholder';
                        newPlaceholder.textContent = `Photo ${i + 1}`;
                        slot.appendChild(newPlaceholder);
                    }
                    if (retakeBtn) retakeBtn.style.display = 'none';
                    slot.classList.remove('filled');
                }
            });
            
            updateUI();
            
            const carouselModal = document.getElementById('carousel-modal');
            if (carouselModal && carouselModal.style.display !== 'none') {
                closeCarousel();
            }
        }
    };

    // 🎠 CAROUSEL MODAL FUNCTIONALITY
    const carouselModal = document.getElementById('carousel-modal');
    const carouselImage = document.getElementById('carousel-image');
    const carouselCloseBtn = document.getElementById('carousel-close-btn');
    const carouselPrevBtn = document.getElementById('carousel-prev-btn');
    const carouselNextBtn = document.getElementById('carousel-next-btn');
    const carouselRetakeBtn = document.getElementById('carousel-retake-btn');

    function openCarousel(index) {
        if (!carouselModal || !carouselImage || images.length === 0) return;
        
        currentImageIndex = index;
        updateCarousel();
        carouselModal.style.display = 'flex';
        carouselModal.classList.add('fade-in');
        
        updateCarouselNavigation();
    }

    function closeCarousel() {
        if (!carouselModal) return;
        
        carouselModal.classList.remove('fade-in');
        carouselModal.classList.add('fade-out');
        
        setTimeout(() => {
            carouselModal.style.display = 'none';
            carouselModal.classList.remove('fade-out');
        }, 300);
    }

    function updateCarousel() {
        if (!carouselImage || images.length === 0) return;
        
        carouselImage.src = images[currentImageIndex];
        carouselImage.alt = `Photo ${currentImageIndex + 1}`;
        updateCarouselNavigation();
    }

    function updateCarouselNavigation() {
        if (carouselPrevBtn) {
            carouselPrevBtn.disabled = currentImageIndex === 0;
        }
        if (carouselNextBtn) {
            carouselNextBtn.disabled = currentImageIndex === images.length - 1;
        }
    }

    // Carousel event listeners
    if (carouselCloseBtn) {
        carouselCloseBtn.addEventListener('click', closeCarousel);
    }

    if (carouselPrevBtn) {
        carouselPrevBtn.addEventListener('click', () => {
            if (currentImageIndex > 0) {
                currentImageIndex--;
                updateCarousel();
            }
        });
    }

    if (carouselNextBtn) {
        carouselNextBtn.addEventListener('click', () => {
            if (currentImageIndex < images.length - 1) {
                currentImageIndex++;
                updateCarousel();
            }
        });
    }

    if (carouselRetakeBtn) {
        carouselRetakeBtn.addEventListener('click', () => {
            window.retakeSinglePhoto(currentImageIndex);
        });
    }

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && carouselModal && carouselModal.style.display !== 'none') {
            closeCarousel();
        }
        if (event.code === 'Space') {
            event.preventDefault();
            if (startBtn && !startBtn.disabled) {
                startPhotobooth();
            }
        }
    });

    if (carouselModal) {
        carouselModal.addEventListener('click', (event) => {
            if (event.target === carouselModal) {
                closeCarousel();
            }
        });
    }

    // 🔄 UI UPDATE FUNCTIONALITY
    function updateUI() {
        if (progressCounter) {
            progressCounter.textContent = `${images.length}/${expectedPhotos}`;
        }
        
        if (images.length === expectedPhotos) {
            if (startBtn) startBtn.style.display = 'none';
            if (doneBtn) doneBtn.style.display = 'block';
        } else {
            if (startBtn) startBtn.style.display = 'block';
            if (doneBtn) doneBtn.style.display = 'none';
        }
        
        const retakeAllBtn = document.getElementById('retakeAllBtn');
        if (retakeAllBtn) {
            retakeAllBtn.disabled = images.length === 0;
        }
    }

    // 🚀 SAVE FUNCTIONALITY - Store to sessionStorage for Layout 3
    async function storeImageArray() {
        if (images.length !== expectedPhotos) {
            alert(`Please capture all ${expectedPhotos} photos before proceeding.`);
            return;
        }

        try {
            sessionStorage.setItem('canvasLayout4_images', JSON.stringify(images));
            
            for (let i = 0; i < images.length; i++) {
                const originalKey = `canvasLayout4_original_${i}`;
                if (!localStorage.getItem(originalKey)) {
                    localStorage.setItem(originalKey, images[i]);
                }
            }
            
            console.log(`✅ Layout 4: Stored ${images.length} photos successfully`);
            
            stopCameraStream();
            window.location.href = 'customizeLayout4.php';
            
        } catch (error) {
            console.error('Error storing images:', error);
            alert('Error saving photos. Please try again.');
        }
    }

    // 🎯 EVENT LISTENERS
    if(startBtn) {
        startBtn.addEventListener('click', startPhotobooth);
    }

    if (doneBtn) {
        doneBtn.addEventListener('click', storeImageArray);
    }

    if (uploadBtn) {
        uploadBtn.addEventListener('click', () => uploadInput?.click());
    }
    
    if(uploadInput) {
        uploadInput.addEventListener('change', handleImageUpload);
    }

    // Retake all functionality
    const retakeAllBtn = document.getElementById('retakeAllBtn');
    if (retakeAllBtn) {
        retakeAllBtn.addEventListener('click', () => {
            if (confirm(`Are you sure you want to retake all ${expectedPhotos} photos?`)) {
                images = [];
                
                for (let i = 0; i < expectedPhotos; i++) {
                    localStorage.removeItem(`canvasLayout4_original_${i}`);
                }
                
                const slots = document.querySelectorAll('.photo-preview-slot');
                slots.forEach((slot, index) => {
                    const img = slot.querySelector('img');
                    if (img) img.remove();
                    
                    const retakeBtn = slot.querySelector('.retake-photo-btn');
                    if (retakeBtn) retakeBtn.style.display = 'none';
                    
                    slot.classList.remove('filled');
                    
                    if (!slot.querySelector('.photo-placeholder')) {
                        const placeholder = document.createElement('div');
                        placeholder.className = 'photo-placeholder';
                        placeholder.textContent = `Photo ${index + 1}`;
                        slot.appendChild(placeholder);
                    }
                });
                
                updateUI();
                closeCarousel();
            }
        });
    }

    // Initial UI update
    updateUI();
    
    if (normalFilter) {
        normalFilter.classList.add('active');
    }

    console.log(`🎯 Layout 4 (${expectedPhotos} photos) initialized successfully!`);
});
