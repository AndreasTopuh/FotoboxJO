#!/bin/bash

echo "🗑️  REMOVING F11 FULLSCREEN IMPLEMENTATION"
echo "=========================================="
echo ""

echo "🎯 TARGET: Remove F11 browser fullscreen only"
echo "✅ PRESERVE: Canvas fullscreen in canvasLayout1-6"
echo ""

# Remove F11 fullscreen files
echo "📁 Removing F11 fullscreen files..."
rm -f /var/www/html/FotoboxJO/src/includes/fullscreen-manager.js
rm -f /var/www/html/FotoboxJO/src/includes/fullscreen-applier.js
rm -f /var/www/html/FotoboxJO/src/includes/fullscreen-handler.js
echo "   ✅ Removed fullscreen-*.js files"

# Remove fullscreen button styles
rm -f /var/www/html/FotoboxJO/src/assets/fullscreen-button-styles.css
echo "   ✅ Removed fullscreen button styles"

# Remove documentation and demo files
rm -f /var/www/html/FotoboxJO/fullscreen-button-demo.html
rm -f /var/www/html/FotoboxJO/FULLSCREEN_DOCS.md
echo "   ✅ Removed documentation files"

# Remove all fullscreen-related scripts
rm -f /var/www/html/FotoboxJO/*fullscreen*.sh
rm -f /var/www/html/FotoboxJO/f11*.sh
rm -f /var/www/html/FotoboxJO/permission*.sh
rm -f /var/www/html/FotoboxJO/visual*.sh
rm -f /var/www/html/FotoboxJO/no_click*.sh
echo "   ✅ Removed utility scripts"

echo ""
echo "📝 Cleaning F11 fullscreen references from PHP files..."

# Remove only F11 fullscreen script includes, not canvas fullscreen functions
find /var/www/html/FotoboxJO/src/pages -name "*.php" -exec sed -i '/fullscreen-manager\.js/d' {} \;
find /var/www/html/FotoboxJO/src/pages -name "*.php" -exec sed -i '/fullscreen-applier\.js/d' {} \;
find /var/www/html/FotoboxJO/src/pages -name "*.php" -exec sed -i '/fullscreen-handler\.js/d' {} \;
find /var/www/html/FotoboxJO/src/pages -name "*.php" -exec sed -i '/Fullscreen Manager/d' {} \;
find /var/www/html/FotoboxJO/src/pages -name "*.php" -exec sed -i '/Fullscreen Applier/d' {} \;
find /var/www/html/FotoboxJO/src/pages -name "*.php" -exec sed -i '/Global Fullscreen Handler/d' {} \;

echo "   ✅ Removed F11 fullscreen script includes"

echo ""
echo "🔍 Cleaning index.php..."

# Check if index.php has F11 fullscreen button
if grep -q "fullscreen-btn" /var/www/html/FotoboxJO/index.php; then
    echo "   📝 Removing F11 fullscreen button from index.php..."
    
    # Remove fullscreen button HTML
    sed -i '/<button class="fullscreen-btn"/,/<\/button>/d' /var/www/html/FotoboxJO/index.php
    
    # Remove fullscreen button CSS
    sed -i '/\/\* Fullscreen Button \*\//,/^        }/d' /var/www/html/FotoboxJO/index.php
    
    # Remove fullscreen button styles import
    sed -i '/fullscreen-button-styles\.css/d' /var/www/html/FotoboxJO/index.php
    
    # Remove fullscreen button JavaScript
    sed -i '/fullscreen-manager\.js/d' /var/www/html/FotoboxJO/index.php
    
    echo "   ✅ Removed F11 fullscreen button from index.php"
else
    echo "   ✅ No F11 fullscreen button found in index.php"
fi

echo ""
echo "🧹 Final cleanup..."

# Remove backup files created during implementation
find /var/www/html/FotoboxJO -name "*.applier-backup" -delete
find /var/www/html/FotoboxJO -name "*.button-backup" -delete
find /var/www/html/FotoboxJO -name "*.cleanup-backup" -delete

echo "   ✅ Removed backup files"

echo ""
echo "✅ CLEANUP SUMMARY:"
echo "   🗑️  F11 fullscreen implementation removed"
echo "   ✅ Canvas fullscreen preserved"
echo "   🧹 Backup files cleaned"
echo "   📝 PHP files sanitized"
echo ""

echo "🔍 Verification..."
remaining_fullscreen=$(find /var/www/html/FotoboxJO/src/pages -name "*.php" -exec grep -l "fullscreen-.*\.js" {} \; | wc -l)
echo "   📊 PHP files with F11 fullscreen scripts: $remaining_fullscreen"

if [ "$remaining_fullscreen" -eq 0 ]; then
    echo "   ✅ All F11 fullscreen references successfully removed"
else
    echo "   ⚠️  Some F11 fullscreen references may remain"
fi

echo ""
echo "🎉 F11 FULLSCREEN REMOVAL COMPLETE!"
echo "Canvas fullscreen functionality preserved ✅"
