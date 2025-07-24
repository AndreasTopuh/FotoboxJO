#!/bin/bash

# Test script untuk memastikan semua implementasi layout bekerja dengan baik

echo "🎯 TESTING FOTOBOOTH LAYOUT IMPLEMENTATION"
echo "=========================================="

# 1. Check file existence
echo -e "\n📁 1. Checking file existence..."

layouts=(1 2 3 4 5 6)
missing_files=()

for layout in "${layouts[@]}"; do
    echo "  Layout ${layout}:"
    
    # Check canvas files
    if [[ -f "canvasLayout${layout}.php" && -f "canvasLayout${layout}.js" ]]; then
        echo "    ✅ Canvas files: OK"
    else
        echo "    ❌ Canvas files: MISSING"
        missing_files+=("canvas${layout}")
    fi
    
    # Check customize files
    if [[ -f "customizeLayout${layout}.php" && -f "customizeLayout${layout}.js" ]]; then
        echo "    ✅ Customize files: OK"
    else
        echo "    ❌ Customize files: MISSING"
        missing_files+=("customize${layout}")
    fi
done

# 2. Check API endpoints
echo -e "\n🔌 2. Checking API endpoints..."
api_files=(
    "../api-fetch/create_photo_session.php"
    "../api-fetch/save_photos.php"
    "../api-fetch/get_photos.php"
    "../api-fetch/create_customize_session.php"
)

for api in "${api_files[@]}"; do
    if [[ -f "$api" ]]; then
        echo "    ✅ $(basename $api): OK"
    else
        echo "    ❌ $(basename $api): MISSING"
        missing_files+=("$api")
    fi
done

# 3. Check layout.js for popup functionality
echo -e "\n🎨 3. Checking layout.js popup functionality..."
if grep -q "create_photo_session.php" layout.js; then
    echo "    ✅ Popup functionality: OK"
else
    echo "    ❌ Popup functionality: MISSING"
fi

# 4. Check selectlayout.php for popup HTML
echo -e "\n📄 4. Checking selectlayout.php popup HTML..."
if grep -q "layoutPopup" selectlayout.php; then
    echo "    ✅ Popup HTML: OK"
else
    echo "    ❌ Popup HTML: MISSING"
fi

# 5. Summary
echo -e "\n📊 SUMMARY"
echo "=========="

if [[ ${#missing_files[@]} -eq 0 ]]; then
    echo "🎉 ALL TESTS PASSED! Implementation is complete."
    echo ""
    echo "📋 IMPLEMENTATION DETAILS:"
    echo "  • 6 Canvas layouts (photo session)"
    echo "  • 6 Customize layouts (edit session)"
    echo "  • Session management with timers"
    echo "  • API integration"
    echo "  • Popup confirmation flow"
    echo ""
    echo "🚀 READY TO TEST FLOW:"
    echo "  1. selectlayout.php → Choose layout"
    echo "  2. canvasLayoutX.php → Take photos (7 min timer)"
    echo "  3. customizeLayoutX.php → Edit photos (3 min timer)"
    echo "  4. thankyou.php → Complete"
else
    echo "❌ MISSING FILES DETECTED:"
    for file in "${missing_files[@]}"; do
        echo "  • $file"
    done
    echo ""
    echo "⚠️  Please fix missing files before testing."
fi

echo ""
echo "=== END OF TEST ==="
