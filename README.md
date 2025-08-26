# GoFotobox - Progressive Web Application Photobooth

[![Version](https://img.shields.io/badge/version-1.0.0-blue.svg)](https://github.com/AndreasTopuh/FotoboxJO)
[![PHP](https://img.shields.io/badge/PHP-8.0+-brightgreen.svg)](https://php.net)
[![JavaScript](https://img.shields.io/badge/JavaScript-ES6+-yellow.svg)](https://developer.mozilla.org/en-US/docs/Web/JavaScript)
[![PWA](https://img.shields.io/badge/PWA-Ready-purple.svg)](https://web.dev/progressive-web-apps/)

## 📋 Deskripsi Project

GoFotobox adalah aplikasi photobooth berbasis web yang memungkinkan pengguna untuk mengambil foto, mengedit dengan berbagai filter dan stiker, serta mencetak hasil foto langsung. Aplikasi ini dibangun sebagai Progressive Web App (PWA) dengan fitur offline-ready dan optimasi mobile-first.

## 🚀 Fitur Utama

### 📸 Photo Capture System

- **Multiple Layout Options**: 6 layout foto berbeda (2, 4, 6, 8 foto)
- **Real-time Camera Preview**: Preview langsung dengan filter dan efek
- **Enhanced Fullscreen Mode**: Mode fullscreen dengan tombol START CAPTURE
- **Batch Photo Capture**: Fitur "CAPTURE ALL" untuk mengambil semua foto sekaligus
- **Individual Retake**: Kemampuan retake foto individual dari preview grid
- **Auto Countdown**: Timer otomatis dengan visual feedback

### 🎨 Photo Customization

- **100+ Frame Colors**: Variasi warna frame yang beragam
- **Background Variations**: Background patterns dan solid colors
- **Sticker System**: Koleksi stiker yang dapat ditambahkan ke foto
- **Photo Shapes**: Rounded corners dan bentuk foto custom
- **Real-time Preview**: Preview langsung saat mengedit
- **Video Conversion**: Convert foto menjadi slideshow video 10 detik

### 💳 Payment Integration

- **Midtrans Integration**: Payment gateway terintegrasi
- **Multiple Payment Methods**:
  - QRIS (GoPay, DANA, OVO, dll)
  - Bank Transfer (BCA)
- **Session-based Payment**: Sistem pembayaran berbasis sesi

### 📱 Progressive Web App (PWA)

- **Offline Capability**: Dapat digunakan tanpa koneksi internet
- **App-like Experience**: Install sebagai aplikasi native
- **Service Worker**: Caching otomatis untuk performa optimal
- **Responsive Design**: Optimasi untuk semua ukuran layar

## 🏗️ Arsitektur Teknologi

### Frontend Stack

- **HTML5**: Semantic markup dengan PWA meta tags
- **CSS3**: Modern styling dengan Grid/Flexbox layout
- **JavaScript (ES6+)**:
  - Vanilla JavaScript untuk performa optimal
  - Canvas API untuk foto manipulation
  - MediaRecorder API untuk video conversion
  - WebRTC getUserMedia untuk camera access

### Backend Stack

- **PHP 8.0+**: Server-side processing
- **Session Management**: Custom session handler dengan state management
- **File System**: Local storage untuk temporary photos
- **Composer**: Dependency management

### Dependencies

```json
{
  "midtrans/midtrans-php": "^2.6"
}
```

### External Libraries

- **Font Awesome 6.4.0**: Icon set
- **Google Fonts**: Poppins, Roboto, Syne font families
- **Animate.css 4.1.1**: CSS animations

## 📁 Struktur Project

```
FotoboxJO/
├── index.php                    # Homepage/Landing page
├── manifest.json               # PWA manifest file
├── sw.js                      # Service worker
├── composer.json              # PHP dependencies
│
├── src/
│   ├── assets/                # Static assets
│   │   ├── icons/            # PWA icons dan logos
│   │   ├── layouts/          # Layout templates
│   │   ├── stickers/         # Sticker collections
│   │   └── frame-backgrounds/ # Frame backgrounds
│   │
│   ├── pages/                # Application pages
│   │   ├── canvasLayout[1-6].php    # Photo capture pages
│   │   ├── canvasLayout[1-6].js     # Canvas functionality
│   │   ├── customizeLayout[1-6].php # Photo editing pages
│   │   ├── customizeLayout[1-6].js  # Customization logic
│   │   ├── description.php          # User guide
│   │   ├── selectlayout.php         # Layout selection
│   │   ├── payment-*.php            # Payment pages
│   │   └── yourphotos.php          # Final result
│   │
│   ├── api-fetch/            # API endpoints
│   │   ├── create_photo_session.php
│   │   ├── save_photos.php
│   │   ├── charge_qris.php
│   │   ├── charge_bank.php
│   │   └── complete_payment.php
│   │
│   ├── includes/             # Shared components
│   │   ├── session-manager.php      # Session state management
│   │   ├── session-protection.php   # Access control
│   │   └── pwa-helper.php          # PWA utilities
│   │
│   └── user-photos/          # Uploaded photos storage
└── vendor/                   # Composer dependencies
```

## 🔄 User Flow & Session Management

### 1. Session States

```php
const STATE_INITIAL = 'initial';
const STATE_LAYOUT_SELECTED = 'layout_selected';
const STATE_PHOTO_SESSION = 'photo_session';
const STATE_CUSTOMIZATION = 'customization';
const STATE_PAYMENT_PENDING = 'payment_pending';
const STATE_PAYMENT_COMPLETED = 'payment_completed';
```

### 2. Typical User Journey

1. **Landing Page** (`index.php`) → Start session
2. **Description** (`description.php`) → Tutorial/guide
3. **Layout Selection** (`selectlayout.php`) → Choose photo layout
4. **Photo Capture** (`canvasLayout[1-6].php`) → Take photos
5. **Customization** (`customizeLayout[1-6].php`) → Edit photos
6. **Payment Selection** (`selectpayment.php`) → Choose payment method
7. **Payment Process** (`payment-*.php`) → Complete payment
8. **Final Result** (`yourphotos.php`) → Download photos

## 🎯 Layout System

### Layout 1 - Photo Strip (2 Photos)

- **Dimensions**: 2x1 grid
- **Use Case**: Classic photobooth strip
- **Photo Count**: 2 photos

### Layout 2 - Grid Layout (4 Photos)

- **Dimensions**: 2x2 grid
- **Use Case**: Social media sharing
- **Photo Count**: 4 photos

### Layout 3 - Extended Grid (6 Photos)

- **Dimensions**: 3x2 grid
- **Use Case**: Event documentation
- **Photo Count**: 6 photos

### Layout 4 - Large Grid (8 Photos)

- **Dimensions**: 4x2 grid
- **Use Case**: Comprehensive photo set
- **Photo Count**: 8 photos

### Layout 5 - Alternative 6 (6 Photos)

- **Dimensions**: 2x3 grid
- **Use Case**: Portrait orientation
- **Photo Count**: 6 photos

### Layout 6 - Square Grid (4 Photos)

- **Dimensions**: 2x2 square
- **Use Case**: Instagram-friendly format
- **Photo Count**: 4 photos

## ⚙️ Konfigurasi & Setup

### Prerequisites

- PHP 8.0 atau lebih tinggi
- Web server (Apache/Nginx)
- Composer
- SSL certificate (untuk camera access)

### Installation

```bash
# Clone repository
git clone https://github.com/AndreasTopuh/FotoboxJO.git

# Install dependencies
composer install

# Set permissions
chmod 755 src/user-photos/
chmod 755 src/user-photos-tmp/

# Configure environment
cp .env.example .env
# Edit .env dengan Midtrans credentials
```

### Environment Variables

```env
SERVER_KEY=your_midtrans_server_key
CLIENT_KEY=your_midtrans_client_key
ENVIRONMENT=sandbox
```

## 🔧 API Endpoints

### Photo Management

- `POST /src/api-fetch/create_photo_session.php` - Membuat sesi foto baru
- `POST /src/api-fetch/save_photos.php` - Menyimpan foto yang diambil
- `GET /src/api-fetch/get_photos.php` - Mengambil foto dari sesi
- `POST /src/api-fetch/save_final_photo.php` - Menyimpan foto final

### Payment System

- `POST /src/api-fetch/charge_qris.php` - Memproses pembayaran QRIS
- `POST /src/api-fetch/charge_bank.php` - Memproses transfer bank
- `POST /src/api-fetch/complete_payment.php` - Menyelesaikan pembayaran
- `GET /src/api-fetch/check_status.php` - Cek status pembayaran

### Session Management

- `GET /src/api-fetch/session_status.php` - Status sesi saat ini
- `POST /src/api-fetch/set_session.php` - Set state sesi
- `POST /src/api-fetch/reset_session.php` - Reset sesi

## 🧪 Testing & Quality Assurance

### Browser Compatibility

- ✅ Chrome 60+
- ✅ Firefox 55+
- ✅ Safari 11+
- ✅ Edge 79+
- ❌ Internet Explorer (tidak didukung)

### Device Compatibility

- ✅ Desktop (1920x1080, 1366x768)
- ✅ Tablet (768x1024, 1024x768)
- ✅ Mobile (375x667, 414x896, 360x640)

### Performance Metrics

- **First Contentful Paint**: < 2s
- **Largest Contentful Paint**: < 3s
- **Time to Interactive**: < 4s
- **PWA Score**: 95+

## 🔒 Security Features

### Session Security

- Session timeout (20 menit)
- State-based access control
- CSRF protection
- Input validation dan sanitization

### Payment Security

- Midtrans secure payment gateway
- Server-side validation
- Transaction logging
- PCI DSS compliant

## 📊 Monitoring & Debugging

### Debug Tools

- `debug.php` - Debug interface
- `debug_session.php` - Session monitoring
- `debug_logger.php` - Error logging
- `debug_monitor.php` - Performance monitoring

### Logging System

- Error logging ke file
- Session activity tracking
- Payment transaction logs
- User interaction analytics

## 🚀 Deployment

### Production Checklist

- [ ] SSL certificate installed
- [ ] Environment variables configured
- [ ] File permissions set correctly
- [ ] Midtrans production keys
- [ ] Service worker cache updated
- [ ] Performance optimization enabled

### Server Requirements

- **PHP**: 8.0+
- **Memory**: 512MB minimum
- **Storage**: 5GB minimum
- **Bandwidth**: Unlimited recommended

## 🤝 Contributing

### Development Guidelines

1. Follow PSR-12 coding standards
2. Write comprehensive tests
3. Document new features
4. Maintain backward compatibility
5. Use semantic versioning

### Branch Strategy

- `main` - Production ready code
- `develop` - Development branch
- `feature/*` - Feature branches
- `hotfix/*` - Critical fixes

## 📄 License

This project is proprietary software. All rights reserved.

## 👥 Team

- **Developer**: Andreas Topuh
- **Project Type**: Photo Booth Progressive Web Application
- **Last Updated**: August 2025

## 📞 Support

Untuk bantuan teknis atau pertanyaan, silakan hubungi:

- **Email**: support@gofotobox.online
- **Website**: https://gofotobox.online

---

**© 2025 GoFotobox. All rights reserved.**
