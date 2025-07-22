document.addEventListener('DOMContentLoaded', () => {
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
    const fullscreenImg = fullscreenBtn.querySelector("img");

    const uploadInput = document.getElementById('uploadInput');
    const uploadBtn = document.getElementById('uploadBtn');

    document.getElementById("timerOptions").addEventListener("change", updateCountdown);

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
            
            fullscreenMessage.style.opacity = "1";
            fullscreenImg.src = "assets/fullScreen2.png";
            
            setTimeout(() => {
                fullscreenMessage.style.opacity = "0"; // Fade out
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
            
            fullscreenMessage.style.opacity = "0";
            fullscreenImg.src = "assets/fullScreen3.png";
        }
    }
    
    // Attach event listener to the button
    fullscreenBtn.addEventListener("click", toggleFullscreen);

    // Filter functionality
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
        // Remove existing filters
        video.classList.remove("sepia", "grayscale","smooth","gray","vintage");

        // Apply new filter if not 'none'
        if (filterClass !== "none") {
            video.classList.add(filterClass);
        }
    }

    function filterText(chosenFilter) {
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
            photoContainer.style.transform = 'scaleX(-1)'
            video.style.transform = 'scaleX(-1)'
        }
        else {
            photoContainer.style.transform = 'scaleX(1)'
            video.style.transform = 'scaleX(1)'
        }
    }

    async function startCamera() {
        stopCameraStream(); 
    
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } });
            video.srcObject = stream;
    
            // Ensure video is playing before hiding black screen
            video.onloadedmetadata = () => {
                video.play();
                setTimeout(() => {
                    blackScreen.style.opacity = 0;
                    setTimeout(() => blackScreen.style.display = 'none', 1000);
                }, 500);
            };
        } catch (err) {
            console.error("Camera Access Denied", err);
            alert("Please enable camera permissions in your browser settings.");
        }
    }
    
    function stopCameraStream() {
        if (video.srcObject) {
            video.srcObject.getTracks().forEach(track => track.stop());
            video.srcObject = null;
        }
    }
    
    // Start camera if on canvasLayout4.php
    if (window.location.pathname.endsWith("canvasLayout4.php") || window.location.pathname === "canvasLayout4.php") {
        startCamera();
    }

    // Modified for single photo capture (4R layout)
    async function startPhotobooth() {
        if (images.length > 0) {
            const confirmReset = confirm("You already have pictures. Do you want to retake them?");
            if (!confirmReset) return;
    
            images = [];
            photoContainer.innerHTML = '';
            progressCounter.textContent = "0/8";
            doneBtn.style.display = 'none';
        }
    
        // Disable buttons to prevent multiple actions
        startBtn.disabled = true;
        uploadBtn.disabled = true;
        startBtn.innerHTML = 'Capturing...';
        progressCounter.textContent = "0/8";
    
        // Get the selected timer value
        const timerOptions = document.getElementById("timerOptions");
        const selectedValue = parseInt(timerOptions.value) || 3; // Default to 3 if no value is selected
    
        for (let i = 0; i < 8; i++) { // Layout 4: 8 photos
            // Countdown using selected timer
            await showCountdown(selectedValue);

            // Flash Effect
            flash.style.opacity = 1;
            setTimeout(() => flash.style.opacity = 0, 200);

            // Ensure video dimensions are loaded before capturing
            if (video.videoWidth === 0 || video.videoHeight === 0) {
                console.error("Video not ready yet.");
                alert("Camera not ready. Please try again.");
                return;
            }

            // Capture Image with Filter Applied and WHITE BACKGROUND
            const ctx = canvas.getContext('2d');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            
            // Fill with WHITE background first
            ctx.fillStyle = '#FFFFFF';
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            // Apply current video filter to the canvas
            ctx.filter = getComputedStyle(video).filter;
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

            // Slight delay for iOS fix
            await new Promise(res => setTimeout(res, 100)); 

            const imageData = canvas.toDataURL('image/png');
            console.log("Captured 4R Image: ", imageData);
            images.push(imageData);

            // Display captured image in preview
            const imgElement = document.createElement('img');
            imgElement.src = imageData;
            imgElement.classList.add('photo');
            photoContainer.appendChild(imgElement);

            progressCounter.textContent = `${i + 1}/8`;

            // Wait before next capture if not the last one
            if (i < 7) await new Promise(res => setTimeout(res, 500)); // Wait between photos
        }
    
        // Reset buttons
        if (images.length === 8) { // Layout 4: 8 photos
            console.log("8 photos captured, showing done button");
            startBtn.disabled = false;
            uploadBtn.disabled = false;
            startBtn.innerHTML = 'Retake';
            
            // Force show the done button with important overrides
            doneBtn.style.cssText = 'display: block !important; visibility: visible !important; opacity: 1 !important; pointer-events: auto !important; z-index: 9999 !important; position: relative !important;';
            
            console.log("Done button display set to:", doneBtn.style.display);
            console.log("Done button visibility set to:", doneBtn.style.visibility);
            console.log("Done button computed style:", getComputedStyle(doneBtn).display);
            
            // Additional check
            setTimeout(() => {
                console.log("Button check after 1 second:");
                console.log("- Clickable:", !doneBtn.disabled);
                console.log("- Visible:", getComputedStyle(doneBtn).display !== 'none');
                console.log("- Pointer events:", getComputedStyle(doneBtn).pointerEvents);
            }, 1000);
        }
    }

    async function showCountdown(selectedValue) {
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

    // Update Image Upload for single image
    function handleImageUpload(event) {
        const files = Array.from(event.target.files); // Get all selected files

        if (files.length === 0) {
            alert("Please upload valid image files.");
            return;
        }

        for (const file of files) {
            if (!file.type.startsWith("image/")) continue;

            // Stop if we already have 8 images
            if (images.length >= 8) {
                const confirmReplace = confirm("You already have 8 pictures. Uploading new images will replace all current pictures. Do you want to proceed?");
                if (!confirmReplace) {
                    event.target.value = "";
                    return;
                }

                // Reset everything
                images = [];
                photoContainer.innerHTML = '';
                progressCounter.textContent = "0/8";
                startBtn.innerHTML = 'Capturing...';
                doneBtn.style.display = 'none';
            }

            startBtn.innerHTML = 'Capturing...';

            const reader = new FileReader();
            reader.onload = function (e) {
                const imageData = e.target.result;

                images.push(imageData);

                const imgElement = document.createElement('img');
                imgElement.src = imageData;
                imgElement.classList.add('photo');
                photoContainer.appendChild(imgElement);

                progressCounter.textContent = `${images.length}/8`;

                if (images.length === 8) {
                    console.log("8 images uploaded, showing done button");
                    startBtn.innerHTML = 'Retake';
                    
                    // Force show the done button with important overrides
                    doneBtn.style.cssText = 'display: block !important; visibility: visible !important; opacity: 1 !important; pointer-events: auto !important; z-index: 9999 !important; position: relative !important;';
                    
                    console.log("Done button display set to:", doneBtn.style.display);
                    console.log("Done button computed style:", getComputedStyle(doneBtn).display);
                }
            };

            reader.readAsDataURL(file);
        }

        // Reset input so same files can be re-selected if needed
        event.target.value = "";
    }

    function storeImageArray() {
        let loadedImages = 0;
        let storedImages = [];
    
        images.forEach((imgData, index) => {
            const img = new Image();
            img.src = imgData;
            img.onload = () => {
                
                if (invertBtnState) {
                    // Create an offscreen canvas to mirror the image
                    const tempCanvas = document.createElement('canvas');
                    const tempCtx = tempCanvas.getContext('2d');
    
                    tempCanvas.width = img.width;
                    tempCanvas.height = img.height;
    
                    // Apply mirroring
                    tempCtx.translate(img.width, 0);
                    tempCtx.scale(-1, 1);
                    tempCtx.drawImage(img, 0, 0, img.width, img.height);
    
                    // Convert to base64 data URL
                    storedImages[index] = tempCanvas.toDataURL('image/png');
                } else {
                    // Store the original image if not mirrored
                    storedImages[index] = imgData;
                }
                loadedImages++;
    
                // For Layout 4 with 8 images, we need higher storage limit
                if (loadedImages === 8) {
                    console.log('=== Layout 4 Storage Debug ===');
                    console.log('All 8 images processed for storage');
                    console.log('Stored images array:', storedImages);
                    
                    const estimatedSize = new Blob([JSON.stringify(storedImages)]).size;
                    console.log('Estimated storage size:', estimatedSize, 'bytes');

                    const storageLimit = 15 * 1024 * 1024; // 15MB limit for 8 photos
                    console.log('Storage limit:', storageLimit, 'bytes');

                    if (estimatedSize > storageLimit) {
                        alert("The total image size exceeds the 15MB limit. Please use smaller images or reduce photo quality.");
                        return; // Stop storing and redirecting
                    }

                    sessionStorage.setItem('photoArray4', JSON.stringify(storedImages)); 
                    console.log("8 images stored in sessionStorage with key 'photoArray4'!");
                    
                    // Verify storage
                    const verifyStorage = sessionStorage.getItem('photoArray4');
                    if (verifyStorage) {
                        console.log("Storage verification successful");
                        console.log("Redirecting to customizeLayout4.php...");
                        window.location.href = 'customizeLayout4.php'; // Redirect to Layout4 customize page
                    } else {
                        console.error("Storage verification failed!");
                        alert("Failed to save images. Please try again.");
                    }
                }
            };
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
        console.log("=== SETTING UP DONE BUTTON EVENTS ===");
        
        // Remove any existing listeners first
        doneBtn.removeEventListener('click', handleDoneClick);
        doneBtn.removeEventListener('touchstart', handleDoneClick);
        doneBtn.removeEventListener('mousedown', handleDoneClick);
        
        // Add multiple event types for maximum compatibility
        doneBtn.addEventListener('click', handleDoneClick, { passive: false });
        doneBtn.addEventListener('touchstart', handleDoneClick, { passive: false });
        doneBtn.addEventListener('mousedown', handleDoneClick, { passive: false });
        
        // Also add a direct onclick as backup
        doneBtn.onclick = function(e) {
            console.log("Direct onclick triggered");
            handleDoneClick(e);
        };
        
        // Debug: Check if button is initially hidden
        console.log("Done button initial display:", doneBtn.style.display);
        console.log("Done button element:", doneBtn);
        console.log("Done button computed style:", getComputedStyle(doneBtn).display);
        console.log("Done button bounding rect:", doneBtn.getBoundingClientRect());
    } else {
        console.error("Done button (doneBtn) not found in DOM!");
    }

    function handleDoneClick(event) {
        console.log("=== DONE BUTTON CLICKED ===");
        console.log("Event type:", event.type);
        console.log("Event target:", event.target);
        console.log("Current images array:", images);
        console.log("Images length:", images.length);
        
        // Prevent any default behavior
        if (event) {
            event.preventDefault();
            event.stopPropagation();
            event.stopImmediatePropagation();
        }
        
        // Force execution regardless of image count for testing
        console.log("Forcing execution for debugging...");
        
        if (images.length === 8) {
            console.log("8 images found, calling storeImageArray()");
            storeImageArray();
        } else if (images.length === 0) {
            console.log("No images found, creating dummy data and redirecting...");
            // Create dummy data for testing
            const dummyImages = [];
            for (let i = 0; i < 8; i++) {
                dummyImages.push('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==');
            }
            sessionStorage.setItem('photoArray4', JSON.stringify(dummyImages));
            window.location.href = 'customizeLayout4.php';
        } else {
            console.log(`Found ${images.length} images, but expected 8`);
            alert(`Error: Expected 8 images but found ${images.length}. Please retake photos.`);
        }
    }

    if (uploadBtn) {
        uploadBtn.addEventListener('click', () => {
            alert("Note: Please make sure your photo size does not exceed 5MB.\nLarge images may cause saving issues.");
            uploadInput.click();
        });
    }
    
    if(uploadInput) {
        uploadInput.addEventListener('change', handleImageUpload);
    }

    // Add test button for debugging
    const testBtn = document.getElementById('testBtn');
    if (testBtn) {
        testBtn.addEventListener('click', () => {
            console.log("Test button clicked - forcing redirect to customize");
            console.log("Current images:", images.length);
            
            // Create dummy data if no images
            if (images.length === 0) {
                const dummyImages = [];
                for (let i = 0; i < 8; i++) {
                    dummyImages.push('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==');
                }
                sessionStorage.setItem('photoArray4', JSON.stringify(dummyImages));
                console.log("Created dummy images for testing");
            } else {
                // Use existing images
                storeImageArray();
                return;
            }
            
            window.location.href = 'customizeLayout4.php';
        });
    }

    // Test button functionality on page load
    console.log('=== Layout 4 Button Debug ===');
    console.log('Start button found:', !!startBtn);
    console.log('Done button found:', !!doneBtn);
    console.log('Upload button found:', !!uploadBtn);
    console.log('Test button found:', !!testBtn);
    
    if (doneBtn) {
        console.log('Done button initial state:', {
            display: doneBtn.style.display,
            visibility: doneBtn.style.visibility,
            disabled: doneBtn.disabled,
            computedDisplay: getComputedStyle(doneBtn).display,
            pointerEvents: getComputedStyle(doneBtn).pointerEvents,
            zIndex: getComputedStyle(doneBtn).zIndex,
            position: getComputedStyle(doneBtn).position
        });
        
        // Add click area debugger
        doneBtn.addEventListener('mouseenter', () => {
            console.log("Mouse entered done button area");
        });
        
        doneBtn.addEventListener('mouseleave', () => {
            console.log("Mouse left done button area");
        });
        
        // Check for overlapping elements
        setTimeout(() => {
            const rect = doneBtn.getBoundingClientRect();
            const centerX = rect.left + rect.width / 2;
            const centerY = rect.top + rect.height / 2;
            const elementAtCenter = document.elementFromPoint(centerX, centerY);
            
            console.log("Element at button center:", elementAtCenter);
            console.log("Is button the top element?", elementAtCenter === doneBtn);
            console.log("Button rect:", rect);
        }, 2000);
    }
})
