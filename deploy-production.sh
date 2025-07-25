#!/bin/bash

# Script Deployment PWA GoFotobox ke Production
# Jalankan script ini untuk upload semua file yang diperlukan

echo "🚀 Starting Enhanced PWA Deployment to Production (v6-modern)..."

# List semua file yang perlu di-upload
FILES_TO_UPLOAD=(
    "manifest.json"
    "sw.js"
    "force-sw-update.js"
    "index.html"
    "offline.html"
    "styles.css"
    "src/includes/pwa-helper.php"
    "src/api-fetch/set_session.php"
    "src/api-fetch/create_photo_session.php"
    "src/api-fetch/reset_session.php"
    "src/pages/selectlayout.php"
    "src/pages/selectpayment.php"
    "src/pages/payment-qris.php"
    "src/pages/payment-bank.php"
    "src/pages/canvas.php"
    "src/pages/customize.php"
    "src/pages/thankyou.php"
    "src/pages/canvasLayout1.php"
    "src/pages/canvasLayout2.php"
    "src/pages/canvasLayout3.php"
    "src/pages/canvasLayout4.php"
    "src/pages/canvasLayout5.php"
    "src/pages/canvasLayout6.php"
    "src/pages/customizeLayout1.php"
    "src/pages/customizeLayout2.php"
    "src/pages/customizeLayout3.php"
    "src/pages/customizeLayout4.php"
    "src/pages/customizeLayout5.php"
    "src/pages/customizeLayout6.php"
    "src/assets/icons/logo-gofotobox-new.jpeg"
    "src/assets/icons/logo-gofotobox-new.png"
    "src/assets/icons/icon-192x192.png"
    "src/assets/icons/icon-512x512.png"
    "src/assets/icons/apple-touch-icon.png"
    "src/assets/icons/favicon.png"
)

echo "📋 Files to upload:"
for file in "${FILES_TO_UPLOAD[@]}"; do
    if [ -f "$file" ]; then
        echo "  ✅ $file"
    else
        echo "  ❌ $file (NOT FOUND)"
    fi
done

echo ""
echo "📁 Current working directory: $(pwd)"
echo "📊 Total files: ${#FILES_TO_UPLOAD[@]}"

echo ""
echo "🆕 v6-modern Updates:"
echo "  ✅ Modern PWA meta tags (mobile-web-app-capable)"
echo "  ✅ Development-friendly service worker caching"
echo "  ✅ Enhanced force update script"
echo "  ✅ Backward compatibility with Apple/Windows"
echo "  ✅ Auto-update detection system"

echo ""
echo "🔧 Next steps for manual upload:"
echo "1. Connect to your hosting via FTP/cPanel File Manager"
echo "2. Upload all the files listed above to the root directory"
echo "3. Make sure file permissions are set to 644 for files, 755 for folders"
echo "4. Clear browser cache completely"
echo "5. Test https://gofotobox.online"

echo ""
echo "🔍 Production testing checklist:"
echo "  □ Open https://gofotobox.online in private/incognito window"
echo "  □ Check browser console for service worker logs"
echo "  □ Test payment flow → selectlayout redirect"
echo "  □ Verify PWA install button appears"
echo "  □ Test offline functionality"

echo ""
echo "🐛 If issues persist:"
echo "  • Check browser Network tab for failed requests"
echo "  • Verify all files uploaded correctly"
echo "  • Clear browser application storage"
echo "  • Check PHP error logs on server"

echo ""
echo "✅ Deployment preparation complete!"
echo "   Upload the files manually via FTP/cPanel and test!"
