# Tutorial Perbaikan Ukuran Canvas - Foto 4R

## Masalah yang Diperbaiki
Ukuran canvas di file `canvasLayout1-6.js` dan `customizeLayout1-6.js` menggunakan ukuran yang salah (5cm x 15cm atau ukuran lainnya) padahal seharusnya menggunakan ukuran foto 4R standar yaitu **10.2cm x 15.2cm**.

## Perubahan yang Dilakukan

### 1. Perhitungan Ukuran Canvas yang Benar
- **Ukuran foto 4R**: 10.2cm x 15.2cm
- **Konversi ke pixels pada 300 DPI**:
  - Lebar: 10.2cm = 4.02 inci = 4.02 × 300 = **1,206 pixels**
  - Tinggi: 15.2cm = 5.98 inci = 5.98 × 300 = **1,794 pixels**

### 2. File yang Diperbaiki

#### A. File customize.js (file asli)
**Lokasi**: `/var/www/html/FotoboxJO/src/pages/customize.js`

**Perubahan di baris 1329-1330**:
```javascript
// SEBELUM:
const canvasWidth = 592;
const canvasHeight = 1352;

// SESUDAH:
const canvasWidth = 1206;   // 10.2cm pada 300 DPI
const canvasHeight = 1794;  // 15.2cm pada 300 DPI
```

**Perubahan di baris 1535-1536**:
```javascript
// SEBELUM:
const canvasWidth = 592;
const canvasHeight = 1352;

// SESUDAH:
const canvasWidth = 1206;   // 10.2cm pada 300 DPI
const canvasHeight = 1794;  // 15.2cm pada 300 DPI
```

#### B. File customizeLayout1.js sampai customizeLayout6.js
**Lokasi**: `/var/www/html/FotoboxJO/src/pages/customizeLayout*.js`

**Perubahan di semua file** (sekitar baris 216-217):
```javascript
// SEBELUM (berbagai variasi):
const canvasWidth = 592;     // atau 1200
const canvasHeight = 1352;   // atau 1800

// SESUDAH (seragam di semua file):
const canvasWidth = 1206;   // 10.2cm pada 300 DPI
const canvasHeight = 1794;  // 15.2cm pada 300 DPI
```

### 3. Script Otomatis yang Digunakan
**File**: `/var/www/html/FotoboxJO/fix_canvas_size.sh`

Script ini menggunakan `sed` untuk mengganti semua instance ukuran canvas yang salah:
```bash
# Ganti 592 ke 1206
sed -i 's/const canvasWidth = 592;/const canvasWidth = 1206;   \/\/ 10.2cm pada 300 DPI/g'

# Ganti 1352 ke 1794  
sed -i 's/const canvasHeight = 1352;/const canvasHeight = 1794;  \/\/ 15.2cm pada 300 DPI/g'

# Ganti 1200 ke 1206 (untuk file yang menggunakan ukuran lama)
sed -i 's/const canvasWidth = 1200;/const canvasWidth = 1206;   \/\/ 10.2cm pada 300 DPI/g'

# Ganti 1800 ke 1794
sed -i 's/const canvasHeight = 1800;/const canvasHeight = 1794;  \/\/ 15.2cm pada 300 DPI/g'
```

### 4. Verifikasi Perubahan
Setelah perbaikan, semua file sekarang menggunakan ukuran yang konsisten:

```javascript
const canvasWidth = 1206;   // 10.2cm pada 300 DPI
const canvasHeight = 1794;  // 15.2cm pada 300 DPI
```

### 5. Dampak Perubahan
- ✅ Semua layout sekarang menghasilkan foto dengan ukuran 4R yang benar (10.2cm x 15.2cm)
- ✅ Konsistensi ukuran di semua file layout (1-6)
- ✅ Kualitas cetak yang lebih baik sesuai standar foto 4R
- ✅ Kompatibilitas dengan printer foto standar

### 6. File yang Terpengaruh
Total **8 file** yang diperbaiki:
1. `customize.js` (file asli)
2. `customizeLayout1.js`
3. `customizeLayout2.js`
4. `customizeLayout3.js`
5. `customizeLayout4.js`
6. `customizeLayout5.js`
7. `customizeLayout6.js`

### 7. Command untuk Verifikasi
Untuk memverifikasi bahwa semua perubahan telah diterapkan:
```bash
grep -n "canvasWidth.*1206\|canvasHeight.*1794" src/pages/customize*.js
```

## Ringkasan
Perbaikan ini memastikan bahwa semua foto yang dihasilkan dari aplikasi fotoboks akan memiliki ukuran standar foto 4R (10.2cm x 15.2cm) yang sesuai dengan ekspektasi pengguna dan standar industri percetakan foto.
