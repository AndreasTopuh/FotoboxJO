document.addEventListener('DOMContentLoaded', function () {

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

        // Frame color controls
        initializeFrameControls();

        // Shape controls
        initializeShapeControls();

        // Action buttons
        initializeActionButtons();

        // Email modal
        initializeEmailModal();

        console.log('✅ Basic controls initialized');
    }

    // Initialize sticker controls (loaded in background)
    function initializeStickerControls() {
        setTimeout(() => {
            console.log('🎭 Initializing sticker controls...');

            const stickerButtons = [
                'noneSticker', 'bunnySticker'
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
            { id: 'pinkBtnFrame', color: '#FFB6C1' },
            { id: 'blueBtnFrame', color: '#87CEEB' },
            { id: 'yellowBtnFrame', color: '#FFFFE0' },
            { id: 'matchaBtnFrame', color: '#90EE90' },
            { id: 'purpleBtnFrame', color: '#DDA0DD' },
            { id: 'brownBtnFrame', color: '#D2691E' },
            { id: 'redBtnFrame', color: '#FFB6C1' },
            { id: 'whiteBtnFrame', color: '#FFFFFF' },
            { id: 'blackBtnFrame', color: '#000000' }
        ];

        colorButtons.forEach(({ id, color }) => {
            const button = document.getElementById(id);
            if (button) {
                button.addEventListener('click', () => {
                    backgroundType = 'color';
                    backgroundColor = color;
                    backgroundImage = null;
                    redrawCanvas();
                });
            }
        });

        // Background pattern buttons
        const patternButtons = [
            'pinkGlitter', 'pinkPlaid', 'bluePlaid'
        ];

        patternButtons.forEach(buttonId => {
            const button = document.getElementById(buttonId);
            if (button) {
                button.addEventListener('click', () => {
                    backgroundType = 'image';
                    backgroundImage = `/src/assets/frame-backgrounds/${buttonId}.png`;
                    backgroundColor = '#FFFFFF';
                    redrawCanvas();
                });
            }
        });
    }

    // Shape controls
    function initializeShapeControls() {
        const shapeButtons = [
            { id: 'noneFrameShape', shape: 'default' },
            { id: 'softFrameShape', shape: 'soft' },
            { id: 'circleFrameShape', shape: 'circle' },
            { id: 'heartFrameShape', shape: 'heart' }
        ];

        shapeButtons.forEach(({ id, shape }) => {
            const button = document.getElementById(id);
            if (button) {
                button.addEventListener('click', () => {
                    selectedShape = shape;
                    redrawCanvas();
                });
            }
        });
    }

    // Action buttons
    function initializeActionButtons() {
        const emailBtn = document.getElementById('emailBtn');
        const printBtn = document.getElementById('printBtn');
        const continueBtn = document.getElementById('continueBtn');

        if (emailBtn) {
            emailBtn.addEventListener('click', () => {
                if (finalCanvas) {
                    showEmailModal();
                } else {
                    alert('Please wait for the photo to finish processing.');
                }
            });
        }

        if (printBtn) {
            printBtn.addEventListener('click', () => {
                if (finalCanvas) {
                    window.location.href = 'payment.php';
                } else {
                    alert('Please wait for the photo to finish processing.');
                }
            });
        }

        if (continueBtn) {
            continueBtn.addEventListener('click', () => {
                if (finalCanvas) {
                    window.location.href = 'payment.php';
                } else {
                    alert('Please wait for the photo to finish processing.');
                }
            });
        }
    }

    // Email Modal Functions
    function showEmailModal() {
        const emailModal = document.getElementById('emailModal');
        if (emailModal) {
            emailModal.style.display = 'flex';
        }
    }

    function hideEmailModal() {
        const emailModal = document.getElementById('emailModal');
        if (emailModal) {
            emailModal.style.display = 'none';
        }
    }

    function initializeEmailModal() {
        const closeEmailModal = document.getElementById('closeEmailModal');
        const cancelEmailBtn = document.getElementById('cancelEmailBtn');
        const sendEmailBtn = document.getElementById('sendEmailBtn');

        if (closeEmailModal) {
            closeEmailModal.addEventListener('click', hideEmailModal);
        }

        if (cancelEmailBtn) {
            cancelEmailBtn.addEventListener('click', hideEmailModal);
        }

        if (sendEmailBtn) {
            sendEmailBtn.addEventListener('click', sendEmail);
        }

        // Close modal when clicking outside
        const emailModal = document.getElementById('emailModal');
        if (emailModal) {
            emailModal.addEventListener('click', (e) => {
                if (e.target === emailModal) {
                    hideEmailModal();
                }
            });
        }

        // Initialize Virtual Keyboard
        initializeVirtualKeyboard();
    }

    // Virtual Keyboard Functions
    function initializeVirtualKeyboard() {
        const emailInput = document.getElementById('emailInput');
        const keyboardKeys = document.querySelectorAll('.key-btn');
        let capsLock = false;

        keyboardKeys.forEach(key => {
            key.addEventListener('click', (e) => {
                e.preventDefault(); // Prevent any default behavior
                const keyValue = key.getAttribute('data-key');
                handleKeyPress(keyValue, emailInput);
            });
        });

        // Update cursor position when user clicks on input
        emailInput.addEventListener('click', () => {
            // Small delay to ensure cursor position is updated
            setTimeout(() => {
                emailInput.focus();
            }, 10);
        });

        // Prevent input from losing focus when clicking virtual keyboard
        const virtualKeyboard = document.getElementById('virtualKeyboard');
        if (virtualKeyboard) {
            virtualKeyboard.addEventListener('mousedown', (e) => {
                e.preventDefault(); // Prevent input from losing focus
            });
        }

        function handleKeyPress(key, input) {
            let currentValue = input.value;
            let cursorPos = input.selectionStart || input.value.length; // Default to end if no selection

            switch(key) {
                case 'backspace':
                    if (cursorPos > 0) {
                        // Remove character before cursor
                        input.value = currentValue.slice(0, cursorPos - 1) + currentValue.slice(cursorPos);
                        // Set cursor to position after deletion
                        setTimeout(() => {
                            input.setSelectionRange(cursorPos - 1, cursorPos - 1);
                        }, 0);
                    }
                    break;
                
                case 'space':
                    // Insert space at cursor position
                    input.value = currentValue.slice(0, cursorPos) + ' ' + currentValue.slice(cursorPos);
                    // Move cursor after the inserted space
                    setTimeout(() => {
                        input.setSelectionRange(cursorPos + 1, cursorPos + 1);
                    }, 0);
                    break;
                
                case 'caps':
                    capsLock = !capsLock;
                    updateCapsLockState();
                    return; // Don't focus or hide validation for caps lock
                
                default:
                    let charToAdd = capsLock ? key.toUpperCase() : key.toLowerCase();
                    // Insert character at cursor position
                    input.value = currentValue.slice(0, cursorPos) + charToAdd + currentValue.slice(cursorPos);
                    // Move cursor after the inserted character
                    setTimeout(() => {
                        input.setSelectionRange(cursorPos + 1, cursorPos + 1);
                    }, 0);
                    break;
            }

            // Hide validation error when user types
            hideValidationError();
            
            // Focus back to input and trigger input event
            setTimeout(() => {
                input.focus();
                // Trigger input event for any listeners
                input.dispatchEvent(new Event('input', { bubbles: true }));
            }, 0);
        }

        function updateCapsLockState() {
            const capsKey = document.querySelector('[data-key="caps"]');
            const letterKeys = document.querySelectorAll('[data-key]');
            
            if (capsLock) {
                capsKey.classList.add('active');
                letterKeys.forEach(key => {
                    const keyValue = key.getAttribute('data-key');
                    if (keyValue.length === 1 && keyValue.match(/[a-z]/i)) {
                        key.textContent = keyValue.toUpperCase();
                    }
                });
            } else {
                capsKey.classList.remove('active');
                letterKeys.forEach(key => {
                    const keyValue = key.getAttribute('data-key');
                    if (keyValue.length === 1 && keyValue.match(/[a-z]/i)) {
                        key.textContent = keyValue.toLowerCase();
                    }
                });
            }
        }

        // Initialize keyboard state
        updateCapsLockState();
    }

    function showValidationError(message) {
        const validationDiv = document.querySelector('.input-validation');
        const validationMessage = document.getElementById('validation-message');
        
        if (validationDiv && validationMessage) {
            validationMessage.textContent = message;
            validationDiv.style.display = 'block';
            validationDiv.classList.add('show-error');
        }
    }

    function hideValidationError() {
        const validationDiv = document.querySelector('.input-validation');
        
        if (validationDiv) {
            validationDiv.style.display = 'none';
            validationDiv.classList.remove('show-error');
        }
    }

    function validateEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Send Email Function
    function sendEmail() {
        const emailInput = document.getElementById('emailInput');
        const email = emailInput.value.trim();

        if (!email) {
            showValidationError('Email tidak boleh kosong');
            return;
        }

        if (!validateEmail(email)) {
            showValidationError('Format email tidak valid');
            return;
        }

        // Show loading state
        const sendBtn = document.getElementById('sendEmailBtn');
        const originalText = sendBtn.innerHTML;
        sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
        sendBtn.disabled = true;

        // Convert canvas to base64
        const imageData = finalCanvas.toDataURL('image/jpeg', 0.9);

        // Send email using EmailJS
        emailjs.send('service_3h5r3o4', 'template_8tc2r5h', {
            to_email: email,
            image: imageData,
            subject: 'Foto Photobooth Anda'
        }).then(function(response) {
            console.log('Email sent successfully:', response);
            alert('Email berhasil dikirim!');
            hideEmailModal();
            emailInput.value = '';
        }).catch(function(error) {
            console.error('Email send failed:', error);
            alert('Gagal mengirim email. Silakan coba lagi.');
        }).finally(function() {
            // Reset button state
            sendBtn.innerHTML = originalText;
            sendBtn.disabled = false;
        });
    }

    // Canvas Drawing Function
    function redrawCanvas() {
        if (!storedImages || storedImages.length === 0) {
            console.error('No images to draw');
            return;
        }

        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        canvas.width = 1080;
        canvas.height = 1920;

        // Clear canvas
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        // Draw background
        if (backgroundType === 'color') {
            ctx.fillStyle = backgroundColor;
            ctx.fillRect(0, 0, canvas.width, canvas.height);
        } else if (backgroundType === 'image' && backgroundImage) {
            const bgImg = new Image();
            bgImg.onload = function() {
                ctx.drawImage(bgImg, 0, 0, canvas.width, canvas.height);
                drawPhotos();
            };
            bgImg.src = backgroundImage;
            return; // Exit early, will continue in onload
        }

        drawPhotos();

        function drawPhotos() {
            const photoWidth = 480;
            const photoHeight = 720;
            const spacing = 60;
            const startX = (canvas.width - (photoWidth * 2 + spacing)) / 2;
            const startY = 300;

            let loadedCount = 0;
            const totalImages = Math.min(storedImages.length, 6);

            // Position mapping for Layout 6 (2x3 grid)
            const positions = [
                { x: startX, y: startY },
                { x: startX + photoWidth + spacing, y: startY },
                { x: startX, y: startY + photoHeight / 3 },
                { x: startX + photoWidth + spacing, y: startY + photoHeight / 3 },
                { x: startX, y: startY + (photoHeight * 2) / 3 },
                { x: startX + photoWidth + spacing, y: startY + (photoHeight * 2) / 3 }
            ];

            storedImages.slice(0, 6).forEach((imageSrc, index) => {
                const img = new Image();
                img.onload = function() {
                    const pos = positions[index];
                    const frameWidth = photoWidth;
                    const frameHeight = photoHeight / 3;

                    // Apply shape transformation
                    if (selectedShape === 'circle') {
                        const centerX = pos.x + frameWidth / 2;
                        const centerY = pos.y + frameHeight / 2;
                        const radius = Math.min(frameWidth, frameHeight) / 2;
                        
                        ctx.save();
                        ctx.beginPath();
                        ctx.arc(centerX, centerY, radius, 0, 2 * Math.PI);
                        ctx.clip();
                        ctx.drawImage(img, pos.x, pos.y, frameWidth, frameHeight);
                        ctx.restore();
                    } else if (selectedShape === 'soft') {
                        const borderRadius = 20;
                        
                        ctx.save();
                        ctx.beginPath();
                        ctx.roundRect(pos.x, pos.y, frameWidth, frameHeight, borderRadius);
                        ctx.clip();
                        ctx.drawImage(img, pos.x, pos.y, frameWidth, frameHeight);
                        ctx.restore();
                    } else {
                        // Default rectangle
                        ctx.drawImage(img, pos.x, pos.y, frameWidth, frameHeight);
                    }

                    loadedCount++;
                    if (loadedCount === totalImages) {
                        // Draw sticker if selected
                        if (selectedSticker) {
                            const stickerImg = new Image();
                            stickerImg.onload = function() {
                                const stickerSize = 150;
                                const stickerX = canvas.width - stickerSize - 50;
                                const stickerY = 50;
                                ctx.drawImage(stickerImg, stickerX, stickerY, stickerSize, stickerSize);
                                finishCanvas();
                            };
                            stickerImg.src = selectedSticker;
                        } else {
                            finishCanvas();
                        }
                    }
                };
                img.src = imageSrc;
            });
        }

        function finishCanvas() {
            finalCanvas = canvas;
            
            // Update preview
            if (photoCustomPreview) {
                photoCustomPreview.innerHTML = '';
                const canvasClone = canvas.cloneNode(true);
                canvasClone.style.width = '100%';
                canvasClone.style.height = 'auto';
                canvasClone.style.maxHeight = '600px';
                canvasClone.style.objectFit = 'contain';
                photoCustomPreview.appendChild(canvasClone);
            }

            console.log('Canvas redrawn successfully');
        }
    }

});
