document.addEventListener('DOMContentLoaded', () => {
  // ⚡ COMPRESSION CONFIGURATION - 3-Level Quality System
  const COMPRESSION_CONFIG = {
    SESSION_QUALITY: 0.85,       SESSION_MAX_WIDTH: 1600,     SESSION_MAX_HEIGHT: 1200,
    DOWNLOAD_QUALITY: 0.95,      DOWNLOAD_MAX_WIDTH: 2400,    DOWNLOAD_MAX_HEIGHT: 1800,
    THUMB_QUALITY: 0.6,          THUMB_MAX_WIDTH: 400,        THUMB_MAX_HEIGHT: 300
  };

  // 🚀 FAST COMPRESSION FUNCTION
  function compressImage(imageData, mode = 'session') {
    return new Promise((resolve, reject) => {
      const img = new Image();
      img.crossOrigin = 'anonymous';
      
      img.onload = () => {
        try {
          const canvas = document.createElement('canvas');
          const ctx = canvas.getContext('2d');
          
          const settings = getCompressionSettings(mode);
          
          // Calculate dimensions maintaining aspect ratio
          let { width, height } = img;
          const aspectRatio = width / height;
          
          if (width > settings.maxWidth) {
            width = settings.maxWidth;
            height = width / aspectRatio;
          }
          
          if (height > settings.maxHeight) {
            height = settings.maxHeight;
            width = height * aspectRatio;
          }
          
          canvas.width = width;
          canvas.height = height;
          
          // High quality settings for better compression
          ctx.imageSmoothingEnabled = true;
          ctx.imageSmoothingQuality = 'high';
          
          // Draw and compress
          ctx.drawImage(img, 0, 0, width, height);
          
          const compressedDataUrl = canvas.toDataURL('image/jpeg', settings.quality);
          
          console.log(`✅ Image compressed for ${mode}:`, {
            original: `${img.width}x${img.height}`,
            compressed: `${width}x${height}`,
            quality: settings.quality
          });
          
          resolve(compressedDataUrl);
        } catch (error) {
          reject(error);
        }
      };
      
      img.onerror = () => reject(new Error('Failed to load image for compression'));
      img.src = imageData;
    });
  }

  function getCompressionSettings(mode) {
    switch (mode) {
      case 'download':
        return {
          quality: COMPRESSION_CONFIG.DOWNLOAD_QUALITY,
          maxWidth: COMPRESSION_CONFIG.DOWNLOAD_MAX_WIDTH,
          maxHeight: COMPRESSION_CONFIG.DOWNLOAD_MAX_HEIGHT
        };
      case 'thumb':
        return {
          quality: COMPRESSION_CONFIG.THUMB_QUALITY,
          maxWidth: COMPRESSION_CONFIG.THUMB_MAX_WIDTH,
          maxHeight: COMPRESSION_CONFIG.THUMB_MAX_HEIGHT
        };
      default: // session
        return {
          quality: COMPRESSION_CONFIG.SESSION_QUALITY,
          maxWidth: COMPRESSION_CONFIG.SESSION_MAX_WIDTH,
          maxHeight: COMPRESSION_CONFIG.SESSION_MAX_HEIGHT
        };
    }
  }

  // Configuration constants - Layout5 specific (6 photos, asymmetric layout)
  const CONFIG = {
    CANVAS_WIDTH: 1224,
    CANVAS_HEIGHT: 1836,
    BORDER_WIDTH: 62,
    MARGIN_TOP: 120,
    SPACING: 37,
    PHOTO_WIDTH: 477,
    PHOTO_HEIGHT: 567,
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
    imageCache: new Map(),
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
      await loadAssetsFromDatabase();
      await createDynamicControls();
      await loadPhotos();
      initializeCanvas();
      initializeControls();
      console.log('✅ App initialization complete');
    } catch (error) {
      console.error('❌ Error initializing app:', error);
      handleError('Gagal memuat foto. Redirecting...');
      window.location.href = 'selectlayout.php';
    }
  }

  // Load assets from database
  /**
   * Loads frames and stickers from database
   */
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
          state.availableFrames = framesData.data;
          console.log(`✅ Loaded ${state.availableFrames.length} frames from database:`, state.availableFrames);
        } else {
          console.warn('⚠️ Invalid frames data structure, using fallback');
          state.availableFrames = getFallbackFrames();
        }
      } else {
        console.warn('⚠️ Failed to load frames from database, using fallback');
        state.availableFrames = getFallbackFrames();
      }

      if (stickersResponse.ok) {
        const stickersData = await stickersResponse.json();
        console.log('🔍 Stickers data received:', stickersData);
        
        if (stickersData.success && stickersData.data && Array.isArray(stickersData.data)) {
          state.availableStickers = stickersData.data;
          console.log(`✅ Loaded ${state.availableStickers.length} stickers from database:`, state.availableStickers);
        } else {
          console.warn('⚠️ Invalid stickers data structure, using fallback');
          state.availableStickers = getFallbackStickers();
        }
      } else {
        console.warn('⚠️ Failed to load stickers from database, using fallback');
        state.availableStickers = getFallbackStickers();
      }

    } catch (error) {
      console.error('❌ Error loading assets from database:', error);
      console.log('🔄 Using fallback assets...');
      state.availableFrames = getFallbackFrames();
      state.availableStickers = getFallbackStickers();
    }
    
    console.log(`🎯 Final frames count: ${state.availableFrames.length}`);
    console.log(`🎯 Final stickers count: ${state.availableStickers.length}`);
    
    // Ensure we have at least fallback data
    if (state.availableFrames.length === 0) {
      console.warn('⚠️ No frames available, forcing fallback');
      state.availableFrames = getFallbackFrames();
    }
    
    if (state.availableStickers.length === 0) {
      console.warn('⚠️ No stickers available, forcing fallback');
      state.availableStickers = getFallbackStickers();
    }
  }

  // Create dynamic controls for frames and stickers
  /**
   * Creates dynamic controls for frames and stickers
   */
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
      
      console.log(`🔄 Creating ${state.availableFrames.length} frame buttons...`);
      state.availableFrames.forEach((frame, index) => {
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
        setActiveButton('.buttonBgFrames, .buttonFrames', button);
        
        if (frame.file_path) {
          state.backgroundType = 'image';
          state.backgroundImage = frame.file_path;
          state.backgroundColor = null;
        } else if (frame.warna) {
          state.backgroundType = 'color';
          state.backgroundColor = frame.warna;
          state.backgroundImage = null;
        }
        
        redrawCanvas();
        console.log(`🎯 Selected frame: ${frame.nama}`);
      });
      
      framesContainer.appendChild(button);
      console.log(`✅ Created frame button: ${frame.nama}`);
    });
    
    console.log(`✅ Created ${state.availableFrames.length} frame controls`);
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
          setActiveButton('.buttonStickers', noneButton);
          
          state.selectedSticker = null;
          redrawCanvas();
          console.log('🎯 No sticker selected');
        });
      }
      
      // Add database stickers
      state.availableStickers.forEach((sticker, index) => {
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
          setActiveButton('.buttonStickers', button);
          state.selectedSticker = sticker.file_path;
          redrawCanvas();
          console.log(`🎯 Selected sticker: ${sticker.nama}`);
        });
        
        stickersContainer.appendChild(button);
      });
      
      console.log(`✅ Created ${state.availableStickers.length} sticker controls`);
    } else {
      console.error('❌ No stickers container found with any of these IDs:', possibleStickerContainers);
    }
    
    // Mark containers as loaded
    if (framesContainer) framesContainer.classList.add('assets-loaded');
    if (stickersContainer) stickersContainer.classList.add('assets-loaded');
    
    console.log('✅ Dynamic controls creation complete');
  }

  // Fallback Data
  /**
   * Returns fallback frames if database fails
   */
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

  /**
   * Returns fallback stickers if database fails
   */
  function getFallbackStickers() {
    return [
      { id: 'fallback-sticker-1', nama: 'Star', file_path: '/src/assets/stickers/bintang1.png' }
    ];
  }

  // Photo Loading
  /**
   * Loads photos from server session
   */
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
      
      state.storedImages = data.photos;
      console.log(`✅ Loaded ${state.storedImages.length} images:`, state.storedImages);
    } catch (error) {
      console.error('❌ Error loading photos:', error.message);
      throw error; // Re-throw to be handled by initializeApp
    }
  }

  // Canvas Management
  /**
   * Initializes the canvas with loaded images
   */
  function initializeCanvas() {
    if (!state.storedImages.length) {
      console.error('❌ No images available for canvas');
      return;
    }
    console.log('🎨 Initializing canvas...');
    redrawCanvas();
    console.log('✅ Canvas initialized');
  }

  // Controls Initialization
  /**
   * Initializes all control systems
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
          state.backgroundColor = color;
          state.backgroundType = 'color';
          state.backgroundImage = null;
          redrawCanvas();
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
          console.log(`🎨 Setting background: ${id} (${src})`);
          state.backgroundType = 'image';
          state.backgroundImage = src;
          state.backgroundColor = null;
          redrawCanvas();
        });
      } else {
        console.warn(`⚠️ Background frame button not found: ${id}`);
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
          state.selectedShape = shape;
          redrawCanvas();
        });
      }
    });
  }

  /**
   * Updates continue button state based on email and print completion
   */
  function updateContinueButtonState() {
    const continueBtn = document.getElementById('continueBtn');
    if (!continueBtn) return;
    
    const canContinue = state.emailSent && state.printUsed;
    
    if (canContinue) {
      continueBtn.innerHTML = '<i class="fas fa-arrow-right"></i> Lanjut ke Terima Kasih';
      continueBtn.disabled = false;
      continueBtn.style.opacity = '1';
      continueBtn.style.cursor = 'pointer';
      continueBtn.classList.remove('disabled');
    } else {
      const missingActions = [];
      if (!state.emailSent) missingActions.push('Email');
      if (!state.printUsed) missingActions.push('Print');
      
      continueBtn.innerHTML = `⏳ ${missingActions.join(' & ')} Dulu`;
      continueBtn.disabled = true;
      continueBtn.style.opacity = '0.5';
      continueBtn.style.cursor = 'not-allowed';
      continueBtn.classList.add('disabled');
    }
  }

  /**
   * Initializes action buttons
   */
  function initializeActionButtons() {
    const buttons = {
      emailBtn: () => {
        if (state.emailSent) {
          handleError('Email sudah pernah dikirim!');
          return;
        }
        if (state.finalCanvas) {
          showEmailModal();
        } else {
          handleError('Tidak ada gambar untuk dikirim ke email');
        }
      },
      printBtn: () => {
        if (state.printUsed) {
          handleError('Print sudah pernah digunakan!');
          return;
        }
        if (state.finalCanvas) {
          showSimplePrintPopup(state.finalCanvas.toDataURL('image/jpeg', 1.0));
        } else {
          handleError('Tidak ada gambar untuk di-print');
        }
      },
      continueBtn: () => {
        // Check if both email and print are completed
        if (!state.emailSent || !state.printUsed) {
          const missing = [];
          if (!state.emailSent) missing.push('mengirim email');
          if (!state.printUsed) missing.push('print foto');
          
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

  // Email Modal Management
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
    }
  }

  /**
   * Initializes email modal events
   */
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

  // Virtual Keyboard Management
  /**
   * Initializes virtual keyboard functionality
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

  // Validation Functions
  /**
   * Shows validation error message
   */
  function showValidationError(message) {
    const validationDiv = document.querySelector('.input-validation');
    const validationMessage = document.getElementById('validation-message');
    if (validationDiv && validationMessage) {
      validationMessage.textContent = message;
      validationDiv.style.display = 'block';
    }
  }

  /**
   * Hides validation error message
   */
  function hideValidationError() {
    const validationDiv = document.querySelector('.input-validation');
    if (validationDiv) validationDiv.style.display = 'none';
  }

  /**
   * Validates email format
   */
  function validateEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  }

  // Email Sending
  /**
   * Sends photo via email
   */
  async function sendPhotoEmail(email) {
    if (!state.finalCanvas) {
      handleError('Tidak ada foto untuk dikirim');
      return;
    }

    const sendBtn = document.getElementById('sendEmailBtn');
    const originalHtml = sendBtn.innerHTML;
    sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan & Mengirim...';
    sendBtn.disabled = true;

    try {
      const blob = await new Promise(resolve => state.finalCanvas.toBlob(resolve, 'image/png'));
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

      await emailjs.send(CONFIG.EMAILJS_SERVICE_ID, CONFIG.EMAILJS_TEMPLATE_ID, emailParams);
      
      // Mark email as sent and disable button
      state.emailSent = true;
      const emailBtn = document.getElementById('emailBtn');
      if (emailBtn) {
        emailBtn.disabled = true;
        emailBtn.style.opacity = '0.5';
        emailBtn.style.cursor = 'not-allowed';
        emailBtn.innerHTML = '✅ Email Terkirim';
      }
      
      // Update continue button state
      updateContinueButtonState();
      
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

  // Canvas Drawing Functions
  /**
   * Redraws the canvas with current settings
   */
  function redrawCanvas() {
    if (!state.storedImages.length) {
      console.warn('⚠️ No images available for redraw');
      return;
    }

    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    
    // Area yang tersedia untuk foto (dikurangi border kiri-kanan dan margin atas)
    const availableWidth = CONFIG.CANVAS_WIDTH - (CONFIG.BORDER_WIDTH * 2);
    const availableHeight = CONFIG.CANVAS_HEIGHT - CONFIG.MARGIN_TOP - CONFIG.BORDER_WIDTH;

    // Lebar setiap kolom (dikurangi spasi tengah, dibagi 2)
    const columnWidth = (availableWidth - 124) / 2; // 124 is center spacing
    const centerSpacing = 124;

    // Posisi x untuk kolom
    const leftColumnX = CONFIG.BORDER_WIDTH;
    const rightColumnX = CONFIG.BORDER_WIDTH + columnWidth + centerSpacing;

    // Menghitung tinggi foto
    const largePhotoHeight = (availableHeight - CONFIG.SPACING * 2) / 2;
    const smallPhotoHeight = (largePhotoHeight - CONFIG.SPACING * 2) / 3;

    canvas.width = CONFIG.CANVAS_WIDTH;
    canvas.height = CONFIG.CANVAS_HEIGHT;
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
      if (state.storedImages.length < CONFIG.EXPECTED_PHOTOS) {
        console.warn(`⚠️ Layout requires ${CONFIG.EXPECTED_PHOTOS} photos, found: ${state.storedImages.length}`);
      }

      const imagesToProcess = Math.min(state.storedImages.length, CONFIG.EXPECTED_PHOTOS);
      let loadedCount = 0;

      state.storedImages.slice(0, imagesToProcess).forEach((imageData, index) => {
        const img = new Image();
        img.crossOrigin = 'anonymous';
        img.onload = () => {
          const positions = [
            { x: leftColumnX, y: CONFIG.MARGIN_TOP, width: columnWidth, height: largePhotoHeight },
            { x: leftColumnX, y: CONFIG.MARGIN_TOP + largePhotoHeight + CONFIG.SPACING, width: columnWidth, height: smallPhotoHeight },
            { x: leftColumnX, y: CONFIG.MARGIN_TOP + largePhotoHeight + CONFIG.SPACING + smallPhotoHeight + CONFIG.SPACING, width: columnWidth, height: smallPhotoHeight },
            { x: rightColumnX, y: CONFIG.MARGIN_TOP, width: columnWidth, height: smallPhotoHeight },
            { x: rightColumnX, y: CONFIG.MARGIN_TOP + smallPhotoHeight + CONFIG.SPACING, width: columnWidth, height: smallPhotoHeight },
            { x: rightColumnX, y: CONFIG.MARGIN_TOP + smallPhotoHeight + CONFIG.SPACING + smallPhotoHeight + CONFIG.SPACING, width: columnWidth, height: largePhotoHeight }
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
   * Draws a cropped image at specified position
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
   * Creates rounded rectangle path
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
      stickerImg.onload = () => ctx.drawImage(stickerImg, 0, 0, canvas.width, canvas.height);
      stickerImg.onerror = () => console.error('❌ Failed to load sticker:', state.selectedSticker);
      stickerImg.src = state.selectedSticker;
    }

    const logoBtn = document.getElementById('engLogo');
    if (logoBtn && logoBtn.classList.contains('active')) {
      const logoImg = new Image();
      logoImg.crossOrigin = 'anonymous';
      logoImg.onload = () => ctx.drawImage(logoImg, 20, canvas.height - 60, 100, 40);
      logoImg.onerror = () => console.error('❌ Failed to load logo:', CONFIG.LOGO_SRC);
      logoImg.src = CONFIG.LOGO_SRC;
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
    }
    state.finalCanvas = canvas;
  }

  // Print Management
  /**
   * Shows simple print popup
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
          state.printUsed = true;
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
    popup.addEventListener('click', (e) => e.target === popup && popup.remove());
    document.addEventListener('keydown', function escapeHandler(e) {
      if (e.key === 'Escape') {
        popup.remove();
        document.removeEventListener('keydown', escapeHandler);
      }
    });
  }

  // Start the application
  initializeApp();
});