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
            startBtn.disabled = false;
            uploadBtn.disabled = false;
            startBtn.innerHTML = 'Retake';
            doneBtn.style.display = 'block';
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
                    startBtn.innerHTML = 'Retake';
                    doneBtn.style.display = 'block';
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
        doneBtn.addEventListener('click', () => {
            console.log("Done button clicked in Layout4, calling storeImageArray()");
            console.log("Current images array:", images);
            console.log("Images length:", images.length);
            if (images.length === 8) {
                storeImageArray();
            } else {
                alert(`Error: Expected 8 images but found ${images.length}. Please retake photos.`);
            }
        });
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
})
