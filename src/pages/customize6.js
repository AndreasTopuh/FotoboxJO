document.addEventListener('DOMContentLoaded', function() {
    
    const photoCustomPreview = document.getElementById('photoPreview')
    const pinkBtn = document.getElementById('pinkBtnFrame')
    const blueBtn = document.getElementById('blueBtnFrame')
    const yellowBtn = document.getElementById('yellowBtnFrame')
    const brownBtn = document.getElementById('brownBtnFrame')
    const redBtn = document.getElementById('redBtnFrame')

    const matchaBtn = document.getElementById('matchaBtnFrame')
    const purpleBtn = document.getElementById('purpleBtnFrame')
    // const ribbonBtn = document.getElementById('ribbonBtnFrame')
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
    const gridPaperFrame = document.getElementById('gridPaperFrame');
    const stardustFrame = document.getElementById('stardustFrame');
    const roughTextureFrame = document.getElementById('roughTextureFrame');
    const ribbonSweaterFrame = document.getElementById('ribbonSweaterFrame');
    const vsPinkFrame = document.getElementById('vsPinkFrame');
    const vsYellowFrame = document.getElementById('vsYellowFrame');
    const redRosesPaintFrame = document.getElementById('redRosesPaintFrame');
    const grayTrashFrame = document.getElementById('grayTrashFrame');
    const blackTrashFrame = document.getElementById('blackTrashFrame');
    const whiteTrashFrame = document.getElementById('whiteTrashFrame');
    const brownKnittedFrame = document.getElementById('brownKnittedFrame');
    const hotPinkKnittedFrame = document.getElementById('hotPinkKnittedFrame');
    const redKnittedFrame = document.getElementById('redKnittedFrame');
    const pinkKnittedFrame = document.getElementById('pinkKnittedFrame');
    const redStripesFrame = document.getElementById('redStripesFrame');
    const greenStripesFrame = document.getElementById('greenStripesFrame');
    const blueStripesFrame = document.getElementById('blueStripesFrame');
    const partyDrapeFrame = document.getElementById('partyDrapeFrame');
    const partyDotsFrame = document.getElementById('partyDotsFrame');
    const blingDenimFrame = document.getElementById('blingDenimFrame');

    const customBack = document.getElementById('customBack');
    // const customNext = document.getElementById('customNext');
    const downloadCopyBtn = document.getElementById('downloadCopyBtn');

    const noneSticker = document.getElementById('noneSticker')
    const kissSticker = document.getElementById('kissSticker')
    const ribbonSticker = document.getElementById('ribbonSticker')
    const sweetSticker = document.getElementById('sweetSticker')
    // const islandSticker = document.getElementById('islandSticker')
    const sparkleSticker = document.getElementById('sparkleSticker')
    const pearlSticker = document.getElementById('pearlSticker')
    // const matchaSticker = document.getElementById('matchaSticker')
    const softSticker = document.getElementById('softSticker');
    const bunnySticker = document.getElementById('bunnySticker');
    const classicSticker = document.getElementById('classicSticker');
    const classicBSticker = document.getElementById('classicBSticker');
    const luckySticker = document.getElementById('luckySticker');
    const confettiSticker = document.getElementById('confettiSticker');
    const ribbonCoquetteSticker = document.getElementById('ribbonCoquetteSticker');
    const blueRibbonCoquetteSticker = document.getElementById('blueRibbonCoquetteSticker');
    const blackStarSticker = document.getElementById('blackStarSticker');
    const yellowChickenSticker = document.getElementById('yellowChickenSticker');
    const brownBearSticker = document.getElementById('brownBearSticker');
    const lotsHeartSticker = document.getElementById('lotsHeartSticker');
    const tabbyCatSticker = document.getElementById('tabbyCatSticker');
    const ballerinaCappuccinoSticker = document.getElementById('ballerinaCappuccinoSticker');
    const doggyWhiteSticker = document.getElementById('doggyWhiteSticker');
    const sakuraBlossomSticker = document.getElementById('sakuraBlossomSticker');
    const myGirlsSticker = document.getElementById('myGirlsSticker');

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
            // if (imageArrayLength === 3) {
            //     window.location.href = 'canvas.html'
            // }
            // else if (imageArrayLength === 4) {
            //     window.location.href = 'canvas4.html'
            // }
            // else if (imageArrayLength === 6) {
            //     window.location.href = 'canvas4.html'
            // }
            // else {
            //     window.location.href = 'canvas2.html'
            // }
            window.location.href = 'canvas6.php'
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
    
    // Retrieve stored images array from sessionStorage
    const storedImages = JSON.parse(sessionStorage.getItem('photoArray'));
    const imageArrayLength = storedImages.length;
    
    if (!storedImages || storedImages.length !== imageArrayLength) {
        console.log("No valid images found in sessionStorage");
    } else {
        console.log("Loaded images:", storedImages);
    }

    // Default background color
    // let backgroundType = 'white';
    let backgroundType = 'color';
    let backgroundColor = '#FFFFFF'; // default
    let backgroundImage = null;

    // Function to set new background and redraw canvas
    // function setBackground(type) {
    //     console.log(`Changing to: ${type}`);
    //     backgroundType = type;
    //     redrawCanvas();
    // }
    function setBackground(option) {
        console.log('Setting background:', option);
    
        if (option.type === 'color') {
            backgroundType = 'color';
            backgroundColor = option.value;
            redrawCanvas();
        } else if (option.type === 'image') {
            backgroundType = 'image';
            backgroundImage = new Image();
            backgroundImage.src = option.src;
    
            backgroundImage.onload = () => {
                redrawCanvas();
            };
        }
    }

    // // Initialize Vanilla Picker
    // const picker = new Picker({
    //     parent: colorPickerBtn,
    //     popup: 'bottom', // Shows popup below the div
    //     color: '#FFFFFF', // Default color
    //     onChange: (color) => {
    //         setBackground(color.hex); // Call your function with the selected color
    //     },
    //     onDone: (color) => {
    //         colorPickerBtn.style.backgroundColor = color.hex; // Update button color
    //     }
    // });
    const picker = new Picker({
        parent: colorPickerBtn,
        popup: 'bottom',
        color: '#FFFFFF',
        onChange: (color) => {
            setBackground({ type: 'color', value: color.hex });
        },
        onDone: (color) => {
            colorPickerBtn.style.backgroundColor = color.hex;
        }
    });

    // Show picker on click
    colorPickerBtn.addEventListener("click", () => picker.show());

    // function setSticker(type) {
    //     console.log(`Sticker changed to: ${type}`);
    //     selectedSticker = type;
    //     redrawCanvas();
    // }
    function setSticker(type) {
        if (selectedSticker === type) {
            selectedSticker = null; // Remove sticker if already selected
            console.log(`Removed sticker: ${type}`);
        } else {
            selectedSticker = type;
            console.log(`Selected sticker: ${type}`);
        }
        redrawCanvas();
    }

    // Event listeners for stickers
    // if (noneSticker) noneSticker.addEventListener('click', () => { selectedSticker = null; redrawCanvas(); }); 
    // if (kissSticker) kissSticker.addEventListener('click', () => setSticker('kiss')); // Kiss sticker
    // if (sweetSticker) sweetSticker.addEventListener('click', () => setSticker('sweet'));
    // if (ribbonSticker) ribbonSticker.addEventListener('click', () => setSticker('ribbon'));
    // // if (islandSticker) islandSticker.addEventListener('click', () => setSticker('island'));
    // if (sparkleSticker) sparkleSticker.addEventListener('click', () => setSticker('sparkle'));
    // if (pearlSticker) pearlSticker.addEventListener('click', () => setSticker('pearl'));
    // // if (matchaSticker) matchaSticker.addEventListener('click', () => setSticker('matcha'));
    // if (classicSticker) classicSticker.addEventListener('click', () => setSticker('classic'));
    // if (classicBSticker) classicBSticker.addEventListener('click', () => setSticker('classicB'));
    // if (softSticker) softSticker.addEventListener('click', () => setSticker('soft'));
    // if (bunnySticker) bunnySticker.addEventListener('click', () => setSticker('bunny'));
    // if (luckySticker) luckySticker.addEventListener('click', () => setSticker('lucky'));
    // if (confettiSticker) confettiSticker.addEventListener('click', () => setSticker('confetti'));
    // if (ribbonCoquetteSticker) ribbonCoquetteSticker.addEventListener('click', () => setSticker('ribboncoquette'));
    // if (blueRibbonCoquetteSticker) blueRibbonCoquetteSticker.addEventListener('click', () => setSticker('blueribboncoquette'));
    // if (blackStarSticker) blackStarSticker.addEventListener('click', () => setSticker('blackstar'));
    // if (yellowChickenSticker) yellowChickenSticker.addEventListener('click', () => setSticker('yellowchicken'));
    // if (brownBearSticker) brownBearSticker.addEventListener('click', () => setSticker('brownbear'));
    // if (lotsHeartSticker) lotsHeartSticker.addEventListener('click', () => setSticker('lotsheart'));
    // if (tabbyCatSticker) tabbyCatSticker.addEventListener('click', () => setSticker('tabbycat'));
    // if (ballerinaCappuccinoSticker) ballerinaCappuccinoSticker.addEventListener('click', () => setSticker('ballerinacp'));
    // if (doggyWhiteSticker) doggyWhiteSticker.addEventListener('click', () => setSticker('doggywhite'));
    // if (sakuraBlossomSticker) sakuraBlossomSticker.addEventListener('click', () => setSticker('sakurablossom'));
    // if (myGirlsSticker) myGirlsSticker.addEventListener('click', () => setSticker('mygirls'));

    const allStickers = [
        kissSticker,
        sweetSticker,
        ribbonSticker,
        sparkleSticker,
        pearlSticker,
        classicSticker,
        classicBSticker,
        softSticker,
        bunnySticker,
        luckySticker,
        confettiSticker,
        ribbonCoquetteSticker,
        blueRibbonCoquetteSticker,
        blackStarSticker,
        yellowChickenSticker,
        brownBearSticker,
        lotsHeartSticker,
        tabbyCatSticker,
        ballerinaCappuccinoSticker,
        doggyWhiteSticker,
        sakuraBlossomSticker,
        myGirlsSticker,
        noneSticker,
        // islandSticker,
        // matchaSticker,
    ];
      
    function resetStickerBackgrounds() {
        allStickers.forEach(el => {
          if (el) el.style.backgroundColor = '';
        });
    }
      
    if (kissSticker) {
        kissSticker.addEventListener('click', () => {
          resetStickerBackgrounds();
          setSticker('kiss');
          kissSticker.style.backgroundColor = '#E28585';
        });
    }
      
    if (sweetSticker) {
        sweetSticker.addEventListener('click', () => {
          resetStickerBackgrounds();
          setSticker('sweet');
          sweetSticker.style.backgroundColor = '#E28585';
        });
    }
      
    if (ribbonSticker) {
        ribbonSticker.addEventListener('click', () => {
          resetStickerBackgrounds();
          setSticker('ribbon');
          ribbonSticker.style.backgroundColor = '#E28585';
        });
    }
      
    if (sparkleSticker) {
        sparkleSticker.addEventListener('click', () => {
          resetStickerBackgrounds();
          setSticker('sparkle');
          sparkleSticker.style.backgroundColor = '#E28585';
        });
    }
      
    if (pearlSticker) {
        pearlSticker.addEventListener('click', () => {
          resetStickerBackgrounds();
          setSticker('pearl');
          pearlSticker.style.backgroundColor = '#E28585';
        });
    }
      
    if (classicSticker) {
        classicSticker.addEventListener('click', () => {
          resetStickerBackgrounds();
          setSticker('classic');
          classicSticker.style.backgroundColor = '#E28585';
        });
    }
      
    if (classicBSticker) {
        classicBSticker.addEventListener('click', () => {
          resetStickerBackgrounds();
          setSticker('classicB');
          classicBSticker.style.backgroundColor = '#E28585';
        });
    }
      
    if (softSticker) {
        softSticker.addEventListener('click', () => {
          resetStickerBackgrounds();
          setSticker('soft');
          softSticker.style.backgroundColor = '#E28585';
        });
    }
      
    if (bunnySticker) {
        bunnySticker.addEventListener('click', () => {
          resetStickerBackgrounds();
          setSticker('bunny');
          bunnySticker.style.backgroundColor = '#E28585';
        });
    }
      
    if (luckySticker) {
        luckySticker.addEventListener('click', () => {
          resetStickerBackgrounds();
          setSticker('lucky');
          luckySticker.style.backgroundColor = '#E28585';
        });
    }
      
    if (confettiSticker) {
        confettiSticker.addEventListener('click', () => {
          resetStickerBackgrounds();
          setSticker('confetti');
          confettiSticker.style.backgroundColor = '#E28585';
        });
    }
      
    if (ribbonCoquetteSticker) {
        ribbonCoquetteSticker.addEventListener('click', () => {
          resetStickerBackgrounds();
          setSticker('ribboncoquette');
          ribbonCoquetteSticker.style.backgroundColor = '#E28585';
        });
    }
      
    if (blueRibbonCoquetteSticker) {
        blueRibbonCoquetteSticker.addEventListener('click', () => {
          resetStickerBackgrounds();
          setSticker('blueribboncoquette');
          blueRibbonCoquetteSticker.style.backgroundColor = '#E28585';
        });
    }
      
    if (blackStarSticker) {
        blackStarSticker.addEventListener('click', () => {
          resetStickerBackgrounds();
          setSticker('blackstar');
          blackStarSticker.style.backgroundColor = '#E28585';
        });
    }
      
    if (yellowChickenSticker) {
        yellowChickenSticker.addEventListener('click', () => {
          resetStickerBackgrounds();
          setSticker('yellowchicken');
          yellowChickenSticker.style.backgroundColor = '#E28585';
        });
    }
      
    if (brownBearSticker) {
        brownBearSticker.addEventListener('click', () => {
          resetStickerBackgrounds();
          setSticker('brownbear');
          brownBearSticker.style.backgroundColor = '#E28585';
        });
    }
      
    if (lotsHeartSticker) {
        lotsHeartSticker.addEventListener('click', () => {
          resetStickerBackgrounds();
          setSticker('lotsheart');
          lotsHeartSticker.style.backgroundColor = '#E28585';
        });
    }
      
    if (tabbyCatSticker) {
        tabbyCatSticker.addEventListener('click', () => {
          resetStickerBackgrounds();
          setSticker('tabbycat');
          tabbyCatSticker.style.backgroundColor = '#E28585';
        });
    }
      
    if (ballerinaCappuccinoSticker) {
        ballerinaCappuccinoSticker.addEventListener('click', () => {
          resetStickerBackgrounds();
          setSticker('ballerinacp');
          ballerinaCappuccinoSticker.style.backgroundColor = '#E28585';
        });
    }
      
    if (doggyWhiteSticker) {
        doggyWhiteSticker.addEventListener('click', () => {
          resetStickerBackgrounds();
          setSticker('doggywhite');
          doggyWhiteSticker.style.backgroundColor = '#E28585';
        });
    }
      
    if (sakuraBlossomSticker) {
        sakuraBlossomSticker.addEventListener('click', () => {
          resetStickerBackgrounds();
          setSticker('sakurablossom');
          sakuraBlossomSticker.style.backgroundColor = '#E28585';
        });
    }
      
    if (myGirlsSticker) {
        myGirlsSticker.addEventListener('click', () => {
          resetStickerBackgrounds();
          setSticker('mygirls');
          myGirlsSticker.style.backgroundColor = '#E28585';
        });
    }
      
    if (noneSticker) {
        noneSticker.addEventListener('click', () => {
          resetStickerBackgrounds();
          selectedSticker = null;
          redrawCanvas();
        });
    }

    // Event listeners for background change buttons
    // if (pinkBtn) pinkBtn.addEventListener('click', () => setBackground('#FFC2D1'));
    // if (blueBtn) blueBtn.addEventListener('click', () => setBackground('#CAF0F8'));
    // if (yellowBtn) yellowBtn.addEventListener('click', () => setBackground('#FFF8A5'));
    // if (matchaBtn) matchaBtn.addEventListener('click', () => setBackground('#90a955'));
    // if (purpleBtn) purpleBtn.addEventListener('click', () => setBackground('#c19ee0'));
    // if (brownBtn) brownBtn.addEventListener('click', () => setBackground('#DDBEA9'));
    // if (redBtn) redBtn.addEventListener('click', () => setBackground('#780000'));
    // if (whiteBtn) whiteBtn.addEventListener('click', () => setBackground('#FFFFFF'));
    // if (blackBtn) blackBtn.addEventListener('click', () => setBackground('#000000'));
    // if (ribbonBtn) ribbonBtn.addEventListener('click', () => setBackground('ribbon'));
    if (pinkBtn) {
        pinkBtn.addEventListener('click', () => {
            setBackground({ type: 'color', value: '#FFC2D1' });
        });
    }

    if (blueBtn) {
        blueBtn.addEventListener('click', () => {
            setBackground({ type: 'color', value: '#CAF0F8' });
        });
    }

    if (yellowBtn) {
        yellowBtn.addEventListener('click', () => {
            setBackground({ type: 'color', value: '#FFF8A5' });
        });
    }

    if (matchaBtn) {
        matchaBtn.addEventListener('click', () => {
            setBackground({ type: 'color', value: '#90a955' });
        });
    }

    if (purpleBtn) {
        purpleBtn.addEventListener('click', () => {
            setBackground({ type: 'color', value: '#c19ee0' });
        });
    }

    if (brownBtn) {
        brownBtn.addEventListener('click', () => {
            setBackground({ type: 'color', value: '#DDBEA9' });
        });
    }

    if (redBtn) {
        redBtn.addEventListener('click', () => {
            setBackground({ type: 'color', value: '#780000' });
        });
    }

    if (whiteBtn) {
        whiteBtn.addEventListener('click', () => {
            setBackground({ type: 'color', value: '#FFFFFF' });
        });
    }

    if (blackBtn) {
        blackBtn.addEventListener('click', () => {
            setBackground({ type: 'color', value: '#000000' });
        });
    }

    // BG IMG FRAMES
    if (pinkPlaid) {
        pinkPlaid.addEventListener('click', () => {
            setBackground({ type: 'image', src: '/src/assets/frame-backgrounds/pink-plaid.jpg'});
        });
    }
    if (pinkGlitter) {
        pinkGlitter.addEventListener('click', () => {
            setBackground({ type: 'image', src: '/src/assets/frame-backgrounds/pink-glitter.jpg'});
        });
    }
    if (bluePlaid) {
        bluePlaid.addEventListener('click', () => {
            setBackground({ type: 'image', src: '/src/assets/frame-backgrounds/blue-plaid.jpg'});
        });
    }
    if (brownLeopard) {
        brownLeopard.addEventListener('click', () => {
            setBackground({ type: 'image', src: '/src/assets/frame-backgrounds/brown-leopard.jpg'});
        });
    }
    if (cowPrint) {
        cowPrint.addEventListener('click', () => {
            setBackground({ type: 'image', src: '/src/assets/frame-backgrounds/cow-print.jpg'});
        });
    }
    if (redLeather) {
        redLeather.addEventListener('click', () => {
            setBackground({ type: 'image', src: '/src/assets/frame-backgrounds/red-leather.jpg'});
        });
    }
    if (pinkGumamela) {
        pinkGumamela.addEventListener('click', () => {
            setBackground({ type: 'image', src: '/src/assets/frame-backgrounds/pink-gumamela.jpg'});
        });
    }
    if (whiteKnitted) {
        whiteKnitted.addEventListener('click', () => {
            setBackground({ type: 'image', src: '/src/assets/frame-backgrounds/white-knitted-cloth.jpg'});
        });
    }
    if (blackCq) {
        blackCq.addEventListener('click', () => {
            setBackground({ type: 'image', src: '/src/assets/frame-backgrounds/black-couqutte.jpg'});
        });
    }
    if (whiteCq) {
        whiteCq.addEventListener('click', () => {
            setBackground({ type: 'image', src: '/src/assets/frame-backgrounds/white-couquette.jpg'});
        });
    }
    if (pinkLeather) {
        pinkLeather.addEventListener('click', () => {
            setBackground({ type: 'image', src: '/src/assets/frame-backgrounds/pink-diamond-leather.jpg'});
        });
    }
    if (ribbonDenim) {
        ribbonDenim.addEventListener('click', () => {
            setBackground({ type: 'image', src: '/src/assets/frame-backgrounds/ribbon-denim.jpg'});
        });
    }
    if (blackPinkRibbon) {
        blackPinkRibbon.addEventListener('click', () => {
            setBackground({ type: 'image', src: '/src/assets/frame-backgrounds/black-pink-ribbon.jpg'});
        });
    }
    if (blueYellowSquares) {
        blueYellowSquares.addEventListener('click', () => {
            setBackground({ type: 'image', src: '/src/assets/frame-backgrounds/blue-yellow-squares.jpg'});
        });
    }
    if (blueWhiteSquares) {
        blueWhiteSquares.addEventListener('click', () => {
            setBackground({ type: 'image', src: '/src/assets/frame-backgrounds/blue-white-squares.jpg'});
        });
    }
    if (fourLockers) {
        fourLockers.addEventListener('click', () => {
            setBackground({ type: 'image', src: '/src/assets/frame-backgrounds/4-lockers.jpg'});
        });
    }
    if (crumpledPaper) {
        crumpledPaper.addEventListener('click', () => {
            setBackground({ type: 'image', src: '/src/assets/frame-backgrounds/crumpled-paper.jpg'});
        });
    }
    if (blueBackdrop) {
        blueBackdrop.addEventListener('click', () => {
            setBackground({ type: 'image', src: '/src/assets/frame-backgrounds/blue-backdrop.jpg'});
        });
    }
    if (greenHills) {
        greenHills.addEventListener('click', () => {
            setBackground({ type: 'image', src: '/src/assets/frame-backgrounds/green-hills.jpg'});
        });
    }
    if (sandShells) {
        sandShells.addEventListener('click', () => {
            setBackground({ type: 'image', src: '/src/assets/frame-backgrounds/sand-shells.jpg'});
        });
    }
    if (waterBeach) {
        waterBeach.addEventListener('click', () => {
            setBackground({ type: 'image', src: '/src/assets/frame-backgrounds/water.jpg'});
        });
    }
    if (cocoTrees) {
        cocoTrees.addEventListener('click', () => {
            setBackground({ type: 'image', src: '/src/assets/frame-backgrounds/coco-trees.jpg'});
        });
    }
    if (pinkLiliesFrame) {
        pinkLiliesFrame.addEventListener('click', () => {
            setBackground({ type: 'image', src: '/src/assets/frame-backgrounds/pink-lilies.jpg'});
        });
    }
    if (roseCardFrame) {
        roseCardFrame.addEventListener('click', () => {
            setBackground({ type: 'image', src: '/src/assets/frame-backgrounds/rose-card.jpg'});
        });
    }
    if (princessVintageFrame) {
        princessVintageFrame.addEventListener('click', () => {
            setBackground({ type: 'image', src: '/src/assets/frame-backgrounds/princess-vintage.jpg'});
        });
    }
    if (gridPaperFrame) {
        gridPaperFrame.addEventListener('click', () => {
            setBackground({ type: 'image', src: '/src/assets/frame-backgrounds/grid-paper.jpg'});
        });
    }
    if (stardustFrame) {
        stardustFrame.addEventListener('click', () => {
            setBackground({ type: 'image', src: '/src/assets/frame-backgrounds/stardust.jpg'});
        });
    }
    if (roughTextureFrame) {
        roughTextureFrame.addEventListener('click', () => {
            setBackground({ type: 'image', src: '/src/assets/frame-backgrounds/rough-texture.jpg'});
        });
    }
    if (ribbonSweaterFrame) {
        ribbonSweaterFrame.addEventListener('click', () => {
            setBackground({ type: 'image', src: '/src/assets/frame-backgrounds/ribbon-sweater.jpg'});
        });
    }
    if (vsPinkFrame) {
        vsPinkFrame.addEventListener('click', () => {
            setBackground({ type: 'image', src: '/src/assets/frame-backgrounds/vs-pink.jpg'});
        });
    }
    if (vsYellowFrame) {
        vsYellowFrame.addEventListener('click', () => {
            setBackground({ type: 'image', src: '/src/assets/frame-backgrounds/vs-yellow.jpg'});
        });
    }
    if (redRosesPaintFrame) {
        redRosesPaintFrame.addEventListener('click', () => {
            setBackground({ type: 'image', src: '/src/assets/frame-backgrounds/red-roses-paint.jpg'});
        });
    }
    if (grayTrashFrame) {
        grayTrashFrame.addEventListener('click', () => {
            setBackground({ type: 'image', src: '/src/assets/frame-backgrounds/gray-trash.jpg'});
        });
    }
    if (blackTrashFrame) {
        blackTrashFrame.addEventListener('click', () => {
            setBackground({ type: 'image', src: '/src/assets/frame-backgrounds/black-trash.jpg'});
        });
    }
    if (whiteTrashFrame) {
        whiteTrashFrame.addEventListener('click', () => {
            setBackground({ type: 'image', src: '/src/assets/frame-backgrounds/white-trash.jpg'});
        });
    }
    if (brownKnittedFrame) {
        brownKnittedFrame.addEventListener('click', () => {
            setBackground({ type: 'image', src: '/src/assets/frame-backgrounds/brown-knitted.jpg'});
        });
    }
    if (hotPinkKnittedFrame) {
        hotPinkKnittedFrame.addEventListener('click', () => {
            setBackground({ type: 'image', src: '/src/assets/frame-backgrounds/hot-pink-knitted.jpg'});
        });
    }
    if (redKnittedFrame) {
        redKnittedFrame.addEventListener('click', () => {
            setBackground({ type: 'image', src: '/src/assets/frame-backgrounds/red-knitted.jpg'});
        });
    }
    if (pinkKnittedFrame) {
        pinkKnittedFrame.addEventListener('click', () => {
            setBackground({ type: 'image', src: '/src/assets/frame-backgrounds/pink-knitted.jpg'});
        });
    }
    if (redStripesFrame) {
        redStripesFrame.addEventListener('click', () => {
            setBackground({ type: 'image', src: '/src/assets/frame-backgrounds/red-stripes.jpg'});
        });
    }
    if (greenStripesFrame) {
        greenStripesFrame.addEventListener('click', () => {
            setBackground({ type: 'image', src: '/src/assets/frame-backgrounds/green-stripes.jpg'});
        });
    }
    if (blueStripesFrame) {
        blueStripesFrame.addEventListener('click', () => {
            setBackground({ type: 'image', src: '/src/assets/frame-backgrounds/blue-stripes.jpg'});
        });
    }
    if (partyDrapeFrame) {
        partyDrapeFrame.addEventListener('click', () => {
            setBackground({ type: 'image', src: '/src/assets/frame-backgrounds/party-drape.jpg'});
        });
    }
    if (partyDotsFrame) {
        partyDotsFrame.addEventListener('click', () => {
            setBackground({ type: 'image', src: '/src/assets/frame-backgrounds/party-dots.jpg'});
        });
    }
    if (blingDenimFrame) {
        blingDenimFrame.addEventListener('click', () => {
            setBackground({ type: 'image', src: '/src/assets/frame-backgrounds/bling-denim.jpg'});
        });
    }

    // if(engLogo) engLogo.addEventListener('click', () => updateText('photobooth'));
    // if(korLogo) korLogo.addEventListener('click', () => updateText('포토부스'));
    // if(engLogo) cnLogo.addEventListener('click', () => updateText('照相亭'));
    
    const allLogoButtons = [engLogo, korLogo, cnLogo];

    function resetLogoBackgrounds() {
      allLogoButtons.forEach(btn => {
        if (btn) btn.style.backgroundColor = '';
      });
    }
    
    if (engLogo) {
      engLogo.addEventListener('click', () => {
        resetLogoBackgrounds();
        updateText('photobooth');
        engLogo.style.backgroundColor = '#E28585';
        engLogo.classList.add('active');
      });
    }
    
    if (korLogo) {
      korLogo.addEventListener('click', () => {
        resetLogoBackgrounds();
        updateText('포토부스');
        korLogo.style.backgroundColor = '#E28585';
        korLogo.classList.add('active');
      });
    }
    
    if (cnLogo) {
      cnLogo.addEventListener('click', () => {
        resetLogoBackgrounds();
        updateText('照相亭');
        cnLogo.style.backgroundColor = '#E28585';
        cnLogo.classList.add('active');
      });
    }

    function updateText(text) {
        selectedText = text;
        redrawCanvas();
        drawTextAndDate(ctx, canvas);
    }

    function drawTextAndDate(ctx, canvas) {
        if (backgroundType === 'color') {
            if(backgroundColor !== '#000000' && backgroundColor !== '#780000' && backgroundColor !== '#90a955') {
                ctx.fillStyle = 'black';
            }
            else {
                ctx.fillStyle = '#FFFFFF';
            }
        } else if (backgroundType === 'image' && backgroundImage) {
            const imageName = backgroundImage.src.split('/').pop(); // just the filename

            const darkImages = [
                'pink-glitter.jpg',
                'brown-leopard.jpg',
                'red-leather.jpg',
                'ribbon-denim.jpg',
                'black-pink-ribbon.jpg',
                'green-hills.jpg',
                'sand-shells.jpg',
                'coco-trees.jpg',
                'brown-knitted.jpg',
                'red-knitted.jpg',
                'blue-yellow-squares.jpg',
                '4-lockers.jpg',
                'black-trash.jpg',
                'party-drape.jpg',
                'party-dots.jpg',
                'bling-denim.jpg'
            ];

            if (darkImages.includes(imageName)) {
                ctx.fillStyle = '#FFFFFF';
            } else {
                ctx.fillStyle = '#000000';
            }
        }
        // ctx.fillStyle = (backgroundType === '#000000' || backgroundType === '#780000' || backgroundType === '#90a955') ? '#FFFFFF' : 'black';
        ctx.font = 'bold 32px Arial, Roboto, sans-serif'; //prev 24px
        ctx.textAlign = 'center';
        ctx.fillText(selectedText, canvas.width / 2, canvas.height - 55); //previously 65
    }

    function drawSticker(ctx, stackedCanvas) {
        return new Promise((resolve) => {
            if (!selectedSticker) {
                resolve(); // No sticker selected, resolve immediately
                return;
            }
    
            const stickerLayouts = {
                'kiss': [{ src: '/src/assets/stickers/kiss1.png', x: 30, y: 300, size: 170 }],
                'sweet': [
                    { src: '/src/assets/stickers/sweet1.png', x: 17, y: 80, size: 90 },
                    { src: '/src/assets/stickers/sweet2.png', x: stackedCanvas.width - 100, y: 390, size: 90 },
                    { src: '/src/assets/stickers/sweet3.png', x: 30, y: stackedCanvas.height - 200, size: 90 }
                ],
                'ribbon': [
                    { src: '/src/assets/stickers/ribbon1.png', x: 17, y: 80, size: 90 },
                    { src: '/src/assets/stickers/ribbon3.png', x: stackedCanvas.width - 100, y: 550, size: 95 },
                    { src: '/src/assets/stickers/ribbon2.png', x: 15, y: stackedCanvas.height - 380, size: 90 }
                ],
                'sparkle': [    
                    { src: '/src/assets/stickers/sparkle1.png', x: stackedCanvas.width - 200, y: 150, size: 250 },
                    { src: '/src/assets/stickers/sparkle2.png', x: 2, y: stackedCanvas.height - 850, size: 200 },
                    { src: '/src/assets/stickers/sparkle2.png', x: stackedCanvas.width - 150, y: stackedCanvas.height - 180, size: 110 }
                ],
                'pearl': [    
                    { src: '/src/assets/stickers/pearl1.png', x: 35, y: 30, size: 30 },
                    { src: '/src/assets/stickers/pearl1.png', x: 25, y: 50, size: 25 },
                    { src: '/src/assets/stickers/pearl1.png', x: 65, y: 40, size: 20 },
                    { src: '/src/assets/stickers/pearl1.png', x: 95, y: 40, size: 15 },
                    { src: '/src/assets/stickers/pearl1.png', x: 75, y: 26, size: 15 },
                    { src: '/src/assets/stickers/pearl1.png', x: 25, y: 65, size: 55 },
                    { src: '/src/assets/stickers/pearl1.png', x: 25, y: 120, size: 15 },
                    //middle
                    { src: '/src/assets/stickers/pearl1.png', x: 825, y: stackedCanvas.height - 620, size: 15 },
                    { src: '/src/assets/stickers/pearl1.png', x: 800, y: stackedCanvas.height - 690, size: 15 },
                    { src: '/src/assets/stickers/pearl1.png', x: 840, y: stackedCanvas.height - 650, size: 25 },
                    { src: '/src/assets/stickers/pearl1.png', x: 830, y: stackedCanvas.height - 700, size: 45 },
                    { src: '/src/assets/stickers/pearl1.png', x: 810, y: stackedCanvas.height - 670, size: 30 },
                    { src: '/src/assets/stickers/pearl1.png', x: 810, y: stackedCanvas.height - 710, size: 30 },
                    // heart pearl
                    { src: '/src/assets/stickers/pearl1.png', x: 15, y: stackedCanvas.height - 200, size: 30 },
                    { src: '/src/assets/stickers/pearl1.png', x: 35, y: stackedCanvas.height - 220, size: 20 },
                    { src: '/src/assets/stickers/pearl1.png', x: 20, y: stackedCanvas.height - 240, size: 15 },
                    //hearts
                    { src: '/src/assets/stickers/pearl2.png', x: 15, y: stackedCanvas.height - 180, size: 150 },
                    { src: '/src/assets/stickers/pearl2.png', x: stackedCanvas.width - 150, y: 270, size: 120 }
                ],
                'classic': [
                    { src: '/src/assets/stickers/classic1.png', x: 1, y: 50, size: 32},
                    { src: '/src/assets/stickers/classic1.png', x: 1, y: 450, size: 32},
                    { src: '/src/assets/stickers/classic1.png', x: 1, y: 950, size: 32},
                    { src: '/src/assets/stickers/classic2.png', x: 4, y: 80, size: 20},
                    { src: '/src/assets/stickers/classic2.png', x: 4, y: 980, size: 20},
                    { src: '/src/assets/stickers/classic3.png', x: stackedCanvas.width - 75, y: 70, size: 120},
                    { src: '/src/assets/stickers/classic3.png', x: stackedCanvas.width - 75, y: 570, size: 120}
                ],
                'classicB': [
                    { src: '/src/assets/stickers/classic4.png', x: 1, y: 50, size: 32},
                    { src: '/src/assets/stickers/classic4.png', x: 1, y: 450, size: 32},
                    { src: '/src/assets/stickers/classic4.png', x: 1, y: 950, size: 32},
                    { src: '/src/assets/stickers/classic5.png', x: 4, y: 80, size: 20},
                    { src: '/src/assets/stickers/classic5.png', x: 4, y: 980, size: 20},
                    { src: '/src/assets/stickers/classic6.png', x: stackedCanvas.width - 75, y: 70, size: 120},
                    { src: '/src/assets/stickers/classic6.png', x: stackedCanvas.width - 75, y: 570, size: 120}
                ],
                'soft': [
                    { src: '/src/assets/stickers/soft1.png', x: 5, y: 20, size: 145},
                    { src: '/src/assets/stickers/soft2.png', x: stackedCanvas.width - 120, y: 30, size: 100},
                    { src: '/src/assets/stickers/soft3.png', x: 10, y: 500, size: 120},
                    { src: '/src/assets/stickers/soft4.png', x: 15, y: stackedCanvas.height - 200, size: 120},
                    { src: '/src/assets/stickers/soft5.png', x: stackedCanvas.width - 130, y: stackedCanvas.height - 200, size: 120},
                    { src: '/src/assets/stickers/soft6.png', x: stackedCanvas.width - 130, y: 430, size: 120}
                ],
                'bunny': [
                    { src: '/src/assets/stickers/bunny1.png', x: stackedCanvas.width - 170, y: 10, size: 150},
                    { src: '/src/assets/stickers/bunny2.png', x: 15, y: 300, size: 95},
                    { src: '/src/assets/stickers/bunny2.png', x: stackedCanvas.width - 100, y: 700, size: 75},
                    { src: '/src/assets/stickers/bunny3.png', x: 15, y: stackedCanvas.height - 200, size: 135}
                ],
                'lucky' : [
                    { src: '/src/assets/stickers/lucky2.png', x: stackedCanvas.width - 170, y: 20, size: 150},
                    { src: '/src/assets/stickers/lucky1.png', x: 15, y: stackedCanvas.height - 215, size: 170},

                    // lucky text
                    { src: '/src/assets/stickers/lucky3.png', x: stackedCanvas.width - 120, y: 420, size: 90},
                    { src: '/src/assets/stickers/lucky4.png', x: stackedCanvas.width - 120, y: 495, size: 90},
                    { src: '/src/assets/stickers/lucky5.png', x: stackedCanvas.width - 128, y: 560, size: 110},
                    { src: '/src/assets/stickers/lucky6.png', x: stackedCanvas.width - 120, y: 650, size: 100},
                    { src: '/src/assets/stickers/lucky7.png', x: stackedCanvas.width - 120, y: 730, size: 95}

                ],
                'confetti' : [
                    { src: '/src/assets/stickers/confetti/star5.png', x: 100, y: 20, size: 40},
                    { src: '/src/assets/stickers/confetti/circle2.png', x: stackedCanvas.width - 120, y: 45, size: 50},
                    { src: '/src/assets/stickers/confetti/circle1.png', x: 30, y: 150, size: 35},
                    { src: '/src/assets/stickers/confetti/circle1.png', x: stackedCanvas.width - 70, y: 180, size: 32},
                    { src: '/src/assets/stickers/confetti/star2.png', x: stackedCanvas.width - 100, y: 320, size: 40},


                    { src: '/src/assets/stickers/confetti/star1.png', x: 100, y: 450, size: 32},
                    { src: '/src/assets/stickers/confetti/circle2.png', x: stackedCanvas.width - 30, y: 530, size: 27},
                    { src: '/src/assets/stickers/confetti/star4.png', x: stackedCanvas.width - 70, y: 580, size: 27},
                    { src: '/src/assets/stickers/confetti/star3.png', x: 22, y: 690, size: 47},
                    { src: '/src/assets/stickers/confetti/circle1.png', x: stackedCanvas.width - 200, y: 800, size: 32},
                    { src: '/src/assets/stickers/confetti/circle1.png', x: stackedCanvas.width - 200, y: 800, size: 32},


                    { src: '/src/assets/stickers/confetti/star2.png', x: 100, y: 850, size: 49},
                    { src: '/src/assets/stickers/confetti/circle2.png', x: 22, y: 990, size: 30},
                    { src: '/src/assets/stickers/confetti/star1.png', x: stackedCanvas.width - 100, y: stackedCanvas.height - 200, size: 35}
                ],
                // NEW STICKERS
                'ribboncoquette' : [
                    { src: '/src/assets/stickers/ribboncq2.png', x: 1, y: 0, size: 80},
                    { src: '/src/assets/stickers/ribboncq3.png', x: 392, y: 15, size: 95},
                    { src: '/src/assets/stickers/ribboncq1.png', x: stackedCanvas.width - 39, y: 25, size: 82},
                    { src: '/src/assets/stickers/ribboncq1.png', x: -22, y: 290, size: 75},
                    { src: '/src/assets/stickers/ribboncq2.png', x: stackedCanvas.width - 100, y: 240, size: 55},
                    { src: '/src/assets/stickers/ribboncq3.png', x: 5, y: 640, size: 52},
                    { src: '/src/assets/stickers/ribboncq3.png', x: stackedCanvas.width - 78, y: 520, size: 69},
                    { src: '/src/assets/stickers/ribboncq1.png', x: 399, y: stackedCanvas.height - 200, size: 82},
                    { src: '/src/assets/stickers/ribboncq2.png', x: stackedCanvas.width - 42, y: 760, size: 55},
                    { src: '/src/assets/stickers/ribboncq1.png', x: -15, y: 890, size: 72},
                    { src: '/src/assets/stickers/ribboncq3.png', x: stackedCanvas.width - 70, y: 1020, size: 55},
                    { src: '/src/assets/stickers/ribboncq2.png', x: -8, y: stackedCanvas.height - 200, size: 80},
                    { src: '/src/assets/stickers/ribboncq3.png', x: stackedCanvas.width - 70, y: stackedCanvas.height - 100, size: 55}
                    

                ],
                'blueribboncoquette' : [
                    { src: '/src/assets/stickers/blueRibbon.png', x: 1, y: 0, size: 80},
                    { src: '/src/assets/stickers/blueRibbon.png', x: 392, y: 15, size: 95},
                    { src: '/src/assets/stickers/blueRibbon.png', x: stackedCanvas.width - 39, y: 25, size: 82},
                    { src: '/src/assets/stickers/blueRibbon.png', x: -22, y: 290, size: 75},
                    { src: '/src/assets/stickers/blueRibbon.png', x: stackedCanvas.width - 100, y: 240, size: 55},
                    { src: '/src/assets/stickers/blueRibbon.png', x: 5, y: 640, size: 52},
                    { src: '/src/assets/stickers/blueRibbon.png', x: stackedCanvas.width - 78, y: 520, size: 69},
                    { src: '/src/assets/stickers/blueRibbon.png', x: 399, y: stackedCanvas.height - 200, size: 82},
                    { src: '/src/assets/stickers/blueRibbon.png', x: stackedCanvas.width - 42, y: 760, size: 55},
                    { src: '/src/assets/stickers/blueRibbon.png', x: -15, y: 890, size: 72},
                    { src: '/src/assets/stickers/blueRibbon.png', x: stackedCanvas.width - 70, y: 1020, size: 55},
                    { src: '/src/assets/stickers/blueRibbon.png', x: -8, y: stackedCanvas.height - 200, size: 80},
                    { src: '/src/assets/stickers/blueRibbon.png', x: stackedCanvas.width - 70, y: stackedCanvas.height - 100, size: 55}
                ],
                'blackstar' : [
                    { src: '/src/assets/stickers/blackStar1.png', x: 340, y: -90, size: 250},
                    { src: '/src/assets/stickers/blackStar2.png', x: stackedCanvas.width - 90, y: -30, size: 110},
                    { src: '/src/assets/stickers/blackStar3.png', x: 18, y: 130, size: 98},
                    { src: '/src/assets/stickers/blackStar4.png', x: stackedCanvas.width - 120, y: 230, size: 115},
                    { src: '/src/assets/stickers/blackStar2.png', x: -17, y: 390, size: 65},
                    { src: '/src/assets/stickers/blackStar3.png', x: stackedCanvas.width - 190, y: 590, size: 145},
                    { src: '/src/assets/stickers/blackStar2.png', x: 15, y: 750, size: 45},
                    { src: '/src/assets/stickers/blackStar2.png', x: stackedCanvas.width - 80, y: 950, size: 55},
                    // { src: '/src/assets/stickers/blackstar1.png', x: 180, y: stackedCanvas.height - 230, size: 250},
                    { src: '/src/assets/stickers/blackStar4.png', x: -50, y: stackedCanvas.height - 150, size: 190}
                    

                ],
                'yellowchicken' : [
                    { src: '/src/assets/stickers/yellowChicken1.png', x: 1, y: 2, size: 90},
                    { src: '/src/assets/stickers/yellowChicken2.png', x: 220, y: -2, size: 66},
                    { src: '/src/assets/stickers/yellowChicken1.png', x: stackedCanvas.width - 160, y: 25, size: 55},
                    { src: '/src/assets/stickers/yellowChicken2.png', x: stackedCanvas.width - 90, y: 145, size: 85},
                    { src: '/src/assets/stickers/yellowChicken2.png', x: -20, y: 245, size: 95},
                    { src: '/src/assets/stickers/yellowChicken1.png', x: 140, y: 360, size: 55},
                    { src: '/src/assets/stickers/yellowChicken2.png', x: stackedCanvas.width - 220, y: 660, size: 55},
                    { src: '/src/assets/stickers/yellowChicken3.png', x: stackedCanvas.width - 70, y: 490, size: 85},
                    { src: '/src/assets/stickers/yellowChicken1.png', x: -5, y: 630, size: 80},
                    { src: '/src/assets/stickers/yellowChicken1.png', x: stackedCanvas.width - 70, y: 830, size: 75},
                    { src: '/src/assets/stickers/yellowChicken2.png', x: stackedCanvas.width - 120, y: stackedCanvas.height - 190, size: 65},
                    { src: '/src/assets/stickers/yellowChicken4.png', x: 1, y: stackedCanvas.height - 230, size: 95},

                ],
                'brownbear' : [
                    { src: '/src/assets/stickers/brownyBear1.png', x: 1, y: 5, size: 120},
                    { src: '/src/assets/stickers/brownyBear2.png', x: stackedCanvas.width - 100, y: 25, size: 80},
                    { src: '/src/assets/stickers/brownyBear3.png', x: -8, y: 315, size: 82},
                    { src: '/src/assets/stickers/brownyBear4.png', x: stackedCanvas.width - 100, y: 490, size: 91},
                    { src: '/src/assets/stickers/brownyBear5.png', x: -2, y: 590, size: 91},
                    { src: '/src/assets/stickers/brownyBear2.png', x: stackedCanvas.width - 70, y: 790, size: 87},
                    { src: '/src/assets/stickers/brownyBear3.png', x: -1, y: stackedCanvas.height - 320, size: 82},
                    { src: '/src/assets/stickers/brownyBear6.png', x: stackedCanvas.width - 90, y: stackedCanvas.height - 200, size: 92}
                ],
                'lotsheart' : [
                    { src: '/src/assets/stickers/lotsHeart1.png', x: 1, y: 15, size: 78},
                    { src: '/src/assets/stickers/lotsHeart5.png', x: -18, y: 450, size: 78},
                    { src: '/src/assets/stickers/lotsHeart7.png', x: -8, y: 860, size: 60},
                    { src: '/src/assets/stickers/lotsHeart6.png', x: 25, y: stackedCanvas.height - 170, size: 70},

                    { src: '/src/assets/stickers/lotsHeart2.png', x: stackedCanvas.width - 250, y: 20, size: 60},
                    { src: '/src/assets/stickers/lotsHeart4.png', x: stackedCanvas.width - 70, y: 200, size: 57},
                    // { src: '/src/assets/stickers/lotsHeart4.png', x: 390, y: 200, size: 50},
                    { src: '/src/assets/stickers/lotsHeart6.png', x: stackedCanvas.width - 80, y: 650, size: 60},
                    { src: '/src/assets/stickers/lotsHeart3.png', x: stackedCanvas.width - 50, y: 990, size: 50},
                    { src: '/src/assets/stickers/lotsHeart8.png', x: stackedCanvas.width - 90, y: stackedCanvas.height - 140, size: 70},
                ],
                'tabbycat' : [
                    { src: '/src/assets/stickers/tabbyCat1.png', x: 1, y: 2, size: 95},
                    { src: '/src/assets/stickers/tabbyCat2.png', x: 220, y: -2, size: 66},
                    { src: '/src/assets/stickers/tabbyCat3.png', x: stackedCanvas.width - 160, y: 25, size: 60},
                    { src: '/src/assets/stickers/tabbyCat4.png', x: stackedCanvas.width - 90, y: 145, size: 95},
                    { src: '/src/assets/stickers/tabbyCat9.png', x: -20, y: 245, size: 100},
                    { src: '/src/assets/stickers/tabbyCat2.png', x: 140, y: 360, size: 55},
                    { src: '/src/assets/stickers/tabbyCat2.png', x: stackedCanvas.width - 220, y: 970, size: 65},
                    { src: '/src/assets/stickers/tabbyCat5.png', x: stackedCanvas.width - 90, y: 490, size: 95},
                    { src: '/src/assets/stickers/tabbyCat6.png', x: -5, y: 630, size: 84},
                    { src: '/src/assets/stickers/tabbyCat9.png', x: stackedCanvas.width - 70, y: 830, size: 75},
                    { src: '/src/assets/stickers/tabbyCat3.png', x: -5, y: 830, size: 75},
                    { src: '/src/assets/stickers/tabbyCat3.png', x: stackedCanvas.width - 120, y: stackedCanvas.height - 190, size: 65},
                    { src: '/src/assets/stickers/tabbyCat7.png', x: 1, y: stackedCanvas.height - 230, size: 120}

                ],
                'ballerinacp' : [
                    { src: '/src/assets/stickers/ballerinaCappuccino/balerinaCappuccino1.png', x: 1, y: 2, size: 125},
                    { src: '/src/assets/stickers/ballerinaCappuccino/balerinaCappuccino3.png', x: 402, y: -2, size: 90},
                    { src: '/src/assets/stickers/ballerinaCappuccino/balerinaCappuccino4.png', x: stackedCanvas.width - 130, y: 25, size: 150},
                    { src: '/src/assets/stickers/ballerinaCappuccino/balerinaCappuccino2.png', x: stackedCanvas.width - 120, y: 345, size: 129},
                    { src: '/src/assets/stickers/ballerinaCappuccino/balerinaCappuccino5.png', x: -10, y: 695, size: 150},
                    { src: '/src/assets/stickers/ballerinaCappuccino/balerinaCappuccino7.png', x: -10, y: 295, size: 100},
                    { src: '/src/assets/stickers/ballerinaCappuccino/balerinaCappuccino6.png', x: stackedCanvas.width - 115, y: 750, size: 110},
                    { src: '/src/assets/stickers/ballerinaCappuccino/balerinaCappuccino3.png', x: -5, y: stackedCanvas.height - 200, size: 145},
                    { src: '/src/assets/stickers/ballerinaCappuccino/balerinaCappuccino4.png', x: stackedCanvas.width - 130, y: stackedCanvas.height - 200, size: 125}
                ],
                'doggywhite' : [
                    { src: '/src/assets/stickers/doggyWhite/doggyWhite3.png', x: 1, y: 230, size: 115},
                    { src: '/src/assets/stickers/doggyWhite/doggyWhite1.png', x: stackedCanvas.width - 130, y: 25, size: 125},
                    { src: '/src/assets/stickers/doggyWhite/doggyWhite4.png', x: stackedCanvas.width - 110, y: 545, size: 115},
                    { src: '/src/assets/stickers/doggyWhite/doggyWhite2.png', x: -10, y: 695, size: 125},
                    { src: '/src/assets/stickers/doggyWhite/doggyWhite3.png', x: -5, y: stackedCanvas.height - 200, size: 115},
                    { src: '/src/assets/stickers/doggyWhite/doggyWhite1.png', x: stackedCanvas.width - 130, y: stackedCanvas.height - 200, size: 125}
                ],
                'mygirls' : [
                    { src: '/src/assets/stickers/myGirls/myGirls4.png', x: -30, y: 15, size: 200},
                    { src: '/src/assets/stickers/myGirls/myGirls7.png', x: 342, y: -25, size: 215},

                    { src: '/src/assets/stickers/myGirls/myGirls8.png', x: stackedCanvas.width - 110, y: 25, size: 105},
                    { src: '/src/assets/stickers/myGirls/myGirls10.png', x: stackedCanvas.width - 150, y: 95, size: 155},
                    { src: '/src/assets/stickers/myGirls/myGirls11.png', x: stackedCanvas.width - 95, y: 185, size: 115},

                    { src: '/src/assets/stickers/myGirls/myGirls6.png', x: stackedCanvas.width - 110, y: 395, size: 135},
                    { src: '/src/assets/stickers/myGirls/myGirls4.png', x: stackedCanvas.width - 110, y: 545, size: 135},
                    { src: '/src/assets/stickers/myGirls/myGirls1.png', x: stackedCanvas.width - 110, y: 695, size: 135},

                    { src: '/src/assets/stickers/myGirls/myGirls2.png', x: -20, y: 305, size: 125},
                    { src: '/src/assets/stickers/myGirls/myGirls4.png', x: -20, y: 450, size: 125},
                    { src: '/src/assets/stickers/myGirls/myGirls6.png', x: -20, y: 580, size: 125},

                    { src: '/src/assets/stickers/myGirls/myGirls10.png', x: -15, y: stackedCanvas.height - 370, size: 115},
                    { src: '/src/assets/stickers/myGirls/myGirls11.png', x: 15, y: stackedCanvas.height - 300, size: 155},
                    { src: '/src/assets/stickers/myGirls/myGirls9.png', x: -5, y: stackedCanvas.height - 200, size: 115},

                    { src: '/src/assets/stickers/myGirls/myGirls5.png', x: stackedCanvas.width - 190, y: stackedCanvas.height - 290, size: 222}
                ],
                'sakurablossom' : [
                    { src: '/src/assets/stickers/sakuraBlossom/sakuraBlossom1.png', x: 1, y: 5, size: 120},
                    { src: '/src/assets/stickers/sakuraBlossom/sakuraBlossom2.png', x: stackedCanvas.width - 100, y: 25, size: 80},
                    { src: '/src/assets/stickers/sakuraBlossom/sakuraBlossom4.png', x: stackedCanvas.width - 120, y: 95, size: 40},
                    { src: '/src/assets/stickers/sakuraBlossom/sakuraBlossom2.png', x: -8, y: 315, size: 82},
                    { src: '/src/assets/stickers/sakuraBlossom/sakuraBlossom3.png', x: 35, y: 390, size: 50},
                    { src: '/src/assets/stickers/sakuraBlossom/sakuraBlossom2.png', x: stackedCanvas.width - 100, y: 490, size: 91},
                    { src: '/src/assets/stickers/sakuraBlossom/sakuraBlossom4.png', x: stackedCanvas.width - 140, y: 430, size: 35},
                    { src: '/src/assets/stickers/sakuraBlossom/sakuraBlossom2.png', x: -2, y: 590, size: 91},
                    { src: '/src/assets/stickers/sakuraBlossom/sakuraBlossom3.png', x: -15, y: 700, size: 45},
                    { src: '/src/assets/stickers/sakuraBlossom/sakuraBlossom5.png', x: 50, y: 900, size: 45},
                    { src: '/src/assets/stickers/sakuraBlossom/sakuraBlossom1.png', x: stackedCanvas.width - 70, y: 790, size: 87},
                    { src: '/src/assets/stickers/sakuraBlossom/sakuraBlossom2.png', x: -1, y: stackedCanvas.height - 320, size: 82},
                    { src: '/src/assets/stickers/sakuraBlossom/sakuraBlossom5.png', x: 30, y: stackedCanvas.height - 100, size: 48},
                    { src: '/src/assets/stickers/sakuraBlossom/sakuraBlossom5.png', x: 90, y: stackedCanvas.height - 40, size: 44},
                    { src: '/src/assets/stickers/sakuraBlossom/sakuraBlossom2.png', x: stackedCanvas.width - 90, y: stackedCanvas.height - 200, size: 92},
                    { src: '/src/assets/stickers/sakuraBlossom/sakuraBlossom4.png', x: stackedCanvas.width - 40, y: stackedCanvas.height - 290, size: 40}
                ]
                // 'island': [
                //     { src: '/src/assets/stickers/aloha1.png', x: 10, y: 80, size: 80 },
                //     { src: '/src/assets/stickers/aloha1.png', x: stackedCanvas.width - 100, y: 320, size: 50 },
                //     { src: '/src/assets/stickers/aloha1.png', x: stackedCanvas.width - 580, y: 750, size: 50 },
                //     { src: '/src/assets/stickers/aloha2.png', x: stackedCanvas.width - 100, y: stackedCanvas.height - 180, size: 95 },
                //     { src: '/src/assets/stickers/aloha2.png', x: stackedCanvas.width - 580, y: stackedCanvas.height - 180, size: 95 }
                // ]
            };
    
            const stickersToDraw = stickerLayouts[selectedSticker];
            if (!stickersToDraw) {
                resolve(); // No stickers found, resolve immediately
                return;
            }
    
            let loadedCount = 0;
            
            stickersToDraw.forEach(({ src, x, y, size }) => {
                const stickerImg = new Image();
                stickerImg.src = src;
                
                stickerImg.onload = function () {
                    ctx.drawImage(stickerImg, x, y, size, size);
                    loadedCount++;
    
                    // Resolve the promise only when all stickers have been drawn
                    if (loadedCount === stickersToDraw.length) {
                        resolve();
                    }
                };
                
                stickerImg.onerror = function () {
                    console.error(`Failed to load sticker: ${src}`);
                    loadedCount++;
                    if (loadedCount === stickersToDraw.length) {
                        resolve(); // Still resolve even if an image fails
                    }
                };
            });
        });
    }
    
    function clipAndDrawImage(ctx, img, sx, sy, sWidth, sHeight, dx, dy, dWidth, dHeight, shapeType) {
        ctx.save();
        ctx.beginPath();
    
        const centerX = dx + dWidth / 2;
        const centerY = dy + dHeight / 2;
    
        if (shapeType === 'circle') {
            const radius = Math.min(dWidth, dHeight) / 2;
            ctx.arc(centerX, centerY, radius, 0, Math.PI * 2);
        } else if (shapeType === 'rounded') {
            const radius = 30;
            ctx.moveTo(dx + radius, dy);
            ctx.lineTo(dx + dWidth - radius, dy);
            ctx.quadraticCurveTo(dx + dWidth, dy, dx + dWidth, dy + radius);
            ctx.lineTo(dx + dWidth, dy + dHeight - radius);
            ctx.quadraticCurveTo(dx + dWidth, dy + dHeight, dx + dWidth - radius, dy + dHeight);
            ctx.lineTo(dx + radius, dy + dHeight);
            ctx.quadraticCurveTo(dx, dy + dHeight, dx, dy + dHeight - radius);
            ctx.lineTo(dx, dy + radius);
            ctx.quadraticCurveTo(dx, dy, dx + radius, dy);
        } else if (shapeType === 'heart') {
            ctx.beginPath();

            // Start at the bottom point
            ctx.moveTo(dx + dWidth / 2, dy + dHeight);

            // Right side of heart with deeper curve
            ctx.bezierCurveTo(
                dx + dWidth * 1.25, dy + dHeight * 0.7,    // More pronounced right bulge
                dx + dWidth * 0.9, dy - dHeight * 0.1,     // Higher right top control point
                dx + dWidth / 2, dy + dHeight * 0.25       // Deeper V dip at top
            );

            // Left side of heart, mirror of right side
            ctx.bezierCurveTo(
                dx + dWidth * 0.1, dy - dHeight * 0.1,     // Higher left top control point
                dx - dWidth * 0.25, dy + dHeight * 0.7,    // More pronounced left bulge
                dx + dWidth / 2, dy + dHeight              // Back to bottom point
            );

            ctx.closePath();
        } else {
            ctx.rect(dx, dy, dWidth, dHeight); // default rectangle
        }
    
        ctx.clip();
        ctx.drawImage(img, sx, sy, sWidth, sHeight, dx, dy, dWidth, dHeight);
        ctx.restore();
    }

    // heartFrameBtn.addEventListener('click', () => {
    //     selectedShape = 'heart';
    //     redrawCanvas();
    // });
    
    // circleFrameBtn.addEventListener('click', () => {
    //     selectedShape = 'circle';
    //     redrawCanvas();
    // });
    
    // roundEdgeFrameBtn.addEventListener('click', () => {
    //     selectedShape = 'rounded';
    //     redrawCanvas();
    // });

    // normalFrameBtn.addEventListener('click', () => {
    //     selectedShape = 'default'; // or 'none'
    //     redrawCanvas();
    // });

    const allFrameButtons = [heartFrameBtn, circleFrameBtn, roundEdgeFrameBtn, normalFrameBtn];

    function resetFrameBackgrounds() {
      allFrameButtons.forEach(btn => {
        if (btn) btn.style.backgroundColor = '';
      });
    }
    
    if (heartFrameBtn) {
      heartFrameBtn.addEventListener('click', () => {
        resetFrameBackgrounds();
        selectedShape = 'heart';
        heartFrameBtn.style.backgroundColor = '#E28585';
        redrawCanvas();
      });
    }
    
    if (circleFrameBtn) {
      circleFrameBtn.addEventListener('click', () => {
        resetFrameBackgrounds();
        selectedShape = 'circle';
        circleFrameBtn.style.backgroundColor = '#E28585';
        redrawCanvas();
      });
    }
    
    if (roundEdgeFrameBtn) {
      roundEdgeFrameBtn.addEventListener('click', () => {
        resetFrameBackgrounds();
        selectedShape = 'rounded';
        roundEdgeFrameBtn.style.backgroundColor = '#E28585';
        redrawCanvas();
      });
    }
    
    if (normalFrameBtn) {
      normalFrameBtn.addEventListener('click', () => {
        resetFrameBackgrounds();
        selectedShape = 'default'; // or 'none'
        normalFrameBtn.style.backgroundColor = '#E28585';
        redrawCanvas();
      });
    }


    // Function to redraw the canvas with stored images & selected background
    function redrawCanvas() {
        console.log(`Redrawing canvas with background: ${backgroundType}`);

        const stackedCanvas = document.createElement('canvas');
        const ctx = stackedCanvas.getContext('2d');

        const columns = 2;
        const rows = 3;
        const imageArrayLength = rows * columns;

        const canvasWidth = 900; //592
        const canvasHeight = 1352;
        const borderWidth = 30; //previously 40
        const spacing = 12; //previously 20
        const bottomPadding = 100;

        // const availableHeight = canvasHeight - (borderWidth * 2) - (spacing * 2) - bottomPadding;
        // const photoHeight = availableHeight / imageArrayLength;
        // const photoWidth = canvasWidth - (borderWidth * 2);

        const availableWidth = canvasWidth - (borderWidth * 2) - (spacing * (columns - 1));
        const availableHeight = canvasHeight - (borderWidth * 2) - (spacing * (rows - 1)) - bottomPadding;

        const photoWidth = availableWidth / columns;
        const photoHeight = availableHeight / rows;

        stackedCanvas.width = canvasWidth;
        stackedCanvas.height = canvasHeight;

        // ctx.fillStyle = backgroundType;
        // ctx.fillRect(0, 0, stackedCanvas.width, stackedCanvas.height);
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
            const imageName = backgroundImage.src.split('/').pop(); // just the filename

            const darkImages = [
                'pink-glitter.jpg',
                'brown-leopard.jpg',
                'red-leather.jpg',
                'ribbon-denim.jpg',
                'black-pink-ribbon.jpg',
                'green-hills.jpg',
                'sand-shells.jpg',
                'coco-trees.jpg',
                'brown-knitted.jpg',
                'red-knitted.jpg',
                'blue-yellow-squares.jpg',
                '4-lockers.jpg',
                'black-trash.jpg',
                'party-drape.jpg',
                'party-dots.jpg',
                'bling-denim.jpg'
            ];

            if (darkImages.includes(imageName)) {
                ctx.fillStyle = '#FFFFFF';
            } else {
                ctx.fillStyle = '#000000';
            }
        }


        ctx.font = 'bold 32px Arial, Roboto, sans-serif'; //prev 24px
        ctx.textAlign = 'center';
        ctx.fillText(selectedText, stackedCanvas.width / 2, stackedCanvas.height - 55);
        // ctx.fillText('photobooth', stackedCanvas.width / 2, stackedCanvas.height - 65);

        // ctx.fillStyle = '#D3D3D3';
        // ctx.font = '13px "DM Sans", Arial, Roboto, sans-serif';
        // ctx.textAlign = 'center';
        // ctx.fillText('©jmm', stackedCanvas.width / 2, stackedCanvas.height - 45);

        // Check if either date or time checkbox is checked
        if (dateCheckbox.checked || dateTimeCheckbox.checked) {
            const currentDate = new Date();
            let displayText = '';

            // Add date if Date checkbox is checked
            if (dateCheckbox.checked) {
                displayText += currentDate.toLocaleDateString();
            }

            // Add time if DateTime checkbox is checked
            if (dateTimeCheckbox.checked) {
                const timeString = currentDate.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                displayText += dateCheckbox.checked ? ' ' + timeString : timeString;
            }

            // Set text color based on background
            if (backgroundType === 'color') {
                ctx.fillStyle = (backgroundColor !== '#000000' && backgroundColor !== '#780000' && backgroundColor !== '#90a955') 
                    ? 'black' 
                    : '#FFFFFF';
            } else if (backgroundType === 'image' && backgroundImage) {
                const imageName = backgroundImage.src.split('/').pop();
                const darkImages = [
                    'pink-glitter.jpg',
                    'brown-leopard.jpg',
                    'red-leather.jpg',
                    'ribbon-denim.jpg',
                    'black-pink-ribbon.jpg',
                    'green-hills.jpg',
                    'sand-shells.jpg',
                    'coco-trees.jpg',
                    'brown-knitted.jpg',
                    'red-knitted.jpg',
                    'blue-yellow-squares.jpg',
                    '4-lockers.jpg',
                    'black-trash.jpg',
                    'party-drape.jpg',
                    'party-dots.jpg',
                    'bling-denim.jpg'
                ];
                ctx.fillStyle = darkImages.includes(imageName) ? '#FFFFFF' : '#000000';
            }

            ctx.font = '18px "DM Sans", Arial, Roboto, sans-serif';
            ctx.textAlign = 'center';

            // Draw the text at the center-bottom of the canvas
            ctx.fillText(displayText, stackedCanvas.width / 2, stackedCanvas.height - 30);
        }
        
        // updatePreview(stackedCanvas);

        // Draw stored images if available
        if (storedImages && storedImages.length === imageArrayLength) {
            let loadedImages = 0;
        
            storedImages.forEach((imgData, index) => {
                const img = new Image();
                img.src = imgData;
                img.onload = function () {
                    const imgAspect = img.width / img.height;
                    const targetAspect = photoWidth / photoHeight;
        
                    let sx, sy, sWidth, sHeight;
        
                    if (imgAspect > targetAspect) {
                        sHeight = img.height;
                        sWidth = img.height * targetAspect;
                        sx = (img.width - sWidth) / 2;
                        sy = 0;
                    } else {
                        sWidth = img.width;
                        sHeight = img.width / targetAspect;
                        sx = 0;
                        sy = (img.height - sHeight) / 2;
                    }
        
                    const col = index % columns;
                    const row = Math.floor(index / columns);
                    const x = borderWidth + col * (photoWidth + spacing);
                    const y = borderWidth + row * (photoHeight + spacing);
        
                    // ctx.drawImage(img, sx, sy, sWidth, sHeight, x, y, photoWidth, photoHeight);
                    clipAndDrawImage(ctx, img, sx, sy, sWidth, sHeight, x, y, photoWidth, photoHeight, selectedShape);
        
                    loadedImages++;
                    if (loadedImages === imageArrayLength) {
                        drawSticker(ctx, stackedCanvas);
                        updatePreview(stackedCanvas);
                    }
                };
            });
        } else {
            console.log("No stored images found.");
        }
    }

    
    // update preview of canvas
    function updatePreview(canvas) {
        if (photoCustomPreview) {
            photoCustomPreview.innerHTML = ''; // Clear old content
            photoCustomPreview.appendChild(canvas); // Append new canvas

            // canvas.style.width = "230px"; //prev 170px
            // Detect if the device is mobile based on screen width
            if (window.innerWidth <= 768) {
                // Mobile size
                canvas.style.width = "190px";
            } else {
                // Default/desktop size
                canvas.style.width = "230px";
            }
            if(backgroundType == '#FFFFFF') {
                canvas.style.border = '1px solid black';
            }
            
        }
    }

    // Detect if the user is on an iOS device
    function isIOS() {
        return /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
    }
    
    async function downloadCanvasCopy() {
        const stackedCanvas = document.createElement('canvas');
        const ctx = stackedCanvas.getContext('2d');
        
        const columns = 2;
        const rows = 3;
        const imageArrayLength = rows * columns;

        const canvasWidth = 900;
        const canvasHeight = 1352;
        const borderWidth = 30; //previously 40
        const spacing = 12; //previously 20
        const bottomPadding = 100;
    
        // const availableHeight = canvasHeight - (borderWidth * 2) - (spacing * 2) - bottomPadding;
        // const photoHeight = availableHeight / imageArrayLength;
        // const photoWidth = canvasWidth - (borderWidth * 2);

        const availableWidth = canvasWidth - (borderWidth * 2) - (spacing * (columns - 1));
        const availableHeight = canvasHeight - (borderWidth * 2) - (spacing * (rows - 1)) - bottomPadding;

        const photoWidth = availableWidth / columns;
        const photoHeight = availableHeight / rows;
    
        stackedCanvas.width = canvasWidth;
        stackedCanvas.height = canvasHeight;
    
        // ctx.fillStyle = backgroundType;
        // ctx.fillRect(0, 0, stackedCanvas.width, stackedCanvas.height);
        ctx.clearRect(0, 0, stackedCanvas.width, stackedCanvas.height);

        if (backgroundType === 'color') {
            ctx.fillStyle = backgroundColor;
            ctx.fillRect(0, 0, stackedCanvas.width, stackedCanvas.height);
        } else if (backgroundType === 'image' && backgroundImage) {
            ctx.drawImage(backgroundImage, 0, 0, stackedCanvas.width, stackedCanvas.height);
        }
    
        // Draw watermark text
        // ctx.fillStyle = '#D3D3D3';
        // ctx.font = '13px "DM Sans", Arial, Roboto, sans-serif';
        // ctx.textAlign = 'center';
        // ctx.fillText('©jmm', stackedCanvas.width / 2, stackedCanvas.height - 45);
    
        // Check if either date or time checkbox is checked
        if (dateCheckbox.checked || dateTimeCheckbox.checked) {
            const currentDate = new Date();
            let displayText = '';

            // Add date if Date checkbox is checked
            if (dateCheckbox.checked) {
                displayText += currentDate.toLocaleDateString();
            }

            // Add time if DateTime checkbox is checked
            if (dateTimeCheckbox.checked) {
                const timeString = currentDate.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                displayText += dateCheckbox.checked ? ' ' + timeString : timeString;
            }

            // Set text color based on background
            if (backgroundType === 'color') {
                ctx.fillStyle = (backgroundColor !== '#000000' && backgroundColor !== '#780000' && backgroundColor !== '#90a955') 
                    ? 'black' 
                    : '#FFFFFF';
            } else if (backgroundType === 'image' && backgroundImage) {
                const imageName = backgroundImage.src.split('/').pop();
                const darkImages = [
                    'pink-glitter.jpg',
                    'brown-leopard.jpg',
                    'red-leather.jpg',
                    'ribbon-denim.jpg',
                    'black-pink-ribbon.jpg',
                    'green-hills.jpg',
                    'sand-shells.jpg',
                    'coco-trees.jpg',
                    'brown-knitted.jpg',
                    'red-knitted.jpg',
                    'blue-yellow-squares.jpg',
                    '4-lockers.jpg',
                    'black-trash.jpg',
                    'party-drape.jpg',
                    'party-dots.jpg',
                    'bling-denim.jpg'
                ];
                ctx.fillStyle = darkImages.includes(imageName) ? '#FFFFFF' : '#000000';
            }

            ctx.font = '18px "DM Sans", Arial, Roboto, sans-serif';
            ctx.textAlign = 'center';

            // Draw the text at the center-bottom of the canvas
            ctx.fillText(displayText, stackedCanvas.width / 2, stackedCanvas.height - 30);
        }
    
        // Draw the 3x2 image grid
        if (storedImages && storedImages.length === imageArrayLength) {
            let loadedImages = 0;

            storedImages.forEach((imgData, index) => {
                const img = new Image();
                img.src = imgData;
                img.onload = async function () {
                    const imgAspect = img.width / img.height;
                    const targetAspect = photoWidth / photoHeight;

                    let sx, sy, sWidth, sHeight;

                    if (imgAspect > targetAspect) {
                        // Crop sides
                        sHeight = img.height;
                        sWidth = img.height * targetAspect;
                        sx = (img.width - sWidth) / 2;
                        sy = 0;
                    } else {
                        // Crop top and bottom
                        sWidth = img.width;
                        sHeight = img.width / targetAspect;
                        sx = 0;
                        sy = (img.height - sHeight) / 2;
                    }

                    // 3x2 grid position
                    const col = index % columns;
                    const row = Math.floor(index / columns);
                    const x = borderWidth + col * (photoWidth + spacing);
                    const y = borderWidth + row * (photoHeight + spacing);

                    // ctx.drawImage(img, sx, sy, sWidth, sHeight, x, y, photoWidth, photoHeight);
                    clipAndDrawImage(ctx, img, sx, sy, sWidth, sHeight, x, y, photoWidth, photoHeight, selectedShape);

                    loadedImages++;
                    if (loadedImages === imageArrayLength) {
                        drawTextAndDate(ctx, stackedCanvas);
                        await drawSticker(ctx, stackedCanvas);

                        const imageData = stackedCanvas.toDataURL('image/png');

                        if (isIOS()) {
                            const byteCharacters = atob(imageData.split(',')[1]);
                            const byteNumbers = new Array(byteCharacters.length);
                            for (let i = 0; i < byteCharacters.length; i++) {
                                byteNumbers[i] = byteCharacters.charCodeAt(i);
                            }
                            const byteArray = new Uint8Array(byteNumbers);
                            const blob = new Blob([byteArray], { type: 'image/png' });

                            const link = document.createElement('a');
                            const url = URL.createObjectURL(blob);
                            link.href = url;
                            link.download = 'photobooth.png';

                            document.body.appendChild(link);
                            link.click();

                            document.body.removeChild(link);
                            URL.revokeObjectURL(url);
                        } else {
                            const link = document.createElement('a');
                            link.href = imageData;
                            link.download = 'photobooth.png';
                            document.body.appendChild(link);
                            link.click();
                            document.body.removeChild(link);
                        }
                    }
                };

                img.onerror = function () {
                    console.error(`Failed to load image: ${img.src}`);
                };
            });
        } else {
            console.log("No stored images found.");
        }
    }
    
    

    //download final copy
    if (downloadCopyBtn) {
        downloadCopyBtn.addEventListener('click', downloadCanvasCopy);
    }

    window.onload = () => {
        redrawCanvas();
    };
})