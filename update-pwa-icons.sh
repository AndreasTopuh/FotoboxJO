#!/bin/bash

# Update PWA Meta Tags with Resized Logo

echo "🔄 Updating PWA meta tags with properly sized logo icons..."

# Directory paths
ASSETS_DIR="/var/www/html/FotoboxJO/src/assets/icons"
PAGES_DIR="/var/www/html/FotoboxJO/src/pages"

# Check if resized icons exist
if [ ! -f "$ASSETS_DIR/logo-gofotobox-new-192.png" ]; then
    echo "❌ Error: logo-gofotobox-new-192.png not found!"
    exit 1
fi

if [ ! -f "$ASSETS_DIR/logo-gofotobox-new-512.png" ]; then
    echo "❌ Error: logo-gofotobox-new-512.png not found!"
    exit 1
fi

if [ ! -f "$ASSETS_DIR/logo-gofotobox-new-180.png" ]; then
    echo "❌ Error: logo-gofotobox-new-180.png not found!"
    exit 1
fi

echo "✅ All resized logo icons found"

# List of PHP files to update
FILES=(
    "/var/www/html/FotoboxJO/index.html"
    "$PAGES_DIR/canvasLayout1.php"
    "$PAGES_DIR/canvasLayout2.php"
    "$PAGES_DIR/canvasLayout3.php"
    "$PAGES_DIR/canvasLayout4.php"
    "$PAGES_DIR/canvasLayout5.php"
    "$PAGES_DIR/canvasLayout6.php"
    "$PAGES_DIR/customizeLayout1.php"
    "$PAGES_DIR/customizeLayout2.php"
    "$PAGES_DIR/customizeLayout3.php"
    "$PAGES_DIR/customizeLayout4.php"
    "$PAGES_DIR/customizeLayout5.php"
    "$PAGES_DIR/customizeLayout6.php"
    "$PAGES_DIR/selectlayout.php"
    "$PAGES_DIR/selectpayment.php"
    "$PAGES_DIR/payment-bank.php"
    "$PAGES_DIR/payment-qris.php"
    "$PAGES_DIR/canvas.php"
    "$PAGES_DIR/customize.php"
)

# Update Apple touch icon references in all files
for file in "${FILES[@]}"; do
    if [ -f "$file" ]; then
        echo "🔧 Updating $file..."
        
        # Update apple-touch-icon to use 180x180 size
        sed -i 's|logo-gofotobox-new\.png|logo-gofotobox-new-180.png|g' "$file"
        
        # Update msapplication-TileImage to use 192x192 size
        sed -i 's|msapplication-TileImage.*logo-gofotobox-new-180\.png|msapplication-TileImage" content="/src/assets/icons/logo-gofotobox-new-192.png|g' "$file"
        
        echo "  ✅ Updated $file"
    else
        echo "  ⚠️  File not found: $file"
    fi
done

echo ""
echo "📄 Files updated for PWA with properly sized icons:"
echo "  • 192x192px - Home screen icon and Windows tile"
echo "  • 512x512px - Splash screen icon"
echo "  • 180x180px - Apple touch icon"
echo ""
echo "🎯 Icon specifications:"
identify "$ASSETS_DIR/logo-gofotobox-new-192.png"
identify "$ASSETS_DIR/logo-gofotobox-new-512.png"
identify "$ASSETS_DIR/logo-gofotobox-new-180.png"
echo ""
echo "✅ PWA icon update complete!"

echo ""
echo "📱 To deploy to production, run:"
echo "   ./deploy-production.sh"
