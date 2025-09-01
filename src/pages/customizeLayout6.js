document.addEventListener('DOMContentLoaded', () => {
    // Variables
    let storedImages = [];
    let finalCanvas = null;
    let selectedSticker = null;
    let selectedShape = 'default';
    let backgroundType = 'color';
    let backgroundColor = '#FFFFFF';
    let backgroundImage = null;
    let availableFrames = [];
    let availableStickers = [];
    
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
        
        // Debug: Check if DOM elements exist
        const photoPreview = document.getElementById('photoPreview');
        console.log('🔍 photoPreview element:', photoPreview ? 'Found' : 'Not found');
        
        // Check for possible frame container IDs
        const frameContainerIds = ['dynamicFramesContainer', 'frames-container', 'framesContainer'];
        frameContainerIds.forEach(id => {
            const element = document.getElementById(id);
            console.log(`🔍 ${id}:`, element ? 'Found' : 'Not found');
        });
        
        // Check for possible sticker container IDs  
        const stickerContainerIds = ['dynamicStickersContainer', 'stickers-container', 'stickersContainer'];
        stickerContainerIds.forEach(id => {
            const element = document.getElementById(id);
            console.log(`🔍 ${id}:`, element ? 'Found' : 'Not found');
        });
        
        try {
            // Load assets from database first
            await loadAssetsFromDatabase();
            // Create dynamic controls after assets loaded
            await createDynamicControls();
            await loadPhotos();
            initializeCanvas();
            initializeControls();
            console.log('✅ App initialization complete');
        } catch (error) {
            console.error('❌ Error initializing app:', error);
            alert('Gagal memuat foto. Redirecting...');
            window.location.href = 'selectlayout.php';
        }
    }

    // Load assets from database
    async function loadAssetsFromDatabase() {
        try {
            console.log('🔄 Loading frames and stickers from database...');
            
            const [framesResponse, stickersResponse] = await Promise.all([
                fetch('/src/api-fetch/get-frames.php'),
                fetch('/src/api-fetch/get-stickers.php')
            ]);

            console.log('🔍 Frames response status:', framesResponse.status);
            console.log('🔍 Stickers response status:', stickersResponse.status);

            if (framesResponse.ok) {
                const framesData = await framesResponse.json();
                console.log('🔍 Frames data received:', framesData);
                
                if (framesData.success && framesData.data && Array.isArray(framesData.data)) {
                    availableFrames = framesData.data;
                    console.log(`✅ Loaded ${availableFrames.length} frames from database:`, availableFrames);
                } else {
                    console.warn('⚠️ Invalid frames data structure, using fallback');
                    availableFrames = getFallbackFrames();
                }
            } else {
                console.warn('⚠️ Failed to load frames from database, using fallback');
                availableFrames = getFallbackFrames();
            }

            if (stickersResponse.ok) {
                const stickersData = await stickersResponse.json();
                console.log('🔍 Stickers data received:', stickersData);
                
                if (stickersData.success && stickersData.data && Array.isArray(stickersData.data)) {
                    availableStickers = stickersData.data;
                    console.log(`✅ Loaded ${availableStickers.length} stickers from database:`, availableStickers);
                } else {
                    console.warn('⚠️ Invalid stickers data structure, using fallback');
                    availableStickers = getFallbackStickers();
                }
            } else {
                console.warn('⚠️ Failed to load stickers from database, using fallback');
                availableStickers = getFallbackStickers();
            }

        } catch (error) {
            console.error('❌ Error loading assets from database:', error);
            console.log('🔄 Using fallback assets...');
            availableFrames = getFallbackFrames();
            availableStickers = getFallbackStickers();
        }
        
        console.log(`🎯 Final frames count: ${availableFrames.length}`);
        console.log(`🎯 Final stickers count: ${availableStickers.length}`);
        
        // Ensure we have at least fallback data
        if (availableFrames.length === 0) {
            console.warn('⚠️ No frames available, forcing fallback');
            availableFrames = getFallbackFrames();
        }
        
        if (availableStickers.length === 0) {
            console.warn('⚠️ No stickers available, forcing fallback');
            availableStickers = getFallbackStickers();
        }
    }

    // Create dynamic controls for frames and stickers
    async function createDynamicControls() {
        console.log('🔄 Creating dynamic asset controls...');
        
        // Create frame controls - try multiple possible container IDs
        const possibleFrameContainers = [
            'dynamicFramesContainer',
            'frames-container', 
            'framesContainer'
        ];
        
        let framesContainer = null;
        for (const containerId of possibleFrameContainers) {
            framesContainer = document.getElementById(containerId);
            if (framesContainer) {
                console.log(`✅ Found frames container: ${containerId}`);
                break;
            }
        }
        
        if (framesContainer) {
            // Remove loading placeholder only
            const loadingPlaceholder = framesContainer.querySelector('.loading-placeholder');
            if (loadingPlaceholder) loadingPlaceholder.remove();
            
            console.log(`🔄 Creating ${availableFrames.length} frame buttons...`);
            availableFrames.forEach((frame, index) => {
                const button = document.createElement('button');
                button.id = `frame_${frame.id}`;
                button.className = 'buttonBgFrames frame-dynamic';
                
                // Handle both image frames and color frames
                if (frame.file_path) {
                    // Image frame
                    button.style.backgroundImage = `url('${frame.file_path}')`;
                    button.style.backgroundSize = 'cover';
                    button.style.backgroundPosition = 'center';
                    button.setAttribute('data-frame-path', frame.file_path);
                } else if (frame.warna) {
                    // Color frame
                    button.style.backgroundColor = frame.warna;
                    button.setAttribute('data-frame-color', frame.warna);
                }
                
                button.setAttribute('data-frame-id', frame.id);
                button.setAttribute('data-frame-name', frame.nama);
                button.title = frame.nama;
                
                button.addEventListener('click', () => {
                    // Remove active class from all frame buttons
                    document.querySelectorAll('.buttonBgFrames, .buttonFrames').forEach(btn => 
                        btn.classList.remove('active'));
                    button.classList.add('active');
                    
                    if (frame.file_path) {
                        backgroundType = 'image';
                        backgroundImage = frame.file_path;
                        backgroundColor = null;
                    } else if (frame.warna) {
                        backgroundType = 'color';
                        backgroundColor = frame.warna;
                        backgroundImage = null;
                    }
                    
                    redrawCanvas();
                    console.log(`🎯 Selected frame: ${frame.nama}`);
                });
                
                framesContainer.appendChild(button);
                console.log(`✅ Created frame button: ${frame.nama}`);
            });
            
            console.log(`✅ Created ${availableFrames.length} frame controls`);
        } else {
            console.error('❌ No frames container found with any of these IDs:', possibleFrameContainers);
        }
        
        // Create sticker controls - try multiple possible container IDs
        const possibleStickerContainers = [
            'dynamicStickersContainer',
            'stickers-container',
            'stickersContainer'
        ];
        
        let stickersContainer = null;
        for (const containerId of possibleStickerContainers) {
            stickersContainer = document.getElementById(containerId);
            if (stickersContainer) {
                console.log(`✅ Found stickers container: ${containerId}`);
                break;
            }
        }
        
        if (stickersContainer) {
            // Remove loading placeholder only
            const loadingPlaceholder = stickersContainer.querySelector('.loading-placeholder');
            if (loadingPlaceholder) loadingPlaceholder.remove();
            
            // Initialize existing "None" sticker button
            const noneButton = document.getElementById('noneSticker');
            if (noneButton) {
                noneButton.addEventListener('click', () => {
                    // Remove active class from all sticker buttons
                    document.querySelectorAll('.buttonStickers').forEach(btn => 
                        btn.classList.remove('active'));
                    noneButton.classList.add('active');
                    
                    selectedSticker = null;
                    redrawCanvas();
                    console.log('🎯 No sticker selected');
                });
            }
            
            // Add database stickers
            availableStickers.forEach((sticker, index) => {
                const button = document.createElement('button');
                button.id = `sticker_${sticker.id}`;
                button.className = 'buttonStickers sticker-dynamic';
                button.setAttribute('data-sticker-id', sticker.id);
                button.setAttribute('data-sticker-name', sticker.nama);
                
                const img = document.createElement('img');
                img.src = sticker.file_path;
                img.alt = sticker.nama;
                img.className = 'sticker-icon';
                img.style.width = '40px';
                img.style.height = '40px';
                img.style.objectFit = 'contain';
                button.appendChild(img);
                
                button.addEventListener('click', () => {
                    // Remove active class from all sticker buttons
                    document.querySelectorAll('.buttonStickers').forEach(btn => 
                        btn.classList.remove('active'));
                    button.classList.add('active');
                    
                    selectedSticker = sticker.file_path;
                    redrawCanvas();
                    console.log(`🎯 Selected sticker: ${sticker.nama}`);
                });
                
                stickersContainer.appendChild(button);
            });
            
            console.log(`✅ Created ${availableStickers.length} sticker controls`);
        } else {
            console.error('❌ No stickers container found with any of these IDs:', possibleStickerContainers);
        }
        
        // Mark containers as loaded
        if (framesContainer) framesContainer.classList.add('assets-loaded');
        if (stickersContainer) stickersContainer.classList.add('assets-loaded');
        
        console.log('✅ Dynamic controls creation complete');
    }

    // Fallback frames if database fails
    function getFallbackFrames() {
        return [
            { id: 1, nama: 'Matcha Frame', file_path: '/src/assets/frame-backgrounds/matcha.jpg' },
            { id: 2, nama: 'Black Star Frame', file_path: '/src/assets/frame-backgrounds/blackStar.jpg' },
            { id: 3, nama: 'Blue Stripe Frame', file_path: '/src/assets/frame-backgrounds/blueStripe.jpg' },
            { id: 4, nama: 'Pink Color', warna: '#FFB6C1' },
            { id: 5, nama: 'Blue Color', warna: '#87CEEB' },
            { id: 6, nama: 'Yellow Color', warna: '#FFFFE0' },
            { id: 7, nama: 'White Color', warna: '#FFFFFF' }
        ];
    }

    // Fallback stickers if database fails
    function getFallbackStickers() {
        return [
            { id: 'fallback-sticker-1', nama: 'Star', file_path: '/src/assets/stickers/bintang1.png' }
        ];
    }

    // Load photos from server
    async function loadPhotos() {
        console.log('🔄 Loading photos...');
        try {
            const response = await fetch('../api-fetch/get_photos.php');
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            
            const data = await response.json();
            
            if (!data.success) {
                console.error('❌ Failed to load photos:', data.error || 'Unknown error');
                throw new Error(data.error || 'No photos found in session');
            }
            
            if (!data.photos || !Array.isArray(data.photos) || data.photos.length === 0) {
                console.error('❌ No valid photos found in response');
                throw new Error('No photos available');
            }
            
            storedImages = data.photos;
            console.log(`✅ Loaded ${storedImages.length} images:`, storedImages);
        } catch (error) {
            console.error('❌ Error loading photos:', error.message);
            throw error; // Re-throw to be handled by initializeApp
        }
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

    // Updates continue button state based on email and print completion
    function updateContinueButtonState() {
        const continueBtn = document.getElementById('continueBtn');
        if (!continueBtn) return;
        
        const canContinue = emailSent && printUsed;
        
        if (canContinue) {
            continueBtn.innerHTML = '<i class="fas fa-arrow-right"></i> Lanjut ke Terima Kasih';
            continueBtn.disabled = false;
            continueBtn.style.opacity = '1';
            continueBtn.style.cursor = 'pointer';
            continueBtn.classList.remove('disabled');
        } else {
            const missingActions = [];
            if (!emailSent) missingActions.push('Email');
            if (!printUsed) missingActions.push('Print');
            
            continueBtn.innerHTML = `⏳ ${missingActions.join(' & ')} Dulu`;
            continueBtn.disabled = true;
            continueBtn.style.opacity = '0.5';
            continueBtn.style.cursor = 'not-allowed';
            continueBtn.classList.add('disabled');
        }
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
            continueBtn: () => {
                // Check if both email and print are completed
                if (!emailSent || !printUsed) {
                    const missing = [];
                    if (!emailSent) missing.push('mengirim email');
                    if (!printUsed) missing.push('print foto');
                    
                    alert(`⚠️ Anda harus ${missing.join(' dan ')} terlebih dahulu!`);
                    return;
                }
                window.location.href = 'thankyou.php';
            }
        };

        Object.entries(buttons).forEach(([id, handler]) => {
            const button = document.getElementById(id);
            if (button) {
                button.addEventListener('click', handler);
            }
        });

        // Set initial continue button state (disabled)
        updateContinueButtonState();
    }

    // Show email modal
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
        console.log('📧 Starting sendPhotoEmail for:', email);
        
        if (!finalCanvas) {
            console.error('❌ No finalCanvas available');
            alert('Tidak ada foto untuk dikirim');
            return;
        }

        const sendBtn = document.getElementById('sendEmailBtn');
        const originalHtml = sendBtn.innerHTML;
        sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan & Mengirim...';
        sendBtn.disabled = true;

        try {
            console.log('🔄 Converting canvas to blob...');
            const blob = await new Promise(resolve => finalCanvas.toBlob(resolve, 'image/png'));
            console.log('✅ Blob created, size:', blob.size);
            
            console.log('🔄 Converting to base64...');
            const base64data = await new Promise(resolve => {
                const reader = new FileReader();
                reader.onloadend = () => resolve(reader.result);
                reader.readAsDataURL(blob);
            });
            console.log('✅ Base64 conversion complete');

            console.log('📤 Sending to server...');
            const response = await fetch('../api-fetch/save_final_photo_v2.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ image: base64data })
            });

            const text = await response.text();
            console.log('📥 Server response received:', text.substring(0, 200));
            
            if (text.trim().startsWith('<') || text.includes('<br />') || text.includes('<b>')) {
                console.error('❌ Server returned HTML error page');
                throw new Error('Server returned HTML error page');
            }

            const data = JSON.parse(text);
            console.log('✅ Response parsed:', data);
            
            if (!data.success) {
                console.error('❌ Server error:', data.message);
                throw new Error(data.message || 'Server error');
            }

            console.log('📷 Photo saved successfully! URL:', data.url);
            const photoLink = window.location.origin + data.url;
            console.log('🔗 Full photo link:', photoLink);
            
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

            console.log('📧 Sending email with params:', emailParams);
            await emailjs.send('service_gtqjb2j', 'template_pp5i4hm', emailParams);
            console.log('✅ Email sent successfully!');
            
            // Mark email as sent and disable button
            emailSent = true;
            const emailBtn = document.getElementById('emailBtn');
            if (emailBtn) {
                emailBtn.disabled = true;
                emailBtn.style.opacity = '0.5';
                emailBtn.style.cursor = 'not-allowed';
                emailBtn.innerHTML = '✅ Email Terkirim';
                console.log('✅ Email button updated');
            }
            
            // Update continue button state
            updateContinueButtonState();
            
            showValidationError('Email berhasil dikirim! ✅ Cek inbox Anda.');
            document.querySelector('.input-validation span').style.color = '#28a745';
            setTimeout(() => {
                console.log('🔄 Closing email modal');
                document.getElementById('emailModal').style.display = 'none';
                document.getElementById('emailInput').value = '';
                hideValidationError();
            }, 3000);
        } catch (error) {
            console.error('❌ Email error:', error);
            showValidationError('Error: ' + error.message);
        } finally {
            console.log('🔄 Resetting send button');
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
        const canvasWidth = 1224; // 10.2 cm at 300 DPI
        const canvasHeight = 1836; // 15.2 cm at 300 DPI
        const borderWidth = 62;
        const marginTopLeft = 120;
        const marginTopRight = 240;
        const spacing = 37;
        const photoWidth = 477;
        const photoHeight = 567;
        const expectedPhotos = 4;   

        canvas.width = canvasWidth;
        canvas.height = canvasHeight;
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        if (backgroundType === 'color') {
            ctx.fillStyle = backgroundColor;
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            drawPhotos();
        } else if (backgroundImage) {
            const bgImg = new Image();
            bgImg.crossOrigin = 'anonymous';
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
            console.log('🖼️ Drawing photos... Expected:', expectedPhotos, 'Available:', storedImages.length);
            
            if (storedImages.length < expectedPhotos) {
                console.warn(`⚠️ Layout requires ${expectedPhotos} photos, found: ${storedImages.length}`);
            }

            const imagesToProcess = Math.min(storedImages.length, expectedPhotos);
            let loadedCount = 0;

            const availableWidth = canvasWidth - (borderWidth * 2);
            const centerSpacing = availableWidth - (photoWidth * 2);
            const leftColumnX = borderWidth;
            const rightColumnX = borderWidth + photoWidth + centerSpacing;
            
            console.log('📐 Layout calculations:', {
                availableWidth,
                centerSpacing,
                leftColumnX,
                rightColumnX
            });

            storedImages.slice(0, imagesToProcess).forEach((imageData, index) => {
                console.log(`📸 Loading photo ${index + 1}:`, imageData);
                
                const img = new Image();
                img.crossOrigin = 'anonymous';
                
                img.onload = () => {
                    console.log(`✅ Photo ${index + 1} loaded successfully`);
                    const positions = [
                        { x: leftColumnX, y: marginTopLeft, width: photoWidth, height: photoHeight },
                        { x: leftColumnX, y: marginTopLeft + photoHeight + spacing, width: photoWidth, height: photoHeight },
                        { x: rightColumnX, y: marginTopRight, width: photoWidth, height: photoHeight },
                        { x: rightColumnX, y: marginTopRight + photoHeight + spacing, width: photoWidth, height: photoHeight }
                    ];
                    
                    try {
                        drawCroppedImage(ctx, img, positions[index], selectedShape);
                        console.log(`✅ Photo ${index + 1} drawn at position:`, positions[index]);
                    } catch (error) {
                        console.error(`❌ Error drawing photo ${index + 1}:`, error);
                    }
                    
                    loadedCount++;
                    if (loadedCount === imagesToProcess) {
                        console.log('🎨 All photos loaded, drawing stickers and logos...');
                        drawStickersAndLogos(ctx, canvas);
                        updateCanvasPreview(canvas);
                    }
                };
                
                img.onerror = () => {
                    console.error(`❌ Failed to load photo ${index + 1}:`, imageData);
                    loadedCount++;
                    if (loadedCount === imagesToProcess) {
                        console.log('🎨 Processing complete (with errors), drawing stickers and logos...');
                        drawStickersAndLogos(ctx, canvas);
                        updateCanvasPreview(canvas);
                    }
                };
                
                img.src = imageData;
            });
        }
    }

    function drawCroppedImage(ctx, img, pos, shape) {
        console.log('✂️ Drawing cropped image with shape:', shape, 'at position:', pos);
        
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

        console.log('📐 Crop calculations:', { sx, sy, sWidth, sHeight });
        
        try {
            drawPhotoWithShape(ctx, img, x, y, width, height, shape, sx, sy, sWidth, sHeight);
            console.log('✅ Photo drawn successfully with shape:', shape);
        } catch (error) {
            console.error('❌ Error in drawPhotoWithShape:', error);
        }
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
            stickerImg.crossOrigin = 'anonymous';
            stickerImg.onload = () => {
                ctx.drawImage(stickerImg, 0, 0, canvas.width, canvas.height);
                console.log('✅ Sticker applied');
            };
            stickerImg.onerror = () => {
                console.error('❌ Failed to load sticker:', selectedSticker);
            };
            stickerImg.src = selectedSticker;
        }

        const logoBtn = document.getElementById('engLogo');
        if (logoBtn && logoBtn.classList.contains('active')) {
            const logoImg = new Image();
            logoImg.crossOrigin = 'anonymous';
            logoImg.onload = () => {
                ctx.drawImage(logoImg, 20, canvas.height - 60, 100, 40);
                console.log('✅ Logo applied');
            };
            logoImg.onerror = () => {
                console.error('❌ Failed to load logo');
            };
            logoImg.src = '/src/assets/logo.png';
        }
    }

    function updateCanvasPreview(canvas) {
        console.log('🖼️ Updating canvas preview...');
        
        if (photoCustomPreview) {
            console.log('✅ Preview container found, updating...');
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
            console.log('✅ Canvas added to preview container');
        } else {
            console.warn('⚠️ Photo preview container not found');
        }
        
        finalCanvas = canvas;
        console.log('✅ Final canvas updated');
    }

    // Initialize sticker controls with database data

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
                    
                    // Update continue button state
                    updateContinueButtonState();
                    
                    popup.remove();
                }, 500);
            };
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
});