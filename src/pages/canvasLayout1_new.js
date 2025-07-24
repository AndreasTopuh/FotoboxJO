document.addEventListener('DOMContentLoaded', () => {
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
            window.location.href = 'customizeLayout1.php';
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
    
    // Start camera if on canvasLayout1.php
    if (window.location.pathname.endsWith("canvasLayout1.php") || window.location.pathname === "canvasLayout1.php") {
        startCamera();
    }

    async function startPhotobooth() {
        const photoCount = 2; // Layout 1 has 2 photos
        
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
        const photoCount = 2; // Layout 1 has 2 photos
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

    function storeImageArray() {
        const photoCount = 2; // Layout 1 has 2 photos
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
    

                if (loadedImages === photoCount) {
                    const estimatedSize = new Blob([JSON.stringify(storedImages)]).size;

                    const storageLimit = 5 * 1024 * 1024; // 5MB limit

                    if (estimatedSize > storageLimit) {
                        alert("The total image size exceeds the 5MB limit. Please upload smaller images.");
                        return; // Stop storing and redirecting
                    }

                    // Simpan ke server-side session daripada sessionStorage
                    fetch('../api-fetch/save_photos.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ photos: storedImages })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            console.log(`All ${photoCount} images stored in server session!`);
                            
                            // Create customize session before redirect
                            return fetch('../api-fetch/create_customize_session.php', {
                                method: 'POST'
                            });
                        } else {
                            throw new Error(data.error || 'Failed to save photos');
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.href = 'customizeLayout1.php'; 
                        } else {
                            console.error('Error creating customize session:', data.error);
                            window.location.href = 'customizeLayout1.php'; // Fallback
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error saving photos: ' + error.message);
                    });
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
        doneBtn.addEventListener('click', () => storeImageArray());
    }

    if (uploadBtn) {
        uploadBtn.addEventListener('click', () => {
            alert("Note: Please make sure your total photo size does not exceed 5MB.\nLarge images may cause saving issues.");
            if (uploadInput) uploadInput.click();
        });
    }
    
    if(uploadInput) {
        uploadInput.addEventListener('change', handleImageUpload);
    }
});
