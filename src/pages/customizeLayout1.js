document.addEventListener('DOMContentLoaded', () => {
    // Variables
    let storedImages = [];
    let finalCanvas = null;
    let selectedSticker = null;
    let selectedShape = 'default';
    let backgroundType = 'color';
    let backgroundColor = '#FFFFFF';
    let backgroundImage = null;
    
    // Track usage - limit button usage
    let emailSent = false;
    let printUsed = false;

    // DOM Elements
    const photoCustomPreview = document.getElementById('photoPreview');

    // Initialize application
    initializeApp();

    // Main initialization function
    async function initializeApp() {
        console.log('🔄 Initializing photobooth customization...');
        try {
            await loadPhotos();
            initializeCanvas();
            initializeControls();
            console.log('📦 Loading stickers in background...');
            initializeStickerControls();
        } catch (error) {
            console.error('❌ Error initializing app:', error);
            alert('Gagal memuat foto. Redirecting...');
            window.location.href = 'selectlayout.php';
        }
    }

    // Load photos from server
    async function loadPhotos() {
        console.log('🔄 Loading photos...');
        const response = await fetch('../api-fetch/get_photos.php');
        const data = await response.json();
        if (!data.success || !data.photos) {
            throw new Error('No photos found in session');
        }
        storedImages = data.photos;
        console.log(`✅ Loaded ${storedImages.length} images:`, storedImages);
    }

    // Initialize canvas
    function initializeCanvas() {
        if (!storedImages.length) {
            console.error('❌ No images available for canvas');
            return;
        }
        console.log('🎨 Initializing canvas...');
        redrawCanvas();
        console.log('✅ Canvas initialized');
    }

    // Initialize all controls
    function initializeControls() {
        console.log('🎛️ Initializing controls...');
        initializeFrameControls();
        initializeBackgroundFrameControls();
        initializeShapeControls();
        initializeActionButtons();
        initializeEmailModal();
        console.log('✅ Controls initialized');
    }

    // Initialize frame color controls
    function initializeFrameControls() {
        const colorButtons = [
            { id: 'pinkBtnFrame', color: '#FFB6C1' },
            { id: 'blueBtnFrame', color: '#87CEEB' },
            { id: 'yellowBtnFrame', color: '#FFFFE0' },
            { id: 'brownBtnFrame', color: '#D2691E' },
            { id: 'redBtnFrame', color: '#FF6347' },
            { id: 'matchaBtnFrame', color: '#9ACD32' },
            { id: 'purpleBtnFrame', color: '#DDA0DD' },
            { id: 'whiteBtnFrame', color: '#FFFFFF' },
            { id: 'blackBtnFrame', color: '#000000' }
        ];

        colorButtons.forEach(({ id, color }) => {
            const element = document.getElementById(id);
            if (element) {
                element.addEventListener('click', () => {
                    backgroundColor = color;
                    backgroundType = 'color';
                    backgroundImage = null;
                    redrawCanvas();
                });
            }
        });
    }

    // Initialize background frame controls
    function initializeBackgroundFrameControls() {
        const backgroundFrameButtons = [
            { id: 'matcha', src: '/src/assets/frame-backgrounds/matcha.jpg' },
            { id: 'blackStar', src: '/src/assets/frame-backgrounds/blackStar.jpg' },
            { id: 'blueStripe', src: '/src/assets/frame-backgrounds/blueStripe.jpg' }
        ];

        backgroundFrameButtons.forEach(({ id, src }) => {
            const element = document.getElementById(id);
            if (element) {
                element.addEventListener('click', () => {
                    console.log(`🎨 Setting background: ${id} (${src})`);
                    backgroundType = 'image';
                    backgroundImage = src;
                    backgroundColor = null;
                    redrawCanvas();
                });
            } else {
                console.warn(`⚠️ Background frame button not found: ${id}`);
            }
        });
    }

    // Initialize shape controls
    function initializeShapeControls() {
        const shapeButtons = [
            { id: 'noneFrameShape', shape: 'default' },
            { id: 'softFrameShape', shape: 'rounded' }
        ];

        shapeButtons.forEach(({ id, shape }) => {
            const element = document.getElementById(id);
            if (element) {
                element.addEventListener('click', () => {
                    selectedShape = shape;
                    redrawCanvas();
                });
            }
        });
    }

    // Initialize action buttons
    function initializeActionButtons() {
        const buttons = {
            emailBtn: () => {
                if (emailSent) {
                    alert('Email sudah pernah dikirim!');
                    return;
                }
                if (finalCanvas) {
                    showEmailModal();
                } else {
                    alert('Tidak ada gambar untuk dikirim ke email');
                }
            },
            printBtn: () => {
                if (printUsed) {
                    alert('Print sudah pernah digunakan!');
                    return;
                }
                if (finalCanvas) {
                    showSimplePrintPopup(finalCanvas.toDataURL('image/jpeg', 1.0));
                } else {
                    alert('Tidak ada gambar untuk di-print');
                }
            },
            videoBtn: () => {
                console.log('🎬 Video button clicked, stored images:', storedImages.length);
                if (storedImages.length >= 2) {
                    createVideoFromPhotos();
                } else {
                    alert('Minimal 2 foto diperlukan untuk membuat video!');
                }
            },
            continueBtn: () => window.location.href = 'thankyou.php'
        };

        Object.entries(buttons).forEach(([id, handler]) => {
            const button = document.getElementById(id);
            if (button) {
                button.addEventListener('click', handler);
            }
        });
    }

    // Add this new function
    function showEmailModal() {
        const emailModal = document.getElementById('emailModal');
        if (emailModal) {
            emailModal.style.display = 'block';
            const emailInput = document.getElementById('emailInput');
            if (emailInput) {
                emailInput.focus();
            }
        } else {
            console.error('❌ Email modal element not found');
        }
    }

    // Initialize email modal
    function initializeEmailModal() {
        const emailModal = document.getElementById('emailModal');
        const closeEmailModal = document.getElementById('closeEmailModal');
        const cancelEmailBtn = document.getElementById('cancelEmailBtn');
        const sendEmailBtn = document.getElementById('sendEmailBtn');
        const emailInput = document.getElementById('emailInput');

        const closeModal = () => emailModal.style.display = 'none';

        if (closeEmailModal) closeEmailModal.addEventListener('click', closeModal);
        if (cancelEmailBtn) cancelEmailBtn.addEventListener('click', closeModal);
        if (emailModal) emailModal.addEventListener('click', e => e.target === emailModal && closeModal());

        if (sendEmailBtn) {
            sendEmailBtn.addEventListener('click', () => {
                const email = emailInput.value.trim();
                if (!email) return showValidationError('Mohon masukkan alamat email');
                if (!validateEmail(email)) return showValidationError('Format email tidak valid');
                hideValidationError();
                sendPhotoEmail(email);
            });
        }

        initializeVirtualKeyboard();
    }

    // Initialize virtual keyboard
    function initializeVirtualKeyboard() {
        const emailInput = document.getElementById('emailInput');
        const keyboardKeys = document.querySelectorAll('.key-btn');
        let capsLock = false;

        keyboardKeys.forEach(key => {
            key.addEventListener('click', e => {
                e.preventDefault();
                handleKeyPress(key.getAttribute('data-key'), emailInput);
            });
        });

        emailInput.addEventListener('click', () => setTimeout(() => emailInput.focus(), 10));
        document.getElementById('virtualKeyboard').addEventListener('mousedown', e => e.preventDefault());

        function handleKeyPress(key, input) {
            const currentValue = input.value;
            const cursorPos = input.selectionStart || currentValue.length;

            switch (key) {
                case 'backspace':
                    if (cursorPos > 0) {
                        input.value = currentValue.slice(0, cursorPos - 1) + currentValue.slice(cursorPos);
                        input.setSelectionRange(cursorPos - 1, cursorPos - 1);
                    }
                    break;
                case 'caps':
                    capsLock = !capsLock;
                    updateCapsLockState();
                    return;
                default:
                    const charToAdd = capsLock ? key.toUpperCase() : key.toLowerCase();
                    input.value = currentValue.slice(0, cursorPos) + charToAdd + currentValue.slice(cursorPos);
                    input.setSelectionRange(cursorPos + 1, cursorPos + 1);
                    break;
            }

            hideValidationError();
            input.focus();
            input.dispatchEvent(new Event('input', { bubbles: true }));
        }

        function updateCapsLockState() {
            const capsKey = document.querySelector('[data-key="caps"]');
            const letterKeys = document.querySelectorAll('[data-key]');
            capsKey.classList.toggle('active', capsLock);
            letterKeys.forEach(key => {
                const keyValue = key.getAttribute('data-key');
                if (keyValue.length === 1 && keyValue.match(/[a-z]/i)) {
                    key.textContent = capsLock ? keyValue.toUpperCase() : keyValue.toLowerCase();
                }
            });
        }

        updateCapsLockState();
    }

    // Validation and email functions
    function showValidationError(message) {
        const validationDiv = document.querySelector('.input-validation');
        const validationMessage = document.getElementById('validation-message');
        if (validationDiv && validationMessage) {
            validationMessage.textContent = message;
            validationDiv.style.display = 'block';
        }
    }

    function hideValidationError() {
        const validationDiv = document.querySelector('.input-validation');
        if (validationDiv) validationDiv.style.display = 'none';
    }

    function validateEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    async function sendPhotoEmail(email) {
        if (!finalCanvas) {
            alert('Tidak ada foto untuk dikirim');
            return;
        }

        const sendBtn = document.getElementById('sendEmailBtn');
        const originalHtml = sendBtn.innerHTML;
        sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan & Mengirim...';
        sendBtn.disabled = true;

        try {
            const blob = await new Promise(resolve => finalCanvas.toBlob(resolve, 'image/png'));
            const base64data = await new Promise(resolve => {
                const reader = new FileReader();
                reader.onloadend = () => resolve(reader.result);
                reader.readAsDataURL(blob);
            });

            const response = await fetch('../api-fetch/save_final_photo_v2.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ image: base64data })
            });

            const text = await response.text();
            if (text.trim().startsWith('<') || text.includes('<br />') || text.includes('<b>')) {
                throw new Error('Server returned HTML error page');
            }

            const data = JSON.parse(text);
            if (!data.success) {
                throw new Error(data.message || 'Server error');
            }

            const photoLink = window.location.origin + data.url;
            const emailParams = {
                email,
                to_email: email,
                recipient_email: email,
                link: photoLink,
                photo_link: photoLink,
                url: photoLink,
                name: 'Sobat',
                user_name: 'Sobat',
                to_name: 'Sobat',
                from_name: 'GOFOTOBOX',
                message: `Halo Sobat! Link foto Anda: ${photoLink}`
            };

            await emailjs.send('service_gtqjb2j', 'template_pp5i4hm', emailParams);
            
            // Mark email as sent and disable button
            emailSent = true;
            const emailBtn = document.getElementById('emailBtn');
            if (emailBtn) {
                emailBtn.disabled = true;
                emailBtn.style.opacity = '0.5';
                emailBtn.style.cursor = 'not-allowed';
                emailBtn.innerHTML = '✅ Email Terkirim';
            }
            showValidationError('Email berhasil dikirim! ✅ Cek inbox Anda.');
            document.querySelector('.input-validation span').style.color = '#28a745';
            setTimeout(() => {
                document.getElementById('emailModal').style.display = 'none';
                document.getElementById('emailInput').value = '';
                hideValidationError();
            }, 3000);
        } catch (error) {
            console.error('❌ Email error:', error);
            showValidationError('Error: ' + error.message);
        } finally {
            sendBtn.innerHTML = originalHtml;
            sendBtn.disabled = false;
        }
    }

    // Canvas drawing functions
    function redrawCanvas() {
        if (!storedImages.length) {
            console.warn('⚠️ No images available for redraw');
            return;
        }

        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        const canvasWidth = 1200;
        const canvasHeight = 1800;
        const borderWidth = 62;
        const marginTop = 120;
        const spacing = 80;
        const photoWidth = 1076;
        const photoHeight = 639;
        const expectedPhotos = 2;

        canvas.width = canvasWidth;
        canvas.height = canvasHeight;
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        if (backgroundType === 'color') {
            ctx.fillStyle = backgroundColor;
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            drawPhotos();
        } else if (backgroundImage) {
            const bgImg = new Image();
            bgImg.onload = () => {
                ctx.drawImage(bgImg, 0, 0, canvas.width, canvas.height);
                drawPhotos();
                updateCanvasPreview(canvas);
            };
            bgImg.onerror = () => {
                console.error('❌ Failed to load background image:', backgroundImage);
                ctx.fillStyle = '#FFFFFF';
                ctx.fillRect(0, 0, canvas.width, canvas.height);
                drawPhotos();
                updateCanvasPreview(canvas);
            };
            bgImg.src = backgroundImage;
            return;
        } else {
            ctx.fillStyle = '#FFFFFF';
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            drawPhotos();
        }

        function drawPhotos() {
            if (storedImages.length < expectedPhotos) {
                console.warn(`⚠️ Layout requires ${expectedPhotos} photos, found: ${storedImages.length}`);
            }

            const imagesToProcess = Math.min(storedImages.length, expectedPhotos);
            let loadedCount = 0;

            storedImages.slice(0, imagesToProcess).forEach((imageData, index) => {
                const img = new Image();
                img.onload = () => {
                    const positions = [
                        { x: borderWidth, y: marginTop, width: photoWidth, height: photoHeight },
                        { x: borderWidth, y: marginTop + photoHeight + spacing, width: photoWidth, height: photoHeight }
                    ];
                    drawCroppedImage(ctx, img, positions[index], selectedShape);
                    loadedCount++;
                    if (loadedCount === imagesToProcess) {
                        drawStickersAndLogos(ctx, canvas);
                        updateCanvasPreview(canvas);
                    }
                };
                img.src = imageData;
            });
        }
    }

    function drawCroppedImage(ctx, img, pos, shape) {
        const { x, y, width, height } = pos;
        const imgAspect = img.width / img.height;
        const targetAspect = width / height;
        let sx, sy, sWidth, sHeight;

        if (imgAspect > targetAspect) {
            sHeight = img.height;
            sWidth = sHeight * targetAspect;
            sx = (img.width - sWidth) / 2;
            sy = 0;
        } else {
            sWidth = img.width;
            sHeight = sWidth / targetAspect;
            sx = 0;
            sy = (img.height - sHeight) / 2;
        }

        drawPhotoWithShape(ctx, img, x, y, width, height, shape, sx, sy, sWidth, sHeight);
    }

    function drawPhotoWithShape(ctx, img, x, y, width, height, shape, sx, sy, sWidth, sHeight) {
        ctx.save();
        if (shape === 'rounded') {
            roundedRect(ctx, x, y, width, height, 20);
            ctx.clip();
        } else {
            ctx.beginPath();
            ctx.rect(x, y, width, height);
            ctx.clip();
        }
        ctx.drawImage(img, sx, sy, sWidth, sHeight, x, y, width, height);
        ctx.restore();
    }

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

    function drawStickersAndLogos(ctx, canvas) {
        if (selectedSticker) {
            const stickerImg = new Image();
            stickerImg.onload = () => ctx.drawImage(stickerImg, 0, 0, canvas.width, canvas.height);
            stickerImg.src = selectedSticker;
        }

        const logoBtn = document.getElementById('engLogo');
        if (logoBtn && logoBtn.classList.contains('active')) {
            const logoImg = new Image();
            logoImg.onload = () => ctx.drawImage(logoImg, 20, canvas.height - 60, 100, 40);
            logoImg.src = '../assets/logoFrame/blackLogo.jpg';
        }
    }

    function updateCanvasPreview(canvas) {
        if (photoCustomPreview) {
            photoCustomPreview.innerHTML = '';
            Object.assign(canvas.style, {
                maxWidth: '300px',
                maxHeight: '450px',
                width: 'auto',
                height: 'auto',
                border: '2px solid #ddd',
                borderRadius: '8px',
                boxShadow: '0 4px 8px rgba(0,0,0,0.1)',
                display: 'block',
                margin: '0 auto'
            });
            photoCustomPreview.appendChild(canvas);
        }
        finalCanvas = canvas;
    }

    // Initialize sticker controls
    function initializeStickerControls() {
        setTimeout(() => {
            const stickerButtons = ['noneSticker', 'bintang1'];
            stickerButtons.forEach(id => {
                const button = document.getElementById(id);
                if (button) {
                    button.addEventListener('click', () => {
                        selectedSticker = id === 'noneSticker' ? null : button.querySelector('img')?.src;
                        redrawCanvas();
                    });
                }
            });
            console.log('✅ Sticker controls initialized');
        }, 100);
    }

    // Print popup function
    function showSimplePrintPopup(imageDataUrl) {
        const existingPopup = document.getElementById('simplePrintPopup');
        if (existingPopup) existingPopup.remove();

        const popup = document.createElement('div');
        popup.id = 'simplePrintPopup';
        Object.assign(popup.style, {
            position: 'fixed',
            top: '0',
            left: '0',
            width: '100%',
            height: '100%',
            background: 'rgba(0,0,0,0.7)',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            zIndex: '9999'
        });

        const popupBox = document.createElement('div');
        Object.assign(popupBox.style, {
            background: 'white',
            padding: '25px',
            borderRadius: '10px',
            textAlign: 'center',
            maxWidth: '450px',
            boxShadow: '0 10px 30px rgba(0,0,0,0.3)'
        });

        popupBox.innerHTML = `
            <h3 style="margin: 0 0 15px 0; color: #333;">Print Preview</h3>
            <img src="${imageDataUrl}" style="max-width: 350px; height: auto; border: 2px solid #ddd; border-radius: 5px; margin-bottom: 20px;" alt="Print Preview" />
            <div>
                <button id="directPrintBtn" style="background: #28a745; color: white; border: none; padding: 12px 25px; border-radius: 6px; cursor: pointer; font-size: 16px; margin-right: 10px; font-weight: bold;">🖨️ Print</button>
                <button id="closePopupBtn" style="background: #6c757d; color: white; border: none; padding: 12px 25px; border-radius: 6px; cursor: pointer; font-size: 16px; font-weight: bold;">✖️ Close</button>
            </div>
        `;

        popup.appendChild(popupBox);
        document.body.appendChild(popup);

        document.getElementById('directPrintBtn').addEventListener('click', () => {
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Print Photo</title>
                    <style>
                        @page { size: 4in 6in; margin: 0; }
                        * { margin: 0; padding: 0; border: none; box-sizing: border-box; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
                        html, body { width: 4in; height: 6in; margin: 0; padding: 0; overflow: hidden; }
                        .print-container { width: 4in; height: 6in; position: absolute; top: 0; left: 0; }
                        .print-image { width: 4in; height: 6in; object-fit: cover; position: absolute; top: 0; left: 0; }
                    </style>
                </head>
                <body>
                    <div class="print-container">
                        <img src="${imageDataUrl}" class="print-image" alt="Print Photo" />
                    </div>
                </body>
                </html>
            `);
            printWindow.document.close();
            printWindow.onload = () => {
                setTimeout(() => {
                    printWindow.print();
                    printWindow.close();
                    
                    // Mark print as used and disable button
                    printUsed = true;
                    const printBtn = document.getElementById('printBtn');
                    if (printBtn) {
                        printBtn.disabled = true;
                        printBtn.style.opacity = '0.5';
                        printBtn.style.cursor = 'not-allowed';
                        printBtn.innerHTML = '✅ Sudah Print';
                    }
                }, 500);
            };
            popup.remove();
        });

        document.getElementById('closePopupBtn').addEventListener('click', () => popup.remove());
        popup.addEventListener('click', e => e.target === popup && popup.remove());
        document.addEventListener('keydown', function escapeHandler(e) {
            if (e.key === 'Escape') {
                popup.remove();
                document.removeEventListener('keydown', escapeHandler);
            }
        });
    }

    // ==================== VIDEO CONVERSION FUNCTIONS ====================

    // Main function to create video from photos
    async function createVideoFromPhotos() {
        try {
            console.log('🎬 Starting video conversion...');
            showVideoProgress();
            
            // Check if we have enough photos
            if (storedImages.length < 2) {
                throw new Error('Minimal 2 foto diperlukan untuk membuat video!');
            }

            updateVideoProgress(10);
            
            // Load and prepare images
            const images = await loadImagesForVideo();
            updateVideoProgress(30);
            
            // Create video
            const videoBlob = await generateSlideShowVideo(images);
            updateVideoProgress(90);
            
            // Download video
            downloadVideo(videoBlob);
            updateVideoProgress(100);
            
            setTimeout(() => {
                hideVideoProgress();
                alert('Video berhasil dibuat dan didownload! 🎉');
            }, 500);
            
        } catch (error) {
            console.error('❌ Error creating video:', error);
            hideVideoProgress();
            alert('Gagal membuat video: ' + error.message);
        }
    }

    // Load images for video conversion
    async function loadImagesForVideo() {
        console.log('📸 Loading images for video...');
        const images = [];
        
        for (const imageSrc of storedImages.slice(0, 2)) { // Take first 2 images
            try {
                const img = await loadImage(imageSrc);
                images.push(img);
                console.log('✅ Loaded image:', imageSrc);
            } catch (error) {
                console.error('❌ Failed to load image:', imageSrc, error);
                throw new Error('Gagal memuat foto untuk video');
            }
        }
        
        return images;
    }

    // Load single image
    function loadImage(src) {
        return new Promise((resolve, reject) => {
            const img = new Image();
            img.crossOrigin = 'anonymous';
            img.onload = () => resolve(img);
            img.onerror = () => reject(new Error('Gagal memuat gambar: ' + src));
            img.src = src;
        });
    }

    // Generate slideshow video
    async function generateSlideShowVideo(images) {
        return new Promise((resolve, reject) => {
            try {
                console.log('🎬 Creating video canvas...');
                
                // Create canvas for video
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                canvas.width = 800;
                canvas.height = 600;
                
                // Setup MediaRecorder
                const stream = canvas.captureStream(30); // 30 FPS
                const mediaRecorder = new MediaRecorder(stream, {
                    mimeType: 'video/webm;codecs=vp9'
                });
                
                const chunks = [];
                mediaRecorder.ondataavailable = (event) => {
                    if (event.data.size > 0) {
                        chunks.push(event.data);
                    }
                };
                
                mediaRecorder.onstop = () => {
                    const blob = new Blob(chunks, { type: 'video/webm' });
                    console.log('✅ Video created, size:', blob.size, 'bytes');
                    resolve(blob);
                };
                
                mediaRecorder.onerror = (error) => {
                    console.error('❌ MediaRecorder error:', error);
                    reject(new Error('Gagal merekam video'));
                };
                
                // Start recording
                mediaRecorder.start();
                console.log('🔴 Recording started...');
                
                // Animation parameters - 2 complete iterations in 10 seconds
                let currentIndex = 0;
                let frameCount = 0;
                const photoDuration = 75; // 2.5 seconds at 30fps (2.5 * 30 = 75 frames)
                const totalFrames = 300; // 10 seconds at 30fps (10 * 30 = 300 frames)
                // This gives us: Photo1(75) -> Photo2(75) -> Photo1(75) -> Photo2(75) = 300 frames total
                
                function animate() {
                    // Clear canvas with black background
                    ctx.fillStyle = '#000000';
                    ctx.fillRect(0, 0, canvas.width, canvas.height);
                    
                    // Draw current image
                    const img = images[currentIndex];
                    if (img) {
                        // Calculate scaling to fit canvas while maintaining aspect ratio
                        const scale = Math.min(
                            canvas.width / img.width,
                            canvas.height / img.height
                        );
                        const width = img.width * scale;
                        const height = img.height * scale;
                        const x = (canvas.width - width) / 2;
                        const y = (canvas.height - height) / 2;
                        
                        // Apply current frame/sticker/background effects
                        applyCanvasEffectsToVideo(ctx, canvas, img, x, y, width, height);
                    }
                    
                    frameCount++;
                    
                    // Calculate current iteration and photo within iteration
                    const currentIteration = Math.floor(frameCount / (photoDuration * 2)) + 1;
                    const photoInIteration = Math.floor((frameCount % (photoDuration * 2)) / photoDuration) + 1;
                    
                    // Update progress
                    const progress = 30 + ((frameCount / totalFrames) * 60); // 30% to 90%
                    updateVideoProgress(Math.floor(progress));
                    
                    // Switch to next photo every photoDuration frames
                    if (frameCount % photoDuration === 0) {
                        currentIndex = (currentIndex + 1) % images.length;
                        console.log(`🔄 Iteration ${currentIteration}, switching to photo ${currentIndex + 1}`);
                    }
                    
                    // Continue animation or stop
                    if (frameCount < totalFrames) {
                        requestAnimationFrame(animate);
                    } else {
                        console.log('🏁 Animation finished, stopping recording...');
                        mediaRecorder.stop();
                    }
                }
                
                // Start animation
                animate();
                
            } catch (error) {
                console.error('❌ Error in generateSlideShowVideo:', error);
                reject(error);
            }
        });
    }

    // Apply canvas effects to video frame
    function applyCanvasEffectsToVideo(ctx, canvas, img, x, y, width, height) {
        // Save current context
        ctx.save();
        
        // Draw background
        if (backgroundType === 'image' && backgroundImage) {
            ctx.drawImage(backgroundImage, 0, 0, canvas.width, canvas.height);
        } else if (backgroundType === 'color') {
            ctx.fillStyle = backgroundColor;
            ctx.fillRect(0, 0, canvas.width, canvas.height);
        }
        
        // Apply shape if selected
        if (selectedShape === 'soft') {
            // Create rounded rectangle path (fallback for older browsers)
            const radius = 20;
            ctx.beginPath();
            if (ctx.roundRect) {
                ctx.roundRect(x, y, width, height, radius);
            } else {
                // Fallback for browsers without roundRect support
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
            ctx.clip();
        }
        
        // Draw the main image
        ctx.drawImage(img, x, y, width, height);
        
        // Apply frame border
        if (backgroundColor !== '#FFFFFF') {
            ctx.strokeStyle = backgroundColor;
            ctx.lineWidth = 10;
            ctx.strokeRect(x - 5, y - 5, width + 10, height + 10);
        }
        
        // Restore context
        ctx.restore();
        
        // Draw sticker if selected
        if (selectedSticker && selectedSticker.image) {
            const stickerSize = Math.min(width, height) * 0.2;
            const stickerX = x + width - stickerSize - 20;
            const stickerY = y + 20;
            ctx.drawImage(selectedSticker.image, stickerX, stickerY, stickerSize, stickerSize);
        }
    }

    // Download video file
    function downloadVideo(videoBlob) {
        console.log('💾 Downloading video...');
        const url = URL.createObjectURL(videoBlob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `fotobox_slideshow_${Date.now()}.webm`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
        console.log('✅ Video download started');
    }

    // Show video progress modal
    function showVideoProgress() {
        const progressModal = document.getElementById('videoProgressModal');
        if (progressModal) {
            progressModal.style.display = 'block';
            updateVideoProgress(0);
        }
    }

    // Update video progress
    function updateVideoProgress(percent) {
        const progressFill = document.getElementById('videoProgressFill');
        if (progressFill) {
            progressFill.style.width = percent + '%';
        }
        
        // Update text based on progress
        const progressText = document.querySelector('.video-progress-text');
        const progressSubtext = document.querySelector('.video-progress-subtext');
        
        if (progressText && progressSubtext) {
            if (percent < 20) {
                progressText.textContent = 'Preparing photos...';
                progressSubtext.textContent = 'Loading images for video conversion';
            } else if (percent < 40) {
                progressText.textContent = 'Creating video canvas...';
                progressSubtext.textContent = 'Setting up video recording';
            } else if (percent < 90) {
                progressText.textContent = 'Recording slideshow...';
                progressSubtext.textContent = 'Creating 10-second video with 2 complete iterations';
            } else {
                progressText.textContent = 'Finalizing video...';
                progressSubtext.textContent = 'Almost done! Preparing download';
            }
        }
    }

    // Hide video progress modal
    function hideVideoProgress() {
        const progressModal = document.getElementById('videoProgressModal');
        if (progressModal) {
            progressModal.style.display = 'none';
        }
    }

    // ==================== END VIDEO FUNCTIONS ====================

});
