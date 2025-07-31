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

        // Background frame controls
        initializeBackgroundFrameControls();

        // Shape controls
        initializeShapeControls();

        // Action buttons
        initializeActionButtons();

        // Initialize email modal
        initializeEmailModal();

        console.log('✅ Basic controls initialized');
    }

    // Initialize sticker controls (loaded in background)
    function initializeStickerControls() {
        setTimeout(() => {
            console.log('🎭 Initializing sticker controls...');

            const stickerButtons = [
                'noneSticker', 'kissSticker', 'ribbonSticker', 'sweetSticker',
                'sparkleSticker', 'pearlSticker', 'softSticker', 'bunnySticker',
                'classicSticker', 'classicBSticker', 'luckySticker', 'confettiSticker',
                'bintang1'
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
        }, 100);
    }

    // Frame controls
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

    // Background frame controls
    function initializeBackgroundFrameControls() {
        const backgroundFrameButtons = [
            { id: 'matcha', src: '/src/assets/frame-backgrounds/matcha.jpg' },
            { id: 'blackStar', src: '/src/assets/frame-backgrounds/blackStar.jpg' },
            { id: 'blueStripe', src: '/src/assets/frame-backgrounds/blueStripe.jpg' }
        ];

        console.log('🖼️ Initializing background frame controls...');
        console.log('🔍 Available buttons:', backgroundFrameButtons.map(btn => btn.id));

        backgroundFrameButtons.forEach(btn => {
            const element = document.getElementById(btn.id);
            if (element) {
                console.log(`✅ Found background frame button: ${btn.id}`);
                element.addEventListener('click', () => {
                    console.log(`🎨 CLICKED BACKGROUND FRAME: ${btn.id}`);
                    console.log(`🎨 Setting background image: ${btn.src}`);

                    // Set background variables
                    backgroundType = 'image';
                    backgroundImage = btn.src;
                    backgroundColor = null;

                    console.log(`📝 Updated variables: backgroundType=${backgroundType}, backgroundImage=${backgroundImage}`);

                    // Redraw canvas
                    redrawCanvas();
                });
            } else {
                console.warn(`⚠️ Background frame button not found: ${btn.id}`);
            }
        });
        console.log('✅ Background frame controls initialized');
    }

    // Shape controls
    function initializeShapeControls() {
        const shapeButtons = [
            { id: 'noneFrameShape', shape: 'default' },
            { id: 'softFrameShape', shape: 'rounded' },
            { id: 'circleFrameShape', shape: 'circle' },
            { id: 'heartFrameShape', shape: 'heart' }
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

        // Email button functionality
        if (emailBtn) {
            emailBtn.addEventListener('click', () => {
                if (finalCanvas) {
                    showEmailModal();
                } else {
                    console.error('No canvas available for email');
                    alert('Tidak ada gambar untuk dikirim ke email');
                }
            });
        }

        if (printBtn) {
            printBtn.addEventListener('click', () => {
                if (finalCanvas) {
                    // Convert finalCanvas to data URL
                    const dataUrl = finalCanvas.toDataURL('image/jpeg', 1.0);
                    showSimplePrintPopup(dataUrl);
                } else {
                    console.error('No canvas available for printing');
                    alert('Tidak ada gambar untuk di-print');
                }
            });
        }

        if (continueBtn) {
            continueBtn.addEventListener('click', () => {
                window.location.href = 'thankyou.php';
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

    // Initialize email modal controls
    function initializeEmailModal() {
        const closeEmailModal = document.getElementById('closeEmailModal');
        const cancelEmailBtn = document.getElementById('cancelEmailBtn');
        const sendEmailBtn = document.getElementById('sendEmailBtn');
        const emailInput = document.getElementById('emailInput');

        if (closeEmailModal) {
            closeEmailModal.addEventListener('click', hideEmailModal);
        }

        if (cancelEmailBtn) {
            cancelEmailBtn.addEventListener('click', hideEmailModal);
        }

        if (sendEmailBtn) {
            sendEmailBtn.addEventListener('click', () => {
                const email = emailInput.value.trim();
                if (!email) {
                    showValidationError('Mohon masukkan alamat email');
                    return;
                }

                if (!validateEmail(email)) {
                    showValidationError('Format email tidak valid');
                    return;
                }

                hideValidationError();
                sendPhotoEmail(email);
            });
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
        }
    }

    function hideValidationError() {
        const validationDiv = document.querySelector('.input-validation');
        if (validationDiv) {
            validationDiv.style.display = 'none';
        }
    }

    function validateEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    function sendPhotoEmail(email) {
        if (!finalCanvas) {
            alert('Tidak ada foto untuk dikirim');
            return;
        }

        // Show loading state
        const sendBtn = document.getElementById('sendEmailBtn');
        const originalHtml = sendBtn.innerHTML;
        sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan & Mengirim...';
        sendBtn.disabled = true;

        console.log('🔄 Starting email process...');

        // Convert canvas to base64
        finalCanvas.toBlob((blob) => {
            const reader = new FileReader();
            reader.onloadend = function() {
                const base64data = reader.result;
                
                console.log('🔄 Saving photo to server, data size:', base64data.length);
                
                // Save photo to server and get link - USING NEW API
                fetch('../api-fetch/save_final_photo_v2.php', {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ image: base64data })
                })
                .then(response => {
                    console.log('📡 Server response status:', response.status);
                    return response.text(); // Get as text first to handle errors
                })
                .then(text => {
                    console.log('📡 Server response text:', text.substring(0, 200) + '...');
                    
                    // Check if response looks like HTML (error page)
                    if (text.trim().startsWith('<') || text.includes('<br />') || text.includes('<b>')) {
                        throw new Error('Server returned HTML error page: ' + text.substring(0, 300));
                    }
                    
                    let data;
                    try {
                        data = JSON.parse(text);
                    } catch (parseError) {
                        console.error('❌ JSON Parse Error:', parseError);
                        console.error('📄 Raw response:', text);
                        throw new Error('Invalid JSON response from server: ' + text.substring(0, 200));
                    }
                    
                    if (data.success) {
                        console.log('✅ Photo saved successfully:', data);
                        // Send email via EmailJS
                        const photoLink = window.location.origin + data.url;
                        console.log('🔗 Sending email to:', email, 'with link:', photoLink);
                        
                        // EmailJS parameters - PASTI BENAR berdasarkan template
                        const emailParams = {
                            // Field utama untuk email tujuan - coba semua kemungkinan
                            email: email,
                            to_email: email,
                            recipient_email: email,
                            
                            // Field untuk link foto
                            link: photoLink,
                            photo_link: photoLink,
                            url: photoLink,
                            
                            // Field tambahan
                            name: "Sobat",
                            user_name: "Sobat",
                            to_name: "Sobat",
                            from_name: "GOFOTOBOX",
                            
                            // Field pesan
                            message: `Halo Sobat! Link foto Anda: ${photoLink}`
                        };
                        
                        console.log('📧 EmailJS parameters (ALL FIELDS):', emailParams);
                        
                        return emailjs.send("service_gtqjb2j", "template_pp5i4hm", emailParams).then(() => {
                            console.log('✅ Email sent successfully via EmailJS');
                            // Show success message
                            showValidationError('Email berhasil dikirim! ✅ Cek inbox Anda.');
                            document.querySelector('.input-validation span').style.color = '#28a745';
                            
                            // Hide modal after delay
                            setTimeout(() => {
                                hideEmailModal();
                                document.getElementById('emailInput').value = '';
                                hideValidationError();
                            }, 3000);
                        }, (emailError) => {
                            console.error('❌ EmailJS error:', emailError);
                            throw new Error('Gagal mengirim email: ' + (emailError.text || emailError.message || 'Unknown EmailJS error'));
                        });
                    } else {
                        console.error('❌ Save photo failed:', data);
                        throw new Error('Gagal menyimpan foto: ' + (data.message || 'Server error'));
                    }
                })
                .catch(error => {
                    console.error('❌ Full error details:', error);
                    showValidationError('Error: ' + error.message);
                })
                .finally(() => {
                    // Reset button state
                    sendBtn.innerHTML = originalHtml;
                    sendBtn.disabled = false;
                });
            };
            reader.readAsDataURL(blob);
        }, 'image/png');
    }

    // Main canvas drawing function
    function redrawCanvas() {
        if (!storedImages || storedImages.length === 0) {
            console.warn('⚠️ No images available for redraw');
            return;
        }

        console.log(`Redrawing canvas with background: ${backgroundType} `);

        const stackedCanvas = document.createElement('canvas');
        const ctx = stackedCanvas.getContext('2d');

        // Dimensi kanvas dan parameter tata letak
        const canvasWidth = 1200;   // 4R standard width
        const canvasHeight = 1800;  // 4R standard height
        const borderWidth = 62;     // Border kiri dan kanan
        const marginTop = 120;      // Margin atas untuk semua foto
        const spacing = 80;         // Spasi vertikal antar foto
        const photoWidth = 1076;    // Lebar foto (1200 - 62 - 62 = 1076px)
        const photoHeight = 639;    // Tinggi foto
        const expectedPhotos = 2;   // Jumlah foto yang diharapkan

        stackedCanvas.width = canvasWidth;
        stackedCanvas.height = canvasHeight;

        // Membersihkan kanvas
        ctx.clearRect(0, 0, stackedCanvas.width, stackedCanvas.height);

        // Mengatur latar belakang
        if (backgroundType === 'color') {
            ctx.fillStyle = backgroundColor;
            ctx.fillRect(0, 0, stackedCanvas.width, stackedCanvas.height);
            drawPhotos();
        } else if (backgroundImage) {
            console.log('🎨 Loading background image:', backgroundImage);
            const bgImg = new Image();
            bgImg.onload = function () {
                console.log('✅ Background image loaded successfully');
                ctx.drawImage(bgImg, 0, 0, stackedCanvas.width, stackedCanvas.height);
                drawPhotos();

                // Update canvas preview AFTER background image is drawn
                updateCanvasPreview(stackedCanvas);
            };
            bgImg.onerror = function () {
                console.error('❌ Failed to load background image:', backgroundImage);
                // Fallback to white background if image fails to load
                ctx.fillStyle = '#FFFFFF';
                ctx.fillRect(0, 0, stackedCanvas.width, stackedCanvas.height);
                drawPhotos();

                // Update canvas preview even on error
                updateCanvasPreview(stackedCanvas);
            };
            bgImg.src = backgroundImage;
            return; // Don't continue to the preview update below
        } else {
            // Default white background
            ctx.fillStyle = '#FFFFFF';
            ctx.fillRect(0, 0, stackedCanvas.width, stackedCanvas.height);
            drawPhotos();
        }

        // Update canvas preview for color backgrounds and default
        updateCanvasPreview(stackedCanvas);

        function drawPhotos() {
            if (storedImages.length < expectedPhotos) {
                console.warn(`⚠️ Layout ${expectedPhotos} requires ${expectedPhotos} photos, only found: ${storedImages.length} `);
            }

            let loadedCount = 0;
            const imagesToProcess = Math.min(storedImages.length, expectedPhotos);

            storedImages.slice(0, imagesToProcess).forEach((imageData, index) => {
                const img = new Image();
                img.onload = function () {
                    // Posisi dan ukuran untuk setiap foto
                    const positions = [
                        // Foto 1: Atas
                        { x: borderWidth, y: marginTop, width: photoWidth, height: photoHeight }, // x: 62, y: 120
                        // Foto 2: Bawah
                        { x: borderWidth, y: marginTop + photoHeight + spacing, width: photoWidth, height: photoHeight } // x: 62, y: 120 + 639 + 80 = 839
                    ];

                    const pos = positions[index];
                    drawCroppedImage(ctx, img, pos.x, pos.y, pos.width, pos.height, selectedShape);

                    loadedCount++;
                    if (loadedCount === imagesToProcess) {
                        drawStickersAndLogos(ctx, stackedCanvas);
                    }
                };
                img.src = imageData;
            });
        }

        function drawCroppedImage(ctx, img, x, y, targetWidth, targetHeight, shape) {
            const imgAspect = img.width / img.height;
            const targetAspect = targetWidth / targetHeight;

            let sx, sy, sWidth, sHeight, dx, dy, dWidth, dHeight;

            if (imgAspect > targetAspect) {
                // Image is wider than target → crop sides
                sHeight = img.height;
                sWidth = sHeight * targetAspect;
                sx = (img.width - sWidth) / 2;
                sy = 0;
                dx = x;
                dy = y;
                dWidth = targetWidth;
                dHeight = targetHeight;
            } else {
                // Image is taller than target → crop top/bottom
                sWidth = img.width;
                sHeight = sWidth / targetAspect;
                sx = 0;
                sy = (img.height - sHeight) / 2;
                dx = x;
                dy = y;
                dWidth = targetWidth;
                dHeight = targetHeight;
            }

            // Draw the image with the specified shape
            drawPhotoWithShape(ctx, img, dx, dy, dWidth, dHeight, shape, sx, sy, sWidth, sHeight);
        }

        function drawPhotoWithShape(ctx, img, x, y, width, height, shape, sx, sy, sWidth, sHeight) {
            ctx.save();

            // Create clipping path based on shape
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
                heartShape(ctx, x + width / 2, y + height / 2, Math.min(width, height) / 2);
                ctx.clip();
            } else {
                // Default to rectangle if no shape specified
                ctx.beginPath();
                ctx.rect(x, y, width, height);
                ctx.clip();
            }

            // Draw the cropped image within the clipped shape
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

        function heartShape(ctx, centerX, centerY, size) {
            ctx.beginPath();
            const x = centerX;
            const y = centerY;
            const width = size * 2;
            const height = size * 2;

            ctx.moveTo(x, y - height / 4);
            ctx.bezierCurveTo(x, y - height / 2, x - width / 2, y - height / 2, x - width / 2, y);
            ctx.bezierCurveTo(x - width / 2, y + height / 2, x, y + height, x, y + height);
            ctx.bezierCurveTo(x, y + height, x + width / 2, y + height / 2, x + width / 2, y);
            ctx.bezierCurveTo(x + width / 2, y - height / 2, x, y - height / 2, x, y - height / 4);
            ctx.closePath();
        }

        // Update canvas preview for color backgrounds and default
        updateCanvasPreview(stackedCanvas);
    }

    // Helper function to update canvas preview
    function updateCanvasPreview(stackedCanvas) {
        // Styling pratinjau kanvas
        if (photoCustomPreview) {
            photoCustomPreview.innerHTML = '';

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

    // Draw stickers and logos
    function drawStickersAndLogos(ctx, canvas) {
        // Draw selected sticker as overlay frame (covers entire canvas)
        if (selectedSticker) {
            const stickerImg = new Image();
            stickerImg.onload = function () {
                // Draw sticker as full canvas overlay - sticker will be on top layer
                ctx.drawImage(stickerImg, 0, 0, canvas.width, canvas.height);
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

    // Simple print popup function
    function showSimplePrintPopup(imageDataUrl) {
        // Remove existing popup if any
        const existingPopup = document.getElementById('simplePrintPopup');
        if (existingPopup) {
            existingPopup.remove();
        }

        // Create simple popup overlay
        const popup = document.createElement('div');
        popup.id = 'simplePrintPopup';
        popup.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        `;

        // Create popup box
        const popupBox = document.createElement('div');
        popupBox.style.cssText = `
            background: white;
            padding: 25px;
            border-radius: 10px;
            text-align: center;
            max-width: 450px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        `;

        popupBox.innerHTML = `
            <h3 style="margin: 0 0 15px 0; color: #333;">Print Preview</h3>
            <img src="${imageDataUrl}" style="max-width: 350px; height: auto; border: 2px solid #ddd; border-radius: 5px; margin-bottom: 20px;" alt="Print Preview" />
            <div>
                <button id="directPrintBtn" style="
                    background: #28a745;
                    color: white;
                    border: none;
                    padding: 12px 25px;
                    border-radius: 6px;
                    cursor: pointer;
                    font-size: 16px;
                    margin-right: 10px;
                    font-weight: bold;
                ">🖨️ Print</button>
                <button id="closePopupBtn" style="
                    background: #6c757d;
                    color: white;
                    border: none;
                    padding: 12px 25px;
                    border-radius: 6px;
                    cursor: pointer;
                    font-size: 16px;
                    font-weight: bold;
                ">✖️ Close</button>
            </div>
        `;

        popup.appendChild(popupBox);
        document.body.appendChild(popup);

        // Add event listeners
        document.getElementById('directPrintBtn').addEventListener('click', () => {
            // Create temporary print element directly with window.print()
            const originalContents = document.body.innerHTML;

            // Create print content
            const printContent = `
                <div style="margin: 0; padding: 0; text-align: center;">
                    <img src="${imageDataUrl}" style="width: 100%; height: auto; max-width: 100%;" alt="Print Image" />
                </div>
                <style>
                    @media print {
                        body { margin: 0; padding: 0; }
                        img { width: 100%; height: auto; page-break-inside: avoid; }
                    }
                </style>
            `;

            // Replace body content temporarily
            document.body.innerHTML = printContent;

            // Print
            window.print();

            // Restore original content
            document.body.innerHTML = originalContents;

            // Re-attach event listeners by triggering DOMContentLoaded
            location.reload();
        });

        document.getElementById('closePopupBtn').addEventListener('click', () => {
            popup.remove();
        });

        // Close popup when clicking outside
        popup.addEventListener('click', (e) => {
            if (e.target === popup) {
                popup.remove();
            }
        });

        // Close popup with Escape key
        document.addEventListener('keydown', function escapeHandler(e) {
            if (e.key === 'Escape') {
                popup.remove();
                document.removeEventListener('keydown', escapeHandler);
            }
        });
    }
});