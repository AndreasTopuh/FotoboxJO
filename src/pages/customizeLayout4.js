/*
 * ⚡ CUSTOMIZE LAYOUT 4 - LAYOUT-SPECIFIC FRAMES VERSION
 * ✅ Updated: Uses frames specific to Layout 4 only
 * 📝 API: /src/api-fetch/get-frames-by-layout.php?layout_id=4
 */
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

  // Configuration constants
  const CONFIG = {
    CANVAS_WIDTH: 1224,
    CANVAS_HEIGHT: 1836,
    BORDER_WIDTH: 62,
    MARGIN_TOP: 120,
    SPACING: 37,
    PHOTO_WIDTH: 477,
    PHOTO_HEIGHT: 300,
    EXPECTED_PHOTOS: 8,
    EMAILJS_SERVICE_ID: 'service_gtqjb2j',
    EMAILJS_TEMPLATE_ID: 'template_pp5i4hm',
    LOGO_SRC: '/src/assets/logo.png',
  };

  // State variables
  let state = {
    storedImages: [],
    finalCanvas: null,
    selectedSticker: null,
    selectedFrameSticker: null,
    selectedShape: 'default',
    backgroundType: 'color',
    backgroundColor: '#FFFFFF',
    backgroundImage: null,
    availableFrames: [],
    availableStickers: [],
    availableFrameStickers: [],
    emailSent: false,
    printUsed: false,
    imageCache: new Map(),
  };

  // DOM Elements
  const DOM = {
    photoCustomPreview: document.getElementById('photoPreview'),
    framesContainer: document.getElementById('dynamicFramesContainer'),
    stickersContainer: document.getElementById('dynamicStickersContainer'),
    frameStickerContainer: document.getElementById('dynamicFrameStickerContainer'),
    noneSticker: document.getElementById('noneSticker'),
    noneFrameSticker: document.getElementById('noneFrameSticker'),
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
   * Loads frames and stickers from database - Layout 4 specific
   */
  async function loadAssetsFromDatabase() {
    console.log('🔄 Loading Layout 4 frames and stickers from database...');
    try {
      const [framesResponse, stickersResponse, comboResponse] = await Promise.all([
        fetch('../api-fetch/get-frames-by-layout.php?layout_id=4'),
        fetch('../api-fetch/get-stickers-by-layout.php?layout_id=4'),
        fetch('../api-fetch/get-frame-sticker-combo.php?layout_id=4')
      ]);

      const framesData = await framesResponse.json();
      const stickersData = await stickersResponse.json();
      const comboData = await comboResponse.json();

      state.availableFrames = framesData.success ? framesData.frames : getFallbackFrames();
      console.log(`✅ Loaded ${state.availableFrames.length} Layout 4 frames from database`);

      state.availableStickers = stickersData.success ? stickersData.data : getFallbackStickers();
      console.log(`✅ Loaded ${state.availableStickers.length} Layout 4 stickers from database`);
      
      state.availableFrameStickers = comboData.success ? comboData.data : [];
      console.log(`✅ Loaded ${state.availableFrameStickers.length} Layout 4 frame & sticker combos from database`);
    } catch (error) {
      console.error('❌ Error loading assets from database:', error);
      state.availableFrames = getFallbackFrames();
      state.availableStickers = getFallbackStickers();
      state.availableFrameStickers = [];
      console.log('🔄 Using fallback assets');
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
          console.log(`🎨 Selected frame: ${frame.nama}`);
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
          state.selectedFrameSticker = null; // Clear frame-sticker combo
          redrawCanvas();
          console.log(`🌟 Selected sticker: ${sticker.nama}`);
        });

        DOM.stickersContainer.appendChild(stickerBtn);
      });
      console.log(`✅ Created ${state.availableStickers.length} dynamic sticker controls`);
    }

    // Create dynamic frame & sticker combos
    if (DOM.frameStickerContainer) {
      const loadingPlaceholder = DOM.frameStickerContainer.querySelector('.loading-placeholder');
      if (loadingPlaceholder) loadingPlaceholder.remove();

      state.availableFrameStickers.forEach((frameSticker) => {
        const frameStickerBtn = document.createElement('button');
        frameStickerBtn.type = 'button'; // Prevent form submission
        frameStickerBtn.id = `frameSticker_${frameSticker.id}`;
        frameStickerBtn.className = 'dynamic-frame-sticker-btn buttonFrameStickers';
        frameStickerBtn.setAttribute('data-frame-sticker-id', frameSticker.id);
        frameStickerBtn.setAttribute('data-frame-sticker-path', frameSticker.file_path);

        const img = document.createElement('img');
        img.src = frameSticker.file_path;
        img.alt = frameSticker.nama;
        img.className = 'frame-sticker-icon';
        img.style.width = '60px';
        img.style.height = '60px';
        img.style.objectFit = 'contain';

        frameStickerBtn.appendChild(img);

        frameStickerBtn.addEventListener('click', (e) => {
          e.preventDefault(); // Prevent any form submission
          e.stopPropagation(); // Prevent event bubbling
          setActiveButton('.buttonFrameStickers', frameStickerBtn);
          state.selectedFrameSticker = frameSticker.file_path;
          state.selectedSticker = null; // Clear regular sticker
          redrawCanvas();
          console.log(`🎪 Selected frame & sticker combo: ${frameSticker.nama}`);
        });

        DOM.frameStickerContainer.appendChild(frameStickerBtn);
      });
      console.log(`✅ Created ${state.availableFrameStickers.length} dynamic frame & sticker combo controls`);
    }

    // Initialize "None" sticker button
    if (DOM.noneSticker) {
      DOM.noneSticker.addEventListener('click', (e) => {
        e.preventDefault(); // Prevent any form submission
        setActiveButton('.buttonStickers', DOM.noneSticker);
        state.selectedSticker = null;
        redrawCanvas();
        console.log('🚫 No sticker selected');
      });
    }

    // Initialize "None" frame & sticker button
    if (DOM.noneFrameSticker) {
      DOM.noneFrameSticker.addEventListener('click', (e) => {
        e.preventDefault(); // Prevent any form submission
        setActiveButton('.buttonFrameStickers', DOM.noneFrameSticker);
        state.selectedFrameSticker = null;
        redrawCanvas();
        console.log('🚫 No frame & sticker combo selected');
      });
    }
  }

  // Photo Loading
  /**
   * Loads photos from server
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
   * Initializes the canvas
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

  /**
   * Redraws the canvas with current settings
   */
  async function redrawCanvas() {
    if (!state.storedImages.length) {
      console.warn('⚠️ No images available for redraw');
      return;
    }

    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    canvas.width = CONFIG.CANVAS_WIDTH;
    canvas.height = CONFIG.CANVAS_HEIGHT;
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    if (state.backgroundType === 'color') {
      ctx.fillStyle = state.backgroundColor;
      ctx.fillRect(0, 0, canvas.width, canvas.height);
      await drawPhotos(ctx, canvas);
    } else if (state.backgroundImage) {
      try {
        const bgImg = await loadImage(state.backgroundImage);
        ctx.drawImage(bgImg, 0, 0, canvas.width, canvas.height);
        await drawPhotos(ctx, canvas);
        updateCanvasPreview(canvas);
      } catch (error) {
        console.error('❌ Failed to load background image:', state.backgroundImage);
        ctx.fillStyle = '#FFFFFF';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        await drawPhotos(ctx, canvas);
        updateCanvasPreview(canvas);
      }
    } else {
      ctx.fillStyle = '#FFFFFF';
      ctx.fillRect(0, 0, canvas.width, canvas.height);
      await drawPhotos(ctx, canvas);
    }
  }

  /**
   * Draws photos on the canvas
   * @param {CanvasRenderingContext2D} ctx - Canvas context
   * @param {HTMLCanvasElement} canvas - Canvas element
   */
  async function drawPhotos(ctx, canvas) {
    if (state.storedImages.length < CONFIG.EXPECTED_PHOTOS) {
      console.warn(`⚠️ Layout requires ${CONFIG.EXPECTED_PHOTOS} photos, found: ${state.storedImages.length}`);
    }

    const imagesToProcess = Math.min(state.storedImages.length, CONFIG.EXPECTED_PHOTOS);
    let loadedCount = 0;

    const availableWidth = CONFIG.CANVAS_WIDTH - (CONFIG.BORDER_WIDTH * 2);
    const centerSpacing = availableWidth - (CONFIG.PHOTO_WIDTH * 2);
    const leftColumnX = CONFIG.BORDER_WIDTH;
    const rightColumnX = CONFIG.BORDER_WIDTH + CONFIG.PHOTO_WIDTH + centerSpacing;

    for (const [index, imageData] of state.storedImages.slice(0, imagesToProcess).entries()) {
      try {
        const img = await loadImage(imageData);
        const positions = [
          { x: leftColumnX, y: CONFIG.MARGIN_TOP, width: CONFIG.PHOTO_WIDTH, height: CONFIG.PHOTO_HEIGHT },
          { x: leftColumnX, y: CONFIG.MARGIN_TOP + CONFIG.PHOTO_HEIGHT + CONFIG.SPACING, width: CONFIG.PHOTO_WIDTH, height: CONFIG.PHOTO_HEIGHT },
          { x: leftColumnX, y: CONFIG.MARGIN_TOP + (CONFIG.PHOTO_HEIGHT + CONFIG.SPACING) * 2, width: CONFIG.PHOTO_WIDTH, height: CONFIG.PHOTO_HEIGHT },
          { x: leftColumnX, y: CONFIG.MARGIN_TOP + (CONFIG.PHOTO_HEIGHT + CONFIG.SPACING) * 3, width: CONFIG.PHOTO_WIDTH, height: CONFIG.PHOTO_HEIGHT },
          { x: rightColumnX, y: CONFIG.MARGIN_TOP, width: CONFIG.PHOTO_WIDTH, height: CONFIG.PHOTO_HEIGHT },
          { x: rightColumnX, y: CONFIG.MARGIN_TOP + CONFIG.PHOTO_HEIGHT + CONFIG.SPACING, width: CONFIG.PHOTO_WIDTH, height: CONFIG.PHOTO_HEIGHT },
          { x: rightColumnX, y: CONFIG.MARGIN_TOP + (CONFIG.PHOTO_HEIGHT + CONFIG.SPACING) * 2, width: CONFIG.PHOTO_WIDTH, height: CONFIG.PHOTO_HEIGHT },
          { x: rightColumnX, y: CONFIG.MARGIN_TOP + (CONFIG.PHOTO_HEIGHT + CONFIG.SPACING) * 3, width: CONFIG.PHOTO_WIDTH, height: CONFIG.PHOTO_HEIGHT },
        ];
        drawCroppedImage(ctx, img, positions[index], state.selectedShape);
        loadedCount++;
        if (loadedCount === imagesToProcess) {
          await drawStickersAndLogos(ctx, canvas);
          updateCanvasPreview(canvas);
        }
      } catch (error) {
        handleError(`Failed to load image: ${imageData}`, 'console');
      }
    }
  }

  /**
   * Draws a cropped image on the canvas
   * @param {CanvasRenderingContext2D} ctx - Canvas context
   * @param {HTMLImageElement} img - Image to draw
   * @param {Object} pos - Position and size
   * @param {string} shape - Shape type
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
   * Draws a photo with specified shape
   * @param {CanvasRenderingContext2D} ctx - Canvas context
   * @param {HTMLImageElement} img - Image to draw
   * @param {number} x - X position
   * @param {number} y - Y position
   * @param {number} width - Width
   * @param {number} height - Height
   * @param {string} shape - Shape type
   * @param {number} sx - Source X
   * @param {number} sy - Source Y
   * @param {number} sWidth - Source width
   * @param {number} sHeight - Source height
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
   * Draws a rounded rectangle
   * @param {CanvasRenderingContext2D} ctx - Canvas context
   * @param {number} x - X position
   * @param {number} y - Y position
   * @param {number} width - Width
   * @param {number} height - Height
   * @param {number} radius - Corner radius
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
   * Draws stickers and logos on the canvas
   * @param {CanvasRenderingContext2D} ctx - Canvas context
   * @param {HTMLCanvasElement} canvas - Canvas element
   */
  async function drawStickersAndLogos(ctx, canvas) {
    // Draw Frame & Sticker combo (priority over regular sticker)
    if (state.selectedFrameSticker) {
      try {
        const frameStickerImg = await loadImage(state.selectedFrameSticker);
        ctx.drawImage(frameStickerImg, 0, 0, canvas.width, canvas.height);
        console.log('🎪 Frame & sticker combo applied');
      } catch (error) {
        console.error('❌ Failed to load frame & sticker combo:', state.selectedFrameSticker);
      }
    }
    // Draw regular sticker (only if no Frame & Sticker combo is selected)
    else if (state.selectedSticker) {
      try {
        const stickerImg = await loadImage(state.selectedSticker);
        ctx.drawImage(stickerImg, 0, 0, canvas.width, canvas.height);
        console.log('🌟 Regular sticker applied');
      } catch (error) {
        console.error('❌ Failed to load sticker:', state.selectedSticker);
      }
    }

    // Draw logo
    const logoBtn = document.getElementById('engLogo');
    if (logoBtn && logoBtn.classList.contains('active')) {
      try {
        const logoImg = await loadImage(CONFIG.LOGO_SRC);
        ctx.drawImage(logoImg, 20, canvas.height - 60, 100, 40);
        console.log('🏷️ Logo applied');
      } catch (error) {
        console.error('❌ Failed to load logo:', CONFIG.LOGO_SRC);
      }
    }
  }

  /**
   * Updates the canvas preview in the DOM
   * @param {HTMLCanvasElement} canvas - Canvas element
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
        margin: '0 auto',
      });
      DOM.photoCustomPreview.appendChild(canvas);
    }
    state.finalCanvas = canvas;
  }

  /**
   * Generates high quality version of the canvas for print/download
   */
  async function generateHighQualityCanvas() {
    console.log('🎨 Generating high quality canvas for print...');
    
    if (!state.storedImages.length) {
      console.warn('⚠️ No images available for high quality generation');
      return state.finalCanvas;
    }

    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    
    // Use higher resolution for print
    const printScale = 2; // 2x resolution for better print quality
    canvas.width = CONFIG.CANVAS_WIDTH * printScale;
    canvas.height = CONFIG.CANVAS_HEIGHT * printScale;
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    
    // Scale context for high resolution
    ctx.scale(printScale, printScale);

    // Apply background
    if (state.backgroundType === 'color') {
      ctx.fillStyle = state.backgroundColor;
      ctx.fillRect(0, 0, CONFIG.CANVAS_WIDTH, CONFIG.CANVAS_HEIGHT);
      await drawHighQualityPhotos(ctx, canvas, printScale);
    } else if (state.backgroundImage) {
      try {
        const bgImg = await loadImage(state.backgroundImage);
        ctx.drawImage(bgImg, 0, 0, CONFIG.CANVAS_WIDTH, CONFIG.CANVAS_HEIGHT);
        await drawHighQualityPhotos(ctx, canvas, printScale);
      } catch (error) {
        console.error('❌ Failed to load background image:', state.backgroundImage);
        ctx.fillStyle = '#FFFFFF';
        ctx.fillRect(0, 0, CONFIG.CANVAS_WIDTH, CONFIG.CANVAS_HEIGHT);
        await drawHighQualityPhotos(ctx, canvas, printScale);
      }
    } else {
      ctx.fillStyle = '#FFFFFF';
      ctx.fillRect(0, 0, CONFIG.CANVAS_WIDTH, CONFIG.CANVAS_HEIGHT);
      await drawHighQualityPhotos(ctx, canvas, printScale);
    }
    
    console.log('✅ High quality canvas generated');
    return canvas;
  }

  /**
   * Draws photos on high quality canvas
   */
  async function drawHighQualityPhotos(ctx, canvas, printScale) {
    if (state.storedImages.length < CONFIG.EXPECTED_PHOTOS) {
      console.warn(`⚠️ Layout requires ${CONFIG.EXPECTED_PHOTOS} photos, found: ${state.storedImages.length}`);
    }

    const imagesToProcess = Math.min(state.storedImages.length, CONFIG.EXPECTED_PHOTOS);
    let loadedCount = 0;

    const availableWidth = CONFIG.CANVAS_WIDTH - (CONFIG.BORDER_WIDTH * 2);
    const centerSpacing = availableWidth - (CONFIG.PHOTO_WIDTH * 2);
    const leftColumnX = CONFIG.BORDER_WIDTH;
    const rightColumnX = CONFIG.BORDER_WIDTH + CONFIG.PHOTO_WIDTH + centerSpacing;

    for (const [index, imageData] of state.storedImages.slice(0, imagesToProcess).entries()) {
      try {
        const img = await loadImage(imageData);
        const positions = [
          { x: leftColumnX, y: CONFIG.MARGIN_TOP, width: CONFIG.PHOTO_WIDTH, height: CONFIG.PHOTO_HEIGHT },
          { x: leftColumnX, y: CONFIG.MARGIN_TOP + CONFIG.PHOTO_HEIGHT + CONFIG.SPACING, width: CONFIG.PHOTO_WIDTH, height: CONFIG.PHOTO_HEIGHT },
          { x: leftColumnX, y: CONFIG.MARGIN_TOP + (CONFIG.PHOTO_HEIGHT + CONFIG.SPACING) * 2, width: CONFIG.PHOTO_WIDTH, height: CONFIG.PHOTO_HEIGHT },
          { x: leftColumnX, y: CONFIG.MARGIN_TOP + (CONFIG.PHOTO_HEIGHT + CONFIG.SPACING) * 3, width: CONFIG.PHOTO_WIDTH, height: CONFIG.PHOTO_HEIGHT },
          { x: rightColumnX, y: CONFIG.MARGIN_TOP, width: CONFIG.PHOTO_WIDTH, height: CONFIG.PHOTO_HEIGHT },
          { x: rightColumnX, y: CONFIG.MARGIN_TOP + CONFIG.PHOTO_HEIGHT + CONFIG.SPACING, width: CONFIG.PHOTO_WIDTH, height: CONFIG.PHOTO_HEIGHT },
          { x: rightColumnX, y: CONFIG.MARGIN_TOP + (CONFIG.PHOTO_HEIGHT + CONFIG.SPACING) * 2, width: CONFIG.PHOTO_WIDTH, height: CONFIG.PHOTO_HEIGHT },
          { x: rightColumnX, y: CONFIG.MARGIN_TOP + (CONFIG.PHOTO_HEIGHT + CONFIG.SPACING) * 3, width: CONFIG.PHOTO_WIDTH, height: CONFIG.PHOTO_HEIGHT },
        ];
        
        drawCroppedImage(ctx, img, positions[index], state.selectedShape);
        loadedCount++;
        
        if (loadedCount === imagesToProcess) {
          await drawHighQualityStickersAndLogos(ctx, canvas);
        }
      } catch (error) {
        console.error(`❌ Failed to load image: ${imageData}`);
      }
    }
  }

  /**
   * Draws high quality stickers and logos
   */
  async function drawHighQualityStickersAndLogos(ctx, canvas) {
    // Draw Frame & Sticker combo (priority over regular sticker)
    if (state.selectedFrameSticker) {
      try {
        const frameStickerImg = await loadImage(state.selectedFrameSticker);
        ctx.drawImage(frameStickerImg, 0, 0, CONFIG.CANVAS_WIDTH, CONFIG.CANVAS_HEIGHT);
        console.log('🎪 High quality frame & sticker combo applied');
      } catch (error) {
        console.error('❌ Failed to load high quality frame & sticker combo:', state.selectedFrameSticker);
      }
    }
    // Draw regular sticker (only if no Frame & Sticker combo is selected)
    else if (state.selectedSticker) {
      try {
        const stickerImg = await loadImage(state.selectedSticker);
        ctx.drawImage(stickerImg, 0, 0, CONFIG.CANVAS_WIDTH, CONFIG.CANVAS_HEIGHT);
        console.log('🌟 High quality regular sticker applied');
      } catch (error) {
        console.error('❌ Failed to load high quality sticker:', state.selectedSticker);
      }
    }

    // Draw logo
    const logoBtn = document.getElementById('engLogo');
    if (logoBtn && logoBtn.classList.contains('active')) {
      try {
        const logoImg = await loadImage(CONFIG.LOGO_SRC);
        ctx.drawImage(logoImg, 20, CONFIG.CANVAS_HEIGHT - 60, 100, 40);
        console.log('🏷️ High quality logo applied');
      } catch (error) {
        console.error('❌ Failed to load high quality logo:', CONFIG.LOGO_SRC);
      }
    }
  }

  // Control Initialization
  /**
   * Initializes all controls
   */
  function initializeControls() {
    console.log('🎛️ Initializing controls...');
    initializeFrameControls();
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
      { id: 'blackBtnFrame', color: '#000000' },
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
   * Initializes shape controls
   */
  function initializeShapeControls() {
    const shapeButtons = [
      { id: 'noneFrameShape', shape: 'default' },
      { id: 'softFrameShape', shape: 'rounded' },
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
   * Updates continue button state based on requirements
   */
  function updateContinueButtonState() {
    const continueBtn = document.getElementById('continueBtn');
    if (!continueBtn) return;

    const canContinue = state.emailSent && state.printUsed;
    
    if (canContinue) {
      continueBtn.disabled = false;
      continueBtn.style.opacity = '1';
      continueBtn.style.cursor = 'pointer';
      continueBtn.innerHTML = '🎉 Lanjut ke Terima Kasih';
    } else {
      continueBtn.disabled = true;
      continueBtn.style.opacity = '0.5';
      continueBtn.style.cursor = 'not-allowed';
      const missingActions = [];
      if (!state.emailSent) missingActions.push('Email');
      if (!state.printUsed) missingActions.push('Print');
      continueBtn.innerHTML = `⏳ ${missingActions.join(' & ')} Dulu`;
    }
  }

  /**
   * Initializes action buttons
   */
  function initializeActionButtons() {
    const buttons = {
      emailBtn: () => {
        if (state.emailSent) {
          handleError('Email sudah pernah dikirim!', 'alert');
          return;
        }
        if (state.finalCanvas) {
          showEmailModal();
        } else {
          handleError('Tidak ada gambar untuk dikirim ke email', 'alert');
        }
      },
      printBtn: async () => {
        if (state.printUsed) {
          handleError('Print sudah pernah digunakan!', 'alert');
          return;
        }
        if (state.finalCanvas) {
          console.log('🖨️ Generating high quality canvas for print...');
          const highQualityCanvas = await generateHighQualityCanvas();
          showSimplePrintPopup(highQualityCanvas.toDataURL('image/jpeg', 1.0));
        } else {
          handleError('Tidak ada gambar untuk di-print', 'alert');
        }
      },
      continueBtn: () => {
        if (state.emailSent && state.printUsed) {
          window.location.href = 'thankyou.php';
        } else {
          const missingActions = [];
          if (!state.emailSent) missingActions.push('Email');
          if (!state.printUsed) missingActions.push('Print');
          handleError(`Silakan ${missingActions.join(' dan ')} foto terlebih dahulu!`, 'alert');
        }
      },
    };

    Object.entries(buttons).forEach(([id, handler]) => {
      const button = document.getElementById(id);
      if (button) {
        button.addEventListener('click', handler);
      }
    });

    // Initialize continue button state
    updateContinueButtonState();
  }

  // Email Modal
  /**
   * Shows the email modal
   */
  function showEmailModal() {
    if (DOM.emailModal) {
      DOM.emailModal.style.display = 'block';
      if (DOM.emailInput) {
        DOM.emailInput.focus();
      }
    } else {
      console.error('❌ Email modal element not found');
    }
  }

  /**
   * Initializes the email modal
   */
  function initializeEmailModal() {
    const closeEmailModal = document.getElementById('closeEmailModal');
    const cancelEmailBtn = document.getElementById('cancelEmailBtn');
    const sendEmailBtn = document.getElementById('sendEmailBtn');

    const closeModal = () => (DOM.emailModal.style.display = 'none');

    if (closeEmailModal) closeEmailModal.addEventListener('click', closeModal);
    if (cancelEmailBtn) cancelEmailBtn.addEventListener('click', closeModal);
    if (DOM.emailModal) DOM.emailModal.addEventListener('click', (e) => e.target === DOM.emailModal && closeModal());

    if (sendEmailBtn) {
      sendEmailBtn.addEventListener('click', () => {
        const email = DOM.emailInput.value.trim();
        if (!email) return handleError('Mohon masukkan alamat email', 'validation');
        if (!validateEmail(email)) return handleError('Format email tidak valid', 'validation');
        hideValidationError();
        sendPhotoEmail(email);
      });
    }

    initializeVirtualKeyboard();
  }

  /**
   * Initializes the virtual keyboard
   */
  function initializeVirtualKeyboard() {
    let capsLock = false;

    document.querySelectorAll('.key-btn').forEach((key) => {
      key.addEventListener('click', (e) => {
        e.preventDefault();
        handleKeyPress(key.getAttribute('data-key'), DOM.emailInput);
      });
    });

    DOM.emailInput.addEventListener('click', () => setTimeout(() => DOM.emailInput.focus(), 10));
    document.getElementById('virtualKeyboard').addEventListener('mousedown', (e) => e.preventDefault());

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
      letterKeys.forEach((key) => {
        const keyValue = key.getAttribute('data-key');
        if (keyValue.length === 1 && keyValue.match(/[a-z]/i)) {
          key.textContent = capsLock ? keyValue.toUpperCase() : keyValue.toLowerCase();
        }
      });
    }

    updateCapsLockState();
  }

  /**
   * Shows validation error message
   * @param {string} message - Error message
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
   * @param {string} email - Email address
   * @returns {boolean} - Validity of email
   */
  function validateEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  }

  /**
   * Sends photo via email
   * @param {string} email - Recipient email
   */
  async function sendPhotoEmail(email) {
    if (!state.finalCanvas) {
      handleError('Tidak ada foto untuk dikirim', 'alert');
      return;
    }

    const sendBtn = document.getElementById('sendEmailBtn');
    const originalHtml = sendBtn.innerHTML;
    sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generating High Quality...';
    sendBtn.disabled = true;

    try {
      console.log('📧 Starting high quality email process...');
      
      // Generate high quality version
      const highQualityCanvas = await generateHighQualityCanvas();
      
      sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Preparing for Email...';
      
      const blob = await new Promise((resolve) => highQualityCanvas.toBlob(resolve, 'image/jpeg', 0.95));
      const base64data = await new Promise((resolve) => {
        const reader = new FileReader();
        reader.onloadend = () => resolve(reader.result);
        reader.readAsDataURL(blob);
      });

      sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving High Quality Photo...';

      const response = await fetch('../api-fetch/save_final_photo_v2.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({ 
          image: base64data,
          quality: 'high',
          source: 'customize_high_quality'
        }),
      });

      const text = await response.text();
      if (text.trim().startsWith('<') || text.includes('<br />') || text.includes('<b>')) {
        throw new Error('Server returned HTML error page');
      }

      const data = JSON.parse(text);
      if (!data.success) {
        throw new Error(data.message || 'Server error');
      }

      sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending Email...';

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
        from_name: 'GOFOTOBOX - HIGH QUALITY',
        message: `Halo Sobat! Link foto berkualitas tinggi Anda: ${photoLink}`,
        subject: 'Foto Berkualitas Tinggi dari GOFOTOBOX'
      };

      await emailjs.send(CONFIG.EMAILJS_SERVICE_ID, CONFIG.EMAILJS_TEMPLATE_ID, emailParams);

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
        DOM.emailModal.style.display = 'none';
        DOM.emailInput.value = '';
        hideValidationError();
      }, 3000);
    } catch (error) {
      handleError('Email error: ' + error.message, 'validation');
    } finally {
      sendBtn.innerHTML = originalHtml;
      sendBtn.disabled = false;
    }
  }

  // Print Functionality
  /**
   * Shows print preview popup
   * @param {string} imageDataUrl - Image data URL
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
      zIndex: '9999',
    });

    const popupBox = document.createElement('div');
    Object.assign(popupBox.style, {
      background: 'white',
      padding: '25px',
      borderRadius: '10px',
      textAlign: 'center',
      maxWidth: '450px',
      boxShadow: '0 10px 30px rgba(0,0,0,0.3)',
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