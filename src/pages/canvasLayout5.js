document.addEventListener('DOMContentLoaded', () => {
    // 🚀 COMPRESSION CONFIGURATION - 3-Level Quality System
    const COMPRESSION_CONFIG = {
        session: { quality: 0.8, maxWidth: 800 },     // Fast session save
        download: { quality: 0.95, maxWidth: 1200 },  // High quality download
        print: { quality: 1.0, maxWidth: 2400 }       // Full quality print
    };

    // 🔥 FAST COMPRESSION FUNCTION
    function compressImage(base64Data, type = 'session') {
        return new Promise((resolve) => {
            const config = COMPRESSION_CONFIG[type];
            const img = new Image();
            
            img.onload = function() {
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                
                // Calculate new dimensions
                const ratio = Math.min(config.maxWidth / img.width, config.maxWidth / img.height);
                canvas.width = img.width * (ratio > 1 ? 1 : ratio);
                canvas.height = img.height * (ratio > 1 ? 1 : ratio);
                
                // Draw and compress
                ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                const compressed = canvas.toDataURL('image/jpeg', config.quality);
                
                resolve(compressed);
            };
            
            img.src = base64Data;
        });
    }

    // Timer functionality
    const timerDisplay = document.getElementById('timer-display');
    const timeoutModal = document.getElementById('timeout-modal');
    const timeoutOkBtn = document.getElementById('timeout-ok-btn');
    
    let timeLeft = 7 * 60; // 7 minutes in seconds
    let timerInterval;

    function updateTimer() {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        const display = `${minutes}:${seconds.toString().padStart(2, '0')}`;
        
        if (timerDisplay) {
            timerDisplay.textContent = display;
        }
        
        if (timeLeft <= 0) {
            clearInterval(timerInterval);
            showTimeoutModal();
        }
        
        timeLeft--;
    }

    function showTimeoutModal() {
        if (timeoutModal) {
            timeoutModal.style.display = 'flex';
        }
    }

    function hideTimeoutModal() {
        if (timeoutModal) {
            timeoutModal.style.display = 'none';
        }
    }

    if (timeoutOkBtn) {
        timeoutOkBtn.addEventListener('click', () => {
            hideTimeoutModal();
            window.location.href = 'customizeLayout5.php';
        });
    }

    // Start the timer
    timerInterval = setInterval(updateTimer, 1000);
    updateTimer(); // Initial call to set display

    const video = document.getElementById('video');
    const blackScreen = document.getElementById('blackScreen');
    const countdownText = document.getElementById('countdownText');
    const progressCounter = document.getElementById('progressCounter');
    const startBtn = document.getElementById('startBtn');
    const invertBtn = document.getElementById('invertBtn');
    const doneBtn = document.getElementById('doneBtn');
    const flash = document.getElementById('flash');
    const photoContainer = document.getElementById('photoContainer');

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

    function toggleFullscreen() {
        if (!document.fullscreenElement && 
            !document.mozFullScreenElement && 
            !document.webkitFullscreenElement && 
            !document.msFullscreenElement) {
            
            // Enter fullscreen
            if (videoContainer.requestFullscreen) {
                videoContainer.requestFullscreen();
            } else if (videoContainer.mozRequestFullScreen) { // Firefox
                videoContainer.mozRequestFullScreen();
            } else if (videoContainer.webkitRequestFullscreen) { // Chrome, Safari, and Opera
                videoContainer.webkitRequestFullscreen();
            } else if (videoContainer.msRequestFullscreen) { // IE/Edge
                videoContainer.msRequestFullscreen();
            }
            
            if (fullscreenMessage) {
                fullscreenMessage.style.opacity = "1";
            }
            if (fullscreenImg) {
                fullscreenImg.src = "/src/assets/fullScreen2.png";
            }
            
            setTimeout(() => {
                if (fullscreenMessage) {
                    fullscreenMessage.style.opacity = "0"; // Fade out
                }
            }, 1000);

        } else {
            // Exit fullscreen
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.mozCancelFullScreen) { // Firefox
                document.mozCancelFullScreen();
            } else if (document.webkitExitFullscreen) { // Chrome, Safari, and Opera
                document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) { // IE/Edge
                document.msExitFullscreen();
            }
            
            if (fullscreenMessage) {
                fullscreenMessage.style.opacity = "0";
            }
            if (fullscreenImg) {
                fullscreenImg.src = "/src/assets/fullScreen3.png";
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

    const canvas = document.createElement('canvas');
    let images = [];
    let invertBtnState = false;

    if(invertBtn) {
        invertBtn.addEventListener('click', () => {
            invertBtnState =!invertBtnState;
            cameraInvertSwitch()
            filterText("invert")
        });
    }

    function cameraInvertSwitch() {
        if (invertBtnState == true) {
            if (photoContainer) photoContainer.style.transform = 'scaleX(-1)'
            if (video) video.style.transform = 'scaleX(-1)'
        }
        else {
            if (photoContainer) photoContainer.style.transform = 'scaleX(1)'
            if (video) video.style.transform = 'scaleX(1)'
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
    }
    
    // Start camera if on canvasLayout5.php
    if (window.location.pathname.endsWith("canvasLayout5.php") || window.location.pathname === "canvasLayout5.php") {
        startCamera();
    }

    async function startPhotobooth() {
        const photoCount = 6; // Layout 5 has 2 photos
        
        if (images.length > 0) {
            const confirmReset = confirm("You already have pictures. Do you want to retake them?");
            if (!confirmReset) return;
    
            images = [];
            if (photoContainer) photoContainer.innerHTML = '';
            if (progressCounter) progressCounter.textContent = `0/${photoCount}`;
            if (doneBtn) doneBtn.style.display = 'none';
        }
    
        // Disable buttons to prevent multiple actions
        if (startBtn) {
            startBtn.disabled = true;
            startBtn.innerHTML = 'Capturing...';
        }
        if (uploadBtn) uploadBtn.disabled = true;
        if (progressCounter) progressCounter.textContent = `0/${photoCount}`;
    
        // Get the selected timer value
        const timerOptions = document.getElementById("timerOptions");
        const selectedValue = parseInt(timerOptions?.value) || 3; // Default to 3 if no value is selected
    
        for (let i = 0; i < photoCount; i++) {
            // Countdown using selected timer
            await showCountdown(selectedValue);
    
            // Flash Effect
            if (flash) {
                flash.style.opacity = 1;
                setTimeout(() => flash.style.opacity = 0, 200);
            }
    
            // Ensure video dimensions are loaded before capturing
            if (!video || video.videoWidth === 0 || video.videoHeight === 0) {
                console.error("Video not ready yet.");
                alert("Camera not ready. Please try again.");
                return;
            }
    
            // Capture Image with Filter Applied
            const ctx = canvas.getContext('2d');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
    
            // Apply current video filter to the canvas
            ctx.filter = getComputedStyle(video).filter;
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
    
            // Slight delay for iOS fix
            await new Promise(res => setTimeout(res, 100)); 
    
            const imageData = canvas.toDataURL('image/png');
            console.log("Captured Image: ", imageData);
            images.push(imageData);
    
            // Display captured image in preview
            if (photoContainer) {
                const imgElement = document.createElement('img');
                imgElement.src = imageData;
                imgElement.classList.add('photo');
                photoContainer.appendChild(imgElement);
            }
    
            if (progressCounter) progressCounter.textContent = `${i + 1}/${photoCount}`;
    
            // Wait before next capture if not the last one
            if (i < photoCount - 1) await new Promise(res => setTimeout(res, 500)); 
        }
    
        // Reset buttons
        if (images.length === photoCount) {
            if (startBtn) {
                startBtn.disabled = false;
                startBtn.innerHTML = 'Retake';
            }
            if (uploadBtn) uploadBtn.disabled = false;
            if (doneBtn) doneBtn.style.display = 'block';
        }
    }

    async function showCountdown(selectedValue) {
        if (!countdownText) return;
        
        countdownText.style.display = "flex";
        for (let countdown = selectedValue; countdown > 0; countdown--) {
            countdownText.textContent = countdown;
            countdownText.classList.remove("bounce");
            void countdownText.offsetWidth; // Trigger reflow for animation
            countdownText.classList.add("bounce");
            await new Promise(res => setTimeout(res, 1000));
        }
        countdownText.style.display = "none";
    }
    
    // Automatically trigger the countdown when option changes
    function updateCountdown() {
        showCountdown();
    }

    // Update Image Upload for Users to choose multiple images at once
    function handleImageUpload(event) {
        const photoCount = 6; // Layout 5 has 2 photos
        const files = Array.from(event.target.files); // Get all selected files

        if (files.length === 0) {
            alert("Please upload a valid image file.");
            return;
        }

        for (const file of files) {
            if (!file.type.startsWith("image/")) continue;

            // Stop if we already have required number of images
            if (images.length >= photoCount) {
                const confirmReplace = confirm(`You already have ${photoCount} pictures. Uploading new images will replace all current pictures. Do you want to proceed?`);
                if (!confirmReplace) {
                    event.target.value = "";
                    return;
                }

                // Reset everything
                images = [];
                if (photoContainer) photoContainer.innerHTML = '';
                if (progressCounter) progressCounter.textContent = `0/${photoCount}`;
                if (startBtn) startBtn.innerHTML = 'Capturing...';
                if (doneBtn) doneBtn.style.display = 'none';
            }

            if (startBtn) startBtn.innerHTML = 'Capturing...';

            const reader = new FileReader();
            reader.onload = function (e) {
                const imageData = e.target.result;

                images.push(imageData);

                if (photoContainer) {
                    const imgElement = document.createElement('img');
                    imgElement.src = imageData;
                    imgElement.classList.add('photo');
                    photoContainer.appendChild(imgElement);
                }

                if (progressCounter) progressCounter.textContent = `${images.length}/${photoCount}`;

                if (images.length === photoCount) {
                    if (startBtn) startBtn.innerHTML = 'Retake';
                    if (doneBtn) doneBtn.style.display = 'block';
                }
            };

            reader.readAsDataURL(file);
        }

        // Reset input so same files can be re-selected if needed
        event.target.value = "";
    }

    // 🚀 OPTIMIZED STORE IMAGE FUNCTION - Layout 5 (6 PHOTOS)
    async function storeImageArray() {
        const photoCount = 6; // Layout 5 has 6 photos ✅
        const doneBtn = document.getElementById('doneBtn');
        const startTime = Date.now();
        
        try {
            console.log('⚡ Starting FAST photo compression for 6 photos...');
            
            if (doneBtn) {
                doneBtn.textContent = 'Compressing...';
                doneBtn.disabled = true;
            }
            
            const sessionPhotos = [];  // For session (80% quality)
            const originalPhotos = []; // Backup original (100% quality)
            
            // Process each image
            for (let index = 0; index < images.length; index++) {
                const imgData = images[index];
                if (!imgData) continue;
                
                console.log(`📸 Processing image ${index + 1}/${photoCount}...`);
                
                // Update progress
                if (doneBtn) {
                    const progress = Math.round(((index + 1) / photoCount) * 50);
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
            
            if (doneBtn) {
                doneBtn.textContent = 'Saving to server...';
            }
            
            console.log('💾 Saving 6 compressed photos to server session...');
            
            const response = await fetch('../api-fetch/save_photos.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ photos: sessionPhotos }),
                signal: AbortSignal.timeout(15000)
            });
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            
            const data = await response.json();
            
            if (data.success) {
                console.log(`🎉 SUCCESS! 6 photos saved in ${Date.now() - startTime}ms`);
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
                        window.location.href = 'customizeLayout5.php';
                        return;
                    }
                }
                
                // Fallback redirect
                console.log('⚠️ Session creation failed, but proceeding...');
                window.location.href = 'customizeLayout5.php';
                
            } else {
                throw new Error(data.error || 'Failed to save photos');
            }
            
        } catch (error) {
            console.error('❌ Error in storeImageArray:', error);
            
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
                
                tempCtx.translate(img.width, 0);
                tempCtx.scale(-1, 1);
                tempCtx.drawImage(img, 0, 0, img.width, img.height);
                
                resolve(tempCanvas.toDataURL('image/png'));
            };
            img.onerror = () => reject(new Error('Failed to apply mirror effect'));
            img.src = imgData;
        });
    }
    
    if(startBtn) {
        startBtn.addEventListener('click', () => startPhotobooth());
    }

    document.addEventListener('keydown', (event) => {
        if (event.code === "Space") {
            event.preventDefault(); // Prevents scrolling when spacebar is pressed
            startPhotobooth();
        }
    });

    if (doneBtn) {
        doneBtn.addEventListener('click', () => storeImageArray());
    }

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
