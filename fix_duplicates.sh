#!/bin/bash

echo "🔧 REMOVING ALL CONTENT AFTER </html> FROM LAYOUT FILES..."

for layout in 3 4 5 6; do
    file="src/pages/canvasLayout$layout.php"
    
    if [ -f "$file" ]; then
        echo "Processing Layout $layout..."
        
        # Find the first occurrence of </html> and cut the file there
        line_num=$(grep -n "</html>" "$file" | head -1 | cut -d: -f1)
        
        if [ ! -z "$line_num" ]; then
            echo "  Found </html> at line $line_num"
            # Create temporary file with content up to and including </html>
            head -n "$line_num" "$file" > "${file}.tmp"
            mv "${file}.tmp" "$file"
            echo "  ✅ Layout $layout cleaned - $(wc -l < "$file") lines"
        else
            echo "  ❌ No </html> found in Layout $layout"
        fi
    else
        echo "  ❌ Layout $layout file not found"
    fi
done

echo ""
echo "📊 FINAL FILE SIZES:"
wc -l src/pages/canvasLayout*.php

echo ""
echo "🔍 VERIFYING NO DUPLICATES:"
for layout in 3 4 5 6; do
    file="src/pages/canvasLayout$layout.php"
    if [ -f "$file" ]; then
        body_count=$(grep -c "<body>" "$file" 2>/dev/null || echo "0")
        head_count=$(grep -c "<head>" "$file" 2>/dev/null || echo "0")
        html_end_count=$(grep -c "</html>" "$file" 2>/dev/null || echo "0")
        echo "Layout $layout: <body>=$body_count, <head>=$head_count, </html>=$html_end_count"
    fi
done

echo ""
echo "✅ CLEANUP COMPLETE!"
