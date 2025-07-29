# FotoboxJO - Photobooth Application

## Overview
FotoboxJO is a web-based photobooth application that allows users to take photos, customize them with frames and stickers, and print their creations. The application supports multiple layouts and provides a complete photobooth experience.

## Adding New Background Frames - Complete Flow

### 📋 Prerequisites
- Access to server file system (`/var/www/html/FotoboxJO/`)
- Background image file (recommended: JPG format, optimized for web)
- Basic knowledge of HTML, CSS, and JavaScript

### 🔄 Step-by-Step Process

#### Step 1: Upload Background Image File
```bash
# Upload your image file to:
/var/www/html/FotoboxJO/src/assets/frame-backgrounds/

# Example:
/var/www/html/FotoboxJO/src/assets/frame-backgrounds/sunset.jpg
/var/www/html/FotoboxJO/src/assets/frame-backgrounds/ocean.jpg
/var/www/html/FotoboxJO/src/assets/frame-backgrounds/forest.jpg
```

**Image Requirements:**
- Format: JPG (recommended) or PNG
- Resolution: 1200x1800px (4R ratio) for best quality
- File size: < 500KB for optimal loading
- File naming: Use lowercase, hyphen-separated names (e.g., `sunset-beach.jpg`)

#### Step 2: Add HTML Button Element
**File:** `/var/www/html/FotoboxJO/src/pages/customizeLayout1.php`

Locate the background frames section and add your button:

```php
<!-- Find this section around line 153 -->
<button id="matcha" class="buttonBgFrames"></button>
<button id="sunset" class="buttonBgFrames"></button> <!-- Add new button here -->
```

**Button Requirements:**
- `id`: Must match the JavaScript entry (lowercase, no spaces)
- `class`: Must be `buttonBgFrames`
- Self-closing tag format

#### Step 3: Add CSS Styling
**File:** `/var/www/html/FotoboxJO/styles.css`

Add styling for your new button:

```css
/* Add after existing frame button styles */
#sunset {
    background-image: url(/src/assets/frame-backgrounds/sunset.jpg);
    background-size: 190%;
    background-position: center;
    cursor: pointer;
}

/* For hover effects (optional) */
#sunset:hover {
    opacity: 0.8;
    transform: scale(1.05);
}
```

**CSS Properties:**
- `background-image`: Absolute path starting with `/src/assets/`
- `background-size: 190%`: Standard zoom for preview buttons
- `background-position: center`: Centers the preview image
- `cursor: pointer`: Indicates clickable element

#### Step 4: Add JavaScript Functionality
**File:** `/var/www/html/FotoboxJO/src/pages/customizeLayout1.js`

Add entry to the `backgroundFrameButtons` array in `initializeBackgroundFrameControls()` function:

```javascript
// Find the backgroundFrameButtons array around line 226
const backgroundFrameButtons = [
    { id: 'matcha', src: '/src/assets/frame-backgrounds/matcha.jpg' },
    { id: 'sunset', src: '/src/assets/frame-backgrounds/sunset.jpg' }, // Add new entry
    // ... other entries
];
```

**JavaScript Properties:**
- `id`: Must match HTML button ID exactly
- `src`: Absolute path to the background image file

#### Step 5: Test Implementation
1. **Upload Test:**
   ```bash
   # Verify file exists and has correct permissions
   ls -la /var/www/html/FotoboxJO/src/assets/frame-backgrounds/sunset.jpg
   ```

2. **Browser Console Test:**
   - Open customizeLayout1.php in browser
   - Open Developer Tools (F12)
   - Look for initialization messages:
     ```
     🖼️ Initializing background frame controls...
     ✅ Found background frame button: sunset
     ✅ Background frame controls initialized
     ```

3. **Functionality Test:**
   - Click the new background frame button
   - Check console for: `🎨 Setting background image: /src/assets/frame-backgrounds/sunset.jpg`
   - Verify canvas updates with new background

#### Step 6: Troubleshooting

**Common Issues:**

