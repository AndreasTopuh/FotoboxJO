# 🧪 Tabel Pengujian GoFotobox - Professional Testing Guidelines

## 📋 Overview

Dokumen ini berisi panduan lengkap untuk pengujian aplikasi GoFotobox oleh tester profesional. Setiap test case dirancang untuk memvalidasi fungsionalitas, performa, dan user experience aplikasi photobooth PWA.

---

## 🎯 Test Environment Setup

### Prerequisites Testing

| Requirement    | Specification                                 | Status |
| -------------- | --------------------------------------------- | ------ |
| **Browser**    | Chrome 60+, Firefox 55+, Safari 11+, Edge 79+ | ⏳     |
| **Device**     | Desktop, Tablet, Mobile                       | ⏳     |
| **Network**    | WiFi/4G dengan kecepatan min 10 Mbps          | ⏳     |
| **Camera**     | Front/back camera functional                  | ⏳     |
| **Microphone** | Audio permission untuk video features         | ⏳     |
| **SSL**        | HTTPS connection required                     | ⏳     |

---

## 📊 Test Cases Matrix

### 1. 🏠 Landing Page & PWA Installation

| Test ID   | Test Case                  | Expected Result                                | Status | Notes                     |
| --------- | -------------------------- | ---------------------------------------------- | ------ | ------------------------- |
| **LP001** | Load homepage (index.php)  | Page loads < 3s, all elements visible          | ⏳     | Check gradient animations |
| **LP002** | PWA install prompt appears | Install button shows on supported browsers     | ⏳     | Test on Chrome/Edge       |
| **LP003** | Install PWA application    | App installs successfully, launches standalone | ⏳     | Verify manifest.json      |
| **LP004** | Offline functionality      | App works without internet after install       | ⏳     | Service worker test       |
| **LP005** | Responsive design          | Layout adapts to screen sizes (320px-1920px)   | ⏳     | Mobile-first approach     |

### 2. 📱 Session Management & Navigation

| Test ID   | Test Case                | Expected Result                               | Status | Notes                  |
| --------- | ------------------------ | --------------------------------------------- | ------ | ---------------------- |
| **SM001** | Start new session        | Session created, redirected to description    | ⏳     | Check session state    |
| **SM002** | Session timeout (20 min) | Auto-logout, session cleared                  | ⏳     | Wait 20 minutes        |
| **SM003** | Page access control      | Unauthorized access redirected to proper page | ⏳     | Test direct URL access |
| **SM004** | Browser back/forward     | Navigation handled correctly, state preserved | ⏳     | Test all pages         |
| **SM005** | Multiple tab handling    | Only one active session allowed               | ⏳     | Open multiple tabs     |

### 3. 🎨 Layout Selection & Photo Capture

| Test ID   | Test Case                     | Expected Result                 | Status | Notes            |
| --------- | ----------------------------- | ------------------------------- | ------ | ---------------- |
| **LC001** | Layout 1 selection (2 photos) | Canvas loads with 2 photo slots | ⏳     | Test grid layout |
| **LC002** | Layout 2 selection (4 photos) | Canvas loads with 4 photo slots | ⏳     | Test 2x2 grid    |
| **LC003** | Layout 3 selection (6 photos) | Canvas loads with 6 photo slots | ⏳     | Test 3x2 grid    |
| **LC004** | Layout 4 selection (8 photos) | Canvas loads with 8 photo slots | ⏳     | Test 4x2 grid    |
| **LC005** | Layout 5 selection (6 photos) | Canvas loads with 6 photo slots | ⏳     | Test 2x3 grid    |
| **LC006** | Layout 6 selection (4 photos) | Canvas loads with 4 photo slots | ⏳     | Test square grid |

### 4. 📸 Camera Functionality

