#!/bin/bash
# filepath: /var/www/html/FotoboxJO/copy_video_feature.sh


# Buat file script
chmod +x /var/www/html/FotoboxJO/src/pages/script-terpakan-video.sh

# Jalankan script
cd /var/www/html/FotoboxJO
./src/pages/script-terpakan-video.sh
echo "🎬 Starting Video Feature Copy to All Layouts..."

# Define source and target files
SOURCE_JS="/var/www/html/FotoboxJO/src/pages/customizeLayout1.js"
SOURCE_PHP="/var/www/html/FotoboxJO/src/pages/customizeLayout1.php"

# Target layouts
LAYOUTS=(2 3 4 5 6)

# CSS to be added
VIDEO_CSS='
/* Video Button Styles */
.video-btn {
    background: linear-gradient(45deg, #ff6b6b, #ee5a52) !important;
    border: none !important;
    color: white !important;
    transition: all 0.3s ease !important;
}

.video-btn:hover {
    background: linear-gradient(45deg, #ee5a52, #dc4545) !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(238, 90, 82, 0.3);
}

.video-btn:disabled {
    background: #cccccc !important;
    cursor: not-allowed !important;
    transform: none !important;
    box-shadow: none !important;
}

/* Video Progress Modal */
.video-progress {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: rgba(0, 0, 0, 0.9);
    color: white;
    padding: 30px;
    border-radius: 15px;
    z-index: 9999;
    text-align: center;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.video-progress .progress-bar {
    width: 250px;
    height: 6px;
    background: #333;
    border-radius: 3px;
    overflow: hidden;
    margin: 15px auto;
    position: relative;
}

.video-progress .progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #ff6b6b, #ee5a52);
    width: 0%;
    transition: width 0.3s ease;
    border-radius: 3px;
}

.video-progress-icon {
    font-size: 3rem;
    color: #ff6b6b;
    margin-bottom: 15px;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

.video-progress-text {
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 10px;
}

.video-progress-subtext {
    font-size: 0.9rem;
    opacity: 0.8;
    margin-top: 10px;
}'

# Video Progress Modal HTML
VIDEO_MODAL='
<!-- Video Progress Modal -->
<div id="videoProgressModal" class="video-progress" style="display: none;">
    <div class="video-progress-icon">
        <i class="fas fa-video"></i>
    </div>
    <div class="video-progress-text">Converting to Video...</div>
    <div class="progress-bar">
        <div class="progress-fill" id="videoProgressFill"></div>
    </div>
    <div class="video-progress-subtext">
        Creating slideshow from your photos...
    </div>
</div>'

# Function to add video CSS to PHP file
add_video_css() {
    local file=$1
    echo "  📝 Adding video CSS to $file..."
    
    # Find the closing </style> tag and add CSS before it
    sed -i "/<\/style>/i\\$VIDEO_CSS" "$file"
}

# Function to add video button to action buttons
add_video_button() {
    local file=$1
    echo "  🔘 Adding video button to $file..."
    
    # Find the print button and add video button after it
    sed -i '/<button class="customize-action-btn print-btn" id="printBtn">/,/<\/button>/ {
        /<\/button>/ a\
    <button class="customize-action-btn video-btn" id="videoBtn">\
        <i class="fas fa-video"></i> Convert to Video\
    </button>
    }' "$file"
}

# Function to add video modal to PHP file
add_video_modal() {
    local file=$1
    echo "  📦 Adding video modal to $file..."
    
    # Add modal before closing </body> tag
    sed -i "/<\/body>/i\\$VIDEO_MODAL" "$file"
}

# Function to update action buttons in JS file
update_action_buttons_js() {
    local file=$1
    echo "  ⚙️ Updating action buttons in $file..."
    
    # Find the videoBtn handler and add it if not exists
    if ! grep -q "videoBtn:" "$file"; then
        sed -i '/printBtn: () => {/,/},/ {
            /},/ a\
        videoBtn: () => {\
            console.log('\''🎬 Video button clicked, stored images:'\'', storedImages.length);\
            if (storedImages.length >= 2) {\
                createVideoFromPhotos();\
            } else {\
                alert('\''Minimal 2 foto diperlukan untuk membuat video!'\'');\
            }\
        },
        }' "$file"
    fi
}