1. **404 Error - Image Not Found**
   ```
   GET https://gofotobox.online/src/assets/frame-backgrounds/sunset.jpg 404 (Not Found)
   ```
   - **Solution:** Verify file path and upload location
   - **Check:** File permissions (644) and ownership

2. **Button Not Responding**
   ```
   ⚠️ Background frame button not found: sunset
   ```
   - **Solution:** Check HTML button ID matches JavaScript ID exactly
   - **Check:** HTML button has correct class `buttonBgFrames`

3. **Canvas Not Updating**
   - **Solution:** Check browser console for JavaScript errors
   - **Check:** Image loading errors in `redrawCanvas()` function

4. **CSS Preview Not Showing**
   - **Solution:** Verify CSS path and background-image URL
   - **Check:** File upload completed successfully

### 📁 File Structure Reference
```
/var/www/html/FotoboxJO/
├── src/
│   ├── assets/
│   │   └── frame-backgrounds/
│   │       ├── matcha.jpg
│   │       ├── sunset.jpg
│   │       └── [new-frame].jpg
│   └── pages/
│       ├── customizeLayout1.php
│       └── customizeLayout1.js
├── styles.css
└── README.md
```

### 🎨 Frame Categories (Available for Uncommenting)
The application includes many pre-configured frames that can be activated by:
1. Uncommenting the JavaScript entry
2. Adding corresponding HTML button
3. Adding CSS styling
4. Uploading the image file

**Categories include:**
- **Patterns:** plaid, stripes, polka dots
- **Textures:** leather, knitted, denim
- **Nature:** hills, beach, trees
- **Decorative:** roses, vintage, party themes

### 📋 Best Practices

1. **File Naming Convention:**
   - Use descriptive, lowercase names
   - Separate words with hyphens
   - Example: `ocean-waves.jpg`, `autumn-leaves.jpg`

2. **Image Optimization:**
   - Compress images before upload
   - Use 4R ratio (1200x1800px) for consistency
   - Test loading performance

3. **Testing Workflow:**
   - Test on multiple devices/browsers
   - Verify print quality
   - Check loading performance

4. **Version Control:**
   - Document changes in commit messages
   - Test in development environment first
   - Backup original files before modifications

### 🚀 Quick Add Template

For quick implementation, copy this template:

**HTML:**
```html
<button id="NEW_FRAME_ID" class="buttonBgFrames"></button>
```

**CSS:**
```css
#NEW_FRAME_ID {
    background-image: url(/src/assets/frame-backgrounds/NEW_FRAME_FILE.jpg);
    background-size: 190%;
    background-position: center;
    cursor: pointer;
}
```

**JavaScript:**
```javascript
{ id: 'NEW_FRAME_ID', src: '/src/assets/frame-backgrounds/NEW_FRAME_FILE.jpg' },
```

**File Upload:**
```bash
# Upload to:
/var/www/html/FotoboxJO/src/assets/frame-backgrounds/NEW_FRAME_FILE.jpg
```

Replace `NEW_FRAME_ID` and `NEW_FRAME_FILE` with your actual frame name and file name.

---

## Application Features

### Layouts Supported
- Layout 1: 2-photo vertical strip (1200x1800px)
- Layout 2: 4-photo grid
- Layout 3: Single large photo
- Layout 4: 3-photo arrangement
- Layout 5: Custom collage
- Layout 6: Special event layout

### Customization Options
- **Background Frames:** Image-based backgrounds
- **Color Frames:** Solid color backgrounds
- **Shapes:** Rectangle, rounded, circle, heart
- **Stickers:** Various decorative elements
- **Date/Time:** Optional timestamp overlay

### Technical Stack
- **Frontend:** HTML5, CSS3, JavaScript (ES6+)
- **Backend:** PHP 8.x
- **Canvas:** HTML5 Canvas API for image processing
- **PWA:** Progressive Web App capabilities
- **Printing:** Direct browser printing integration

---

*Last updated: July 28, 2025*
*Version: 2.0*