| Test ID   | Test Case                  | Expected Result                      | Status | Notes                  |
| --------- | -------------------------- | ------------------------------------ | ------ | ---------------------- |
| **CF001** | Camera permission request  | Browser asks for camera permission   | ⏳     | Test permission flow   |
| **CF002** | Camera initialization      | Camera stream appears in preview     | ⏳     | Check video element    |
| **CF003** | Camera switch (front/back) | Toggle between cameras works         | ⏳     | Mobile devices only    |
| **CF004** | Fullscreen mode            | Enter/exit fullscreen correctly      | ⏳     | Test F11 and button    |
| **CF005** | Single photo capture       | Photo captured and stored correctly  | ⏳     | Check photo quality    |
| **CF006** | CAPTURE ALL functionality  | All photos captured with 2s interval | ⏳     | Test batch capture     |
| **CF007** | Individual retake          | Single photo can be retaken          | ⏳     | Test from preview grid |
| **CF008** | Retake all photos          | All photos cleared and recaptured    | ⏳     | Test full reset        |
| **CF009** | Countdown timer            | 3-2-1 countdown before capture       | ⏳     | Visual feedback test   |
| **CF010** | Grid overlay toggle        | Grid shows/hides for photo alignment | ⏳     | Composition guide      |

### 5. 🎨 Photo Customization

| Test ID   | Test Case                   | Expected Result                      | Status | Notes                        |
| --------- | --------------------------- | ------------------------------------ | ------ | ---------------------------- |
| **PC001** | Load customization page     | Photos load with editing interface   | ⏳     | Check photo array            |
| **PC002** | Frame color selection       | 100+ frame colors apply correctly    | ⏳     | Test pink, blue, yellow, etc |
| **PC003** | Background selection        | Background patterns apply to canvas  | ⏳     | Test matcha, blackstar, etc  |
| **PC004** | Sticker application         | Stickers can be added and positioned | ⏳     | Drag & drop functionality    |
| **PC005** | Photo shape modification    | Rounded corners and shapes apply     | ⏳     | Test soft corners            |
| **PC006** | Real-time preview           | Changes reflect immediately          | ⏳     | Performance test             |
| **PC007** | Undo/Reset functionality    | Changes can be reverted              | ⏳     | State management             |
| **PC008** | Video conversion (Layout 1) | 2 photos convert to 10s video        | ⏳     | Test video generation        |
| **PC009** | Download final image        | High-quality image downloads         | ⏳     | Check resolution             |
| **PC010** | Print-ready format          | 4R paper size format (6x4 inches)    | ⏳     | Print dimension test         |

### 6. 💳 Payment Integration

| Test ID   | Test Case                | Expected Result                      | Status | Notes                   |
| --------- | ------------------------ | ------------------------------------ | ------ | ----------------------- |
| **PI001** | Payment method selection | QRIS and Bank transfer options shown | ⏳     | UI display test         |
| **PI002** | QRIS payment flow        | QR code generated for GoPay/Dana/OVO | ⏳     | Midtrans integration    |
| **PI003** | Bank transfer flow       | BCA virtual account created          | ⏳     | Test account generation |
| **PI004** | Payment status check     | Real-time payment status updates     | ⏳     | Polling mechanism       |
| **PI005** | Payment timeout          | Payment expires after timeout        | ⏳     | Check expiration        |
| **PI006** | Payment success          | Redirect to final photo page         | ⏳     | Success flow            |
| **PI007** | Payment failure          | Error handling and retry option      | ⏳     | Error scenarios         |
| **PI008** | Session security         | Payment data secured properly        | ⏳     | Security audit          |

### 7. 🖼️ Final Photo Delivery

| Test ID   | Test Case              | Expected Result                        | Status | Notes              |
| --------- | ---------------------- | -------------------------------------- | ------ | ------------------ |
| **FD001** | Photo generation       | Final composite image generated        | ⏳     | Quality check      |
| **FD002** | Download functionality | Photo downloads with correct filename  | ⏳     | Test download link |
| **FD003** | Image quality          | High resolution (minimum 1200px width) | ⏳     | Resolution test    |
| **FD004** | File format            | PNG format with transparency support   | ⏳     | Format validation  |
| **FD005** | Expiration handling    | Download link expires after 24 hours   | ⏳     | Time-based test    |
| **FD006** | Error handling         | Graceful handling of missing files     | ⏳     | Edge case testing  |

