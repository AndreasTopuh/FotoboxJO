# Developer Access Feature

## Overview

Fitur Developer Access memungkinkan developer atau admin untuk melewati proses pembayaran dan langsung masuk ke halaman select layout dengan timer yang sama (20 menit).

## Cara Menggunakan

### 1. Akses Developer Button

- Buka halaman `selectpayment.php`
- Di pojok kanan bawah akan ada tombol developer yang hampir transparan (opacity 0.1)
- Hover pada tombol tersebut untuk melihatnya lebih jelas (opacity 0.5)
- Klik tombol tersebut untuk membuka modal kode akses

### 2. Masukkan Kode Akses

- Modal akan muncul dengan input field untuk kode akses
- Masukkan kode: **54321**
- Tekan Enter atau klik tombol "Masuk"
- Jika kode salah, akan muncul alert dan input akan dikosongkan

### 3. Akses Berhasil

- Jika kode benar, sistem akan:
  - Memulai session dengan timer 20 menit (sama seperti payment session)
  - Menandai payment sebagai completed otomatis
  - Menambahkan flag `is_developer_session = true`
  - Redirect langsung ke halaman `selectlayout.php`

### 4. Visual Indicator

- Di halaman select layout, akan muncul badge "Developer Mode" di pojok kanan atas
- Badge ini hanya muncul jika session dimulai melalui developer access

## Technical Implementation

### Frontend Changes

**File: `src/pages/selectpayment.php`**

- Tombol developer tersembunyi di pojok kanan bawah
- Modal dengan input kode akses
- JavaScript untuk verifikasi kode dan komunikasi dengan API

### Backend Changes

**File: `src/api-fetch/set_session.php`**

- Handler baru untuk action `start_developer_session`
- Otomatis complete payment dengan order ID `DEV_ACCESS_[timestamp]`
- Set flag `is_developer_session = true`

**File: `src/pages/selectlayout.php`**

- Visual indicator untuk developer mode

## Security Notes

- Kode akses: `54321`
- Tombol developer hampir tidak terlihat untuk mencegah akses yang tidak diinginkan
- Session tetap menggunakan timer 20 menit yang sama
- Flag developer session tersimpan untuk tracking

## Testing

1. Buka `http://localhost:8000/src/pages/selectpayment.php`
2. Cari tombol di pojok kanan bawah (hover untuk melihat)
3. Klik tombol, masukkan kode `54321`
4. Verify redirect ke selectlayout.php dengan badge "Developer Mode"

## File Changes Summary

- `src/pages/selectpayment.php` - Added developer button and modal
- `src/api-fetch/set_session.php` - Added developer session handler
- `src/pages/selectlayout.php` - Added developer mode indicator
- `DEVELOPER_ACCESS.md` - This documentation
