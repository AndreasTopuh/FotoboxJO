# Session Management System - GoFotobox

## 📋 Overview

Sistem session management untuk memastikan user harus melakukan pembayaran sebelum dapat mengakses halaman fotobooth. Sistem ini menggunakan timer 5 menit untuk pembayaran dan melakukan monitoring session secara real-time.

## 🔄 Flow Diagram

```
Index.php → SelectPayment.php → Payment (QRIS/Bank) → SelectLayout.php → Canvas Pages
    ↓              ↓                      ↓                    ↓             ↓
 No Session   Start Session        5 min timer           Require Payment  Protected
              (payment)            + Monitor             + 15 min extend   + Monitor
```

## 🎯 Key Features

### 1. **Session Timer System**

- **Payment Session**: 5 menit untuk menyelesaikan pembayaran
- **Layout Selection**: 15 menit setelah pembayaran berhasil
- **Auto Redirect**: Otomatis kembali ke index.php jika session expired

### 2. **Real-time Monitoring**

- Session status check setiap 10-15 detik
- Warning notification 5 menit sebelum expired
- Auto-redirect jika session invalid

### 3. **Payment Protection**

- Halaman layout dan canvas memerlukan pembayaran yang valid
- Session validation pada setiap page load
- Anti-bypass dengan server-side validation

## 📁 File Structure

```
src/
├── includes/
│   ├── session-manager.php      # Core session management
│   ├── session-protection.php   # Page protection middleware
│   └── session-monitor.js       # Client-side monitoring
├── api-fetch/
│   ├── session_status.php       # Session status API
│   ├── complete_payment.php     # Complete payment API
│   ├── reset_session.php        # Reset session API
│   └── set_session.php          # Set session data API
└── pages/
    ├── selectpayment.php         # Start payment session
    ├── payment-qris.php          # QRIS payment with timer
    ├── payment-bank.php          # Bank payment with timer
    ├── selectlayout.php          # Protected - requires payment
    └── canvasLayout*.php         # Protected - requires payment
```

## 🔧 Implementation

### 1. **Starting Payment Session**

```php
// In selectpayment.php
require_once '../includes/session-manager.php';
SessionManager::startPaymentSession();
```

### 2. **Protecting Pages**

```php
// In any protected page (layout, canvas, etc.)
require_once '../includes/session-protection.php';
// Page is automatically protected
```

### 3. **Client-side Monitoring**

```javascript
// In canvas pages
<script src="../includes/session-monitor.js"></script>
// Monitoring automatically starts
```

### 4. **Completing Payment**

```javascript
// After successful payment
fetch("../api-fetch/complete_payment.php", {
  method: "POST",
  headers: { "Content-Type": "application/json" },
  body: JSON.stringify({ order_id: orderId }),
});
```

## 🎛️ API Endpoints

### GET `/src/api-fetch/session_status.php`

**Response:**

```json
{
  "valid": true,
  "expired": false,
  "session_type": "layout_selection",
  "time_remaining": 847,
  "payment_completed": true,
  "order_id": "ORDER-123"
}
```

### POST `/src/api-fetch/complete_payment.php`

**Request:**

```json
{
  "order_id": "ORDER-123"
}
```

### POST `/src/api-fetch/reset_session.php`

**Response:**

```json
{
  "success": true,
  "message": "Session reset successfully"
}
```

## ⏱️ Timer Configuration

### Payment Session (5 minutes)

```php
const PAYMENT_TIMEOUT = 300; // 5 menit dalam detik
```

### Layout Session (15 minutes)

```php
const LAYOUT_TIMEOUT = 900; // 15 menit dalam detik
```

## 🚨 Security Features

### 1. **Server-side Validation**

- Session expires dicheck di server, bukan client
- Timer sync antara client dan server
- Anti-manipulation dengan real-time validation

### 2. **Automatic Cleanup**

- Session otomatis destroyed jika expired
- Redirect ke halaman yang sesuai
- Clear interval timers saat page unload

### 3. **Protection Middleware**

- Semua halaman protected menggunakan session-protection.php
- Automatic redirect jika belum bayar
- No bypass possible

## 🧪 Testing

### Test Page: `/test-session.html`

- Check session status
- Test navigation flow
- Force session actions
- Debug session data

### Test Commands:

```bash
# Test session status
curl http://localhost/src/api-fetch/session_status.php

# Test reset session
curl -X POST http://localhost/src/api-fetch/reset_session.php

# Test complete payment
curl -X POST http://localhost/src/api-fetch/complete_payment.php \
  -H "Content-Type: application/json" \
  -d '{"order_id":"test-123"}'
```

## 🎨 User Experience

### Payment Flow

1. User mulai dari index.php
2. Pilih metode pembayaran (start session 5 menit)
3. Timer countdown tampil di layar
4. Session monitoring berjalan di background
5. Setelah bayar, extend session 15 menit
6. Access ke layout selection dan canvas

### Session Expiry

1. Warning notification 5 menit sebelum expired
2. Modal notification jika session habis
3. Automatic redirect ke index.php
4. Session data di-reset completely

## 🔄 Session States

```
1. NO_SESSION    → redirect to index.php
2. PAYMENT       → 5 min timer, payment required
3. LAYOUT_SELECTION → 15 min timer, payment completed
4. EXPIRED       → auto redirect to index.php
```

## 💡 Best Practices

1. **Always use SessionManager** untuk session operations
2. **Include session-protection.php** di semua halaman protected
3. **Add session-monitor.js** untuk real-time monitoring
4. **Clear intervals** saat page unload
5. **Test extensively** dengan test-session.html

## 🛠️ Troubleshooting

### Session tidak valid

- Check session_status.php response
- Verify session_expires timestamp
- Check server time vs client time

### Timer tidak sync

- Session monitoring akan sync timer dari server
- Check network connectivity
- Verify session_status.php response

### Auto-redirect tidak berfungsi

- Check JavaScript console errors
- Verify session-monitor.js loaded
- Check modal CSS z-index

---

**Created for GoFotobox Project**  
_Timer-based session management dengan payment protection_
