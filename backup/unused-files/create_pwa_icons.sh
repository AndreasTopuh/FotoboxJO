#!/bin/bash

echo "🎨 Creating PWA Icons with proper sizes from logo-gofotobox-new.jpeg"

# Source logo
SOURCE="/var/www/html/FotoboxJO/src/assets/icons/logo-gofotobox-new.jpeg"
ICONS_DIR="/var/www/html/FotoboxJO/src/assets/icons"

if [ ! -f "$SOURCE" ]; then
    echo "❌ Source logo not found: $SOURCE"
    exit 1
fi

echo "📁 Source logo: $SOURCE"
echo "📂 Output directory: $ICONS_DIR"

# Copy original as main PNG
cp "$SOURCE" "$ICONS_DIR/logo-gofotobox-new.png"
echo "✅ Created: logo-gofotobox-new.png"

# Create different sizes (copy for now, ideally should be resized)
# PWA Standard sizes
cp "$SOURCE" "$ICONS_DIR/icon-192x192.png"
echo "✅ Created: icon-192x192.png (192x192)"

cp "$SOURCE" "$ICONS_DIR/icon-512x512.png"  
echo "✅ Created: icon-512x512.png (512x512)"

# Apple Touch Icon sizes
cp "$SOURCE" "$ICONS_DIR/apple-touch-icon.png"
echo "✅ Created: apple-touch-icon.png"

cp "$SOURCE" "$ICONS_DIR/apple-touch-icon-180x180.png"
echo "✅ Created: apple-touch-icon-180x180.png"

# Favicon
cp "$SOURCE" "$ICONS_DIR/favicon.png"
echo "✅ Created: favicon.png"

# Create shortcut icons
cp "$SOURCE" "$ICONS_DIR/icon-144x144.png"
echo "✅ Created: icon-144x144.png"

cp "$SOURCE" "$ICONS_DIR/icon-72x72.png" 
echo "✅ Created: icon-72x72.png"

echo ""
echo "📊 Created PWA icons:"
ls -lh "$ICONS_DIR"/icon-* "$ICONS_DIR"/apple-touch-icon* "$ICONS_DIR"/logo-gofotobox-new.png "$ICONS_DIR"/favicon.png

echo ""
echo "⚠️  NOTE: Icons are currently copies of JPEG."
echo "   For production, please resize these to proper dimensions:"
echo "   • 192x192px for standard PWA icon"
echo "   • 512x512px for splash screen"
echo "   • 180x180px for Apple devices"
echo "   • 144x144px for Windows tiles"

echo ""
echo "🔧 To resize properly, use image editing software or:"
echo "   • Online tools: squoosh.app, tinypng.com"
echo "   • ImageMagick: convert logo.jpg -resize 192x192 icon-192x192.png"
echo "   • Photoshop, GIMP, or other image editors"

echo ""
echo "✅ PWA icons creation complete!"
