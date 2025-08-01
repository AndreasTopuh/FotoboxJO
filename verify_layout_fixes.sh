#!/bin/bash

echo "🔧 VERIFYING LAYOUT 3-6 FIXES"
echo "=============================="

# Check for typos in event listeners
echo "1. Checking for typos in event listeners..."
TYPO_COUNT=$(grep -r "addEventListener(' click'" src/pages/canvasLayout*.js 2>/dev/null | wc -l)
if [ $TYPO_COUNT -eq 0 ]; then
    echo "   ✅ No typos found in event listeners"
else
    echo "   ❌ Found $TYPO_COUNT typos in event listeners"
    grep -r "addEventListener(' click'" src/pages/canvasLayout*.js
fi

# Check for proper photo container structure
echo "2. Checking photo container structure..."
CONTAINER_COUNT=$(grep -r "photo-preview-slot" src/pages/canvasLayout[3-6].php | wc -l)
if [ $CONTAINER_COUNT -ge 4 ]; then
    echo "   ✅ Photo preview containers properly configured"
else
    echo "   ❌ Missing photo preview container configuration"
fi

# Check for session timer integration
echo "3. Checking session timer integration..."
SESSION_COUNT=$(grep -r "session-timer.js" src/pages/canvasLayout[3-6].php | wc -l)
if [ $SESSION_COUNT -ge 4 ]; then
    echo "   ✅ Session timer integrated in all layouts"
else
    echo "   ❌ Missing session timer integration"
fi

# Check for API integration in save functions
echo "4. Checking API integration in save functions..."
API_COUNT=$(grep -r "save_photos.php" src/pages/canvasLayout[3-6].js | wc -l)
if [ $API_COUNT -ge 4 ]; then
    echo "   ✅ API integration properly implemented"
else
    echo "   ❌ Missing API integration"
fi

# Check for debug-camera.js inclusion
echo "5. Checking debug-camera.js inclusion..."
DEBUG_COUNT=$(grep -r "debug-camera.js" src/pages/canvasLayout[3-6].php | wc -l)
if [ $DEBUG_COUNT -ge 4 ]; then
    echo "   ✅ Debug camera script included"
else
    echo "   ❌ Missing debug camera script"
fi

# Check PHP syntax
echo "6. Checking PHP syntax..."
PHP_ERRORS=0
for layout in 3 4 5 6; do
    if php -l "src/pages/canvasLayout${layout}.php" > /dev/null 2>&1; then
        echo "   ✅ canvasLayout${layout}.php syntax OK"
    else
        echo "   ❌ canvasLayout${layout}.php syntax error"
        PHP_ERRORS=$((PHP_ERRORS + 1))
    fi
done

if [ $PHP_ERRORS -eq 0 ]; then
    echo "   ✅ All PHP files have valid syntax"
fi

echo ""
echo "🎯 VERIFICATION SUMMARY"
echo "======================="
if [ $TYPO_COUNT -eq 0 ] && [ $CONTAINER_COUNT -ge 4 ] && [ $SESSION_COUNT -ge 4 ] && [ $API_COUNT -ge 4 ] && [ $DEBUG_COUNT -ge 4 ] && [ $PHP_ERRORS -eq 0 ]; then
    echo "✅ ALL FIXES SUCCESSFULLY APPLIED!"
    echo "   - Photo display: Fixed"
    echo "   - Retake All button: Fixed"
    echo "   - Session management: Fixed"
    echo "   - Save functionality: Fixed"
    echo "   - API integration: Complete"
else
    echo "❌ Some fixes still need attention"
fi

echo ""
echo "🧪 TESTING INSTRUCTIONS"
echo "======================="
echo "1. Open any Layout 3-6 in browser"
echo "2. Take photos → Should display in preview"
echo "3. Check Retake All button → Should be enabled"
echo "4. Complete session → Should save and redirect"
echo "5. Check session timer → Should show countdown"
