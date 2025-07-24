document.addEventListener('DOMContentLoaded', function() {
    
    // Timer functionality
    const timerDisplay = document.getElementById('timer-display');
    const timeoutModal = document.getElementById('timeout-modal');
    const timeoutOkBtn = document.getElementById('timeout-ok-btn');
    
    let timeLeft = 3 * 60; // 3 minutes in seconds
    let timerInterval;

    function updateTimer() {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        timerDisplay.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        
        if (timeLeft <= 0) {
            clearInterval(timerInterval);
            showTimeoutModal();
            return;
        }
        
        timeLeft--;
    }

    function showTimeoutModal() {
        timeoutModal.style.display = 'block';
    }

    function hideTimeoutModal() {
        timeoutModal.style.display = 'none';
    }

    timeoutOkBtn.addEventListener('click', () => {
        hideTimeoutModal();
        window.location.href = '/FotoboxJO/index.html';
    });

    // Start the timer
    timerInterval = setInterval(updateTimer, 1000);
    updateTimer(); // Initial call to set display

    const photoCustomPreview = document.getElementById('photoPreview')
    const pinkBtn = document.getElementById('pinkBtnFrame')
    const blueBtn = document.getElementById('blueBtnFrame')
    const yellowBtn = document.getElementById('yellowBtnFrame')
    const brownBtn = document.getElementById('brownBtnFrame')
    const redBtn = document.getElementById('redBtnFrame')

    const matchaBtn = document.getElementById('matchaBtnFrame')
    const purpleBtn = document.getElementById('purpleBtnFrame')
    const whiteBtn = document.getElementById('whiteBtnFrame')
    const blackBtn = document.getElementById('blackBtnFrame')

    const pinkGlitter = document.getElementById('pinkGlitter');
    const pinkPlaid = document.getElementById('pinkPlaid');
    const bluePlaid = document.getElementById('bluePlaid');
    const brownLeopard = document.getElementById('brownLeopard');
    const cowPrint = document.getElementById('cowPrint');
    const redLeather = document.getElementById('redLeather');
    const pinkGumamela = document.getElementById('pinkGumamela');
    const whiteKnitted = document.getElementById('whiteKnitted');
    const blackCq = document.getElementById('black-cq');
    const whiteCq = document.getElementById('white-cq');
    const pinkLeather = document.getElementById('pinkLeather');
    const ribbonDenim = document.getElementById('ribbonDenim');
    const blackPinkRibbon = document.getElementById('blackPinkRibbon');
    const blueYellowSquares = document.getElementById('blueYellowSquares');
    const blueWhiteSquares = document.getElementById('blueWhiteSquares');
    const fourLockers = document.getElementById('fourLockers');
    const crumpledPaper = document.getElementById('crumpledPaper');
    const blueBackdrop = document.getElementById('blueBackdrop');
    const greenHills = document.getElementById('greenHills');
    const sandShells = document.getElementById('sandShells');
    const waterBeach = document.getElementById('waterBeach');
    const cocoTrees = document.getElementById('cocoTrees');
    const pinkLiliesFrame = document.getElementById('pinkLiliesFrame');
    const roseCardFrame = document.getElementById('roseCardFrame');
    const princessVintageFrame = document.getElementById('princessVintageFrame');
    const gridPaperFrame = document.getElementById('gridPaperFrame');
    const stardustFrame = document.getElementById('stardustFrame');
    const roughTextureFrame = document.getElementById('roughTextureFrame');
    const ribbonSweaterFrame = document.getElementById('ribbonSweaterFrame');
    const vsPinkFrame = document.getElementById('vsPinkFrame');
    const vsYellowFrame = document.getElementById('vsYellowFrame');
    const redRosesPaintFrame = document.getElementById('redRosesPaintFrame');
    const grayTrashFrame = document.getElementById('grayTrashFrame');
    const blackTrashFrame = document.getElementById('blackTrashFrame');
    const whiteTrashFrame = document.getElementById('whiteTrashFrame');
    const brownKnittedFrame = document.getElementById('brownKnittedFrame');
    const hotPinkKnittedFrame = document.getElementById('hotPinkKnittedFrame');
    const redKnittedFrame = document.getElementById('redKnittedFrame');
    const pinkKnittedFrame = document.getElementById('pinkKnittedFrame');
    const redStripesFrame = document.getElementById('redStripesFrame');
    const greenStripesFrame = document.getElementById('greenStripesFrame');
    const blueStripesFrame = document.getElementById('blueStripesFrame');
    const partyDrapeFrame = document.getElementById('partyDrapeFrame');
    const partyDotsFrame = document.getElementById('partyDotsFrame');
    const blingDenimFrame = document.getElementById('blingDenimFrame');

    const customBack = document.getElementById('customBack');
    const downloadCopyBtn = document.getElementById('downloadCopyBtn');

    const noneSticker = document.getElementById('noneSticker')
    const kissSticker = document.getElementById('kissSticker')
    const ribbonSticker = document.getElementById('ribbonSticker')
    const sweetSticker = document.getElementById('sweetSticker')
    const sparkleSticker = document.getElementById('sparkleSticker')
    const pearlSticker = document.getElementById('pearlSticker')
    const softSticker = document.getElementById('softSticker');
    const bunnySticker = document.getElementById('bunnySticker');
    const classicSticker = document.getElementById('classicSticker');
    const classicBSticker = document.getElementById('classicBSticker');
    const luckySticker = document.getElementById('luckySticker');
    const confettiSticker = document.getElementById('confettiSticker');
    const ribbonCoquetteSticker = document.getElementById('ribbonCoquetteSticker');
    const blueRibbonCoquetteSticker = document.getElementById('blueRibbonCoquetteSticker');
    const blackStarSticker = document.getElementById('blackStarSticker');
    const yellowChickenSticker = document.getElementById('yellowChickenSticker');
    const brownBearSticker = document.getElementById('brownBearSticker');
    const lotsHeartSticker = document.getElementById('lotsHeartSticker');
    const tabbyCatSticker = document.getElementById('tabbyCatSticker');
    const ballerinaCappuccinoSticker = document.getElementById('ballerinaCappuccinoSticker');
    const doggyWhiteSticker = document.getElementById('doggyWhiteSticker');
    const sakuraBlossomSticker = document.getElementById('sakuraBlossomSticker');
    const myGirlsSticker = document.getElementById('myGirlsSticker');

    const engLogo = document.getElementById('engLogo');
    const korLogo = document.getElementById('korLogo');
    const cnLogo = document.getElementById('cnLogo');

    const normalFrameBtn = document.getElementById('noneFrameShape');
    const roundEdgeFrameBtn = document.getElementById('softFrameShape');
    const circleFrameBtn = document.getElementById('circleFrameShape');
    const heartFrameBtn = document.getElementById('heartFrameShape');

    const colorPickerBtn = document.getElementById("colorPickerBtn");


    let finalCanvas = null;
    let backgroundType = 'color';
    let backgroundColor = '#FFC2D1';
    let backgroundImage = null;
    let currentStickers = [];
    let logoStickers = [];
    let shapeFrame = 'none';
    let textOverlay = '';
    let logoOverlay = '';

    let storedImages = [];
    let imageArrayLength = 0;

    // Fetch photos from server session instead of sessionStorage
    fetch('/FotoboxJO/src/api-fetch/get_photos.php')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.photos) {
                storedImages = data.photos;
                imageArrayLength = storedImages.length;
                
                console.log('Stored Images from server:', storedImages);
                console.log('Image Array Length:', imageArrayLength);

                if (imageArrayLength === 0) {
                    console.error('No images found in server session!');
                    alert('No images found. Please go back and take photos first.');
                    window.location.href = 'canvasLayout1.php';
                    return;
                }

                // Initialize canvas after getting images
                initializeCanvas();
            } else {
                console.error('Failed to get photos from server:', data.error);
                alert('Failed to load photos. Please try again.');
                window.location.href = 'canvasLayout1.php';
            }
        })
        .catch(error => {
            console.error('Error fetching photos:', error);
            alert('Error loading photos. Please try again.');
            window.location.href = 'canvasLayout1.php';
        });

    function initializeCanvas() {
        if (imageArrayLength === 0) {
            console.error('No images found in server session!');
            alert('No images found. Redirecting to camera page.');
            window.location.href = 'canvasLayout1.php';
            return;
        }

        // Continue with canvas initialization
        console.log('Initializing canvas with', imageArrayLength, 'images');
        redrawCanvas(); // Call initial draw
    }

    // Function to set new background and redraw canvas
    function setBackground(option) {
        console.log('Setting background:', option);
        
        if (option.type === 'color') {
            backgroundType = 'color';
            backgroundColor = option.value;
            redrawCanvas();
        } else if (option.type === 'image') {
            backgroundType = 'image';
            backgroundImage = new Image();
            backgroundImage.crossOrigin = 'anonymous';
            backgroundImage.src = option.src;
            
            backgroundImage.onload = () => {
                redrawCanvas();
            };
            
            backgroundImage.onerror = () => {
                console.error('Failed to load background image:', option.src);
            };
        }
    }

    // Initialize Vanilla Picker
    const picker = new Picker({
        parent: colorPickerBtn,
        popup: 'bottom',
        color: '#FFFFFF',
        onChange: (color) => {
            setBackground({ type: 'color', value: color.hex });
        },
        onDone: (color) => {
            colorPickerBtn.style.backgroundColor = color.hex;
        }
    });

    // Show picker on click
    colorPickerBtn.addEventListener("click", () => picker.show());

    // function setSticker(type) {
    //     console.log(`Sticker changed to: ${type}`);
    //     selectedSticker = type;
    //     redrawCanvas();
    // }
    function setSticker(type) {
        if (selectedSticker === type) {
            selectedSticker = null; // Remove sticker if already selected
            console.log(`Removed sticker: ${type}`);
        } else {
            selectedSticker = type;
            console.log(`Selected sticker: ${type}`);
        }
        redrawCanvas();
    }

    // 4R Canvas dimensions - Optimized for web and print
    function redrawCanvas() {
        console.log(`Redrawing Layout 1 canvas with background: ${backgroundType}, color: ${backgroundColor}`);

        const stackedCanvas = document.createElement('canvas');
        const ctx = stackedCanvas.getContext('2d');

        const canvasWidth = 1200;
        const canvasHeight = 1800;
        const borderWidth = 30;
        const bottomPadding = 80;
        const photoGap = 15;

        const availableHeight = canvasHeight - (borderWidth * 2) - bottomPadding;
        const availableWidth = canvasWidth - (borderWidth * 2);
        const photoWidth = availableWidth;
        const photoHeight = (availableHeight - photoGap) / 2;

        stackedCanvas.width = canvasWidth;
        stackedCanvas.height = canvasHeight;

        if (backgroundType === 'color') {
            ctx.fillStyle = backgroundColor;
            ctx.fillRect(0, 0, stackedCanvas.width, stackedCanvas.height);
        } else if (backgroundType === 'image') {
            const bgImg = new Image();
            bgImg.crossOrigin = 'anonymous';
            bgImg.onload = () => {
                const pattern = ctx.createPattern(bgImg, 'repeat');
                ctx.fillStyle = pattern;
                ctx.fillRect(0, 0, stackedCanvas.width, stackedCanvas.height);
                drawPhotos();
            };
            bgImg.src = backgroundImage.src;
            return;
        } else {
            ctx.fillStyle = '#FFC2D1';
            ctx.fillRect(0, 0, stackedCanvas.width, stackedCanvas.height);
        }

        drawPhotos();

        function drawPhotos() {
            let loadedCount = 0;
            const totalPhotos = Math.min(storedImages.length, 2);

            for (let i = 0; i < totalPhotos; i++) {
                const img = new Image();
                img.crossOrigin = 'anonymous';
                img.onload = () => {
                    const x = borderWidth;
                    const y = borderWidth + (i * (photoHeight + photoGap));

                    ctx.save();
                    if (shapeFrame === 'circle') {
                        const centerX = x + photoWidth / 2;
                        const centerY = y + photoHeight / 2;
                        const radius = Math.min(photoWidth, photoHeight) / 2 - 10;
                        ctx.beginPath();
                        ctx.arc(centerX, centerY, radius, 0, 2 * Math.PI);
                        ctx.clip();
                    } else if (shapeFrame === 'roundedRect') {
                        const cornerRadius = 20;
                        ctx.beginPath();
                        ctx.roundRect(x, y, photoWidth, photoHeight, cornerRadius);
                        ctx.clip();
                    } else if (shapeFrame === 'heart') {
                        const centerX = x + photoWidth / 2;
                        const centerY = y + photoHeight / 2;
                        const size = Math.min(photoWidth, photoHeight) / 2;
                        ctx.beginPath();
                        ctx.moveTo(centerX, centerY + size/4);
                        ctx.bezierCurveTo(centerX, centerY-size/2, centerX-size, centerY-size/2, centerX-size/2, centerY);
                        ctx.bezierCurveTo(centerX-size, centerY+size/2, centerX, centerY+size/2, centerX, centerY+size/4);
                        ctx.bezierCurveTo(centerX, centerY+size/2, centerX+size, centerY+size/2, centerX+size/2, centerY);
                        ctx.bezierCurveTo(centerX+size, centerY-size/2, centerX, centerY-size/2, centerX, centerY+size/4);
                        ctx.clip();
                    }

                    const imgAspect = img.width / img.height;
                    let drawWidth, drawHeight, drawX, drawY;

                    if (imgAspect > 1) {
                        drawHeight = photoHeight;
                        drawWidth = photoHeight * imgAspect;
                        drawX = x - (drawWidth - photoWidth) / 2;
                        drawY = y;
                    } else {
                        drawWidth = photoWidth;
                        drawHeight = photoWidth / imgAspect;
                        drawX = x;
                        drawY = y - (drawHeight - photoHeight) / 2;
                    }

                    ctx.drawImage(img, drawX, drawY, drawWidth, drawHeight);
                    ctx.restore();

                    loadedCount++;
                    if (loadedCount === totalPhotos) {
                        addStickers(stackedCanvas);
                        addTextOverlay(stackedCanvas);
                        addLogo(stackedCanvas);
                        updatePreview(stackedCanvas);
                    }
                };
                img.src = storedImages[i];
            }
        }
    }

    function addStickers(canvas) {
        const ctx = canvas.getContext('2d');
        
        // 4R specific sticker configurations
        const stickerConfigs = {
            'kiss': [{ src: '/src/assets/stickers/kiss1.png', x: 50, y: 400, size: 200 }],
            'sweet': [
                { src: '/src/assets/stickers/sweet1.png', x: 30, y: 100, size: 120 },
                { src: '/src/assets/stickers/sweet2.png', x: canvas.width - 150, y: 600, size: 120 },
                { src: '/src/assets/stickers/sweet3.png', x: 50, y: canvas.height - 300, size: 120 }
            ],
            'ribbon': [
                { src: '/src/assets/stickers/ribbon1.png', x: 30, y: 100, size: 120 },
                { src: '/src/assets/stickers/ribbon3.png', x: canvas.width - 150, y: 800, size: 130 },
                { src: '/src/assets/stickers/ribbon2.png', x: 25, y: canvas.height - 500, size: 120 }
            ],
            'sparkle': [
                { src: '/src/assets/stickers/sparkle1.png', x: canvas.width - 250, y: 200, size: 300 },
                { src: '/src/assets/stickers/sparkle2.png', x: 5, y: canvas.height - 1200, size: 250 },
                { src: '/src/assets/stickers/sparkle2.png', x: canvas.width - 200, y: canvas.height - 250, size: 150 }
            ]
            // Add more sticker configurations as needed
        };

        currentStickers.forEach(stickerType => {
            if (stickerConfigs[stickerType]) {
                stickerConfigs[stickerType].forEach(config => {
                    const stickerImg = new Image();
                    stickerImg.crossOrigin = 'anonymous';
                    stickerImg.onload = () => {
                        ctx.drawImage(stickerImg, config.x, config.y, config.size, config.size);
                        updatePreview(canvas);
                    };
                    stickerImg.src = config.src;
                });
            }
        });
    }

    function addTextOverlay(canvas) {
        if (!textOverlay) return;
        
        const ctx = canvas.getContext('2d');
        ctx.fillStyle = '#fff';
        ctx.strokeStyle = '#000';
        ctx.lineWidth = 2;
        ctx.font = 'bold 40px Arial'; // Adjusted text size for smaller padding
        ctx.textAlign = 'center';
        
        const selectedText = textOverlay;
        ctx.strokeText(selectedText, canvas.width / 2, canvas.height - 40); // Closer to bottom
        ctx.fillText(selectedText, canvas.width / 2, canvas.height - 40);
    }

    function addLogo(canvas) {
        logoStickers.forEach(logoType => {
            const logoConfigs = {
                'english': { src: '/src/assets/icons/photobooth-new-logo.png', x: canvas.width - 120, y: canvas.height - 120, size: 80 },
                'korean': { src: '/src/assets/icons/photobooth-new-logo.png', x: canvas.width - 120, y: canvas.height - 120, size: 80 },
                'chinese': { src: '/src/assets/icons/photobooth-new-logo.png', x: canvas.width - 120, y: canvas.height - 120, size: 80 }
            };

            if (logoConfigs[logoType]) {
                const config = logoConfigs[logoType];
                const logoImg = new Image();
                logoImg.crossOrigin = 'anonymous';
                logoImg.onload = () => {
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(logoImg, config.x, config.y, config.size, config.size);
                    updatePreview(canvas);
                };
                logoImg.src = config.src;
            }
        });
    }

    function updatePreview(canvas) {
        finalCanvas = canvas;
        photoCustomPreview.innerHTML = '';
        
        const clonedCanvas = document.createElement('canvas');
        const clonedCtx = clonedCanvas.getContext('2d');
        clonedCanvas.width = canvas.width;
        clonedCanvas.height = canvas.height;
        clonedCtx.drawImage(canvas, 0, 0);
        
        // Scale down for preview
        clonedCanvas.style.width = "300px"; // Larger preview for high-res 4R
        clonedCanvas.style.height = "auto";
        clonedCanvas.style.border = "1px solid #ccc";
        
        photoCustomPreview.appendChild(clonedCanvas);
    }

    // Color frame handlers
    if (pinkBtn) {
        pinkBtn.addEventListener('click', () => {
            setBackground({ type: 'color', value: '#FFC2D1' });
        });
    }

    if (blueBtn) {
        blueBtn.addEventListener('click', () => {
            setBackground({ type: 'color', value: '#CAF0F8' });
        });
    }

    if (yellowBtn) {
        yellowBtn.addEventListener('click', () => {
            setBackground({ type: 'color', value: '#FFF8A5' });
        });
    }

    if (brownBtn) {
        brownBtn.addEventListener('click', () => {
            setBackground({ type: 'color', value: '#DDBEA9' });
        });
    }

    if (redBtn) {
        redBtn.addEventListener('click', () => {
            setBackground({ type: 'color', value: '#780000' });
        });
    }

    if (matchaBtn) {
        matchaBtn.addEventListener('click', () => {
            setBackground({ type: 'color', value: '#90a955' });
        });
    }

    if (purpleBtn) {
        purpleBtn.addEventListener('click', () => {
            setBackground({ type: 'color', value: '#c19ee0' });
        });
    }

    if (whiteBtn) {
        whiteBtn.addEventListener('click', () => {
            setBackground({ type: 'color', value: '#FFFFFF' });
        });
    }

    if (blackBtn) {
        blackBtn.addEventListener('click', () => {
            setBackground({ type: 'color', value: '#000000' });
        });
    }

    // Shape frame handlers
    if (normalFrameBtn) {
        normalFrameBtn.addEventListener('click', () => {
            shapeFrame = 'none';
            redrawCanvas();
        });
    }

    if (roundEdgeFrameBtn) {
        roundEdgeFrameBtn.addEventListener('click', () => {
            shapeFrame = 'roundedRect';
            redrawCanvas();
        });
    }

    if (circleFrameBtn) {
        circleFrameBtn.addEventListener('click', () => {
            shapeFrame = 'circle';
            redrawCanvas();
        });
    }

    if (heartFrameBtn) {
        heartFrameBtn.addEventListener('click', () => {
            shapeFrame = 'heart';
            redrawCanvas();
        });
    }

    // Sticker handlers
    if (noneSticker) {
        noneSticker.addEventListener('click', () => {
            currentStickers = [];
            redrawCanvas();
        });
    }

    if (kissSticker) {
        kissSticker.addEventListener('click', () => {
            currentStickers = ['kiss'];
            redrawCanvas();
        });
    }

    if (sweetSticker) {
        sweetSticker.addEventListener('click', () => {
            currentStickers = ['sweet'];
            redrawCanvas();
        });
    }

    if (ribbonSticker) {
        ribbonSticker.addEventListener('click', () => {
            currentStickers = ['ribbon'];
            redrawCanvas();
        });
    }

    if (sparkleSticker) {
        sparkleSticker.addEventListener('click', () => {
            currentStickers = ['sparkle'];
            redrawCanvas();
        });
    }

    // Download functionality
    if (downloadCopyBtn) {
        downloadCopyBtn.addEventListener('click', () => {
            if (finalCanvas) {
                const link = document.createElement('a');
                link.download = `photobooth-4R-${Date.now()}.png`;
                link.href = finalCanvas.toDataURL('image/png');
                link.click();
            }
        });
    }

    // Back button
    if (customBack) {
        customBack.addEventListener('click', () => {
            window.location.href = 'canvasLayout1.php';
        });
    }

    // Email Modal Functions
    function showEmailModal() {
        document.getElementById('emailModal').style.display = 'flex';
    }

    function hideEmailModal() {
        document.getElementById('emailModal').style.display = 'none';
    }

    // Email Function
    function sendEmail() {
        const email = document.getElementById('emailInput').value;
        if (!email) {
            alert('Please enter your email address');
            return;
        }

        if (!email.includes('@')) {
            alert('Please enter a valid email address');
            return;
        }

        // Get the current canvas as base64
        const canvas = document.getElementById('combinedCanvas');
        const imageData = canvas.toDataURL('image/png');

        fetch('/FotoboxJO/src/api-fetch/send_email.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                email: email,
                customized_image: imageData,
                layout: 'layout1'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Email berhasil dikirim!');
                hideEmailModal();
            } else {
                alert('Gagal mengirim email: ' + (data.error || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error sending email: ' + error.message);
        });
    }

    // Print Function
    function printPhoto() {
        const canvas = document.getElementById('combinedCanvas');
        const imageData = canvas.toDataURL('image/png');

        fetch('/FotoboxJO/src/api-fetch/print_photo.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                image: imageData,
                layout: 'layout1'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Foto berhasil dicetak! Silakan ambil foto Anda.');
            } else {
                alert('Gagal mencetak foto: ' + (data.error || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error printing photo: ' + error.message);
        });
    }

    // Continue Function
    function continueToThankYou() {
        clearInterval(timerInterval);
        window.location.href = 'thankyou.php';
    }

    // Button Event Listeners
    document.getElementById('emailBtn').addEventListener('click', showEmailModal);
    document.getElementById('closeEmailModal').addEventListener('click', hideEmailModal);
    document.getElementById('sendEmailBtn').addEventListener('click', sendEmail);
    document.getElementById('printBtn').addEventListener('click', printPhoto);
    document.getElementById('continueBtn').addEventListener('click', continueToThankYou);

    // Initialize with default settings
    redrawCanvas();
});
