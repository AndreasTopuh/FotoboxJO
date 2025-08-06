#!/bin/bash

echo "🔄 Updating customizeLayout2-3 with database integration..."

for i in {2..3}; do
    echo "Processing customizeLayout${i}..."
    
    # Update PHP file - add CSS styles if not exists
    echo "  Updating PHP file..."
    
    # Check if styles already exist
    if ! grep -q "loading-placeholder" src/pages/customizeLayout${i}.php; then
        # Add CSS styles after Mukta font link
        sed -i '/Mukta+Mahee.*&display=swap"/a\\n    <!-- Loading placeholder styles -->\n    <style>\n        .loading-placeholder {\n            display: flex;\n            justify-content: center;\n            align-items: center;\n            color: #666;\n            font-style: italic;\n            padding: 20px;\n            min-height: 50px;\n        }\n        \n        /* Hide loading placeholder when assets loaded */\n        .assets-loaded .loading-placeholder {\n            display: none;\n        }\n    </style>' src/pages/customizeLayout${i}.php
    fi
    
    # Add assets manager script if not exists
    if ! grep -q "assets-manager.js" src/pages/customizeLayout${i}.php; then
        sed -i "/customizeLayout${i}\.js/a\\    <!-- Assets Manager for dynamic loading -->\n    <script src=\"../assets/js/assets-manager.js\"></script>" src/pages/customizeLayout${i}.php
    fi
    
    echo "  ✅ Updated customizeLayout${i}.php"
    
    # Update JS file  
    echo "  Updating JavaScript file..."
    
    # Check if already updated
    if ! grep -q "loadAssetsFromDatabase" src/pages/customizeLayout${i}.js; then
        # Create temporary file with new header
        cat > temp_header_${i}.js << 'EOF'
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

            if (framesResponse.ok) {
                const framesData = await framesResponse.json();
                availableFrames = framesData.data || [];
                console.log(`✅ Loaded ${availableFrames.length} frames from database`);
            } else {
                console.warn('⚠️ Failed to load frames from database, using fallback');
                availableFrames = getFallbackFrames();
            }

            if (stickersResponse.ok) {
                const stickersData = await stickersResponse.json();
                availableStickers = stickersData.data || [];
                console.log(`✅ Loaded ${availableStickers.length} stickers from database`);
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
    }

    // Create dynamic controls for frames and stickers
    async function createDynamicControls() {
        console.log('🔄 Creating dynamic asset controls...');
        
        // Create frame controls
        const framesContainer = document.getElementById('frames-container');
        if (framesContainer) {
            framesContainer.innerHTML = ''; // Clear loading placeholder
            
            availableFrames.forEach((frame, index) => {
                const button = document.createElement('button');
                button.id = `frame_${frame.id}`;
                button.className = 'buttonFrames frame-dynamic';
                button.style.backgroundColor = frame.warna || '#ffffff';
                button.setAttribute('data-frame-id', frame.id);
                button.setAttribute('data-frame-color', frame.warna);
                button.setAttribute('data-frame-name', frame.nama);
                
                button.addEventListener('click', () => {
                    backgroundColor = frame.warna;
                    redrawCanvas();
                    console.log(`🎯 Selected frame: ${frame.nama} (${frame.warna})`);
                });
                
                framesContainer.appendChild(button);
            });
            
            console.log(`✅ Created ${availableFrames.length} frame controls`);
        }
        
        // Create sticker controls
        const stickersContainer = document.getElementById('stickers-container');
        if (stickersContainer) {
            stickersContainer.innerHTML = ''; // Clear loading placeholder
            
            // Add "none" sticker button first
            const noneButton = document.createElement('button');
            noneButton.id = 'noneSticker';
            noneButton.className = 'buttonStickers sticker-none';
            noneButton.innerHTML = '<img src="../assets/block (1).png" alt="None" class="shape-icon">';
            noneButton.addEventListener('click', () => {
                selectedSticker = null;
                redrawCanvas();
                console.log('🎯 No sticker selected');
            });
            stickersContainer.appendChild(noneButton);
            
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
                button.appendChild(img);
                
                // Create sticker object
                const stickerObj = {
                    image: new Image(),
                    src: sticker.file_path,
                    name: sticker.nama,
                    id: sticker.id
                };
                
                stickerObj.image.onload = () => {
                    console.log(`✅ Sticker preloaded: ${sticker.nama}`);
                };
                
                stickerObj.image.src = sticker.file_path;
                
                button.addEventListener('click', () => {
                    selectedSticker = stickerObj;
                    redrawCanvas();
                    console.log(`🎯 Selected sticker: ${sticker.nama}`);
                });
                
                stickersContainer.appendChild(button);
            });
            
            console.log(`✅ Created ${availableStickers.length} sticker controls`);
        }
        
        // Mark containers as loaded
        if (framesContainer) framesContainer.classList.add('assets-loaded');
        if (stickersContainer) stickersContainer.classList.add('assets-loaded');
        
        console.log('✅ Dynamic controls creation complete');
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

EOF
        
        # Get the rest of the JS file after the old initialization
        if grep -q "// Load photos from server" src/pages/customizeLayout${i}.js; then
            sed -n '/\/\/ Load photos from server/,$p' src/pages/customizeLayout${i}.js > temp_rest_${i}.js
        else
            # Find the first function after DOMContentLoaded
            sed -n '/async function loadPhotos/,$p' src/pages/customizeLayout${i}.js > temp_rest_${i}.js
        fi
        
        # Combine new header with rest of file
        cat temp_header_${i}.js temp_rest_${i}.js > temp_combined_${i}.js
        
        # Remove old initializeStickerControls function if exists
        sed -i '/async function initializeStickerControls/,/^    }/d' temp_combined_${i}.js
        
        # Replace the original file
        mv temp_combined_${i}.js src/pages/customizeLayout${i}.js
        
        # Clean up temporary files
        rm -f temp_header_${i}.js temp_rest_${i}.js
    fi
    
    echo "  ✅ Updated customizeLayout${i}.js"
    echo "  ✅ customizeLayout${i} update complete!"
done

echo "🎉 Layout 2-3 updated successfully!"
