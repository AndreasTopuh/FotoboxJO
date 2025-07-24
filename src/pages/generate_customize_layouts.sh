#!/bin/bash

# Generate customizeLayout JS files for layouts 1-6
layouts=(1 2 3 4 5 6)
photo_counts=(2 4 6 8 6 4)

for i in "${!layouts[@]}"; do
    layout=${layouts[$i]}
    count=${photo_counts[$i]}
    
    echo "Generating customizeLayout${layout}.js with ${count} photos..."
    
    # Backup original file
    mv customizeLayout${layout}.js customizeLayout${layout}_backup.js 2>/dev/null || true
    
    # Create new file based on customizeLayout1_new.js
    if [ $layout -eq 1 ]; then
        # For layout 1, just copy the new file
        cp customizeLayout1_new.js customizeLayout${layout}.js
    else
        # For other layouts, modify the template
        sed "s/Layout 1/Layout ${layout}/g; s/customizeLayout1/customizeLayout${layout}/g; s/canvasLayout1/canvasLayout${layout}/g; s/layout1/layout${layout}/g" customizeLayout1_new.js > customizeLayout${layout}.js
    fi
    
done

echo "All customize layout files generated successfully!"
