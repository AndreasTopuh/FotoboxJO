## 🚨 CARA MEMPERBAIKI EMAILJS ERROR "recipients address is empty"

### 📧 **Yang Harus Dilakukan di EmailJS Dashboard:**

1. **Buka EmailJS Dashboard** → https://dashboard.emailjs.com/admin/templates/
2. **Klik Template "Feedback Request"**
3. **Pergi ke tab "Settings"**
4. **Pastikan "To Email" field diisi dengan:** `{{email}}`
5. **Klik "Save"**

### 🔧 **Template Settings yang Benar:**

**Tab Settings:**

- **To Email:** `{{email}}` (bukan `{{to_email}}`)
- **From Name:** GOFOTOBOX
- **From Email:** figojen3@gmail.com

**Tab Content:**

- Subject: `Foto Photobooth Anda Siap!`
- Content sudah benar dengan `{{link}}`

### 📝 **Template Variables yang Digunakan:**

- `{{email}}` - untuk email tujuan
- `{{link}}` - untuk link foto
- `{{user_name}}` - untuk nama (opsional)

### ⚠️ **PENTING:**

Error "recipients address is empty" terjadi karena di **Settings Tab**, field "To Email" tidak menggunakan variable yang tepat.

### 🧪 **Setelah Diperbaiki, Test dengan:**

1. Save template di EmailJS
2. Test di customizeLayout1.php
3. Email akan terkirim dengan benar

**Masalahnya BUKAN di kode JavaScript, tapi di konfigurasi template EmailJS!**
