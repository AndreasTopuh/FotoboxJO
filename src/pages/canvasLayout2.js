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
            
            if (videoContainer.requestFullscreen) {
                videoContainer.requestFullscreen();
            } else if (videoContainer.mozRequestFullScreen) {
                videoContainer.mozRequestFullScreen();
            } else if (videoContainer.webkitRequestFullscreen) {
                videoContainer.webkitRequestFullscreen();
            } else if (videoContainer.msRequestFullscreen) {
                videoContainer.msRequestFullscreen();
            }
            
            fullscreenMessage.style.opacity = "1";
            fullscreenImg.src = "/src/assets/fullScreen2.png";
            
            setTimeout(() => {
                fullscreenMessage.style.opacity = "0";
            }, 1000);

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
            
            fullscreenMessage.style.opacity = "0";
            fullscreenImg.src = "/src/assets/fullScreen3.png";
        }
    }
    
    fullscreenBtn.addEventListener("click", toggleFullscreen);

    if(bnwFilter) {
        bnwFilter.addEventListener('click', () => {
            applyFilter("grayscale");
            filterText("BNW");
        });
    }

    if(sepiaFilter) {
        sepiaFilter.addEventListener('click', () => {
            applyFilter("sepia");
            filterText("sepia");
        });
    }

    if(smoothFilter) {
        smoothFilter.addEventListener('click', () => {
            applyFilter("smooth");
            filterText("smooth");
        });
    }

    if(grayFilter) {
        grayFilter.addEventListener('click', () => {
            applyFilter("gray");
            filterText("grayscale");
        });
    }

    if(vintageFilter) {
        vintageFilter.addEventListener('click', () => {
            applyFilter("vintage");
            filterText("vintage");
        });
    }

    if(normalFilter) {
        normalFilter.addEventListener('click', () => {
            applyFilter("none");
            filterText("none");
        });
    }

    function applyFilter(filterClass) {
        video.classList.remove("sepia", "grayscale", "smooth", "gray", "vintage");
        if (filterClass !== "none") {
            video.classList.add(filterClass);
        }
    }

    function filterText(chosenFilter) {
        filterMessage.style.opacity = "1";
        filterMessage.innerHTML = chosenFilter;
            
        setTimeout(() => {
            filterMessage.style.opacity = "0";
        }, 1000);
    }

    const canvas = document.createElement('canvas');
    let images = [];
    let invertBtnState = false;

    if(invertBtn) {
        invertBtn.addEventListener('click', () => {
            invertBtnState = !invertBtnState;
            cameraInvertSwitch();
            filterText("invert");
        });
    }

    function cameraInvertSwitch() {
        if (invertBtnState) {
            photoContainer.style.transform = 'scaleX(-1)';
            video.style.transform = 'scaleX(-1)';
        } else {
            photoContainer.style.transform = 'scaleX(1)';
            video.style.transform = 'scaleX(1)';
        }
    }

    async function startCamera() {
        console.log("startCamera() called");
        stopCameraStream(); 
    
        try {
            console.log("Requesting camera access...");
            const stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } });
            console.log("Camera access granted, setting video source");
            video.srcObject = stream;
    
            video.onloadedmetadata = () => {
                console.log("Video metadata loaded, starting video");
                video.play();
                setTimeout(() => {
                    console.log("Hiding black screen");
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
    
    if (window.location.pathname.endsWith("canvasLayout2.php") || window.location.pathname === "canvasLayout2.php") {
        console.log("Starting camera for canvasLayout2...");
        startCamera();
    } else {
        console.log("Path doesn't match canvasLayout2.php");
    }

    async function startPhotobooth() {
        if (images.length > 0) {
            const confirmReset = confirm("You already have pictures. Do you want to retake them?");
            if (!confirmReset) return;
    
            images = [];
            photoContainer.innerHTML = '';
            progressCounter.textContent = "0/4";
            doneBtn.style.display = 'none';
        }
    
        startBtn.disabled = true;
        uploadBtn.disabled = true;
        startBtn.innerHTML = 'Capturing...';
        progressCounter.textContent = "0/4";
    
        const timerOptions = document.getElementById("timerOptions");
        const selectedValue = parseInt(timerOptions.value) || 3;
    
        for (let i = 0; i < 4; i++) {
            await showCountdown(selectedValue);

            flash.style.opacity = 1;
            setTimeout(() => flash.style.opacity = 0, 200);

            if (video.videoWidth === 0 || video.videoHeight === 0) {
                console.error("Video not ready yet.");
                alert("Camera not ready. Please try again.");
                return;
            }

            const ctx = canvas.getContext('2d');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            
            ctx.fillStyle = '#FFFFFF';
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            ctx.filter = getComputedStyle(video).filter;
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

            await new Promise(res => setTimeout(res, 100));

            const imageData = canvas.toDataURL('image/png');
            console.log("Captured 4R Image: ", imageData);
            images.push(imageData);

            const imgElement = document.createElement('img');
            imgElement.src = imageData;
            imgElement.classList.add('photo');
            photoContainer.appendChild(imgElement);

            progressCounter.textContent = `${i + 1}/4`;

            if (i < 3) await new Promise(res => setTimeout(res, 500));
        }
    
        if (images.length === 4) {
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
            void countdownText.offsetWidth;
            countdownText.classList.add("bounce");
            await new Promise(res => setTimeout(res, 1000));
        }
        countdownText.style.display = "none";
    }
    
    function updateCountdown() {
        showCountdown();
    }

    function handleImageUpload(event) {
        const files = Array.from(event.target.files);

        if (files.length === 0) {
            alert("Please upload valid image files.");
            return;
        }

        if (images.length >= 4) {
            const confirmReplace = confirm("You already have 4 pictures. Uploading new images will replace all current pictures. Do you want to proceed?");
            if (!confirmReplace) {
                event.target.value = "";
                return;
            }

            images = [];
            photoContainer.innerHTML = '';
            progressCounter.textContent = "0/4";
            startBtn.innerHTML = 'Capturing...';
            doneBtn.style.display = 'none';
        }

        startBtn.innerHTML = 'Capturing...';

        for (const file of files) {
            if (!file.type.startsWith("image/")) continue;

            const reader = new FileReader();
            reader.onload = function (e) {
                const imageData = e.target.result;

                images.push(imageData);

                const imgElement = document.createElement('img');
                imgElement.src = imageData;
                imgElement.classList.add('photo');
                photoContainer.appendChild(imgElement);

                progressCounter.textContent = `${images.length}/4`;

                if (images.length === 4) {
                    startBtn.innerHTML = 'Retake';
                    doneBtn.style.display = 'block';
                }
            };

            reader.readAsDataURL(file);
        }

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
                    const tempCanvas = document.createElement('canvas');
                    const tempCtx = tempCanvas.getContext('2d');
                    tempCanvas.width = img.width;
                    tempCanvas.height = img.height;
                    tempCtx.translate(img.width, 0);
                    tempCtx.scale(-1, 1);
                    tempCtx.drawImage(img, 0, 0, img.width, img.height);
                    storedImages[index] = tempCanvas.toDataURL('image/png');
                } else {
                    storedImages[index] = imgData;
                }
                loadedImages++;

                if (loadedImages === 4) { // Pastikan 4 gambar untuk Layout 2
                    const estimatedSize = new Blob([JSON.stringify(storedImages)]).size;
                    const storageLimit = 10 * 1024 * 1024; // 10MB limit
                    if (estimatedSize > storageLimit) {
                        alert("The total image size exceeds the 10MB limit. Please upload smaller images.");
                        return;
                    }
                    sessionStorage.setItem('photoArray2', JSON.stringify(storedImages));
                    console.log("4 images stored in sessionStorage!");
                    window.location.href = 'customizeLayout2.php';
                }
            };
            img.onerror = () => {
                console.error(`Failed to load image at index ${index}`);
            };
        });
    }
    
    if(startBtn) {
        startBtn.addEventListener('click', () => startPhotobooth());
    }

    document.addEventListener('keydown', (event) => {
        if (event.code === "Space") {
            event.preventDefault();
            startPhotobooth();
        }
    });

    if (doneBtn) {
        doneBtn.addEventListener('click', () => storeImageArray());
    }

    if (uploadBtn) {
        uploadBtn.addEventListener('click', () => {
            alert("Note: Please make sure your photo size does not exceed 8MB.\nLarge images may cause saving issues.");
            uploadInput.click();
        });
    }
    
    if(uploadInput) {
        uploadInput.addEventListener('change', handleImageUpload);
    }
});