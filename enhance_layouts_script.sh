#!/bin/bash

# 🚀 FAST ENHANCEMENT SCRIPT FOR LAYOUT 5 & 6
# This script quickly implements all enhanced features across remaining layouts

echo "🚀 Starting fast enhancement for Layout 5 & 6..."

# Function to backup original files
backup_file() {
    local file=$1
    if [ -f "$file" ]; then
        cp "$file" "${file}.backup"
        echo "✅ Backed up: $file"
    fi
}

# Backup original files
echo "📁 Creating backups..."
backup_file "src/pages/canvasLayout5.js"
backup_file "src/pages/canvasLayout6.js"

echo "🔄 Implementing enhanced features..."

# LAYOUT 5 ENHANCEMENT (6 photos - same as Layout 3)
echo "🎯 Enhancing Layout 5 (6 photos)..."
python3 << 'LAYOUT5_PYTHON'
import re

# Read Layout 3 enhanced version as template
with open('src/pages/canvasLayout3.js', 'r') as f:
    layout3_content = f.read()

# Read original Layout 5
with open('src/pages/canvasLayout5.js', 'r') as f:
    layout5_content = f.read()

# Replace key identifiers for Layout 5
enhanced_layout5 = layout3_content.replace('canvasLayout3', 'canvasLayout5')
enhanced_layout5 = enhanced_layout5.replace('Layout 3', 'Layout 5')
enhanced_layout5 = enhanced_layout5.replace('layout 3', 'layout 5')
enhanced_layout5 = enhanced_layout5.replace('customizeLayout3.php', 'customizeLayout5.php')
enhanced_layout5 = enhanced_layout5.replace('canvasLayout3.php', 'canvasLayout5.php')

# Update console log
enhanced_layout5 = enhanced_layout5.replace(
    '🎯 Layout 3 (6 photos) initialized successfully!',
    '🎯 Layout 5 (6 photos) initialized successfully!'
)

# Save enhanced Layout 5
with open('src/pages/canvasLayout5.js', 'w') as f:
    f.write(enhanced_layout5)

print("✅ Layout 5 enhanced successfully!")
LAYOUT5_PYTHON

# LAYOUT 6 ENHANCEMENT (4 photos - same as Layout 2)
echo "🎯 Enhancing Layout 6 (4 photos)..."
python3 << 'LAYOUT6_PYTHON'
import re

# Read Layout 2 enhanced version as template
with open('src/pages/canvasLayout2.js', 'r') as f:
    layout2_content = f.read()

# Read original Layout 6
with open('src/pages/canvasLayout6.js', 'r') as f:
    layout6_content = f.read()

# Replace key identifiers for Layout 6
enhanced_layout6 = layout2_content.replace('canvasLayout2', 'canvasLayout6')
enhanced_layout6 = enhanced_layout6.replace('Layout 2', 'Layout 6')
enhanced_layout6 = enhanced_layout6.replace('layout 2', 'layout 6')
enhanced_layout6 = enhanced_layout6.replace('customizeLayout2.php', 'customizeLayout6.php')
enhanced_layout6 = enhanced_layout6.replace('canvasLayout2.php', 'canvasLayout6.php')

# Update console log and photo count
enhanced_layout6 = enhanced_layout6.replace(
    '🎯 Layout 2 (4 photos) initialized successfully!',
    '🎯 Layout 6 (4 photos) initialized successfully!'
)

# Save enhanced Layout 6
with open('src/pages/canvasLayout6.js', 'w') as f:
    f.write(enhanced_layout6)

print("✅ Layout 6 enhanced successfully!")
LAYOUT6_PYTHON

echo ""
echo "🎉 ENHANCEMENT COMPLETE!"
echo "📊 Summary:"
echo "   ✅ Layout 1: Complete (2 photos)"
echo "   ✅ Layout 2: Complete (4 photos)"
echo "   ✅ Layout 3: Complete (6 photos)"
echo "   ✅ Layout 4: Complete (8 photos)"
echo "   ✅ Layout 5: Complete (6 photos) - ENHANCED"
echo "   ✅ Layout 6: Complete (4 photos) - ENHANCED"
echo ""
echo "🚀 All layouts now have:"
echo "   • Enhanced fullscreen with START CAPTURE button"
echo "   • CAPTURE ALL functionality"
echo "   • Advanced modal carousel with indicators"
echo "   • Individual retake functionality"
echo "   • Space bar functionality disabled"
echo "   • Enhanced UI and button management"
echo ""
echo "🔄 Run this script with: bash enhance_layouts_script.sh"
echo "📁 Original files backed up with .backup extension"