# Function to extract and add video functions
add_video_functions() {
    local file=$1
    echo "  🎬 Adding video functions to $file..."
    
    # Check if video functions already exist
    if grep -q "createVideoFromPhotos" "$file"; then
        echo "    ⚠️ Video functions already exist in $file, skipping..."
        return
    fi
    
    # Extract video functions from source file
    sed -n '/\/\/ ==================== VIDEO CONVERSION FUNCTIONS ====================/,/\/\/ ==================== END VIDEO FUNCTIONS ====================/p' "$SOURCE_JS" > /tmp/video_functions.js
    
    # Add video functions before the last closing brace
    sed -i '/^});$/i\
\
// ==================== VIDEO CONVERSION FUNCTIONS ====================\
\
// Main function to create video from photos\
async function createVideoFromPhotos() {\
    try {\
        console.log("🎬 Starting video conversion...");\
        showVideoProgress();\
        \
        // Check if we have enough photos\
        if (storedImages.length < 2) {\
            throw new Error("Minimal 2 foto diperlukan untuk membuat video!");\
        }\
\
        updateVideoProgress(10);\
        \
        // Load and prepare images\
        const images = await loadImagesForVideo();\
        updateVideoProgress(30);\
        \
        // Create video\
        const videoBlob = await generateSlideShowVideo(images);\
        updateVideoProgress(90);\
        \
        // Download video\
        downloadVideo(videoBlob);\
        updateVideoProgress(100);\
        \
        setTimeout(() => {\
            hideVideoProgress();\
            alert("Video berhasil dibuat dan didownload! 🎉");\
        }, 500);\
        \
    } catch (error) {\
        console.error("❌ Error creating video:", error);\
        hideVideoProgress();\
        alert("Gagal membuat video: " + error.message);\
    }\
}\
\
// Load images for video conversion\
async function loadImagesForVideo() {\
    console.log("📸 Loading images for video...");\
    const images = [];\
    \
    for (const imageSrc of storedImages.slice(0, 2)) { // Take first 2 images\
        try {\
            const img = await loadImage(imageSrc);\
            images.push(img);\
            console.log("✅ Loaded image:", imageSrc);\
        } catch (error) {\
            console.error("❌ Failed to load image:", imageSrc, error);\
            throw new Error("Gagal memuat foto untuk video");\
        }\
    }\
    \
    return images;\
}\
\
// Load single image\
function loadImage(src) {\
    return new Promise((resolve, reject) => {\
        const img = new Image();\
        img.crossOrigin = "anonymous";\
        img.onload = () => resolve(img);\
        img.onerror = () => reject(new Error("Gagal memuat gambar: " + src));\
        img.src = src;\
    });\
}\
\
// Generate slideshow video\
async function generateSlideShowVideo(images) {\
    return new Promise((resolve, reject) => {\
        try {\
            console.log("🎬 Creating video canvas...");\
            \
            // Create canvas for video\
            const canvas = document.createElement("canvas");\
            const ctx = canvas.getContext("2d");\
            canvas.width = 800;\
            canvas.height = 600;\
            \
            // Setup MediaRecorder\
            const stream = canvas.captureStream(30); // 30 FPS\
            const mediaRecorder = new MediaRecorder(stream, {\
                mimeType: "video/webm;codecs=vp9"\
            });\
            \
            const chunks = [];\
            mediaRecorder.ondataavailable = (event) => {\
                if (event.data.size > 0) {\
                    chunks.push(event.data);\
                }\
            };\
            \
            mediaRecorder.onstop = () => {\
                const blob = new Blob(chunks, { type: "video/webm" });\
                console.log("✅ Video created, size:", blob.size, "bytes");\
                resolve(blob);\
            };\
            \
            mediaRecorder.onerror = (error) => {\
                console.error("❌ MediaRecorder error:", error);\
                reject(new Error("Gagal merekam video"));\
            };\
            \
            // Start recording\
            mediaRecorder.start();\
            console.log("🔴 Recording started...");\
            \
            // Animation parameters - 2 complete iterations in 10 seconds\
            let currentIndex = 0;\
            let frameCount = 0;\
            const photoDuration = 75; // 2.5 seconds at 30fps\
            const totalFrames = 300; // 10 seconds at 30fps\
            \
            function animate() {\
                // Clear canvas with black background\
                ctx.fillStyle = "#000000";\
                ctx.fillRect(0, 0, canvas.width, canvas.height);\
                \
                // Draw current image\
                const img = images[currentIndex];\
                if (img) {\
                    // Calculate scaling to fit canvas while maintaining aspect ratio\
                    const scale = Math.min(\
                        canvas.width / img.width,\
                        canvas.height / img.height\
                    );\
                    const width = img.width * scale;\
                    const height = img.height * scale;\
                    const x = (canvas.width - width) / 2;\
                    const y = (canvas.height - height) / 2;\
                    \
                    // Apply current frame/sticker/background effects\
                    applyCanvasEffectsToVideo(ctx, canvas, img, x, y, width, height);\
                }\
                \
                frameCount++;\
                \
                // Calculate current iteration and photo within iteration\
                const currentIteration = Math.floor(frameCount / (photoDuration * 2)) + 1;\
                const photoInIteration = Math.floor((frameCount % (photoDuration * 2)) / photoDuration) + 1;\
                \
                // Update progress\
                const progress = 30 + ((frameCount / totalFrames) * 60); // 30% to 90%\
                updateVideoProgress(Math.floor(progress));\
                \
                // Switch to next photo every photoDuration frames\
                if (frameCount % photoDuration === 0) {\
                    currentIndex = (currentIndex + 1) % images.length;\
                    console.log(`🔄 Iteration ${currentIteration}, switching to photo ${currentIndex + 1}`);\
                }\
                \
                // Continue animation or stop\
                if (frameCount < totalFrames) {\
                    requestAnimationFrame(animate);\
                } else {\
                    console.log("🏁 Animation finished, stopping recording...");\
                    mediaRecorder.stop();\
                }\
            }\
            \
            // Start animation\
            animate();\
            \
        } catch (error) {\
            console.error("❌ Error in generateSlideShowVideo:", error);\
            reject(error);\
        }\
    });\
}\
\
// Apply canvas effects to video frame\
function applyCanvasEffectsToVideo(ctx, canvas, img, x, y, width, height) {\
    // Save current context\
    ctx.save();\
    \
    // Draw background\
    if (backgroundType === "image" && backgroundImage) {\
        ctx.drawImage(backgroundImage, 0, 0, canvas.width, canvas.height);\
    } else if (backgroundType === "color") {\
        ctx.fillStyle = backgroundColor;\
        ctx.fillRect(0, 0, canvas.width, canvas.height);\
    }\
    \
    // Apply shape if selected\
    if (selectedShape === "soft") {\
        // Create rounded rectangle path (fallback for older browsers)\
        const radius = 20;\
        ctx.beginPath();\
        if (ctx.roundRect) {\
            ctx.roundRect(x, y, width, height, radius);\
        } else {\
            // Fallback for browsers without roundRect support\
            ctx.moveTo(x + radius, y);\
            ctx.lineTo(x + width - radius, y);\
            ctx.quadraticCurveTo(x + width, y, x + width, y + radius);\
            ctx.lineTo(x + width, y + height - radius);\
            ctx.quadraticCurveTo(x + width, y + height, x + width - radius, y + height);\
            ctx.lineTo(x + radius, y + height);\
            ctx.quadraticCurveTo(x, y + height, x, y + height - radius);\
            ctx.lineTo(x, y + radius);\
            ctx.quadraticCurveTo(x, y, x + radius, y);\
            ctx.closePath();\
        }\
        ctx.clip();\
    }\
    \
    // Draw the main image\
    ctx.drawImage(img, x, y, width, height);\
    \
    // Apply frame border\
    if (backgroundColor !== "#FFFFFF") {\
        ctx.strokeStyle = backgroundColor;\
        ctx.lineWidth = 10;\
        ctx.strokeRect(x - 5, y - 5, width + 10, height + 10);\
    }\
    \
    // Restore context\
    ctx.restore();\
    \
    // Draw sticker if selected\
    if (selectedSticker && selectedSticker.image) {\
        const stickerSize = Math.min(width, height) * 0.2;\
        const stickerX = x + width - stickerSize - 20;\
        const stickerY = y + 20;\
        ctx.drawImage(selectedSticker.image, stickerX, stickerY, stickerSize, stickerSize);\
    }\
}\
\
// Download video file\
function downloadVideo(videoBlob) {\
    console.log("💾 Downloading video...");\
    const url = URL.createObjectURL(videoBlob);\
    const a = document.createElement("a");\
    a.href = url;\
    a.download = `fotobox_slideshow_${Date.now()}.webm`;\
    document.body.appendChild(a);\
    a.click();\
    document.body.removeChild(a);\
    URL.revokeObjectURL(url);\
    console.log("✅ Video download started");\
}\
\
// Show video progress modal\
function showVideoProgress() {\
    const progressModal = document.getElementById("videoProgressModal");\
    if (progressModal) {\
        progressModal.style.display = "block";\
        updateVideoProgress(0);\
    }\
}\
\
// Update video progress\
function updateVideoProgress(percent) {\
    const progressFill = document.getElementById("videoProgressFill");\
    if (progressFill) {\
        progressFill.style.width = percent + "%";\
    }\
    \
    // Update text based on progress\
    const progressText = document.querySelector(".video-progress-text");\
    const progressSubtext = document.querySelector(".video-progress-subtext");\
    \
    if (progressText && progressSubtext) {\
        if (percent < 20) {\
            progressText.textContent = "Preparing photos...";\
            progressSubtext.textContent = "Loading images for video conversion";\
        } else if (percent < 40) {\
            progressText.textContent = "Creating video canvas...";\
            progressSubtext.textContent = "Setting up video recording";\
        } else if (percent < 90) {\
            progressText.textContent = "Recording slideshow...";\
            progressSubtext.textContent = "Creating 10-second video with 2 complete iterations";\
        } else {\
            progressText.textContent = "Finalizing video...";\
            progressSubtext.textContent = "Almost done! Preparing download";\
        }\
    }\
}\
\
// Hide video progress modal\
function hideVideoProgress() {\
    const progressModal = document.getElementById("videoProgressModal");\
    if (progressModal) {\
        progressModal.style.display = "none";\
    }\
}\
\
// ==================== END VIDEO FUNCTIONS ====================' "$file"
}