### 8. 📱 Mobile Responsiveness

| Test ID   | Test Case                   | Expected Result                    | Status | Notes               |
| --------- | --------------------------- | ---------------------------------- | ------ | ------------------- |
| **MR001** | iPhone portrait (375x667)   | All elements fit and functional    | ⏳     | iOS Safari          |
| **MR002** | iPhone landscape (667x375)  | Layout adapts correctly            | ⏳     | Orientation change  |
| **MR003** | Android portrait (360x640)  | All elements fit and functional    | ⏳     | Chrome mobile       |
| **MR004** | Android landscape (640x360) | Layout adapts correctly            | ⏳     | Orientation change  |
| **MR005** | Tablet portrait (768x1024)  | Optimized for tablet view          | ⏳     | iPad test           |
| **MR006** | Tablet landscape (1024x768) | Optimized for tablet view          | ⏳     | iPad landscape      |
| **MR007** | Touch interactions          | Buttons and gestures work properly | ⏳     | Touch targets 44px+ |
| **MR008** | Camera on mobile            | Mobile camera functionality        | ⏳     | Front/back camera   |

### 9. ⚡ Performance Testing

| Test ID   | Test Case               | Expected Result                | Status | Notes                |
| --------- | ----------------------- | ------------------------------ | ------ | -------------------- |
| **PT001** | Page load speed         | First contentful paint < 2s    | ⏳     | Use Lighthouse       |
| **PT002** | Image processing        | Photo capture < 1s             | ⏳     | Performance timing   |
| **PT003** | Customization rendering | Filter/frame apply < 0.5s      | ⏳     | Canvas performance   |
| **PT004** | Memory usage            | No memory leaks during session | ⏳     | DevTools memory tab  |
| **PT005** | PWA performance score   | Lighthouse score > 90          | ⏳     | PWA audit            |
| **PT006** | Network optimization    | Minimal data usage             | ⏳     | Network throttling   |
| **PT007** | Cache effectiveness     | Repeat visits load faster      | ⏳     | Service worker cache |

### 10. 🔒 Security Testing

| Test ID   | Test Case              | Expected Result                 | Status | Notes              |
| --------- | ---------------------- | ------------------------------- | ------ | ------------------ |
| **ST001** | Session hijacking      | Session tokens properly secured | ⏳     | Security audit     |
| **ST002** | File upload validation | Only valid image files accepted | ⏳     | Upload security    |
| **ST003** | XSS prevention         | No script injection possible    | ⏳     | Input sanitization |
| **ST004** | CSRF protection        | Cross-site requests blocked     | ⏳     | Token validation   |
| **ST005** | Payment security       | Sensitive data encrypted        | ⏳     | Midtrans security  |
| **ST006** | Directory traversal    | File access properly restricted | ⏳     | Path validation    |

### 11. 🌐 Cross-Browser Compatibility

| Test ID   | Browser          | Version | Desktop | Mobile | Status | Notes             |
| --------- | ---------------- | ------- | ------- | ------ | ------ | ----------------- |
| **CB001** | Chrome           | 120+    | ✅      | ✅     | ⏳     | Primary browser   |
| **CB002** | Firefox          | 115+    | ✅      | ✅     | ⏳     | Secondary support |
| **CB003** | Safari           | 16+     | ✅      | ✅     | ⏳     | iOS/macOS         |
| **CB004** | Edge             | 120+    | ✅      | ✅     | ⏳     | Windows primary   |
| **CB005** | Samsung Internet | Latest  | ❌      | ✅     | ⏳     | Android default   |

### 12. 🚨 Error Handling & Edge Cases

