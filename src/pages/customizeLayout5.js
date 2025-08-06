document.addEventListener('DOMContentLoaded', () => {
  // Configuration constants
  const CONFIG = {
    CANVAS_WIDTH: 1200,
    CANVAS_HEIGHT: 1800,
    BORDER_WIDTH: 62,
    MARGIN_TOP: 120,
    SPACING: 37,
    CENTER_SPACING: 124,
    EXPECTED_PHOTOS: 6,
    EMAILJS_SERVICE_ID: 'service_gtqjb2j',
    EMAILJS_TEMPLATE_ID: 'template_pp5i4hm',
    LOGO_SRC: '/src/assets/logo.png',
  };

  // State variables
  let state = {
    storedImages: [],
    finalCanvas: null,
    selectedSticker: null,
    selectedShape: 'default',
    backgroundType: 'color',
    backgroundColor: '#FFFFFF',
    backgroundImage: null,
    availableFrames: [],
    availableStickers: [],
    emailSent: false,
    printUsed: false,
    imageCache: new Map(), // Cache untuk gambar
  };

  // DOM Elements
  const DOM = {
      photoCustomPreview: document.getElementById('photoPreview'),
      framesContainer: document.getElementById('dynamicFramesContainer'),
      stickersContainer: document.getElementById('dynamicStickersContainer'),
      noneSticker: document.getElementById('noneSticker'),
      emailModal: document.getElementById('emailModal'),
      emailInput: document.getElementById('emailInput'),
  };

  // Utility Functions
  /**
   * Loads an image and caches it
   * @param {string} src - Image source URL
   * @returns {Promise<Image>} - Loaded image
   */
  async function loadImage(src) {
    if (state.imageCache.has(src)) {
      return state.imageCache.get(src);
    }
    return new Promise((resolve, reject) => {
      const img = new Image();
      img.crossOrigin = 'anonymous';
      img.onload = () => {
        state.imageCache.set(src, img);
        resolve(img);
      };
      img.onerror = () => reject(new Error(`Failed to load image: ${src}`));
      img.src = src;
    });
  }

  /**
   * Sets active state for a button and removes active from others
   * @param {string} selector - CSS selector for buttons
   * @param {HTMLElement} selectedButton - Button to set as active
   */
  function setActiveButton(selector, selectedButton) {
    document.querySelectorAll(selector).forEach((btn) => btn.classList.remove('active'));
    selectedButton.classList.add('active');
  }

  /**
   * Handles errors consistently
   * @param {string} message - Error message
   * @param {string} type - Error type ('alert' or 'validation')
   */
  function handleError(message, type = 'alert') {
    console.error(`❌ ${message}`);
    if (type === 'validation') {
      showValidationError(message);
    } else {
      alert(message);
    }
  }

  // Initialization
  /**
   * Initializes the photobooth application
   */
  async function initializeApp() {
    console.log('🔄 Initializing photobooth customization...');
    try {
      await loadAssetsFromDatabase();
      await createDynamicControls();
      await loadPhotos();
      initializeCanvas();
      initializeControls();
      console.log('✅ App initialization complete');
    } catch (error) {
      handleError('Gagal memuat foto. Redirecting...', 'alert');
      window.location.href = 'selectlayout.php';
    }
  }

  // Asset Loading
  /**
   * Loads frames and stickers from database
   */
  async function loadAssetsFromDatabase() {
    console.log('🔄 Loading frames and stickers from database...');
    try {
      const [framesResponse, stickersResponse] = await Promise.all([
        fetch('/src/api-fetch/get-frames.php'),
        fetch('/src/api-fetch/get-stickers.php'),
      ]);

      const framesData = await framesResponse.json();
      const stickersData = await stickersResponse.json();

      state.availableFrames = framesData.success ? framesData.data : getFallbackFrames();
      console.log(`✅ Loaded ${state.availableFrames.length} frames from database`);

      state.availableStickers = stickersData.success ? stickersData.data : getFallbackStickers();
      console.log(`✅ Loaded ${state.availableStickers.length} stickers from database`);
    } catch (error) {
      console.error('❌ Error loading assets from database:', error);
      state.availableFrames = getFallbackFrames();
      state.availableStickers = getFallbackStickers();
    }
  }

  /**
   * Returns fallback frames
   * @returns {Array} - Array of fallback frame objects
   */
  function getFallbackFrames() {
    return [
      { id: 1, nama: 'Matcha Frame', file_path: '/src/assets/frame-backgrounds/matcha.jpg' },
      { id: 2, nama: 'Black Star Frame', file_path: '/src/assets/frame-backgrounds/blackStar.jpg' },
      { id: 3, nama: 'Blue Stripe Frame', file_path: '/src/assets/frame-backgrounds/blueStripe.jpg' },
    ];
  }

  /**
   * Returns fallback stickers
   * @returns {Array} - Array of fallback sticker objects
   */
  function getFallbackStickers() {
    return [{ id: 1, nama: 'Star Sticker', file_path: '/src/assets/stickers/bintang1.png' }];
  }

  // Dynamic Controls
  /**
   * Creates dynamic frame and sticker controls
   */
  async function createDynamicControls() {
    console.log('🔄 Creating dynamic controls...');

    // Create dynamic frames
    if (DOM.framesContainer) {
      DOM.framesContainer.innerHTML = '';
      state.availableFrames.forEach((frame) => {
        const frameBtn = document.createElement('button');
        frameBtn.id = `frame_${frame.id}`;
        frameBtn.className = 'dynamic-frame-btn buttonBgFrames';
        frameBtn.style.backgroundImage = `url('${frame.file_path}')`;
        frameBtn.style.backgroundSize = 'cover';
        frameBtn.style.backgroundPosition = 'center';
        frameBtn.title = frame.nama;
        frameBtn.setAttribute('data-frame-id', frame.id);
        frameBtn.setAttribute('data-frame-path', frame.file_path);

        frameBtn.addEventListener('click', () => {
          setActiveButton('.dynamic-frame-btn', frameBtn);
          state.backgroundType = 'image';
          state.backgroundImage = frame.file_path;
          redrawCanvas();
          console.log(`� Selected frame: ${frame.nama}`);
        });

        DOM.framesContainer.appendChild(frameBtn);
      });
      console.log(`✅ Created ${state.availableFrames.length} dynamic frame controls`);
    }

    // Create dynamic stickers
    if (DOM.stickersContainer) {
      const loadingPlaceholder = DOM.stickersContainer.querySelector('.loading-placeholder');
      if (loadingPlaceholder) loadingPlaceholder.remove();

      state.availableStickers.forEach((sticker) => {
        const stickerBtn = document.createElement('button');
        stickerBtn.id = `sticker_${sticker.id}`;
        stickerBtn.className = 'dynamic-sticker-btn buttonStickers';
        stickerBtn.setAttribute('data-sticker-id', sticker.id);
        stickerBtn.setAttribute('data-sticker-path', sticker.file_path);

        const img = document.createElement('img');
        img.src = sticker.file_path;
        img.alt = sticker.nama;
        img.className = 'sticker-icon';
        img.style.width = '40px';
        img.style.height = '40px';
        img.style.objectFit = 'contain';

        stickerBtn.appendChild(img);

        stickerBtn.addEventListener('click', () => {
          setActiveButton('.buttonStickers', stickerBtn);
          state.selectedSticker = sticker.file_path;
          redrawCanvas();
          console.log(`� Selected sticker: ${sticker.nama}`);
        });

        DOM.stickersContainer.appendChild(stickerBtn);
      });
      console.log(`✅ Created ${state.availableStickers.length} dynamic sticker controls`);
    }

    // Initialize "None" sticker button
    if (DOM.noneSticker) {
      DOM.noneSticker.addEventListener('click', () => {
        setActiveButton('.buttonStickers', DOM.noneSticker);
        state.selectedSticker = null;
        redrawCanvas();
        console.log('🚫 No sticker selected');
      });
    }
  }

    // Fallback frames if database fails
    function getFallbackFrames() {
        return [
            { id: 'fallback-1', nama: 'Pink', warna: '#FFB6C1' },
            { id: 'fallback-2', nama: 'Blue', warna: '#87CEEB' },
            { id: 'fallback-3', nama: 'Yellow', warna: '#FFFFE0' },
            { id: 'fallback-4', nama: 'White', warna: '#FFFFFF' }
        ];
    }

    // Fallback stickers if database fails
    function getFallbackStickers() {
        return [
            { id: 'fallback-sticker-1', nama: 'Star', file_path: '/src/assets/stickers/bintang1.png' }
        ];
    }

  // Photo Management
  /**
   * Loads photos from server
   */
  async function loadPhotos() {
    try {
      console.log('� Loading photos from server...');
      const response = await fetch('../api-fetch/get_photos.php');
      
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      
      const data = await response.json();
      
      if (!data.success || !data.photos || !Array.isArray(data.photos)) {
        throw new Error(data.message || 'Invalid photos data received');
      }

      state.storedImages = data.photos;
      console.log(`✅ Loaded ${state.storedImages.length} photos:`, state.storedImages);
      
      return state.storedImages;
    } catch (error) {
      console.error('❌ Failed to load photos:', error);
      handleError('Failed to load photos: ' + error.message);
      return [];
    }
  }

  // Canvas Management
  /**
   * Initializes the canvas
   */
  function initializeCanvas() {
    if (!state.storedImages || !state.storedImages.length) {
      console.error('❌ No images available for canvas');
      handleError('No images available for canvas');
      return;
    }
    console.log('🎨 Initializing canvas...');
    redrawCanvas();
    console.log('✅ Canvas initialized');
  }

  // Control Initialization
  /**
   * Initializes all control buttons and event listeners
   */
  function initializeControls() {
    console.log('🎛️ Initializing controls...');
    initializeFrameControls();
    initializeBackgroundFrameControls();
    initializeShapeControls();
    initializeActionButtons();
    initializeEmailModal();
    console.log('✅ Controls initialized');
  }

  /**
   * Initializes frame color controls
   */
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
          setActiveButton('.buttonFrames', element);
          state.backgroundColor = color;
          state.backgroundType = 'color';
          state.backgroundImage = null;
          redrawCanvas();
          console.log(`🎨 Selected frame color: ${color}`);
        });
      }
    });
  }

  /**
   * Initializes background frame controls
   */
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
          setActiveButton('.buttonBgFrames', element);
          console.log(`🎨 Setting background: ${id} (${src})`);
          state.backgroundType = 'image';
          state.backgroundImage = src;
          redrawCanvas();
        });
      }
    });
  }

  /**
   * Initializes shape controls
   */
  function initializeShapeControls() {
    const shapeButtons = [
      { id: 'noneFrameShape', shape: 'default' },
      { id: 'softFrameShape', shape: 'rounded' }
    ];

    shapeButtons.forEach(({ id, shape }) => {
      const element = document.getElementById(id);
      if (element) {
        element.addEventListener('click', () => {
          setActiveButton('.buttonShapes', element);
          state.selectedShape = shape;
          redrawCanvas();
          console.log(`🔸 Selected shape: ${shape}`);
        });
      }
    });
  }

  /**
   * Initializes action buttons
   */
  function initializeActionButtons() {
    const buttons = {
      emailBtn: () => {
        if (state.emailSent) {
          alert('Email sudah pernah dikirim!');
          return;
        }
        if (state.finalCanvas) {
          showEmailModal();
        } else {
          alert('Tidak ada gambar untuk dikirim ke email');
        }
      },
      printBtn: () => {
        if (state.printUsed) {
          alert('Print sudah pernah digunakan!');
          return;
        }
        if (state.finalCanvas) {
          showSimplePrintPopup(state.finalCanvas.toDataURL('image/jpeg', 1.0));
        } else {
          alert('Tidak ada gambar untuk di-print');
        }
      },
      continueBtn: () => window.location.href = 'thankyou.php'
    };

    Object.entries(buttons).forEach(([id, handler]) => {
      const button = document.getElementById(id);
      if (button) {
        button.addEventListener('click', handler);
        console.log(`✅ Button initialized: ${id}`);
      }
    });
  }

  /**
   * Shows the email modal
   */
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
      handleError('Email modal not found');
    }
  }

  /**
   * Initializes email modal functionality
   */
  function initializeEmailModal() {
    const emailModal = document.getElementById('emailModal');
    const closeEmailModal = document.getElementById('closeEmailModal');
    const cancelEmailBtn = document.getElementById('cancelEmailBtn');
    const sendEmailBtn = document.getElementById('sendEmailBtn');
    const emailInput = document.getElementById('emailInput');

    const closeModal = () => {
      emailModal.style.display = 'none';
      if (emailInput) emailInput.value = '';
    };

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
    console.log('✅ Email modal initialized');
  }

  /**
   * Initializes virtual keyboard
   */
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

    if (emailInput) {
      emailInput.addEventListener('click', () => setTimeout(() => emailInput.focus(), 10));
    }
    
    const virtualKeyboard = document.getElementById('virtualKeyboard');
    if (virtualKeyboard) {
      virtualKeyboard.addEventListener('mousedown', e => e.preventDefault());
    }

    function handleKeyPress(key, input) {
      if (!input) return;
      
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
            state.emailSent = true;
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

  // Canvas Rendering
  /**
   * Redraws the canvas with current settings
   * Layout 5 specific calculations preserved
   */
  function redrawCanvas() {
    if (!state.storedImages || !state.storedImages.length) {
      console.warn('⚠️ No images available for redraw');
      return;
    }

    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    
    // Layout 5 specific dimensions - DO NOT CHANGE
    const canvasWidth = 1200;
    const canvasHeight = 1800;
    const borderWidth = 62;
    const marginTop = 120;
    const spacing = 37;
    const centerSpacing = 124;
    const expectedPhotos = 6;

    // Area yang tersedia untuk foto (dikurangi border kiri-kanan dan margin atas)
    const availableWidth = canvasWidth - (borderWidth * 2);
    const availableHeight = canvasHeight - marginTop - borderWidth;

    // Lebar setiap kolom (dikurangi spasi tengah, dibagi 2)
    const columnWidth = (availableWidth - centerSpacing) / 2;

    // Posisi x untuk kolom
    const leftColumnX = borderWidth;
    const rightColumnX = borderWidth + columnWidth + centerSpacing;

    // Menghitung tinggi foto
    const largePhotoHeight = (availableHeight - spacing * 2) / 2;
    const smallPhotoHeight = (largePhotoHeight - spacing * 2) / 3;

    canvas.width = canvasWidth;
    canvas.height = canvasHeight;
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    if (state.backgroundType === 'color') {
      ctx.fillStyle = state.backgroundColor;
      ctx.fillRect(0, 0, canvas.width, canvas.height);
      drawPhotos();
    } else if (state.backgroundImage) {
      const bgImg = new Image();
      bgImg.crossOrigin = 'anonymous';
      bgImg.onload = () => {
        ctx.drawImage(bgImg, 0, 0, canvas.width, canvas.height);
        drawPhotos();
        updateCanvasPreview(canvas);
      };
      bgImg.onerror = () => {
        console.error('❌ Failed to load background image:', state.backgroundImage);
        ctx.fillStyle = '#FFFFFF';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        drawPhotos();
        updateCanvasPreview(canvas);
      };
      bgImg.src = state.backgroundImage;
      return;
    } else {
      ctx.fillStyle = '#FFFFFF';
      ctx.fillRect(0, 0, canvas.width, canvas.height);
      drawPhotos();
    }

    function drawPhotos() {
      if (state.storedImages.length < expectedPhotos) {
        console.warn(`⚠️ Layout requires ${expectedPhotos} photos, found: ${state.storedImages.length}`);
      }

      const imagesToProcess = Math.min(state.storedImages.length, expectedPhotos);
      let loadedCount = 0;

      state.storedImages.slice(0, imagesToProcess).forEach((imageData, index) => {
        const img = new Image();
        img.crossOrigin = 'anonymous';
        img.onload = () => {
          const positions = [
            // Layout 5 specific positions - DO NOT CHANGE THESE CALCULATIONS
            { x: leftColumnX, y: marginTop, width: columnWidth, height: largePhotoHeight },
            { x: leftColumnX, y: marginTop + largePhotoHeight + spacing, width: columnWidth, height: smallPhotoHeight },
            { x: leftColumnX, y: marginTop + largePhotoHeight + spacing + smallPhotoHeight + spacing, width: columnWidth, height: smallPhotoHeight },
            { x: rightColumnX, y: marginTop, width: columnWidth, height: smallPhotoHeight },
            { x: rightColumnX, y: marginTop + smallPhotoHeight + spacing, width: columnWidth, height: smallPhotoHeight },
            { x: rightColumnX, y: marginTop + smallPhotoHeight + spacing + smallPhotoHeight + spacing, width: columnWidth, height: largePhotoHeight }
          ];
          
          drawCroppedImage(ctx, img, positions[index], state.selectedShape);
          loadedCount++;
          
          if (loadedCount === imagesToProcess) {
            drawStickersAndLogos(ctx, canvas);
            updateCanvasPreview(canvas);
          }
        };
        
        img.onerror = () => {
          console.error(`❌ Failed to load image ${index}:`, imageData);
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

  /**
   * Draws cropped image with Layout 5 specific calculations
   */
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

  /**
   * Draws photo with specified shape
   */
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

  /**
   * Draws rounded rectangle path
   */
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

  /**
   * Draws stickers and logos on canvas
   */
  function drawStickersAndLogos(ctx, canvas) {
    if (state.selectedSticker) {
      const stickerImg = new Image();
      stickerImg.crossOrigin = 'anonymous';
      stickerImg.onload = () => {
        ctx.drawImage(stickerImg, 0, 0, canvas.width, canvas.height);
        console.log('✅ Sticker applied');
      };
      stickerImg.onerror = () => {
        console.error('❌ Failed to load sticker:', state.selectedSticker);
        handleError('Failed to load sticker');
      };
      stickerImg.src = state.selectedSticker;
    }

    // Logo drawing
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
        handleError('Failed to load logo');
      };
      logoImg.src = '/src/assets/logo.png';
    }
  }

  /**
   * Updates canvas preview in DOM
   */
  function updateCanvasPreview(canvas) {
    if (DOM.photoCustomPreview) {
      DOM.photoCustomPreview.innerHTML = '';
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
      DOM.photoCustomPreview.appendChild(canvas);
      console.log('✅ Canvas preview updated');
    }
    state.finalCanvas = canvas;
  }

  // Print Functions
  /**
   * Shows print popup with canvas image
   */
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
                    state.printUsed = true;
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
});