# Main execution
echo "🎯 Target layouts: ${LAYOUTS[*]}"

for layout in "${LAYOUTS[@]}"; do
    echo ""
    echo "🔄 Processing Layout $layout..."
    
    PHP_FILE="/var/www/html/FotoboxJO/src/pages/customizeLayout${layout}.php"
    JS_FILE="/var/www/html/FotoboxJO/src/pages/customizeLayout${layout}.js"
    
    # Check if files exist
    if [[ ! -f "$PHP_FILE" ]]; then
        echo "  ❌ $PHP_FILE not found, skipping..."
        continue
    fi
    
    if [[ ! -f "$JS_FILE" ]]; then
        echo "  ❌ $JS_FILE not found, skipping..."
        continue
    fi
    
    # Process PHP file
    echo "  📄 Processing PHP file..."
    add_video_css "$PHP_FILE"
    add_video_button "$PHP_FILE"
    add_video_modal "$PHP_FILE"
    
    # Process JS file
    echo "  📜 Processing JS file..."
    update_action_buttons_js "$JS_FILE"
    add_video_functions "$JS_FILE"
    
    echo "  ✅ Layout $layout completed!"
done

echo ""
echo "🎉 Video feature copy completed!"
echo "📊 Summary:"
echo "   - CSS styles added to all PHP files"
echo "   - Video buttons added to all layouts"
echo "   - Video modals added to all layouts"
echo "   - JavaScript functions copied to all layouts"
echo ""
echo "🚀 Ready to test! All layouts now have video conversion feature."