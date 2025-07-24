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
    updateTimer();

    // 🎯 PRIORITAS 1: LOAD FOTO TERLEBIH DAHULU
    console.log('🔄 Loading photos first for better UX...');
    
    // Variables
    let storedImages = [];
    let imageArrayLength = 0;
    let finalCanvas = null;
    let selectedSticker = null;
    let selectedShape = 'default';
    let backgroundType = 'color';
    let backgroundColor = '#FFFFFF';
    let backgroundImage = null;
    
    // DOM Elements
    const photoCustomPreview = document.getElementById('photoPreview');
    const customBack = document.getElementById('customBack');

    // Load photos first with priority
    loadPhotosFirst()
        .then(() => {
            console.log('✅ Photos loaded successfully');
            // Initialize canvas immediately after photos load
            initializeCanvas();
            // Load UI controls
            initializeControls();
            // Load stickers in background (non-blocking)
            console.log('📦 Loading stickers in background...');
            initializeStickerControls();
        })
        .catch(error => {
            console.error('❌ Error loading photos:', error);
            alert('Gagal memuat foto. Redirecting...');
            window.location.href = 'selectlayout.php';
        });

    // Function to load photos with priority
    function loadPhotosFirst() {
        return new Promise((resolve, reject) => {
            fetch('../api-fetch/get_photos.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.photos) {
                        storedImages = data.photos;
                        imageArrayLength = storedImages.length;
                        console.log(`Loaded ${imageArrayLength} images from server session:`, storedImages);
                        resolve(storedImages);
                    } else {
                        throw new Error('No photos found in session');
                    }
                })
                .catch(error => {
                    console.error('Error fetching photos:', error);
                    reject(error);
                });
        });
    }

    // Initialize canvas immediately after photos load
    function initializeCanvas() {
        if (!storedImages || storedImages.length === 0) {
            console.error('❌ No images available for canvas');
            return;
        }

        console.log('🎨 Initializing canvas with photos...');
        
        // Create and render canvas immediately
        redrawCanvas();
        
        console.log('✅ Canvas initialized and rendered');
    }

    // Initialize basic controls (non-sticker related)
    function initializeControls() {
        console.log('🎛️ Initializing basic controls...');
        
        // Back button
        if (customBack) {
            customBack.addEventListener('click', () => {
                window.location.href = 'canvasLayout1.php';
            });
        }

        // Date checkboxes
        const dateCheckbox = document.getElementById('dateCheckbox');
        const dateTimeCheckbox = document.getElementById('dateTimeCheckbox');
        
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

        // Frame color controls
        initializeFrameControls();
        
        // Shape controls
        initializeShapeControls();
        
        // Action buttons
        initializeActionButtons();
        
        console.log('✅ Basic controls initialized');
    }

    // Initialize sticker controls (loaded in background)
    function initializeStickerControls() {
        setTimeout(() => {
            console.log('🎭 Initializing sticker controls...');
            
            const stickerButtons = [
                'noneSticker', 'kissSticker', 'ribbonSticker', 'sweetSticker',
                'sparkleSticker', 'pearlSticker', 'softSticker', 'bunnySticker',
                'classicSticker', 'classicBSticker', 'luckySticker', 'confettiSticker'
            ];
            
            stickerButtons.forEach(buttonId => {
                const button = document.getElementById(buttonId);
                if (button) {
                    button.addEventListener('click', () => {
                        if (buttonId === 'noneSticker') {
                            selectedSticker = null;
                        } else {
                            const img = button.querySelector('img');
                            if (img) {
                                selectedSticker = img.src;
                            }
                        }
                        redrawCanvas();
                    });
                }
            });
            
            console.log('✅ Sticker controls initialized');
        }, 100); // Small delay to not block photo rendering
    }

    // Frame controls
    function initializeFrameControls() {
        const colorButtons = [
            {id: 'pinkBtnFrame', color: '#FFB6C1'},
            {id: 'blueBtnFrame', color: '#87CEEB'},
            {id: 'yellowBtnFrame', color: '#FFFFE0'},
            {id: 'brownBtnFrame', color: '#D2691E'},
            {id: 'redBtnFrame', color: '#FF6347'},
            {id: 'matchaBtnFrame', color: '#9ACD32'},
            {id: 'purpleBtnFrame', color: '#DDA0DD'},
            {id: 'whiteBtnFrame', color: '#FFFFFF'},
            {id: 'blackBtnFrame', color: '#000000'}
        ];
        
        colorButtons.forEach(btn => {
            const element = document.getElementById(btn.id);
            if (element) {
                element.addEventListener('click', () => {
                    backgroundColor = btn.color;
                    backgroundType = 'color';
                    backgroundImage = null;
                    redrawCanvas();
                });
            }
        });
    }

    // Shape controls
    function initializeShapeControls() {
        const shapeButtons = [
            {id: 'noneFrameShape', shape: 'default'},
            {id: 'softFrameShape', shape: 'rounded'},
            {id: 'circleFrameShape', shape: 'circle'},
            {id: 'heartFrameShape', shape: 'heart'}
        ];
        
        shapeButtons.forEach(btn => {
            const element = document.getElementById(btn.id);
            if (element) {
                element.addEventListener('click', () => {
                    selectedShape = btn.shape;
                    redrawCanvas();
                });
            }
        });
    }

    // Action buttons
    function initializeActionButtons() {
        const downloadBtn = document.getElementById('downloadCopyBtn');
        const emailBtn = document.getElementById('emailBtn');
        const printBtn = document.getElementById('printBtn');
        const continueBtn = document.getElementById('continueBtn');
        
        if (downloadBtn) {
            downloadBtn.addEventListener('click', () => {
                if (finalCanvas) {
                    const link = document.createElement('a');
                    link.download = 'fotoboks-layout1.png';
                    link.href = finalCanvas.toDataURL();
                    link.click();
                }
            });
        }
        
        if (printBtn) {
            printBtn.addEventListener('click', () => {
                if (finalCanvas) {
                    const printWindow = window.open('', '', 'width=800,height=600');
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
    }

    // Main canvas drawing function
    function redrawCanvas() {
        if (!storedImages || storedImages.length === 0) {
            console.warn('⚠️ No images available for redraw');
            return;
        }

        console.log(`Redrawing canvas with background: ${backgroundType}`);

        const stackedCanvas = document.createElement('canvas');
        const ctx = stackedCanvas.getContext('2d');

        // Layout 1 dimensions (2 photos in vertical strip)
        const canvasWidth = 1200;   // 4R standard width
        const canvasHeight = 1800;  // 4R standard height
        const borderWidth = 30;
        const spacing = 12;
        const bottomPadding = 100;
        const expectedPhotos = 2;

        const availableHeight = canvasHeight - (borderWidth * 2) - (spacing * 2) - bottomPadding;
        const photoHeight = availableHeight / expectedPhotos;
        const photoWidth = canvasWidth - (borderWidth * 2);

        stackedCanvas.width = canvasWidth;
        stackedCanvas.height = canvasHeight;

        // Clear the entire canvas first
        ctx.clearRect(0, 0, stackedCanvas.width, stackedCanvas.height);

        // Set background
        if (backgroundType === 'color') {
            ctx.fillStyle = backgroundColor;
            ctx.fillRect(0, 0, stackedCanvas.width, stackedCanvas.height);
        } else if (backgroundImage) {
            const bgImg = new Image();
            bgImg.onload = function() {
                ctx.drawImage(bgImg, 0, 0, stackedCanvas.width, stackedCanvas.height);
                drawPhotos();
            };
            bgImg.src = backgroundImage;
            return;
        }

        drawPhotos();

        function drawPhotos() {
            if (storedImages.length < expectedPhotos) {
                console.warn(`⚠️ Layout ${expectedPhotos} requires ${expectedPhotos} photos, only found: ${storedImages.length}`);
            }

            let loadedCount = 0;
            const imagesToProcess = Math.min(storedImages.length, expectedPhotos);

            storedImages.slice(0, imagesToProcess).forEach((imageData, index) => {
                const img = new Image();
                img.onload = function() {
                    // Layout 1: Vertical strip
                    const x = borderWidth;
                    const y = borderWidth + (index * (photoHeight + spacing));
                    
                    drawPhotoWithShape(ctx, img, x, y, photoWidth, photoHeight, selectedShape);
                    
                    loadedCount++;
                    if (loadedCount === imagesToProcess) {
                        drawStickersAndLogos(ctx, stackedCanvas);
                    }
                };
                img.src = imageData;
            });
        }

        // Update preview with optimized canvas style
        if (photoCustomPreview) {
            photoCustomPreview.innerHTML = '';
            
            // Set canvas style for small preview display
            stackedCanvas.style.maxWidth = "300px";
            stackedCanvas.style.maxHeight = "450px";
            stackedCanvas.style.width = "auto";
            stackedCanvas.style.height = "auto";
            stackedCanvas.style.border = "2px solid #ddd";
            stackedCanvas.style.borderRadius = "8px";
            stackedCanvas.style.boxShadow = "0 4px 8px rgba(0,0,0,0.1)";
            stackedCanvas.style.display = "block";
            stackedCanvas.style.margin = "0 auto";
            
            photoCustomPreview.appendChild(stackedCanvas);
        }

        finalCanvas = stackedCanvas;
    }

    // Helper function to draw photo with shape
    function drawPhotoWithShape(ctx, img, x, y, width, height, shape) {
        ctx.save();
        
        if (shape === 'circle') {
            const centerX = x + width / 2;
            const centerY = y + height / 2;
            const radius = Math.min(width, height) / 2;
            
            ctx.beginPath();
            ctx.arc(centerX, centerY, radius, 0, 2 * Math.PI);
            ctx.clip();
        } else if (shape === 'rounded') {
            roundedRect(ctx, x, y, width, height, 20);
            ctx.clip();
        } else if (shape === 'heart') {
            heartShape(ctx, x + width/2, y + height/2, Math.min(width, height)/2);
            ctx.clip();
        }
        
        ctx.drawImage(img, x, y, width, height);
        ctx.restore();
    }

    // Helper functions for shapes
    function roundedRect(ctx, x, y, width, height, radius) {
        ctx.beginPath();
        ctx.moveTo(x + radius, y);
        ctx.lineTo(x + width - radius, y);
        ctx.quadraticCurveTo(x + width, y, x + width, y + radius);
        ctx.lineTo(x + width, y + height - radius);
        ctx.quadraticCurveTo(x + width, y + height, x + width - radius, y + height);
        ctx.lineTo(x + radius, y + height);
        ctx.quadraticCurveTo(x, y + height, x, y + height - radius);
        ctx.lineTo(x, y + radius);
        ctx.quadraticCurveTo(x, y, x + radius, y);
        ctx.closePath();
    }

    function heartShape(ctx, x, y, size) {
        ctx.beginPath();
        ctx.moveTo(x, y + size / 4);
        ctx.quadraticCurveTo(x, y, x + size / 4, y);
        ctx.quadraticCurveTo(x + size / 2, y, x + size / 2, y + size / 4);
        ctx.quadraticCurveTo(x + size / 2, y, x + size * 3 / 4, y);
        ctx.quadraticCurveTo(x + size, y, x + size, y + size / 4);
        ctx.quadraticCurveTo(x + size, y + size / 2, x + size * 3 / 4, y + size * 3 / 4);
        ctx.lineTo(x + size / 2, y + size);
        ctx.lineTo(x + size / 4, y + size * 3 / 4);
        ctx.quadraticCurveTo(x, y + size / 2, x, y + size / 4);
        ctx.closePath();
    }

    // Draw stickers and logos
    function drawStickersAndLogos(ctx, canvas) {
        // Draw selected sticker
        if (selectedSticker) {
            const stickerImg = new Image();
            stickerImg.onload = function() {
                const stickerSize = 100;
                const stickerX = canvas.width - stickerSize - 20;
                const stickerY = canvas.height - stickerSize - 120;
                
                ctx.drawImage(stickerImg, stickerX, stickerY, stickerSize, stickerSize);
            };
            stickerImg.src = selectedSticker;
        }

        // Add date/time if checked
        const dateCheckbox = document.getElementById('dateCheckbox');
        const dateTimeCheckbox = document.getElementById('dateTimeCheckbox');
        
        if (dateCheckbox && dateCheckbox.checked) {
            const now = new Date();
            const dateStr = now.toLocaleDateString();
            
            ctx.fillStyle = 'white';
            ctx.font = '16px Arial';
            ctx.fillText(dateStr, 40, canvas.height - 40);
        }
        
        if (dateTimeCheckbox && dateTimeCheckbox.checked) {
            const now = new Date();
            const timeStr = now.toLocaleTimeString();
            
            ctx.fillStyle = 'white';
            ctx.font = '16px Arial';
            ctx.fillText(timeStr, 40, canvas.height - 20);
        }
    }
});
