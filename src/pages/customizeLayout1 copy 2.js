document.addEventListener('DOMContentLoaded', () => {
  const COMPRESSION_CONFIG = {
    SESSION_QUALITY: 0.9,
    SESSION_MAX_WIDTH: 2000,
    SESSION_MAX_HEIGHT: 2400,
    DOWNLOAD_QUALITY: 0.98,
    DOWNLOAD_MAX_WIDTH: 3000,
    DOWNLOAD_MAX_HEIGHT: 3600,
    THUMB_QUALITY: 0.8,
    THUMB_MAX_WIDTH: 600,
    THUMB_MAX_HEIGHT: 800
  };

  function compressImage(imageData, mode = 'session') {
    console.log(`🔄 Compressing image with mode: ${mode}`);
    return new Promise((resolve, reject) => {
      try {
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        const img = new Image();
        
        img.onload = () => {
          let { width, height, quality, maxWidth, maxHeight } = getCompressionSettings(mode);
          
          const aspectRatio = img.width / img.height;
          if (img.width > maxWidth || img.height > maxHeight) {
            if (aspectRatio > 1) {
              width = Math.min(maxWidth, img.width);
              height = width / aspectRatio;
            } else {
              height = Math.min(maxHeight, img.height);
              width = height * aspectRatio;
            }
          } else {
            width = img.width;
            height = img.height;
          }
          
          canvas.width = width;
          canvas.height = height;
          
          ctx.drawImage(img, 0, 0, width, height);
          const compressedData = canvas.toDataURL('image/jpeg', quality);
          console.log(`✅ Image compressed successfully with quality: ${quality}`);
          resolve(compressedData);
        };
        
        img.onerror = () => reject(new Error('Failed to load image for compression'));
        img.src = imageData;
        
      } catch (error) {
        console.error('❌ Compression failed:', error);
        reject(error);
      }
    });
  }

  async function compressAndSaveFinalImage() {
    if (!state.finalCanvas) {
      console.warn('⚠️ No final canvas available for compression');
      return null;
    }
    
    try {
      const imageData = state.finalCanvas.toDataURL('image/jpeg', 1.0);
      const compressedData = await compressImage(imageData, 'session');
      
      sessionStorage.setItem('compressedPreview', compressedData);
      console.log('✅ Compressed image saved to session storage');
      
      return compressedData;
    } catch (error) {
      console.error('❌ Failed to compress and save image:', error);
      return null;
    }
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
      default:
        return {
          quality: COMPRESSION_CONFIG.SESSION_QUALITY,
          maxWidth: COMPRESSION_CONFIG.SESSION_MAX_WIDTH,
          maxHeight: COMPRESSION_CONFIG.SESSION_MAX_HEIGHT
        };
    }
  }

 const CONFIG = {
  CANVAS_WIDTH: 1224,
  CANVAS_HEIGHT: 1836,
  MARGIN_TOP: 142,
  SPACING: 72,

  PHOTO_WIDTH: 1052,
  PHOTO_HEIGHT: 574.2,
  PHOTO_MARGIN_LEFT: 86,
  PHOTO_MARGIN_RIGHT: 86,

  EXPECTED_PHOTOS: 2,
  EMAILJS_SERVICE_ID: 'service_gtqjb2j',
  EMAILJS_TEMPLATE_ID: 'template_pp5i4hm',
  LOGO_SRC: '/src/assets/logo.png',
};

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
    brightness: 1.0,
  };

  const DOM = {
    photoCustomPreview: document.getElementById('photoPreview'),
    framesContainer: document.getElementById('dynamicFramesContainer'),
    stickersContainer: document.getElementById('dynamicStickersContainer'),
    frameStickerContainer: document.getElementById('dynamicFrameStickerContainer'),
    noneSticker: document.getElementById('noneSticker'),
    noneFrameSticker: document.getElementById('noneFrameSticker'),
    emailModal: document.getElementById('emailModal'),
    emailInput: document.getElementById('emailInput'),

    brightnessSlider: document.getElementById('brightnessSlider'),
    brightnessValue: document.getElementById('brightnessValue'),
    darkerBtn: document.getElementById('darkerBtn'),
    normalBtn: document.getElementById('normalBtn'),
    brighterBtn: document.getElementById('brighterBtn'),
    superBrightBtn: document.getElementById('superBrightBtn'),
  };

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

  function setActiveButton(selector, selectedButton) {
    document.querySelectorAll(selector).forEach((btn) => btn.classList.remove('active'));
    selectedButton.classList.add('active');
  }

  function handleError(message, type = 'alert') {
    console.error(`❌ ${message}`);
    if (type === 'validation') {
      showValidationError(message);
    } else {
      alert(message);
    }
  }

  function saveCustomizationSettings() {
    const settings = {
      selectedSticker: state.selectedSticker,
      selectedFrameSticker: state.selectedFrameSticker,
      selectedShape: state.selectedShape,
      backgroundColor: state.backgroundColor,
      backgroundType: state.backgroundType,
      brightness: state.brightness,
      timestamp: Date.now()
    };
    
    try {
      sessionStorage.setItem('customizationSettings', JSON.stringify(settings));
      console.log('💾 Customization settings saved');
    } catch (error) {
      console.warn('⚠️ Failed to save settings to session storage:', error);
    }
  }

  function loadCustomizationSettings() {
    try {
      const settingsStr = sessionStorage.getItem('customizationSettings');
      if (settingsStr) {
        const settings = JSON.parse(settingsStr);

        if (Date.now() - settings.timestamp < 3600000) {
          state.selectedSticker = settings.selectedSticker;
          state.selectedFrameSticker = settings.selectedFrameSticker;
          state.selectedShape = settings.selectedShape || 'default';
          state.backgroundColor = settings.backgroundColor || '#FFFFFF';
          state.backgroundType = settings.backgroundType || 'color';
          state.brightness = settings.brightness || 1.0;
          
          console.log('📂 Customization settings loaded');
          return true;
        }
      }
    } catch (error) {
      console.warn('⚠️ Failed to load settings from session storage:', error);
    }
    return false;
  }

  async function initializeApp() {
    console.log('🔄 Initializing photobooth customization...');
    try {

      loadCustomizationSettings();
      
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

  async function loadAssetsFromDatabase() {
    console.log('🔄 Loading Layout 1 frames and stickers from database...');
    try {
      const [framesResponse, stickersResponse, comboResponse] = await Promise.all([
        fetch('../api-fetch/get-frames-by-layout.php?layout_id=1'),
        fetch('../api-fetch/get-stickers-by-layout.php?layout_id=1'),
        fetch('../api-fetch/get-frame-sticker-combo.php?layout_id=1')
      ]);

      const framesData = await framesResponse.json();
      const stickersData = await stickersResponse.json();
      const comboData = await comboResponse.json();

      console.log('🔍 Debug - Frames Response:', framesData);
      console.log('🔍 Debug - Stickers Response:', stickersData);
      console.log('🔍 Debug - Combo Response:', comboData);

      state.availableFrames = framesData.success ? framesData.frames : getFallbackFrames();
      console.log(`✅ Loaded ${state.availableFrames.length} Layout 1 frames from database`);

      state.availableStickers = stickersData.success ? stickersData.data : getFallbackStickers();
      console.log(`✅ Loaded ${state.availableStickers.length} Layout 1 stickers from database`);

      state.availableFrameStickers = comboData.success ? comboData.data : [];
      console.log(`✅ Loaded ${state.availableFrameStickers.length} Layout 1 frame & sticker combos from database`);
      console.log('🔍 Debug - Frame Stickers Array:', state.availableFrameStickers);
    } catch (error) {
      console.error('❌ Error loading assets from database:', error);
      state.availableFrames = getFallbackFrames();
      state.availableStickers = getFallbackStickers();
      state.availableFrameStickers = [];
    }
  }

  function getFallbackFrames() {
    return [
      { id: 1, nama: 'Matcha Frame', file_path: '/src/assets/frame-backgrounds/matcha.jpg' },
      { id: 2, nama: 'Black Star Frame', file_path: '/src/assets/frame-backgrounds/blackStar.jpg' },
      { id: 3, nama: 'Blue Stripe Frame', file_path: '/src/assets/frame-backgrounds/blueStripe.jpg' },
    ];
  }

  function getFallbackStickers() {
    return [{ id: 1, nama: 'Star Sticker', file_path: '/src/assets/stickers/bintang1.png' }];
  }

  async function createDynamicControls() {
    console.log('🔄 Creating dynamic controls...');

    if (DOM.framesContainer) {
      DOM.framesContainer.innerHTML = '';
      state.availableFrames.forEach((frame) => {
        const frameBtn = document.createElement('button');
        frameBtn.type = 'button';
        frameBtn.id = `frame_${frame.id}`;
        frameBtn.className = 'dynamic-frame-btn buttonBgFrames';
        frameBtn.style.backgroundImage = `url('${frame.file_path}')`;
        frameBtn.style.backgroundSize = 'cover';
        frameBtn.style.backgroundPosition = 'center';
        frameBtn.title = frame.nama;
        frameBtn.setAttribute('data-frame-id', frame.id);
        frameBtn.setAttribute('data-frame-path', frame.file_path);

        frameBtn.addEventListener('click', (e) => {
          e.preventDefault();
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

    if (DOM.stickersContainer) {
      const loadingPlaceholder = DOM.stickersContainer.querySelector('.loading-placeholder');
      if (loadingPlaceholder) loadingPlaceholder.remove();

      state.availableStickers.forEach((sticker) => {
        const stickerBtn = document.createElement('button');
        stickerBtn.type = 'button';
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

        stickerBtn.addEventListener('click', (e) => {
          e.preventDefault();
          setActiveButton('.buttonStickers', stickerBtn);
          state.selectedSticker = sticker.file_path;
          state.selectedFrameSticker = null;
          redrawCanvas();
          console.log(`🦋 Selected sticker: ${sticker.nama}`);
        });

        DOM.stickersContainer.appendChild(stickerBtn);
      });
      console.log(`✅ Created ${state.availableStickers.length} dynamic sticker controls`);
    }

    if (DOM.frameStickerContainer) {
      const loadingPlaceholder = DOM.frameStickerContainer.querySelector('.loading-placeholder');
      if (loadingPlaceholder) loadingPlaceholder.remove();

      state.availableFrameStickers.forEach((frameSticker) => {
        const frameStickerBtn = document.createElement('button');
        frameStickerBtn.type = 'button';
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
          e.preventDefault();
          e.stopPropagation();
          setActiveButton('.buttonFrameStickers', frameStickerBtn);
          state.selectedFrameSticker = frameSticker.file_path;
          state.selectedSticker = null;
          redrawCanvas();
          console.log(`🎪 Selected frame & sticker combo: ${frameSticker.nama}`);
        });

        DOM.frameStickerContainer.appendChild(frameStickerBtn);
      });
      console.log(`✅ Created ${state.availableFrameStickers.length} dynamic frame & sticker combo controls`);
    }

    if (DOM.noneSticker) {
      DOM.noneSticker.addEventListener('click', (e) => {
        e.preventDefault();
        setActiveButton('.buttonStickers', DOM.noneSticker);
        state.selectedSticker = null;
        redrawCanvas();
        console.log('🚫 No sticker selected');
      });
    }

    if (DOM.noneFrameSticker) {
      DOM.noneFrameSticker.addEventListener('click', (e) => {
        e.preventDefault();
        setActiveButton('.buttonFrameStickers', DOM.noneFrameSticker);
        state.selectedFrameSticker = null;
        redrawCanvas();
        console.log('🚫 No frame & sticker combo selected');
      });
    }
  }

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
      throw error;
    }
  }

  function initializeCanvas() {
    if (!state.storedImages.length) {
      console.error('❌ No images available for canvas');
      return;
    }
    console.log('🎨 Initializing canvas...');
    redrawCanvas();
    console.log('✅ Canvas initialized');
  }

  async function redrawCanvas() {
    if (!state.storedImages.length) {
      console.warn('⚠️ No images available for redraw');
      return;
    }

    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');

    const previewScale = 1.5;
    canvas.width = CONFIG.CANVAS_WIDTH * previewScale;
    canvas.height = CONFIG.CANVAS_HEIGHT * previewScale;
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    ctx.scale(previewScale, previewScale);

    if (state.backgroundType === 'color') {
      ctx.fillStyle = state.backgroundColor;
      ctx.fillRect(0, 0, CONFIG.CANVAS_WIDTH, CONFIG.CANVAS_HEIGHT);
      await drawPhotos(ctx, canvas);
    } else if (state.backgroundImage) {
      try {
        const bgImg = await loadImage(state.backgroundImage);
        ctx.drawImage(bgImg, 0, 0, CONFIG.CANVAS_WIDTH, CONFIG.CANVAS_HEIGHT);
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
      ctx.fillRect(0, 0, CONFIG.CANVAS_WIDTH, CONFIG.CANVAS_HEIGHT);
      await drawPhotos(ctx, canvas);
      updateCanvasPreview(canvas);
    }
  }

  async function drawPhotos(ctx, canvas) {
    if (state.storedImages.length < CONFIG.EXPECTED_PHOTOS) {
      console.warn(`⚠️ Layout requires ${CONFIG.EXPECTED_PHOTOS} photos, found: ${state.storedImages.length}`);
    }

    const imagesToProcess = Math.min(state.storedImages.length, CONFIG.EXPECTED_PHOTOS);
    let loadedCount = 0;

    for (const [index, imageData] of state.storedImages.slice(0, imagesToProcess).entries()) {
      try {
        const img = await loadImage(imageData);
        const xPosition = CONFIG.PHOTO_MARGIN_LEFT;
        const positions = [
          { x: xPosition, y: CONFIG.MARGIN_TOP, width: CONFIG.PHOTO_WIDTH, height: CONFIG.PHOTO_HEIGHT },
          {
            x: xPosition,
            y: CONFIG.MARGIN_TOP + CONFIG.PHOTO_HEIGHT + CONFIG.SPACING,
            width: CONFIG.PHOTO_WIDTH,
            height: CONFIG.PHOTO_HEIGHT,
          },
        ];
        drawCroppedImage(ctx, img, positions[index], state.selectedShape);
        loadedCount++;
        if (loadedCount === imagesToProcess) {
          await drawStickersAndLogos(ctx, canvas);
          updateCanvasPreview(canvas);

          saveCustomizationSettings();
        }
      } catch (error) {
        handleError(`Failed to load image: ${imageData}`, 'console');
      }
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

    ctx.imageSmoothingEnabled = true;
    ctx.imageSmoothingQuality = 'high';

    if (state.brightness !== 1.0) {
      const brightness = state.brightness;
      let brightnessPercent;
      let contrastPercent = 100;
      
      if (brightness > 1.0) {

        brightnessPercent = 100 + (brightness - 1.0) * 100;
        contrastPercent = 100 + (brightness - 1.0) * 20;
      } else {

        brightnessPercent = brightness * 100;
        contrastPercent = 100 - (1.0 - brightness) * 10;
      }
      
      ctx.filter = `brightness(${brightnessPercent}%) contrast(${contrastPercent}%)`;
    }
    
    if (shape === 'rounded') {
      roundedRect(ctx, x, y, width, height, 20);
      ctx.clip();
    } else {
      ctx.beginPath();
      ctx.rect(x, y, width, height);
      ctx.clip();
    }
    
    ctx.drawImage(img, sx, sy, sWidth, sHeight, x, y, width, height);

    ctx.filter = 'none';
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

  async function drawStickersAndLogos(ctx, canvas) {

    if (state.selectedFrameSticker) {
      try {
        const frameStickerImg = await loadImage(state.selectedFrameSticker);

        ctx.drawImage(frameStickerImg, 0, 0, CONFIG.CANVAS_WIDTH, CONFIG.CANVAS_HEIGHT);
        console.log('🎪 Frame & sticker combo applied with proper scale');
      } catch (error) {
        console.error('❌ Failed to load frame & sticker combo:', state.selectedFrameSticker);
      }
    }

    else if (state.selectedSticker) {
      try {
        const stickerImg = await loadImage(state.selectedSticker);

        ctx.drawImage(stickerImg, 0, 0, CONFIG.CANVAS_WIDTH, CONFIG.CANVAS_HEIGHT);
        console.log('🌟 Regular sticker applied with proper scale');
      } catch (error) {
        console.error('❌ Failed to load sticker:', state.selectedSticker);
      }
    }

    const logoBtn = document.getElementById('engLogo');
    if (logoBtn && logoBtn.classList.contains('active')) {
      try {
        const logoImg = await loadImage(CONFIG.LOGO_SRC);

        ctx.drawImage(logoImg, 20, CONFIG.CANVAS_HEIGHT - 60, 100, 40);
        console.log('🏷️ Logo applied with proper scale');
      } catch (error) {
        console.error('❌ Failed to load logo:', CONFIG.LOGO_SRC);
      }
    }
  }

  function updateCanvasPreview(canvas) {
    if (DOM.photoCustomPreview) {
      DOM.photoCustomPreview.innerHTML = '';

      Object.assign(canvas.style, {
        maxWidth: '350px',
        maxHeight: '525px',
        width: 'auto',
        height: 'auto',
        border: '2px solid #ddd',
        borderRadius: '8px',
        boxShadow: '0 4px 8px rgba(0,0,0,0.1)',
        display: 'block',
        margin: '0 auto',

        imageRendering: 'auto',
        imageRendering: '-webkit-optimize-contrast',
        imageRendering: 'pixelated',
        '-ms-interpolation-mode': 'bicubic',

        filter: 'none'
      });

      const ctx = canvas.getContext('2d');
      if (ctx) {
        ctx.imageSmoothingEnabled = true;
        ctx.imageSmoothingQuality = 'high';
      }
      
      DOM.photoCustomPreview.appendChild(canvas);
    }
    state.finalCanvas = canvas;

    compressAndSaveFinalImage().catch(error => {
      console.warn('⚠️ Failed to compress for session storage:', error);
    });
  }

  function initializeControls() {
    console.log('🎛️ Initializing controls...');
    initializeBrightnessControls();
    initializeFrameControls();
    initializeShapeControls();
    initializeLogoControls();
    initializeActionButtons();
    initializeEmailModal();
    console.log('✅ Controls initialized');
  }

  function initializeBrightnessControls() {
    console.log('🌞 Initializing brightness controls...');

    if (DOM.brightnessSlider) {
      DOM.brightnessSlider.addEventListener('input', (e) => {
        const brightness = parseFloat(e.target.value);
        state.brightness = brightness;
        updateBrightnessDisplay(brightness);
        updateBrightnessButtons(brightness);
        redrawCanvas();
      });
    }

    if (DOM.darkerBtn) {
      DOM.darkerBtn.addEventListener('click', () => {
        setBrightness(0.7);
      });
    }

    if (DOM.normalBtn) {
      DOM.normalBtn.addEventListener('click', () => {
        setBrightness(1.0);
      });
    }

    if (DOM.brighterBtn) {
      DOM.brighterBtn.addEventListener('click', () => {
        setBrightness(2.0);
      });
    }

    if (DOM.superBrightBtn) {
      DOM.superBrightBtn.addEventListener('click', () => {
        setBrightness(2.8);
      });
    }
  }

  function setBrightness(brightness) {
    state.brightness = brightness;

    if (DOM.brightnessSlider) {
      DOM.brightnessSlider.value = brightness;
    }

    updateBrightnessDisplay(brightness);
    updateBrightnessButtons(brightness);

    redrawCanvas();
  }

  function updateBrightnessDisplay(brightness) {
    if (DOM.brightnessValue) {
      const percentage = Math.round(brightness * 100);
      DOM.brightnessValue.textContent = `${percentage}%`;
    }
  }

  function updateBrightnessButtons(brightness) {

    [DOM.darkerBtn, DOM.normalBtn, DOM.brighterBtn, DOM.superBrightBtn].forEach(btn => {
      if (btn) btn.classList.remove('active');
    });

    if (brightness <= 0.8) {
      if (DOM.darkerBtn) DOM.darkerBtn.classList.add('active');
    } else if (brightness >= 2.5) {
      if (DOM.superBrightBtn) DOM.superBrightBtn.classList.add('active');
    } else if (brightness >= 1.5) {
      if (DOM.brighterBtn) DOM.brighterBtn.classList.add('active');
    } else {
      if (DOM.normalBtn) DOM.normalBtn.classList.add('active');
    }
  }

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

  function initializeLogoControls() {
    const nonLogo = document.getElementById('nonLogo');
    const engLogo = document.getElementById('engLogo');

    if (nonLogo) {
      nonLogo.addEventListener('click', () => {
        setActiveButton('.logoCustomBtn', nonLogo);
        redrawCanvas();
      });
    }

    if (engLogo) {
      engLogo.addEventListener('click', () => {
        setActiveButton('.logoCustomBtn', engLogo);
        redrawCanvas();
      });
    }
  }

  function updateContinueButtonState() {
    const continueBtn = document.getElementById('continueBtn');
    if (!continueBtn) return;

    const canContinue = state.printUsed;
    
    if (canContinue) {
      continueBtn.disabled = false;
      continueBtn.style.opacity = '1';
      continueBtn.style.cursor = 'pointer';
      continueBtn.innerHTML = '🎉 Lanjutkan';
    } else {
      continueBtn.disabled = true;
      continueBtn.style.opacity = '0.5';
      continueBtn.style.cursor = 'not-allowed';
      continueBtn.innerHTML = '⏳ Print Dulu';
    }
  }

  function showEmailConfirmationDialog() {
    const existingDialog = document.getElementById('emailConfirmationDialog');
    if (existingDialog) existingDialog.remove();

    const dialog = document.createElement('div');
    dialog.id = 'emailConfirmationDialog';
    Object.assign(dialog.style, {
      position: 'fixed',
      top: '0',
      left: '0',
      width: '100%',
      height: '100%',
      background: 'rgba(0, 0, 0, 0.8)',
      display: 'flex',
      alignItems: 'center',
      justifyContent: 'center',
      zIndex: '10001',
      animation: 'fadeIn 0.3s ease-in-out'
    });

    const dialogBox = document.createElement('div');
    Object.assign(dialogBox.style, {
      background: 'white',
      padding: '35px 30px',
      borderRadius: '15px',
      textAlign: 'center',
      maxWidth: '420px',
      width: '90%',
      boxShadow: '0 20px 60px rgba(0, 0, 0, 0.3)',
      border: '2px solid #E28585',
      position: 'relative',
      animation: 'slideInUp 0.4s ease-out'
    });

    dialogBox.innerHTML = `
      <div style="margin-bottom: 25px;">
        <div style="width: 70px; height: 70px; background: linear-gradient(135deg, #ffc107, #e0a800); border-radius: 50%; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center;">
          <i class="fas fa-envelope" style="font-size: 30px; color: white;"></i>
        </div>
        <h3 style="margin: 0 0 10px 0; color: #333; font-size: 22px; font-weight: 600;">Kirim Foto ke Email?</h3>
        <p style="margin: 0; color: #666; font-size: 15px; line-height: 1.5;">Apakah Anda ingin mengirim foto ini ke alamat email Anda sebagai backup?</p>
      </div>
      
      <div style="background: rgba(255, 193, 7, 0.1); padding: 18px; border-radius: 12px; margin-bottom: 25px; border-left: 4px solid #ffc107;">
        <p style="margin: 0; color: #856404; font-size: 14px; text-align: left;">
          <i class="fas fa-info-circle" style="margin-right: 8px;"></i>
          <strong>Opsional:</strong> Anda bisa melanjutkan tanpa mengirim email, dan perlu ingat foto akan langsung hilang.
        </p>
      </div>

      <div style="display: flex; gap: 12px;">
        <button id="confirmEmailYes" style="
          flex: 1;
          background: linear-gradient(135deg, #E28585, #d67575);
          color: white;
          border: none;
          padding: 15px 20px;
          border-radius: 8px;
          cursor: pointer;
          font-size: 15px;
          font-weight: 600;
          transition: all 0.3s ease;
          box-shadow: 0 4px 15px rgba(226, 133, 133, 0.3);
        " onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(226, 133, 133, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(226, 133, 133, 0.3)'">
          <i class="fas fa-check"></i> Ya, Kirim Email
        </button>
        
        <button id="confirmEmailNo" style="
          flex: 1;
          background: linear-gradient(135deg, #28a745, #20c997);
          color: white;
          border: none;
          padding: 15px 20px;
          border-radius: 8px;
          cursor: pointer;
          font-size: 15px;
          font-weight: 600;
          transition: all 0.3s ease;
          box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        " onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(40, 167, 69, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(40, 167, 69, 0.3)'">
          <i class="fas fa-arrow-right"></i> Tidak, Lanjutkan
        </button>
      </div>
    `;

    dialog.appendChild(dialogBox);
    document.body.appendChild(dialog);

    if (!document.getElementById('emailConfirmDialogStyles')) {
      const style = document.createElement('style');
      style.id = 'emailConfirmDialogStyles';
      style.textContent = `
        @keyframes fadeIn {
          from { opacity: 0; }
          to { opacity: 1; }
        }
        @keyframes slideInUp {
          from { 
            opacity: 0;
            transform: translateY(30px);
          }
          to { 
            opacity: 1;
            transform: translateY(0);
          }
        }
      `;
      document.head.appendChild(style);
    }

    document.getElementById('confirmEmailYes').addEventListener('click', () => {
      dialog.remove();
      showEmailModal();
    });

    document.getElementById('confirmEmailNo').addEventListener('click', () => {
      dialog.remove();
      window.location.href = 'thankyou.php';
    });

    dialog.addEventListener('click', (e) => {
      if (e.target === dialog) {
        dialog.remove();
      }
    });

    const escapeHandler = (e) => {
      if (e.key === 'Escape') {
        dialog.remove();
        document.removeEventListener('keydown', escapeHandler);
      }
    };
    document.addEventListener('keydown', escapeHandler);
  }

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

          console.log('🖨️ Generating high quality for print...');
          try {
            const highQualityCanvas = await generateHighQualityCanvas();

            const highQualityDataUrl = highQualityCanvas.toDataURL('image/jpeg', 0.98);
            showSimplePrintPopup(highQualityDataUrl);
          } catch (error) {
            console.error('❌ Error generating high quality for print:', error);

            showSimplePrintPopup(highQualityDataUrl);
          }
        } else {
          handleError('Tidak ada gambar untuk di-print', 'alert');
        }
      },
      continueBtn: () => {

        if (state.printUsed) {

          if (!state.emailSent) {
            showEmailConfirmationDialog();
          } else {

            window.location.href = 'thankyou.php';
          }
        } else {
          handleError('Silakan Print foto terlebih dahulu!', 'alert');
        }
      },
    };

    Object.entries(buttons).forEach(([id, handler]) => {
      const button = document.getElementById(id);
      if (button) {
        button.addEventListener('click', handler);
      }
    });

    updateContinueButtonState();
  }

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

    if (typeof window.emailJSHelper !== 'undefined') {
      const validation = window.emailJSHelper.validateEmail(email);

      if (!validation.isValid && validation.suggestions.length > 0) {
        const suggestion = validation.suggestions[0];
        const emailInput = document.getElementById('emailInput');

        showValidationError(`${validation.error}\n💡 Mungkin maksud Anda: ${suggestion}?`);

        setTimeout(() => {
          if (emailInput && confirm(`Auto-correct ke "${suggestion}"?`)) {
            emailInput.value = suggestion;
            hideValidationError();
            return true;
          }
        }, 2000);
        
        return false;
      }
      
      if (!validation.isValid) {
        showValidationError(validation.error);
        return false;
      }

      if (validation.emailType === 'indonesian') {
        console.log('✅ Indonesian email domain detected:', email);
      }
      
      return true;
    }

    const isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    if (!isValid) {
      showValidationError('Format email tidak valid');
    }
    return isValid;
  }

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

      const highQualityCanvas = await generateHighQualityCanvas();
      
      sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Compressing for Email...';

      const highQualityDataUrl = highQualityCanvas.toDataURL('image/jpeg', 1.0);
      const compressedEmailImage = await compressImage(highQualityDataUrl, 'download');
      
      sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Preparing for Email...';

      const blob = await new Promise((resolve) => {
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        const img = new Image();
        img.onload = () => {
          canvas.width = img.width;
          canvas.height = img.height;
          ctx.drawImage(img, 0, 0);
          canvas.toBlob(resolve, 'image/jpeg', 0.95);
        };
        img.src = compressedEmailImage;
      });
      
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
        emailBtn.innerHTML = '✅ Email Terkirim (HQ)';
      }

      updateContinueButtonState();
      
      showValidationError('Email berkualitas tinggi berhasil dikirim! ✅ Cek inbox Anda.');
      document.querySelector('.input-validation span').style.color = '#28a745';
      
      setTimeout(() => {
        DOM.emailModal.style.display = 'none';
        DOM.emailInput.value = '';
        hideValidationError();
      }, 3000);
      
      console.log('✅ High quality email sent successfully');
      
    } catch (error) {
      console.error('❌ Email error:', error);
      handleError('Email error: ' + error.message, 'validation');
    } finally {
      sendBtn.innerHTML = originalHtml;
      sendBtn.disabled = false;
    }
  }

  async function generateHighQualityCanvas() {
    console.log('🎨 Generating high quality canvas for print...');
    
    if (!state.storedImages.length) {
      console.warn('⚠️ No images available for high quality generation');
      return state.finalCanvas;
    }

    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');

    const printScale = 3;
    canvas.width = CONFIG.CANVAS_WIDTH * printScale;
    canvas.height = CONFIG.CANVAS_HEIGHT * printScale;
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    ctx.imageSmoothingEnabled = true;
    ctx.imageSmoothingQuality = 'high';

    ctx.scale(printScale, printScale);

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

  async function drawHighQualityPhotos(ctx, canvas, printScale) {
    if (state.storedImages.length < CONFIG.EXPECTED_PHOTOS) {
      console.warn(`⚠️ Layout requires ${CONFIG.EXPECTED_PHOTOS} photos, found: ${state.storedImages.length}`);
    }

    const imagesToProcess = Math.min(state.storedImages.length, CONFIG.EXPECTED_PHOTOS);
    let loadedCount = 0;

    for (const [index, imageData] of state.storedImages.slice(0, imagesToProcess).entries()) {
      try {
        const img = await loadImage(imageData);
        const xPosition = CONFIG.PHOTO_MARGIN_LEFT;
        const positions = [
          { x: xPosition, y: CONFIG.MARGIN_TOP, width: CONFIG.PHOTO_WIDTH, height: CONFIG.PHOTO_HEIGHT },
          {
            x: xPosition,
            y: CONFIG.MARGIN_TOP + CONFIG.PHOTO_HEIGHT + CONFIG.SPACING,
            width: CONFIG.PHOTO_WIDTH,
            height: CONFIG.PHOTO_HEIGHT,
          },
        ];
        drawCroppedImage(ctx, img, positions[index], state.selectedShape);
        loadedCount++;
        
        if (loadedCount === imagesToProcess) {
          await drawHighQualityStickersAndLogos(ctx, canvas, printScale);
        }
      } catch (error) {
        console.error(`❌ Failed to load high quality image ${index}:`, error);
        handleError(`Failed to load image: ${imageData}`, 'console');
      }
    }
  }

  async function drawHighQualityStickersAndLogos(ctx, canvas, printScale) {

    if (state.selectedFrameSticker) {
      try {
        const frameStickerImg = await loadImage(state.selectedFrameSticker);

        ctx.drawImage(frameStickerImg, 0, 0, CONFIG.CANVAS_WIDTH, CONFIG.CANVAS_HEIGHT);
        console.log('🎪 High quality frame & sticker combo applied');
      } catch (error) {
        console.error('❌ Failed to load high quality frame & sticker combo:', state.selectedFrameSticker);
      }
    }

    else if (state.selectedSticker) {
      try {
        const stickerImg = await loadImage(state.selectedSticker);

        ctx.drawImage(stickerImg, 0, 0, CONFIG.CANVAS_WIDTH, CONFIG.CANVAS_HEIGHT);
        console.log('🌟 High quality regular sticker applied');
      } catch (error) {
        console.error('❌ Failed to load high quality sticker:', state.selectedSticker);
      }
    }

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
  
  function showPrintNotificationDialog() {
    const existingDialog = document.getElementById('printNotificationDialog');
    if (existingDialog) existingDialog.remove();

    fetch('/src/api-fetch/set_session.php', {
      method: 'POST',
      headers: { 
        'Content-Type': 'application/json',
        'Cache-Control': 'no-cache'
      },
      body: JSON.stringify({ 
        action: 'extend',
        extend_minutes: 3,
        reason: 'print_notification_backup'
      })
    }).then(response => {
      if (response.ok) {
        console.log('⏰ Additional session extension for print notification backup');
      }
    }).catch(error => {
      console.warn('⚠️ Backup session extension failed:', error);
    });

    const dialog = document.createElement('div');
    dialog.id = 'printNotificationDialog';
    Object.assign(dialog.style, {
      position: 'fixed',
      top: '0',
      left: '0',
      width: '100%',
      height: '100%',
      background: 'rgba(0, 0, 0, 0.8)',
      display: 'flex',
      alignItems: 'center',
      justifyContent: 'center',
      zIndex: '10000',
      animation: 'fadeIn 0.3s ease-in-out'
    });

    const dialogBox = document.createElement('div');
    Object.assign(dialogBox.style, {
      background: 'white',
      padding: '40px 35px',
      borderRadius: '15px',
      textAlign: 'center',
      maxWidth: '450px',
      width: '90%',
      boxShadow: '0 20px 60px rgba(0, 0, 0, 0.3)',
      border: '2px solid #E28585',
      position: 'relative',
      animation: 'slideInUp 0.4s ease-out'
    });

    dialogBox.innerHTML = `
      <div style="margin-bottom: 25px;">
        <div id="printIconContainer" style="width: 90px; height: 90px; background: linear-gradient(135deg, #E28585, #d67575); border-radius: 50%; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center; animation: pulse 2s infinite; position: relative; overflow: hidden;">
          <i class="fas fa-print" style="font-size: 40px; color: white;"></i>
          <div id="printIconOverlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.1); border-radius: 50%; display: none;"></div>
        </div>
        <h3 style="margin: 0 0 10px 0; color: #333; font-size: 24px; font-weight: 600;">Print Berhasil Dikirim!</h3>
        <p style="margin: 0; color: #666; font-size: 16px; line-height: 1.4;">Foto Anda sedang diproses oleh printer</p>
      </div>
      
      <!-- Loading Progress Section -->
      <div id="loadingSection" style="background: rgba(226, 133, 133, 0.1); padding: 25px; border-radius: 12px; margin-bottom: 25px; border-left: 4px solid #E28585;">
        <!-- Progress Ring -->
        <div style="position: relative; width: 120px; height: 120px; margin: 0 auto 20px;">
          <svg width="120" height="120" style="transform: rotate(-90deg);">
            <circle cx="60" cy="60" r="50" stroke="rgba(226, 133, 133, 0.2)" stroke-width="6" fill="none"></circle>
            <circle id="progressCircle" cx="60" cy="60" r="50" stroke="#E28585" stroke-width="6" fill="none" 
              stroke-dasharray="314.16" stroke-dashoffset="314.16" 
              style="transition: stroke-dashoffset 1s ease; stroke-linecap: round;">
            </circle>
          </svg>
          <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center;">
            <div id="countdownTime" style="font-size: 24px; font-weight: 700; color: #E28585; line-height: 1;">02:00</div>
            <div style="font-size: 12px; color: #888; margin-top: 2px;">menit</div>
          </div>
        </div>
        
        <!-- Status Text -->
        <div style="display: flex; align-items: center; justify-content: center; gap: 10px; margin-bottom: 8px;">
          <div id="loadingSpinner" style="width: 20px; height: 20px; border: 3px solid rgba(226, 133, 133, 0.3); border-top: 3px solid #E28585; border-radius: 50%; animation: spin 1s linear infinite;"></div>
          <span id="statusText" style="color: #333; font-weight: 600; font-size: 16px;">Sedang Memproses...</span>
        </div>
        <p id="instructionText" style="margin: 0; color: #777; font-size: 14px;">Harap menunggu di area printer</p>
      </div>

      <!-- Action Button -->
      <button id="printNotificationBtn" style="
        background: linear-gradient(135deg, #6c757d, #5a6268);
        color: white;
        border: none;
        padding: 15px 40px;
        border-radius: 8px;
        cursor: not-allowed;
        font-size: 16px;
        font-weight: 600;
        width: 100%;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
        opacity: 0.7;
      ">
        <i class="fas fa-hourglass-half"></i> Tunggu Selesai...
      </button>
    `;

    dialog.appendChild(dialogBox);
    document.body.appendChild(dialog);

    if (!document.getElementById('printDialogStyles')) {
      const style = document.createElement('style');
      style.id = 'printDialogStyles';
      style.textContent = `
        @keyframes fadeIn {
          from { opacity: 0; }
          to { opacity: 1; }
        }
        @keyframes slideInUp {
          from { 
            opacity: 0;
            transform: translateY(30px);
          }
          to { 
            opacity: 1;
            transform: translateY(0);
          }
        }
        @keyframes pulse {
          0%, 100% { transform: scale(1); }
          50% { transform: scale(1.05); }
        }
        @keyframes spin {
          0% { transform: rotate(0deg); }
          100% { transform: rotate(360deg); }
        }
        @keyframes fadeOut {
          from { opacity: 1; }
          to { opacity: 0; }
        }
        @keyframes slideOutDown {
          from { 
            opacity: 1;
            transform: translateY(0);
          }
          to { 
            opacity: 0;
            transform: translateY(30px);
          }
        }
        @keyframes checkmark {
          0% { transform: scale(0); }
          50% { transform: scale(1.2); }
          100% { transform: scale(1); }
        }
        @keyframes successPulse {
          0%, 100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.4); }
          70% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(40, 167, 69, 0); }
        }
      `;
      document.head.appendChild(style);
    }

    let timeLeft = 72;
    const totalTime = 72;
    const progressCircle = document.getElementById('progressCircle');
    const countdownElement = document.getElementById('countdownTime');
    const statusText = document.getElementById('statusText');
    const instructionText = document.getElementById('instructionText');
    const actionButton = document.getElementById('printNotificationBtn');
    const loadingSpinner = document.getElementById('loadingSpinner');
    const printIcon = dialogBox.querySelector('.fas.fa-print');
    
    const circumference = 2 * Math.PI * 50;
    
    const countdownInterval = setInterval(() => {
      timeLeft--;

      const minutes = Math.floor(timeLeft / 60);
      const seconds = timeLeft % 60;
      countdownElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

      const progress = (totalTime - timeLeft) / totalTime;
      const offset = circumference * (1 - progress);
      progressCircle.style.strokeDashoffset = offset;

      if (timeLeft === 90) {
        statusText.textContent = 'Menyiapkan Tinta...';
        instructionText.textContent = 'Printer sedang mempersiapkan tinta berkualitas tinggi';
      } else if (timeLeft === 60) {
        statusText.textContent = 'Memproses Gambar...';
        instructionText.textContent = 'Sedang memproses foto dengan resolusi tinggi';
      } else if (timeLeft === 30) {
        statusText.textContent = 'Mulai Mencetak...';
        instructionText.textContent = 'Foto Anda sedang dicetak, hampir selesai!';
        loadingSpinner.style.borderTopColor = '#28a745';
      } else if (timeLeft === 10) {
        statusText.textContent = 'Finishing...';
        instructionText.textContent = 'Menyelesaikan proses printing...';
      }

      if (timeLeft <= 0) {
        clearInterval(countdownInterval);

        progressCircle.style.strokeDashoffset = '0';
        progressCircle.style.stroke = '#28a745';
        countdownElement.textContent = '00:00';

        loadingSpinner.style.display = 'none';

        statusText.innerHTML = '<i class="fas fa-check-circle" style="color: #28a745; margin-right: 8px;"></i>Print Selesai!';
        instructionText.textContent = 'Foto Anda sudah siap! Silakan ambil di area printer.';
        instructionText.style.color = '#28a745';
        instructionText.style.fontWeight = '600';

        actionButton.style.background = 'linear-gradient(135deg, #28a745, #20c997)';
        actionButton.style.cursor = 'pointer';
        actionButton.style.opacity = '1';
        actionButton.innerHTML = '<i class="fas fa-check"></i> OK, Saya Mengerti';
        actionButton.style.animation = 'successPulse 2s ease-in-out 3';

        printIcon.className = 'fas fa-check';
        printIcon.parentElement.style.background = 'linear-gradient(135deg, #28a745, #20c997)';
        printIcon.parentElement.style.animation = 'checkmark 0.5s ease-out';

        actionButton.onclick = handleDialogClose;
      }
    }, 1000);

    function handleDialogClose() {
      if (timeLeft <= 0) {
        dialog.style.animation = 'fadeOut 0.3s ease-in-out';
        dialogBox.style.animation = 'slideOutDown 0.3s ease-in';
        
        setTimeout(() => {
          dialog.remove();
          clearInterval(countdownInterval);
        }, 300);
      }
    }

    dialog.addEventListener('click', (e) => {
      if (e.target === dialog && timeLeft <= 0) {
        handleDialogClose();
      }
    });

    const escapeHandler = (e) => {
      if (e.key === 'Escape' && timeLeft <= 0) {
        handleDialogClose();
        document.removeEventListener('keydown', escapeHandler);
      }
    };
    document.addEventListener('keydown', escapeHandler);

    dialog.addEventListener('DOMNodeRemoved', () => {
      if (countdownInterval) {
        clearInterval(countdownInterval);
      }
    });
  }

  async function showSimplePrintPopup(imageDataUrl) {
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
        <button id="directPrintBtn" style="background: #28a745; color: white; border: none; padding: 12px 25px; border-radius: 6px; cursor: pointer; font-size: 16px; margin-right: 10px; font-weight: bold;">
          <i class="fas fa-spinner fa-spin" style="display: none; margin-right: 5px;"></i>
          🖨️ Print High Quality
        </button>
        <button id="closePopupBtn" style="background: #6c757d; color: white; border: none; padding: 12px 25px; border-radius: 6px; cursor: pointer; font-size: 16px; font-weight: bold;">✖️ Close</button>
      </div>
    `;

    popup.appendChild(popupBox);
    document.body.appendChild(popup);

    document.getElementById('directPrintBtn').addEventListener('click', async () => {
      const printBtn = document.getElementById('directPrintBtn');
      const spinner = printBtn.querySelector('.fa-spinner');
      const originalText = printBtn.innerHTML;
      
      try {

        printBtn.disabled = true;
        printBtn.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right: 5px;"></i> Extending Session...';

        try {
          const extendResponse = await fetch('/src/api-fetch/set_session.php', {
            method: 'POST',
            headers: { 
              'Content-Type': 'application/json',
              'Cache-Control': 'no-cache'
            },
            body: JSON.stringify({ 
              action: 'extend',
              extend_minutes: 5,
              reason: 'print_process'
            })
          });
          
          if (extendResponse.ok) {
            console.log('⏰ Session extended for 5 minutes for print process');
          } else {
            console.warn('⚠️ Failed to extend session, continuing with print...');
          }
        } catch (extendError) {
          console.warn('⚠️ Session extend request failed:', extendError);

        }
        
        printBtn.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right: 5px;"></i> Generating High Quality...';
        console.log('🖨️ Starting high quality print process...');

        const highQualityCanvas = await generateHighQualityCanvas();

        const highQualityDataUrl = highQualityCanvas.toDataURL('image/jpeg', 0.98);
        
        printBtn.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right: 5px;"></i> Opening Print Dialog...';

        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
          <!DOCTYPE html>
          <html>
          <head>
            <title>Print Photo - High Quality</title>
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
              <img src="${highQualityDataUrl}" class="print-image" alt="Print Photo - High Quality" />
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
              printBtn.innerHTML = '✅ Sudah Print (HQ)';
            }

            updateContinueButtonState();
            
            popup.remove();
            console.log('✅ High quality print completed');

            setTimeout(() => {
              showPrintNotificationDialog();
            }, 500);
          }, 500);
        };
        
      } catch (error) {
        console.error('❌ Print error:', error);
        alert('❌ Gagal print dengan kualitas tinggi. Coba lagi.');
        printBtn.innerHTML = originalText;
        printBtn.disabled = false;
      }
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

  initializeApp();
});
