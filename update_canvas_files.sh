#!/bin/bash

# Script untuk mengupdate semua file canvas dan customize
# Mengganti session validation dan menambahkan timer

FILES_TO_UPDATE=(
    "canvasLayout3.php"
    "canvasLayout4.php" 
    "canvasLayout5.php"
    "canvasLayout6.php"
)

CANVAS_SESSION_HEADER='<?php
session_start();

// Validasi session photo
if (!isset($_SESSION["photo_expired_time"]) || time() > $_SESSION["photo_expired_time"]) {
    // Session photo expired atau tidak ada
    header("Location: customize.php");
    exit();
}

if (!isset($_SESSION["session_type"]) || $_SESSION["session_type"] !== "photo") {
    // Session tidak valid
    header("Location: selectlayout.php");
    exit();
}

// Hitung waktu tersisa
$timeLeft = $_SESSION["photo_expired_time"] - time();
?>'

echo "Starting canvas files update..."

for file in "${FILES_TO_UPDATE[@]}"; do
    if [[ -f "/var/www/html/FotoboxJO/src/pages/$file" ]]; then
        echo "Processing $file..."
        # Backup original file
        cp "/var/www/html/FotoboxJO/src/pages/$file" "/var/www/html/FotoboxJO/src/pages/${file}.backup"
        echo "Backup created for $file"
    fi
done

echo "Script prepared. Manual updates still required for each file."
