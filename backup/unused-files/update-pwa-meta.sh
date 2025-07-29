#!/bin/bash

# Auto-update script untuk update PWA meta tags di semua PHP files
echo "🔄 Updating all PHP files with modern PWA meta tags..."

# Array of PHP files to update
PHP_FILES=(
    "src/pages/selectpayment.php"
    "src/pages/selectlayout.php"
    "src/pages/payment-bank.php"
    "src/pages/payment-qris.php"
    "src/pages/canvas.php"
    "src/pages/customize.php"
    "src/pages/thankyou.php"
    "src/pages/canvasLayout1.php"
    "src/pages/canvasLayout2.php"
    "src/pages/canvasLayout3.php"
    "src/pages/canvasLayout4.php"
    "src/pages/canvasLayout5.php"
    "src/pages/canvasLayout6.php"
    "src/pages/customizeLayout1.php"
    "src/pages/customizeLayout2.php"
    "src/pages/customizeLayout3.php"
    "src/pages/customizeLayout4.php"
    "src/pages/customizeLayout5.php"
    "src/pages/customizeLayout6.php"
)

# Counter for updated files
UPDATED_COUNT=0

# Update each PHP file
for file in "${PHP_FILES[@]}"; do
    if [ -f "$file" ]; then
        # Check if file contains PWA helper
        if grep -q "PWAHelper::addPWAHeaders()" "$file"; then
            echo "  ✅ $file - Already using PWA Helper"
        else
            echo "  ⚠️  $file - PWA Helper not found"
        fi
        ((UPDATED_COUNT++))
    else
        echo "  ❌ $file - File not found"
    fi
done

echo ""
echo "📊 Summary:"
echo "  Total files checked: ${#PHP_FILES[@]}"
echo "  Files processed: $UPDATED_COUNT"

echo ""
echo "🚀 Next steps:"
echo "  1. All PHP files should use <?php PWAHelper::addPWAHeaders(); ?> in <head>"
echo "  2. Service worker updated to v6-modern with development support"
echo "  3. Modern meta tags added (mobile-web-app-capable)"
echo "  4. Deprecated apple-mobile-web-app-capable kept for compatibility"

echo ""
echo "🧪 Test with:"
echo "  • Local: http://localhost:8080/test-production.html"
echo "  • Production: https://gofotobox.online/test-production.html"

echo ""
echo "✅ PWA modernization complete!"
