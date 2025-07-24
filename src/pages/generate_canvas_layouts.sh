#!/bin/bash

# Generate canvasLayout JS files for layouts 3-6
layouts=(3 4 5 6)
photo_counts=(6 8 6 4)

for i in "${!layouts[@]}"; do
    layout=${layouts[$i]}
    count=${photo_counts[$i]}
    
    echo "Generating canvasLayout${layout}.js with ${count} photos..."
    
    # Backup original file
    mv canvasLayout${layout}.js canvasLayout${layout}_backup.js 2>/dev/null || true
    
    # Create new file based on canvasLayout1.js
    sed "s/Layout 1/Layout ${layout}/g; s/canvasLayout1/canvasLayout${layout}/g; s/customizeLayout1/customizeLayout${layout}/g; s/photoCount = 2/photoCount = ${count}/g" canvasLayout1.js > canvasLayout${layout}.js
    
done

echo "All canvas layout files generated successfully!"
