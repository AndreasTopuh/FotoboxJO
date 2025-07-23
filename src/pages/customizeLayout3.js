document.addEventListener('DOMContentLoaded', function() {
    
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

    let finalCanvas = null;
    let backgroundType = '#FFC2D1'; // Default background
    let currentStickers = [];
    let logoStickers = [];
    let shapeFrame = 'none';
    let textOverlay = '';
    let logoOverlay = '';

    const storedImages = JSON.parse(sessionStorage.getItem('photoArray3')) || [];
    const imageArrayLength = storedImages.length; // Should be 6 for Layout 3

    console.log('=== Layout 3 Customize Debug ===');
    console.log('Stored Images:', storedImages);
    console.log('Image Array Length:', imageArrayLength);
    console.log('Session Storage Key:', 'photoArray3');
    console.log('All Session Storage Keys:', Object.keys(sessionStorage));

    if (imageArrayLength === 0) {
        console.error('No images found in session storage for Layout 3');
        alert('No images found. Redirecting to camera page.');
        window.location.href = 'canvasLayout3.php';
        return;
    }

    if (imageArrayLength !== 6) {
        console.warn(`Expected 6 images for Layout 3, but found ${imageArrayLength}`);
        alert(`Expected 6 images for Layout 3, but found ${imageArrayLength}. Please retake photos.`);
        window.location.href = 'canvasLayout3.php';
        return;
    }

    // 4R Canvas dimensions - Optimized for web and print
    function redrawCanvas() {
        console.log(`Redrawing Layout 3 canvas with background: ${backgroundType}`);

        const stackedCanvas = document.createElement('canvas');
        const ctx = stackedCanvas.getContext('2d');

        // 4R dimensions: 4 inch x 6 inch
        // Using 300 DPI for high print quality
        const canvasWidth = 1200;   // 4 inch x 300 DPI
        const canvasHeight = 1800;  // 6 inch x 300 DPI
        const borderWidth = 30;     // Smaller border for more photo space
        const bottomPadding = 80;   // Smaller padding for more photo space
        const photoGap = 15;        // Gap between photos

        const availableHeight = canvasHeight - (borderWidth * 2) - bottomPadding;
        const availableWidth = canvasWidth - (borderWidth * 2);

        // For Layout 3 with 6 photos, arrange them in a 3x2 grid (3 columns, 2 rows)
        const photoWidth = (availableWidth - (photoGap * 2)) / 3; // 3 columns with gaps
        const photoHeight = (availableHeight - photoGap) / 2; // 2 rows with gap

        stackedCanvas.width = canvasWidth;
        stackedCanvas.height = canvasHeight;

        // Apply background based on layout image pattern (no forced white background)
        if (backgroundType.startsWith('#')) {
            ctx.fillStyle = backgroundType;
            ctx.fillRect(0, 0, stackedCanvas.width, stackedCanvas.height);
        } else if (backgroundType.startsWith('url(')) {
            // Handle background images
            const bgImg = new Image();
            bgImg.crossOrigin = 'anonymous';
            bgImg.onload = () => {
                // Create pattern from image
                const pattern = ctx.createPattern(bgImg, 'repeat');
                ctx.fillStyle = pattern;
                ctx.fillRect(0, 0, stackedCanvas.width, stackedCanvas.height);
                drawPhotos();
            };
            bgImg.src = backgroundType.match(/url\(([^)]+)\)/)[1];
            return; // Exit early, continue in onload
        }

        drawPhotos();

        function drawPhotos() {
            let loadedCount = 0;
            const totalPhotos = Math.min(storedImages.length, 6); // Max 6 photos for Layout 3

            // Draw all 6 photos in 3x2 grid
            for (let i = 0; i < totalPhotos; i++) {
                if (storedImages[i]) {
                    const img = new Image();
                    img.crossOrigin = 'anonymous';
                    img.onload = () => {
                        const col = i % 3; // Column (0, 1, or 2)
                        const row = Math.floor(i / 3); // Row (0 or 1)
                        const x = borderWidth + (col * (photoWidth + photoGap));
                        const y = borderWidth + (row * (photoHeight + photoGap));

                        // Apply shape frame
                        ctx.save();
                        if (shapeFrame === 'circle') {
                            // Create circular clipping path
                            const centerX = x + photoWidth / 2;
                            const centerY = y + photoHeight / 2;
                            const radius = Math.min(photoWidth, photoHeight) / 2 - 10;
                            
                            ctx.beginPath();
                            ctx.arc(centerX, centerY, radius, 0, 2 * Math.PI);
                        ctx.clip();
                    } else if (shapeFrame === 'roundedRect') {
                        // Create rounded rectangle clipping path
                        const cornerRadius = 20;
                        ctx.beginPath();
                        ctx.roundRect(x, y, photoWidth, photoHeight, cornerRadius);
                        ctx.clip();
                    } else if (shapeFrame === 'heart') {
                        // Create heart-shaped clipping path
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

                    // Calculate aspect ratio and fit image to fill frame completely (crop if needed)
                    const imgAspect = img.width / img.height;
                    const frameAspect = photoWidth / photoHeight;
                    
                    let drawWidth, drawHeight, drawX, drawY;
                    
                    // Use "cover" method - image will fill entire frame, cropping if necessary
                    if (imgAspect > frameAspect) {
                        // Image is wider than frame - fit to height and crop sides
                        drawHeight = photoHeight;
                        drawWidth = photoHeight * imgAspect;
                        drawX = x - (drawWidth - photoWidth) / 2; // Center horizontally
                        drawY = y;
                    } else {
                        // Image is taller than frame - fit to width and crop top/bottom
                        drawWidth = photoWidth;
                        drawHeight = photoWidth / imgAspect;
                        drawX = x;
                        drawY = y - (drawHeight - photoHeight) / 2; // Center vertically
                    }

                    // Ensure image fills the entire frame
                    if (drawWidth < photoWidth) {
                        const scale = photoWidth / drawWidth;
                        drawWidth = photoWidth;
                        drawHeight *= scale;
                        drawY = y - (drawHeight - photoHeight) / 2;
                    }
                    
                    if (drawHeight < photoHeight) {
                        const scale = photoHeight / drawHeight;
                        drawHeight = photoHeight;
                        drawWidth *= scale;
                        drawX = x - (drawWidth - photoWidth) / 2;
                    }

                    ctx.drawImage(img, drawX, drawY, drawWidth, drawHeight);
                    ctx.restore();

                    loadedCount++;
                    
                    // When all 6 photos are loaded, add stickers, text, and logo
                    if (loadedCount === totalPhotos) {
                        // Add stickers
                        addStickers(stackedCanvas);
                        
                        // Add text overlay
                        addTextOverlay(stackedCanvas);
                        
                        // Add logo
                        addLogo(stackedCanvas);
                        
                        updatePreview(stackedCanvas);
                    }
                };
                img.src = storedImages[i];
            }
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
            backgroundType = '#FFC2D1';
            redrawCanvas();
        });
    }

    if (blueBtn) {
        blueBtn.addEventListener('click', () => {
            backgroundType = '#CAF0F8';
            redrawCanvas();
        });
    }

    if (yellowBtn) {
        yellowBtn.addEventListener('click', () => {
            backgroundType = '#FFF8A5';
            redrawCanvas();
        });
    }

    if (brownBtn) {
        brownBtn.addEventListener('click', () => {
            backgroundType = '#DDBEA9';
            redrawCanvas();
        });
    }

    if (redBtn) {
        redBtn.addEventListener('click', () => {
            backgroundType = '#780000';
            redrawCanvas();
        });
    }

    if (matchaBtn) {
        matchaBtn.addEventListener('click', () => {
            backgroundType = '#90a955';
            redrawCanvas();
        });
    }

    if (purpleBtn) {
        purpleBtn.addEventListener('click', () => {
            backgroundType = '#c19ee0';
            redrawCanvas();
        });
    }

    if (whiteBtn) {
        whiteBtn.addEventListener('click', () => {
            backgroundType = '#FFFFFF';
            redrawCanvas();
        });
    }

    if (blackBtn) {
        blackBtn.addEventListener('click', () => {
            backgroundType = '#000000';
            redrawCanvas();
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
            window.location.href = 'canvasLayout3.php';
        });
    }

    // Initialize with default settings
    redrawCanvas();
});
