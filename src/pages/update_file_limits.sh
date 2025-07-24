#!/bin/bash

# Update file size limit dari 5MB ke 15MB di semua canvas layout files

echo "🔄 Updating file size limits from 5MB to 15MB..."

layouts=(1 2 3 4 5 6)

for layout in "${layouts[@]}"; do
    file="canvasLayout${layout}.js"
    
    if [[ -f "$file" ]]; then
        echo "  Updating $file..."
        
        # Update storage limit variable
        sed -i 's/5 \* 1024 \* 1024; \/\/ 5MB limit/15 * 1024 * 1024; \/\/ 15MB limit/g' "$file"
        
        # Update error message
        sed -i 's/exceeds the 5MB limit/exceeds the 15MB limit/g' "$file"
        
        # Update upload button alert
        sed -i 's/does not exceed 5MB/does not exceed 15MB/g' "$file"
        
        echo "    ✅ $file updated"
    else
        echo "    ❌ $file not found"
    fi
done

# Also update original canvas.js if exists
if [[ -f "canvas.js" ]]; then
    echo "  Updating canvas.js..."
    sed -i 's/5 \* 1024 \* 1024; \/\/ 5MB limit/15 * 1024 * 1024; \/\/ 15MB limit/g' "canvas.js"
    sed -i 's/exceeds the 5MB limit/exceeds the 15MB limit/g' "canvas.js"
    sed -i 's/does not exceed 5MB/does not exceed 15MB/g' "canvas.js"
    echo "    ✅ canvas.js updated"
fi

echo ""
echo "🎉 All files updated successfully!"
echo ""
echo "📊 New file size limits:"
echo "  • Layout 1 (2 photos): 15MB total"
echo "  • Layout 2 (4 photos): 15MB total"
echo "  • Layout 3 (6 photos): 15MB total"
echo "  • Layout 4 (8 photos): 15MB total ✨"
echo "  • Layout 5 (6 photos): 15MB total"
echo "  • Layout 6 (4 photos): 15MB total"
echo ""
echo "💡 15MB adalah ruang yang cukup untuk:"
echo "  • 8 foto berkualitas tinggi (2MB per foto)"
echo "  • Upload dari smartphone modern"
echo "  • DSLR photos dengan compression"
