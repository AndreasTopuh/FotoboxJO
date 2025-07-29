#!/bin/bash

# Script untuk menambahkan PWA support ke semua halaman PHP
# Usage: ./add_pwa_to_all.sh

echo "Adding PWA support to remaining PHP files..."

# Array of files to update
files=(
    "src/pages/canvasLayout1.php"
    "src/pages/canvasLayout2.php"
    "src/pages/canvasLayout3.php"
    "src/pages/canvasLayout4.php"
    "src/pages/canvasLayout5.php"
    "src/pages/canvasLayout6.php"
    "src/pages/customizeLayout2.php"
    "src/pages/customizeLayout3.php"
    "src/pages/customizeLayout4.php"
    "src/pages/customizeLayout5.php"
    "src/pages/customizeLayout6.php"
)

for file in "${files[@]}"; do
    if [ -f "$file" ]; then
        echo "Processing $file..."
        
        # Add PWA helper include after session_start
        sed -i '/session_start();/a\\n// Include PWA helper\nrequire_once '\''../includes/pwa-helper.php'\'';' "$file"
        
        # Add PWA headers in head section
        sed -i '/<head>/a\    <?php PWAHelper::addPWAHeaders(); ?>' "$file"
        
        # Add PWA script before closing body tag
        sed -i 's|</body>|    <?php PWAHelper::addPWAScript(); ?>\n</body>|' "$file"
        
        echo "✅ Updated $file"
    else
        echo "❌ File not found: $file"
    fi
done

echo "🎉 PWA support added to all files!"
echo ""
echo "📝 Manual updates needed:"
echo "- Update page titles to include '- GoFotobox'"
echo "- Test all pages for PWA functionality"
echo "- Update service worker cache if needed"
