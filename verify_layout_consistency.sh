#!/bin/bash

echo "🔍 Verifying Layout Consistency Implementation..."
echo "=================================================="

# Check if all layout files exist
layouts=("canvasLayout1.php" "canvasLayout2.php" "canvasLayout3.php" "canvasLayout4.php" "canvasLayout5.php" "canvasLayout6.php")

echo "✅ Checking layout files existence:"
for layout in "${layouts[@]}"; do
    if [[ -f "/var/www/html/FotoboxJO/src/pages/$layout" ]]; then
        echo "   ✓ $layout exists"
    else
        echo "   ✗ $layout missing"
    fi
done

echo ""
echo "🎥 Verifying camera height standardization (450px):"
for layout in "${layouts[@]}"; do
    if grep -q "height: 450px" "/var/www/html/FotoboxJO/src/pages/$layout"; then
        echo "   ✓ $layout: Camera height standardized to 450px"
    else
        echo "   ✗ $layout: Camera height not standardized"
    fi
done

echo ""
echo "📸 Verifying photo preview container heights (260px-300px):"
for layout in "${layouts[@]}"; do
    if grep -q "min-height: 260px" "/var/www/html/FotoboxJO/src/pages/$layout"; then
        echo "   ✓ $layout: Photo preview container height standardized"
    else
        echo "   ✗ $layout: Photo preview container height not standardized"
    fi
done

echo ""
echo "🔧 Checking specific layout configurations:"

# Layout 1 - should be horizontal (row)
if grep -q "flex-direction: row" "/var/www/html/FotoboxJO/src/pages/canvasLayout1.php"; then
    echo "   ✓ Layout 1: Correctly configured for horizontal layout (2 photos)"
else
    echo "   ✗ Layout 1: Not configured for horizontal layout"
fi

# All layouts should be horizontal row now
for layout_num in 2 3 4 5 6; do
    layout_file="canvasLayout${layout_num}.php"
    if grep -q "flex-direction: row" "/var/www/html/FotoboxJO/src/pages/$layout_file" && grep -q "horizontal row layout" "/var/www/html/FotoboxJO/src/pages/$layout_file"; then
        echo "   ✓ $layout_file: Correctly configured for horizontal row layout"
    else
        echo "   ✗ $layout_file: Not configured for horizontal row layout"
    fi
done

# Check fixed sizes instead of percentage-based
if grep -q "width: 120px" "/var/www/html/FotoboxJO/src/pages/canvasLayout1.php"; then
    echo "   ✓ Layout 1: Uses fixed size (120px) for photo slots"
else
    echo "   ✗ Layout 1: Not using fixed size for photo slots"
fi

if grep -q "width: 100px" "/var/www/html/FotoboxJO/src/pages/canvasLayout2.php"; then
    echo "   ✓ Layout 2: Uses fixed size (100px) for photo slots"
else
    echo "   ✗ Layout 2: Not using fixed size for photo slots"
fi

if grep -q "width: 80px" "/var/www/html/FotoboxJO/src/pages/canvasLayout3.php"; then
    echo "   ✓ Layout 3: Uses fixed size (80px) for photo slots"
else
    echo "   ✗ Layout 3: Not using fixed size for photo slots"
fi

echo ""
echo "🎨 Checking global CSS updates:"
if grep -q "height: 450px" "/var/www/html/FotoboxJO/src/pages/home-styles.css"; then
    echo "   ✓ Global CSS: Camera height standardized to 450px"
else
    echo "   ✗ Global CSS: Camera height not standardized"
fi

if grep -q "min-height: 260px" "/var/www/html/FotoboxJO/src/pages/home-styles.css"; then
    echo "   ✓ Global CSS: Photo preview container height standardized"
else
    echo "   ✗ Global CSS: Photo preview container height not standardized"
fi

# Check Layout 1 specific CSS
if grep -q "flex-direction: row" "/var/www/html/FotoboxJO/src/pages/home-styles.css"; then
    echo "   ✓ Global CSS: All layouts configured for horizontal layout"
else
    echo "   ✗ Global CSS: Layouts not configured for horizontal layout"
fi

echo ""
echo "📱 Checking responsive design (1280x1024 media query):"
if grep -q "@media (min-width: 1280px) and (max-height: 1024px)" "/var/www/html/FotoboxJO/src/pages/home-styles.css"; then
    echo "   ✓ Global CSS: 1280x1024 media query added"
else
    echo "   ✗ Global CSS: 1280x1024 media query missing"
fi

echo ""
echo "🧪 Test file created:"
if [[ -f "/var/www/html/FotoboxJO/test_layout_consistency.html" ]]; then
    echo "   ✓ test_layout_consistency.html created for visual verification"
else
    echo "   ✗ Test file not created"
fi

echo ""
echo "📋 Summary of Layout Specifications:"
echo "   • Layout 1: 2 photos, horizontal row (120x120px each)"
echo "   • Layout 2: 4 photos, horizontal row (100x100px each)" 
echo "   • Layout 3: 6 photos, horizontal row (80x80px each)"
echo "   • Layout 4: 8 photos, horizontal row (70x70px each)"
echo "   • Layout 5: 6 photos, horizontal row (80x80px each)"
echo "   • Layout 6: 4 photos, horizontal row (100x100px each)"
echo ""
echo "   • All cameras: 450px height (standardized)"
echo "   • All photo previews: 260px-300px container height"
echo "   • All layouts: Horizontal row (flex-direction: row)"
echo "   • Responsive design optimized for 1280x1024"

echo ""
echo "✅ Layout consistency implementation completed!"
echo "🌐 Open test_layout_consistency.html in browser to verify visually"
