#!/bin/bash

# Generate customizeLayout PHP files for layouts 1-6
layouts=(2 3 4 5 6)
layout_names=("Layout 2" "Layout 3" "Layout 4" "Layout 5" "Layout 6")
photo_counts=(4 6 8 6 4)

echo "Generating customizeLayout PHP files..."

for i in "${!layouts[@]}"; do
    layout=${layouts[$i]}
    layout_name="${layout_names[$i]}"
    count=${photo_counts[$i]}
    
    echo "Generating customizeLayout${layout}.php for ${layout_name} with ${count} photos..."
    
    # Backup original file
    cp customizeLayout${layout}.php customizeLayout${layout}_backup_new.php 2>/dev/null || true
    
    # Create new file based on customizeLayout1.php
    sed "s/Layout 1/${layout_name}/g; s/layout 1/${layout_name,,}/g; s/customizeLayout1/customizeLayout${layout}/g; s/canvasLayout1/canvasLayout${layout}/g; s/2-photo/${count}-photo/g" customizeLayout1.php > customizeLayout${layout}_temp.php
    
    # Replace the file
    mv customizeLayout${layout}_temp.php customizeLayout${layout}.php
    
done

echo "All customize layout PHP files generated successfully!"
