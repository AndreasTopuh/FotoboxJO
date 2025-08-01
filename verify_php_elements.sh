#!/bin/bash

# 🔍 PHP VERIFICATION SCRIPT - Check all required HTML elements
echo "🔍 VERIFYING PHP FILES FOR REQUIRED HTML ELEMENTS..."
echo ""

check_php_element() {
    local layout=$1
    local element=$2
    local search_term=$3
    
    if grep -q "$search_term" "src/pages/canvasLayout${layout}.php"; then
        echo "   ✅ $element"
    else
        echo "   ❌ $element - NOT FOUND"
    fi
}

for layout in 1 2 3 4 5 6; do
    echo "🎯 LAYOUT $layout PHP:"
    check_php_element $layout "START button" 'id="startBtn"'
    check_php_element $layout "CAPTURE ALL button" 'id="captureAllBtn"'
    check_php_element $layout "RETAKE ALL button" 'id="retakeAllBtn"'
    check_php_element $layout "DONE button" 'id="doneBtn"'
    check_php_element $layout "Carousel Modal" 'id="carousel-modal"'
    check_php_element $layout "Carousel Indicators" 'id="carousel-indicators"'
    check_php_element $layout "Progress Counter" 'id="progressCounter"'
    echo ""
done

echo "📊 PHP VERIFICATION SUMMARY:"
echo "🚀 All PHP files should now have complete HTML structure!"
echo ""
echo "🎉 READY FOR TESTING!"
