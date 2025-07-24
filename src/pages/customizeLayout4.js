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
            window.location.href = '/';
        });
    }

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

    const customBack = document.getElementById('customBack');
    const downloadCopyBtn = document.getElementById('downloadCopyBtn');
    const emailBtn = document.getElementById('emailBtn');
    const printBtn = document.getElementById('printBtn');
    const continueBtn = document.getElementById('continueBtn');

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

    const engLogo = document.getElementById('engLogo');
    const korLogo = document.getElementById('korLogo');
    const cnLogo = document.getElementById('cnLogo');

    const normalFrameBtn = document.getElementById('noneFrameShape');
    const roundEdgeFrameBtn = document.getElementById('softFrameShape');
    const circleFrameBtn = document.getElementById('circleFrameShape');
    const heartFrameBtn = document.getElementById('heartFrameShape');

    let engLogoToggle = false;
    let korLogoToggle = false;
    let cnLogoToggle = false;

    let selectedShape = 'default'; // or 'circle', 'rounded', 'heart'

    const dateCheckbox = document.getElementById('dateCheckbox');
    const dateTimeCheckbox = document.getElementById('dateTimeCheckbox');

    const colorPickerBtn = document.getElementById("colorPickerBtn");

    let finalCanvas = null;
    let selectedSticker = null;

    let selectedText = 'photobooth';

    if (customBack) {
        customBack.addEventListener('click', () => {
            window.location.href = 'canvasLayout4.php'
        })
    }

    if (dateCheckbox) {
        dateCheckbox.addEventListener('change', () => {
            redrawCanvas();
        });
    }

    if (dateTimeCheckbox) {
        dateTimeCheckbox.addEventListener('change', () => {
            redrawCanvas();
        });
    }
    
    // Retrieve stored images array from server session
    let storedImages = [];
    let imageArrayLength = 0;
    
    // Fetch photos from server session
    fetch('../api-fetch/get_photos.php')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.photos) {
                storedImages = data.photos;
                imageArrayLength = storedImages.length;
                console.log("Loaded images from server session:", storedImages);
                
                // Initialize canvas with photos after loading
                initializeCanvas();
            } else {
                console.log("No valid images found in server session");
                alert("Tidak ada foto ditemukan. Kembali ke halaman sebelumnya.");
                window.location.href = 'selectlayout.php';
            }
        })
        .catch(error => {
            console.error('Error loading photos:', error);
            alert("Error loading photos. Please try again.");
            window.location.href = 'selectlayout.php';
        });

    function initializeCanvas() {
        if (!storedImages || storedImages.length !== imageArrayLength) {
            console.log("No valid images found");
            return;
        }

        // Default background color
        let backgroundType = 'color';
        let backgroundColor = '#FFFFFF'; // default
        let backgroundImage = null;

        // Function to set new background and redraw canvas
        function setBackground(option) {
            console.log('Setting background:', option);
        
            if (option.type === 'color') {
                backgroundType = 'color';
                backgroundColor = option.value;
                redrawCanvas();
            } else if (option.type === 'image') {
                backgroundType = 'image';
                const img = new Image();
                img.onload = function() {
                    backgroundImage = img;
                    redrawCanvas();
                };
                img.src = option.value;
            }
        }

        // Function to redraw the canvas with stored images & selected background
        function redrawCanvas() {
            console.log(`Redrawing canvas with background: ${backgroundType}`);

            const stackedCanvas = document.createElement('canvas');
            const ctx = stackedCanvas.getContext('2d');

            // Layout 4 dimensions (2 photos in vertical strip)
            const canvasWidth = 1206;   // 10.2cm pada 300 DPI
            const canvasHeight = 1794;  // 15.2cm pada 300 DPI
            const borderWidth = 30;
            const spacing = 12;
            const bottomPadding = 100;

            const availableHeight = canvasHeight - (borderWidth * 2) - (spacing * 2) - bottomPadding;
            const photoHeight = availableHeight / imageArrayLength;
            const photoWidth = canvasWidth - (borderWidth * 2);

            stackedCanvas.width = canvasWidth;
            stackedCanvas.height = canvasHeight;

            // Clear the entire canvas first
            ctx.clearRect(0, 0, stackedCanvas.width, stackedCanvas.height);

            if (backgroundType === 'color') {
                ctx.fillStyle = backgroundColor;
                ctx.fillRect(0, 0, stackedCanvas.width, stackedCanvas.height);
                if(backgroundColor !== '#000000' && backgroundColor !== '#780000' && backgroundColor !== '#90a955') {
                    ctx.fillStyle = 'black';
                }
                else {
                    ctx.fillStyle = '#FFFFFF';
                }
            } else if (backgroundType === 'image' && backgroundImage) {
                ctx.drawImage(backgroundImage, 0, 0, stackedCanvas.width, stackedCanvas.height);
                const imageName = backgroundImage.src.split('/').pop();

                const darkImages = [
                    'pink-glitter.jpg',
                    'brown-leopard.jpg',
                    'red-leather.jpg',
                    'ribbon-denim.jpg',
                    'black-pink-ribbon.jpg',
                    'green-hills.jpg',
                    'sand-shells.jpg',
                    'coco-trees.jpg'
                ];

                if (darkImages.includes(imageName)) {
                    ctx.fillStyle = '#FFFFFF';
                } else {
                    ctx.fillStyle = '#000000';
                }
            }

            ctx.font = 'bold 32px Arial, Roboto, sans-serif';
            ctx.textAlign = 'center';
            ctx.fillText(selectedText, stackedCanvas.width / 2, stackedCanvas.height - 55);

            // Draw photos
            storedImages.forEach((imageData, index) => {
                const img = new Image();
                img.onload = function() {
                    const x = borderWidth;
                    const y = borderWidth + (index * (photoHeight + spacing));

                    ctx.save();

                    if (selectedShape === 'circle') {
                        // Create circular clipping path
                        const centerX = x + photoWidth / 2;
                        const centerY = y + photoHeight / 2;
                        const radius = Math.min(photoWidth, photoHeight) / 2;
                        
                        ctx.beginPath();
                        ctx.arc(centerX, centerY, radius, 0, 2 * Math.PI);
                        ctx.clip();
                    } else if (selectedShape === 'rounded') {
                        // Create rounded rectangle clipping path
                        const radius = 20;
                        ctx.beginPath();
                        ctx.roundRect(x, y, photoWidth, photoHeight, radius);
                        ctx.clip();
                    } else if (selectedShape === 'heart') {
                        // Create heart clipping path
                        const centerX = x + photoWidth / 2;
                        const centerY = y + photoHeight / 2;
                        const size = Math.min(photoWidth, photoHeight) / 2;
                        
                        ctx.beginPath();
                        ctx.moveTo(centerX, centerY + size/4);
                        ctx.bezierCurveTo(centerX, centerY-size/2, centerX-size*3/4, centerY-size/2, centerX-size/2, centerY);
                        ctx.bezierCurveTo(centerX-size/4, centerY+size/4, centerX, centerY+size/2, centerX, centerY+size*3/4);
                        ctx.bezierCurveTo(centerX, centerY+size/2, centerX+size/4, centerY+size/4, centerX+size/2, centerY);
                        ctx.bezierCurveTo(centerX+size*3/4, centerY-size/2, centerX, centerY-size/2, centerX, centerY+size/4);
                        ctx.clip();
                    }

                    ctx.drawImage(img, x, y, photoWidth, photoHeight);
                    ctx.restore();

                    // Draw stickers and logos after all photos are drawn
                    if (index === storedImages.length - 1) {
                        drawStickersAndLogos(ctx, stackedCanvas);
                    }
                };
                img.src = imageData;
            });

            // Update preview
            if (photoCustomPreview) {
                photoCustomPreview.innerHTML = '';
                photoCustomPreview.appendChild(stackedCanvas);
            }

            finalCanvas = stackedCanvas;
        }

        function drawStickersAndLogos(ctx, canvas) {
            // Draw selected sticker
            if (selectedSticker) {
                const stickerImg = new Image();
                stickerImg.onload = function() {
                    const stickerSize = 100;
                    const stickerX = canvas.width - stickerSize - 20;
                    const stickerY = 20;
                    ctx.drawImage(stickerImg, stickerX, stickerY, stickerSize, stickerSize);
                };
                stickerImg.src = selectedSticker;
            }

            // Draw logos
            if (engLogoToggle) {
                // Draw English logo
                const logoImg = new Image();
                logoImg.onload = function() {
                    ctx.drawImage(logoImg, 20, 20, 80, 40);
                };
                logoImg.src = '/src/assets/logos/eng-logo.png';
            }

            // Draw date/time if enabled
            if (dateCheckbox && dateCheckbox.checked) {
                const now = new Date();
                const dateString = now.toLocaleDateString();
                ctx.font = '16px Arial';
                ctx.fillText(dateString, canvas.width / 2, canvas.height - 20);
            }

            if (dateTimeCheckbox && dateTimeCheckbox.checked) {
                const now = new Date();
                const timeString = now.toLocaleTimeString();
                ctx.font = '14px Arial';
                ctx.fillText(timeString, canvas.width / 2, canvas.height - 5);
            }
        }

        // Color button event listeners
        if (pinkBtn) {
            pinkBtn.addEventListener('click', () => setBackground({type: 'color', value: '#FF69B4'}));
        }
        if (blueBtn) {
            blueBtn.addEventListener('click', () => setBackground({type: 'color', value: '#4169E1'}));
        }
        if (yellowBtn) {
            yellowBtn.addEventListener('click', () => setBackground({type: 'color', value: '#FFD700'}));
        }
        if (brownBtn) {
            brownBtn.addEventListener('click', () => setBackground({type: 'color', value: '#8B4513'}));
        }
        if (redBtn) {
            redBtn.addEventListener('click', () => setBackground({type: 'color', value: '#DC143C'}));
        }
        if (matchaBtn) {
            matchaBtn.addEventListener('click', () => setBackground({type: 'color', value: '#90a955'}));
        }
        if (purpleBtn) {
            purpleBtn.addEventListener('click', () => setBackground({type: 'color', value: '#8A2BE2'}));
        }
        if (whiteBtn) {
            whiteBtn.addEventListener('click', () => setBackground({type: 'color', value: '#FFFFFF'}));
        }
        if (blackBtn) {
            blackBtn.addEventListener('click', () => setBackground({type: 'color', value: '#000000'}));
        }

        // Background image event listeners
        if (pinkGlitter) {
            pinkGlitter.addEventListener('click', () => setBackground({type: 'image', value: '/src/assets/backgrounds/pink-glitter.jpg'}));
        }
        if (pinkPlaid) {
            pinkPlaid.addEventListener('click', () => setBackground({type: 'image', value: '/src/assets/backgrounds/pink-plaid.jpg'}));
        }
        // Add more background image listeners...

        // Shape button event listeners
        if (normalFrameBtn) {
            normalFrameBtn.addEventListener('click', () => {
                selectedShape = 'default';
                redrawCanvas();
            });
        }
        if (roundEdgeFrameBtn) {
            roundEdgeFrameBtn.addEventListener('click', () => {
                selectedShape = 'rounded';
                redrawCanvas();
            });
        }
        if (circleFrameBtn) {
            circleFrameBtn.addEventListener('click', () => {
                selectedShape = 'circle';
                redrawCanvas();
            });
        }
        if (heartFrameBtn) {
            heartFrameBtn.addEventListener('click', () => {
                selectedShape = 'heart';
                redrawCanvas();
            });
        }

        // Sticker event listeners
        if (noneSticker) {
            noneSticker.addEventListener('click', () => {
                selectedSticker = null;
                redrawCanvas();
            });
        }
        if (kissSticker) {
            kissSticker.addEventListener('click', () => {
                selectedSticker = '/src/assets/stickers/kiss.png';
                redrawCanvas();
            });
        }
        // Add more sticker listeners...

        // Action button event listeners
        if (downloadCopyBtn) {
            downloadCopyBtn.addEventListener('click', () => {
                if (finalCanvas) {
                    const link = document.createElement('a');
                    link.download = 'photobooth-layout4.png';
                    link.href = finalCanvas.toDataURL();
                    link.click();
                }
            });
        }

        if (emailBtn) {
            emailBtn.addEventListener('click', () => {
                // Show email modal
                const emailModal = document.getElementById('emailModal');
                if (emailModal) {
                    emailModal.style.display = 'flex';
                }
            });
        }

        if (printBtn) {
            printBtn.addEventListener('click', () => {
                if (finalCanvas) {
                    const printWindow = window.open('', '_blank');
                    printWindow.document.write(`
                        <html>
                            <head><title>Print Photo</title></head>
                            <body style="margin:0; display:flex; justify-content:center; align-items:center;">
                                <img src="${finalCanvas.toDataURL()}" style="max-width:100%; max-height:100vh;" onload="window.print(); window.close();">
                            </body>
                        </html>
                    `);
                }
            });
        }

        if (continueBtn) {
            continueBtn.addEventListener('click', () => {
                window.location.href = 'thankyou.php';
            });
        }

        // Initialize canvas on load
        redrawCanvas();
    }
});
