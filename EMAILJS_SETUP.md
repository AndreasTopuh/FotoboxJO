# EmailJS Integration untuk Photobooth

## Setup yang Telah Dilakukan

### 1. EmailJS Configuration

- **Public Key**: `9SDzOfKjxuULQ5ZW8`
- **Service ID**: `service_gtqjb2j`
- **Template ID**: `template_pp5i4hm`

### 2. File yang Dibuat/Diubah

#### File Baru:

- `src/api-fetch/save_final_photo.php` - API untuk menyimpan foto dan generate token
- `src/pages/yourphotos.php` - Halaman untuk menampilkan foto dengan link
- `src/user-photos/` - Directory untuk menyimpan foto sementara
- `src/api-fetch/cleanup_photos.php` - Script cleanup untuk foto expired
- `cleanup_photos.sh` - Bash script untuk automated cleanup
- `test_emailjs.html` - File test untuk EmailJS integration

#### File yang Diubah:

- `src/pages/customizeLayout1.php` - Ditambahkan EmailJS scripts
- `src/pages/customizeLayout1.js` - Diubah fungsi sendPhotoEmail menggunakan EmailJS
- `src/pages/customizeLayout2.php` - Ditambahkan EmailJS scripts (untuk konsistensi)

### 3. Cara Kerja System

1. **User mengklik tombol Email** di halaman customize
2. **Input email** di modal popup
3. **Canvas foto disimpan** ke server dengan token unik
4. **EmailJS mengirim email** dengan link ke yourphotos.php?token=xxx
5. **User menerima email** dengan link download
6. **Link berlaku 30 menit** lalu otomatis dihapus

### 4. Setup EmailJS Template

Template EmailJS harus menggunakan variabel berikut:

- `{{to_email}}` - Email tujuan
- `{{photo_link}}` - Link ke foto
- `{{user_name}}` - Nama user (opsional)

Contoh template email:

```
Subject: Foto Photobooth Anda Siap!

Hi {{user_name}},

Foto hasil customize Anda sudah siap! Klik link dibawah untuk mengunduh:

{{photo_link}}

Link ini hanya berlaku selama 30 menit untuk menjaga privasi Anda.

Terima kasih telah menggunakan layanan Photobooth kami!
```

### 5. Automatic Cleanup

Untuk setup automatic cleanup, tambahkan ke crontab:

```bash
# Edit crontab
crontab -e

# Tambahkan line ini untuk cleanup setiap 5 menit
*/5 * * * * /var/www/html/FotoboxJO/cleanup_photos.sh
```

### 6. Testing

Gunakan file `test_emailjs.html` untuk test:

1. Buka `/test_emailjs.html` di browser
2. Masukkan email
3. Klik "Send Test Email"
4. Cek email yang diterima

### 7. Security Features

- **Token-based access**: Foto hanya bisa diakses dengan token unik
- **Time-limited**: Link expired dalam 30 menit
- **Automatic cleanup**: File dihapus otomatis setelah expired
- **Session tracking**: Token disimpan di session untuk tracking

### 8. Directory Structure

```
FotoboxJO/
├── src/
│   ├── api-fetch/
│   │   ├── save_final_photo.php (NEW)
│   │   └── cleanup_photos.php (NEW)
│   ├── pages/
│   │   ├── yourphotos.php (NEW)
│   │   ├── customizeLayout1.php (MODIFIED)
│   │   ├── customizeLayout1.js (MODIFIED)
│   │   └── customizeLayout2.php (MODIFIED)
│   └── user-photos/ (NEW)
├── cleanup_photos.sh (NEW)
└── test_emailjs.html (NEW)
```

### 9. Troubleshooting

**Jika email tidak terkirim:**

1. Cek console browser untuk error EmailJS
2. Pastikan Service ID dan Template ID benar
3. Cek template EmailJS memiliki variabel yang tepat

**Jika foto tidak tersimpan:**

1. Cek permission directory `src/user-photos`
2. Cek error di console browser
3. Cek log PHP error

**Jika link expired:**

1. Normal behavior - link memang expired dalam 30 menit
2. File otomatis terhapus untuk privacy

### 10. Next Steps (Opsional)

- Implementasi email untuk customizeLayout lainnya
- Add email statistics/tracking
- Customize email template design
- Add email validation yang lebih robust
