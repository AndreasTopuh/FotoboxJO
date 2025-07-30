# Session Timer Implementation - GoFotobox

## Overview

Implementasi sistem session timer 20 menit untuk aplikasi GoFotobox photobooth dengan logika yang sesuai requirements.

## Alur Kerja Session Timer

### 1. Index.php → Description.php

- **Index.php**: Halaman utama dengan tombol "Mulai" yang mengarahkan ke `description.php`
- **Description.php**: Halaman baru yang menampilkan:
  - **Kiri**: Langkah-langkah penggunaan aplikasi (6 steps)
  - **Kanan**: Contoh gambar 6 layout foto
  - **Tombol**: "Lanjutkan" ke `selectpayment.php`

### 2. Selectpayment.php

- Menampilkan daftar metode pembayaran (QRIS & Virtual Bank BCA)
- **Timer dimulai**: Saat user klik salah satu metode pembayaran
- **Timer durasi**: 20 menit dari server
- **Back button**: Kembali ke index.php

### 3. Payment Pages (payment-qris.php & payment-bank.php)

- **Timer display**: Tampil di kanan atas (countdown dari 20 menit)
- **Timer behavior**: Melanjutkan timer server (tidak reset saat refresh)
- **Timer expired**: Pop-up dengan tombol "Selesai" → reset session → redirect ke index.php

### 4. Selectlayout.php

- **Timer display**: Melanjutkan countdown timer 20 menit
- **Timer expired**: Semua opsi layout ditutup + pop-up "Selesai" → reset session → index.php

### 5. CanvasLayout1.php (Photo Session)

- **Timer display**: Melanjutkan countdown timer 20 menit
- **Timer expired logic**:
  - Jika `foto = 0` (belum ambil foto): Pop-up "Selesai" → reset session → index.php
  - Jika `foto > 0` (sudah ambil foto): Redirect langsung ke `customizeLayout1.php`

### 6. CustomizeLayout1.php

- **Timer display**: Melanjutkan countdown timer 20 menit
- **Timer expired**: Redirect langsung ke `thankyou.php`

### 7. Thankyou.php

- **No timer**: Mengabaikan semua session timer
- **Tombol "Selesai"**: Reset session → redirect ke index.php

## Technical Implementation

### Backend (PHP)

1. **SessionManager** class direvisi:

   - `SESSION_TIMEOUT = 1200` (20 menit)
   - `startPaymentSession()`: Dimulai saat pilih metode pembayaran
   - `getMainTimerRemaining()`: Mendapatkan sisa waktu timer utama

2. **API Endpoints**:
   - `session_status.php`: Cek status session dan sisa waktu
   - `set_session.php`: Start payment session, complete payment, select layout
   - `reset_session.php`: Reset session

### Frontend (JavaScript)

1. **session-timer.js**:

   - Class `SessionTimer` untuk menangani timer client-side
   - Auto-sync dengan server setiap 30 detik
   - Countdown display di kanan atas
   - Custom expired handlers per halaman

2. **Page-specific behavior**:
   - Payment pages: Show expiration modal
   - Layout page: Disable options + modal
   - Canvas page: Check photo count logic
   - Customize page: Redirect to thank you

## Key Features

### Timer Synchronization

- Timer dimulai di server saat user pilih metode pembayaran
- Client-side timer sync dengan server setiap 30 detik
- Tidak reset saat refresh halaman
- Persistent across page navigation

### Expiration Handling

- Berbeda per halaman sesuai business logic
- Pop-up modal dengan tombol reset session
- Smart redirect berdasarkan user progress

### Session Management

- Progressive session states
- Validation per halaman
- Auto-cleanup saat expired

## File Changes

### New Files:

- `/src/pages/description.php`
- `/src/includes/session-timer.js`

### Modified Files:

- `/index.php` - Redirect ke description.php
- `/src/includes/session-manager.php` - Timer 20 menit
- `/src/api-fetch/session_status.php` - Timer API
- `/src/api-fetch/set_session.php` - Start payment session
- `/src/pages/selectpayment.php` - Start timer on payment selection
- `/src/pages/payment-qris.php` - Timer display
- `/src/pages/payment-bank.php` - Timer display
- `/src/pages/selectlayout.php` - Timer + layout selection
- `/src/pages/canvasLayout1.php` - Timer + photo logic
- `/src/pages/customizeLayout1.php` - Timer + redirect logic
- `/src/pages/thankyou.php` - Reset session functionality

## Testing

1. **Normal Flow**: Index → Description → Payment → Layout → Canvas → Customize → Thank You
2. **Timer Expiry**: Test expiration at each stage
3. **Refresh Behavior**: Timer should continue, not reset
4. **Session Reset**: Should work from any expiration point

## Notes

- Timer berjalan total 20 menit sejak pilih metode pembayaran
- Reset session mereset juga client-side timer
- Session state validation mencegah akses unauthorized
- Mobile-friendly timer display
