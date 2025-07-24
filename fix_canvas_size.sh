#!/bin/bash

# Script untuk memperbaiki ukuran canvas di semua file customizeLayout1-6.js
# Mengubah dari ukuran yang salah ke ukuran foto 4R yang benar: 1206 x 1794 pixels (10.2cm x 15.2cm pada 300 DPI)

echo "Memperbaiki ukuran canvas di file customizeLayout1-6.js..."

# Directory path
DIR="/var/www/html/FotoboxJO/src/pages"

# Loop untuk semua file customizeLayout1-6.js
for i in {1..6}; do
    FILE="$DIR/customizeLayout${i}.js"
    
    if [ -f "$FILE" ]; then
        echo "Memperbaiki $FILE..."
        
        # Ganti canvasWidth = 592 ke 1206
        sed -i 's/const canvasWidth = 592;/const canvasWidth = 1206;   \/\/ 10.2cm pada 300 DPI/g' "$FILE"
        
        # Ganti canvasHeight = 1352 ke 1794
        sed -i 's/const canvasHeight = 1352;/const canvasHeight = 1794;  \/\/ 15.2cm pada 300 DPI/g' "$FILE"
        
        # Untuk file yang mungkin menggunakan ukuran lama 1200 x 1800
        sed -i 's/const canvasWidth = 1200;/const canvasWidth = 1206;   \/\/ 10.2cm pada 300 DPI/g' "$FILE"
        sed -i 's/const canvasHeight = 1800;/const canvasHeight = 1794;  \/\/ 15.2cm pada 300 DPI/g' "$FILE"
        
        echo "✓ $FILE selesai diperbaiki"
    else
        echo "✗ $FILE tidak ditemukan"
    fi
done

echo ""
echo "Verifikasi perubahan:"
echo "====================="

# Tampilkan semua canvasWidth dan canvasHeight yang ditemukan
for i in {1..6}; do
    FILE="$DIR/customizeLayout${i}.js"
    if [ -f "$FILE" ]; then
        echo "File: customizeLayout${i}.js"
        grep -n "canvasWidth\|canvasHeight" "$FILE" | head -4
        echo ""
    fi
done

echo "Perbaikan selesai!"
echo ""
echo "Ringkasan perubahan:"
echo "- canvasWidth: 592 atau 1200 → 1206 pixels (10.2cm pada 300 DPI)"
echo "- canvasHeight: 1352 atau 1800 → 1794 pixels (15.2cm pada 300 DPI)"
echo "- Ukuran ini sesuai dengan foto 4R standar: 10.2cm x 15.2cm"
