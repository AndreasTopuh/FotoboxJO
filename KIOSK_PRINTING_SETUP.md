# 🖨️ KIOSK PRINTING SETUP GUIDE

## Panduan Setup Kiosk Printing untuk Photobooth

### 1. Chrome Kiosk Mode Setup

#### Untuk Windows:
1. **Buat shortcut Chrome khusus:**
   - Klik kanan di desktop → New → Shortcut
   - Target: `"C:\Program Files\Google\Chrome\Application\chrome.exe" --kiosk --kiosk-printing https://www.gofotobox.online/src/pages/customizeLayout1.php`
   - Name: `Photobooth Kiosk`

2. **Atau gunakan command line:**
   ```bash
   "C:\Program Files\Google\Chrome\Application\chrome.exe" --kiosk --kiosk-printing --disable-web-security --allow-running-insecure-content --disable-features=VizDisplayCompositor https://www.gofotobox.online
   ```

#### Untuk Linux:
```bash
google-chrome --kiosk --kiosk-printing --disable-web-security --allow-running-insecure-content https://www.gofotobox.online/src/pages/customizeLayout1.php
```

#### Untuk macOS:
```bash
/Applications/Google\ Chrome.app/Contents/MacOS/Google\ Chrome --kiosk --kiosk-printing https://www.gofotobox.online/src/pages/customizeLayout1.php
```

### 2. Chrome Flags untuk Kiosk Printing

Akses `chrome://flags/` dan aktifkan:
- `#kiosk-mode-printing` → Enabled
- `#print-preview-kiosk-mode` → Enabled
- `#enable-print-browser` → Enabled

### 3. Default Printer Setup

1. **Set default printer di sistem:**
   - Windows: Control Panel → Devices and Printers → Right-click printer → Set as default
   - Linux: System Settings → Printers → Select printer → Set as default
   - macOS: System Preferences → Printers & Scanners → Select printer → Set as default

2. **Printer harus support langsung print tanpa dialog**

### 4. Testing Kiosk Mode

#### URL Testing:
- Normal mode: `https://www.gofotobox.online/src/pages/customizeLayout1.php`
- Force kiosk mode: `https://www.gofotobox.online/src/pages/customizeLayout1.php?kiosk=true`

#### Browser Console Testing:
```javascript
// Enable kiosk mode manually
window.enableKioskMode();

// Check kiosk status
console.log('Kiosk Mode:', window.kioskMode);

// Disable kiosk mode
window.disableKioskMode();
```

### 5. Features dalam Kiosk Mode

✅ **Automatic Detection:**
- Deteksi otomatis Chrome kiosk mode
- Indikator kiosk mode di kiri atas
- Print button berubah jadi "Print (Kiosk Mode)"

✅ **Silent Printing:**
- Print langsung tanpa dialog
- Menggunakan iframe tersembunyi
- Feedback visual saat printing
- Fallback ke standard print jika gagal

✅ **Error Handling:**
- Deteksi jika popup diblokir
- Fallback methods
- User feedback yang informatif

### 6. Troubleshooting

#### Problem: Print dialog masih muncul
**Solution:**
1. Pastikan Chrome dalam mode kiosk (`--kiosk` flag)
2. Pastikan `--kiosk-printing` flag digunakan
3. Set default printer di sistem
4. Coba force kiosk mode dengan `?kiosk=true`

#### Problem: Print tidak berfungsi
**Solution:**
1. Check printer connection
2. Check printer driver
3. Check browser console untuk error
4. Coba test print dari aplikasi lain

#### Problem: Kiosk mode tidak terdeteksi
**Solution:**
1. Gunakan Chrome flags yang tepat
2. Check browser console untuk detection log
3. Gunakan URL parameter `?kiosk=true` untuk testing
4. Manual enable: `window.enableKioskMode()`

### 7. Production Setup Checklist

- [ ] Chrome installed dengan flags kiosk
- [ ] Default printer configured
- [ ] Network connection stable
- [ ] Kiosk shortcut created
- [ ] Testing semua fungsi print
- [ ] Backup printer setup
- [ ] Auto-start script (optional)

### 8. Security Notes

- Kiosk mode disable beberapa browser feature untuk security
- Pastikan hanya aplikasi photobooth yang bisa diakses
- Consider disable right-click dan F12 untuk production

### 9. Performance Tips

- Use SSD untuk loading cepat
- Minimum 4GB RAM
- Disable antivirus real-time scan untuk folder Chrome
- Regular restart untuk prevent memory leak

---

## 🎯 Implementasi di Code

File yang dimodifikasi:
- `src/pages/customizeLayout1.js` - Main kiosk printing logic
- `src/pages/customizeLayout1.php` - UI dan setup script

### Key Functions:
- `detectKioskMode()` - Auto detection
- `handleKioskPrint()` - Main print handler
- `silentKioskPrint()` - Kiosk mode printing
- `standardPrint()` - Fallback printing

### Visual Indicators:
- Kiosk indicator badge (top-left)
- Enhanced print button
- Print status feedback
- Loading overlay saat printing

---

**Support:** Jika ada masalah, check browser console untuk debugging info.
