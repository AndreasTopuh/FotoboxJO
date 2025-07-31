#!/bin/bash

# Photobooth Photo Cleanup Script
# This script should be run every 5 minutes via cron job
# Add to crontab: */5 * * * * /var/www/html/FotoboxJO/cleanup_photos.sh

cd /var/www/html/FotoboxJO

# Run PHP cleanup script
php src/api-fetch/cleanup_photos.php

# Also directly clean files older than 30 minutes from user-photos directory
find src/user-photos -name "*.png" -type f -mmin +30 -delete

echo "Photo cleanup completed at $(date)"