| Test ID   | Test Case               | Expected Result                          | Status | Notes                  |
| --------- | ----------------------- | ---------------------------------------- | ------ | ---------------------- |
| **EH001** | Camera access denied    | Graceful error message, alternative flow | ⏳     | Permission denied      |
| **EH002** | No camera available     | Error message with instructions          | ⏳     | Desktop without camera |
| **EH003** | Network disconnection   | Offline mode or error handling           | ⏳     | Network simulation     |
| **EH004** | Payment gateway timeout | Retry mechanism or error message         | ⏳     | Timeout simulation     |
| **EH005** | Large file upload       | File size validation and error           | ⏳     | Upload limits          |
| **EH006** | Browser compatibility   | Graceful degradation for old browsers    | ⏳     | Feature detection      |
| **EH007** | Session corruption      | Clean session restart                    | ⏳     | Data corruption test   |
| **EH008** | Server error 500        | User-friendly error page                 | ⏳     | Server simulation      |

---

## 📊 Test Execution Report Template

### Test Summary

- **Total Test Cases**: 85
- **Passed**: \_\_\_
- **Failed**: \_\_\_
- **Blocked**: \_\_\_
- **Not Executed**: \_\_\_

### Critical Issues Found

| Issue ID | Severity        | Description | Impact | Status     |
| -------- | --------------- | ----------- | ------ | ---------- |
|          | High/Medium/Low |             |        | Open/Fixed |

### Performance Metrics

| Metric             | Target | Actual | Status |
| ------------------ | ------ | ------ | ------ |
| Page Load Time     | < 3s   |        |        |
| PWA Score          | > 90   |        |        |
| Mobile Performance | > 85   |        |        |

### Browser Support Matrix

| Browser | Desktop | Mobile | Critical Issues |
| ------- | ------- | ------ | --------------- |
| Chrome  | ✅/❌   | ✅/❌  |                 |
| Firefox | ✅/❌   | ✅/❌  |                 |
| Safari  | ✅/❌   | ✅/❌  |                 |
| Edge    | ✅/❌   | ✅/❌  |                 |

---

## 🔧 Testing Tools & Utilities

### Recommended Testing Tools

1. **Chrome DevTools** - Performance, Network, Application tabs
2. **Lighthouse** - PWA audit and performance metrics
3. **BrowserStack** - Cross-browser testing
4. **Postman** - API endpoint testing
5. **WAVE** - Accessibility testing
6. **GTmetrix** - Performance analysis

### Debug Endpoints

- `/debug.php` - General debugging interface
- `/debug_session.php` - Session state monitoring
- `/debug_monitor.php` - Performance monitoring
- `/src/api-fetch/session_status.php` - Current session info

### Test Data

- **Test Images**: Use images 1MB-5MB, various formats (JPG, PNG)
- **Test Payments**: Use Midtrans sandbox credentials
- **Test Devices**: Minimum 3 different screen sizes

---

## 📝 Test Execution Guidelines

### Before Testing

1. Clear browser cache and cookies
2. Ensure stable internet connection
3. Grant necessary permissions (camera, microphone)
4. Use fresh session for each test cycle

### During Testing

1. Document exact steps taken
2. Capture screenshots of any issues
3. Note browser console errors
4. Record response times for performance tests

### After Testing

1. Complete test report with all findings
2. Categorize issues by severity
3. Provide reproduction steps for bugs
4. Suggest improvements where applicable

---

## 🎯 Success Criteria

### Minimum Acceptance Criteria

- ✅ 95% of critical test cases pass
- ✅ No high-severity security issues
- ✅ PWA score above 90
- ✅ Page load time under 3 seconds
- ✅ Mobile functionality fully working
- ✅ Payment integration stable

### Recommended Improvements

- Accessibility score above 90
- Performance optimizations
- Additional browser support
- Enhanced error messages
- Advanced photo editing features

---

**📞 Support Contact**
For technical questions during testing:

- **Developer**: Andreas Topuh
- **Email**: support@gofotobox.online
- **Documentation**: See README.md for detailed technical specs

---

**© 2025 GoFotobox Testing Documentation. All rights reserved.**
