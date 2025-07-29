#!/bin/bash

echo "🔍 Verifying Logo Usage - GoFotobox PWA"
echo "========================================"

LOGO_PATH="src/assets/icons/photobooth-new-logo.png"

# Check if main logo exists
if [ -f "$LOGO_PATH" ]; then
    echo "✅ Main logo exists: $LOGO_PATH"
    echo "   Size: $(du -h $LOGO_PATH | cut -f1)"
else
    echo "❌ Main logo missing: $LOGO_PATH"
    exit 1
fi

# Check icon files
echo ""
echo "📱 Icon Files:"
for size in "192x192" "512x512"; do
    icon_file="src/assets/icons/icon-${size}.png"
    if [ -f "$icon_file" ]; then
        echo "✅ $icon_file exists"
    else
        echo "❌ $icon_file missing"
    fi
done

# Check manifest.json
echo ""
echo "📄 Manifest.json references:"
if grep -q "photobooth-new-logo.png" manifest.json; then
    echo "✅ manifest.json references correct logo"
else
    echo "❌ manifest.json does not reference correct logo"
fi

# Check PWA helper
echo ""
echo "🔧 PWA Helper references:"
if grep -q "photobooth-new-logo.png" src/includes/pwa-helper.php; then
    echo "✅ PWA helper references correct logo"
else
    echo "❌ PWA helper does not reference correct logo"
fi

# Check index.html
echo ""
echo "🏠 Index.html references:"
if grep -q "photobooth-new-logo.png" index.html; then
    echo "✅ index.html references correct logo"
else
    echo "❌ index.html does not reference correct logo"
fi

# Count PHP files using correct logo
echo ""
echo "📊 PHP Pages using correct logo:"
php_count=$(grep -l "photobooth-new-logo.png" src/pages/*.php | wc -l)
total_php=$(find src/pages/ -name "*.php" | wc -l)
echo "✅ $php_count out of $total_php PHP files use correct logo"

# Check service worker cache
echo ""
echo "⚙️ Service Worker Cache:"
if grep -q "photobooth-new-logo.png" sw.js; then
    echo "✅ Service worker caches correct logo"
    cache_version=$(grep "CACHE_NAME" sw.js | head -1)
    echo "   Current cache: $cache_version"
else
    echo "❌ Service worker does not cache correct logo"
fi

echo ""
echo "🎉 Logo verification complete!"
echo ""
echo "💡 All references should point to: $LOGO_PATH"
echo "🌐 Logo accessible at: http://localhost:8080/$LOGO_PATH"
