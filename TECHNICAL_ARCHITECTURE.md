# 🏗️ Arsitektur & Teknologi GoFotobox

## 📋 Technology Stack Overview

### Frontend Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                    Frontend Layer                           │
├─────────────────────────────────────────────────────────────┤
│ HTML5 + CSS3 + Vanilla JavaScript (ES6+)                  │
│ ├── Progressive Web App (PWA)                              │
│ ├── Service Worker (sw.js)                                 │
│ ├── Canvas API (Photo Manipulation)                        │
│ ├── WebRTC getUserMedia (Camera Access)                    │
│ ├── MediaRecorder API (Video Generation)                   │
│ └── Local Storage + Session Management                     │
└─────────────────────────────────────────────────────────────┘
```

### Backend Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                    Backend Layer                            │
├─────────────────────────────────────────────────────────────┤
│ PHP 8.0+ (Server-side Processing)                          │
│ ├── Session Management System                              │
│ ├── File System Storage                                    │
│ ├── API Endpoints (RESTful)                               │
│ ├── Payment Gateway (Midtrans)                            │
│ └── State Machine Management                               │
└─────────────────────────────────────────────────────────────┘
```

---

## 🎯 Core Technologies Deep Dive

### 1. Progressive Web App (PWA)

```json
// manifest.json configuration
{
  "name": "GoFotobox - Photo Booth Online",
  "short_name": "GoFotobox",
  "display": "fullscreen",
  "background_color": "#FFE4EA",
  "theme_color": "#E28585",
  "orientation": "portrait"
}
```

**Features Implemented:**

- ✅ App-like installation experience
- ✅ Offline functionality with Service Worker
- ✅ Fullscreen display mode
- ✅ Custom splash screen
- ✅ Native app icons (192x192, 512x512)

### 2. Camera & Media APIs

```javascript
// WebRTC Camera Implementation
navigator.mediaDevices.getUserMedia({
  video: {
    width: { ideal: 1280 },
    height: { ideal: 720 },
    facingMode: "user", // or 'environment'
  },
});

// Canvas Photo Capture
const canvas = document.createElement("canvas");
const ctx = canvas.getContext("2d");
ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
const imageData = canvas.toDataURL("image/png");
```

### 3. Photo Processing Pipeline

```
Photo Capture Flow:
┌─────────────┐    ┌─────────────┐    ┌─────────────┐    ┌─────────────┐
│   Camera    │───▶│   Canvas    │───▶│  Processing │───▶│   Storage   │
│   Stream    │    │  Capture    │    │   Filters   │    │   System    │
└─────────────┘    └─────────────┘    └─────────────┘    └─────────────┘
       │                   │                   │                   │
       │                   │                   │                   │
    WebRTC            Canvas API          JavaScript           PHP Backend
```

### 4. Session State Management

```php
// SessionManager.php - State Machine
class SessionManager {
    const STATE_INITIAL = 'initial';
    const STATE_LAYOUT_SELECTED = 'layout_selected';
    const STATE_PHOTO_SESSION = 'photo_session';
    const STATE_CUSTOMIZATION = 'customization';
    const STATE_PAYMENT_PENDING = 'payment_pending';
    const STATE_PAYMENT_COMPLETED = 'payment_completed';

    const SESSION_TIMEOUT = 1200; // 20 minutes
}
```

---

## 🏛️ Application Architecture

### MVC-like Pattern Implementation

```
┌─────────────────────────────────────────────────────────────┐
│                      View Layer                             │
│  ┌─────────────────┐  ┌─────────────────┐  ┌─────────────────┐  │
│  │   canvasLayout  │  │ customizeLayout │  │   Other Pages   │  │
│  │   [1-6].php     │  │   [1-6].php     │  │   (payment,     │  │
│  │                 │  │                 │  │   selection)    │  │
│  └─────────────────┘  └─────────────────┘  └─────────────────┘  │
└─────────────────────────────────────────────────────────────┘
                                │
┌─────────────────────────────────────────────────────────────┐
│                   Controller Layer                          │
│  ┌─────────────────┐  ┌─────────────────┐  ┌─────────────────┐  │
│  │   canvasLayout  │  │ customizeLayout │  │   API Endpoints │  │
│  │   [1-6].js      │  │   [1-6].js      │  │   (api-fetch/)  │  │
│  │                 │  │                 │  │                 │  │
│  └─────────────────┘  └─────────────────┘  └─────────────────┘  │
└─────────────────────────────────────────────────────────────┘
                                │
┌─────────────────────────────────────────────────────────────┐
│                     Model Layer                             │
│  ┌─────────────────┐  ┌─────────────────┐  ┌─────────────────┐  │
│  │ SessionManager  │  │  File System    │  │  Payment API    │  │
│  │  (includes/)    │  │ (user-photos/)  │  │   (Midtrans)    │  │
│  │                 │  │                 │  │                 │  │
│  └─────────────────┘  └─────────────────┘  └─────────────────┘  │
└─────────────────────────────────────────────────────────────┘
```

---

## 🔧 Key Components Analysis

### 1. Photo Layout System

Each layout has identical structure but different photo arrangements:

```php
// Layout Configuration Matrix
┌─────────┬───────────┬─────────────┬──────────────┐
│ Layout  │ Photo Count│ Grid Size   │ Use Case     │
├─────────┼───────────┼─────────────┼──────────────┤
│ Layout 1│     2     │    2x1      │ Photo Strip  │
│ Layout 2│     4     │    2x2      │ Social Grid  │
│ Layout 3│     6     │    3x2      │ Event Shots  │
│ Layout 4│     8     │    4x2      │ Large Set    │
│ Layout 5│     6     │    2x3      │ Portrait     │
│ Layout 6│     4     │    2x2      │ Square       │
└─────────┴───────────┴─────────────┴──────────────┘
```

### 2. Photo Capture Enhanced Features

```javascript
// Enhanced Capture System
const enhancedFeatures = {
  fullscreenMode: {
    enterFullscreen: () => document.documentElement.requestFullscreen(),
    exitFullscreen: () => document.exitFullscreen(),
    startCaptureButton: true,
  },

  batchCapture: {
    captureAll: async () => {
      for (let i = 0; i < photoCount; i++) {
        await capturePhoto();
        await countdown(2000); // 2s interval
      }
    },
  },

  individualRetake: {
    retakeSinglePhoto: (index) => {
      // Replace specific photo in array
      images[index] = newPhoto;
    },
  },

  modalCarousel: {
    indicators: true,
    clickNavigation: true,
    smoothTransitions: true,
  },
};
```

### 3. Customization Engine

```javascript
// Photo Customization Pipeline
const customizationEngine = {
    frameColors: {
        count: 100+,
        types: ['pink', 'blue', 'yellow', 'matcha', 'purple', 'brown', 'red'],
        application: 'real-time canvas rendering'
    },

    backgrounds: {
        patterns: ['matcha', 'blackstar', 'bluestripe'],
        solidColors: ['transparent', 'white', 'black'],
        implementation: 'canvas background layers'
    },

    stickers: {
        categories: ['emoji', 'shapes', 'decorative'],
        positioning: 'drag-and-drop',
        scaling: 'proportional resize'
    },

    photoShapes: {
        cornerRadius: 'configurable',
        borderEffects: 'soft-rounded',
        aspectRatio: 'maintained'
    }
};
```

### 4. Video Generation System (Layout 1)

```javascript
// Video Creation Technical Specs
const videoSpecs = {
  resolution: "800x600",
  frameRate: 30,
  duration: 10, // seconds
  format: "WebM (VP9 codec)",

  timeline: {
    photo1: { frames: [0, 74], duration: 2.5 },
    photo2: { frames: [75, 149], duration: 2.5 },
    loop: 2, // 2 complete iterations
    totalFrames: 300,
  },

  implementation: "MediaRecorder API + Canvas Animation",
};
```

---

## 💳 Payment Gateway Integration

### Midtrans Architecture

```php
// Payment Flow Implementation
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   User Action   │───▶│  PHP Backend    │───▶│   Midtrans API  │
│ (Select Payment)│    │ (charge_*.php)  │    │   (Sandbox)     │
└─────────────────┘    └─────────────────┘    └─────────────────┘
         │                       │                       │
         ▼                       ▼                       ▼
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│  Payment UI     │◀───│  Session State  │◀───│ Payment Status  │
│ (QR/Bank Info)  │    │  Management     │    │   Callback      │
└─────────────────┘    └─────────────────┘    └─────────────────┘
```

### Payment Methods Configuration

```php
// QRIS Configuration
$qrisParams = [
    'payment_type' => 'gopay',
    'transaction_details' => [
        'order_id' => 'ORDER-QRIS-' . time(),
        'gross_amount' => 25000
    ]
];

// Bank Transfer Configuration
$bankParams = [
    'payment_type' => 'bank_transfer',
    'bank_transfer' => [
        'bank' => 'bca'
    ]
];
```

---

## 🔒 Security Architecture

### Multi-Layer Security Approach

```
┌─────────────────────────────────────────────────────────────┐
│                   Security Layers                           │
├─────────────────────────────────────────────────────────────┤
│ 1. Transport Layer Security (HTTPS/SSL)                    │
│ 2. Session Management & State Validation                   │
│ 3. Input Sanitization & Validation                         │
│ 4. File Upload Security                                    │
│ 5. Payment Gateway Security (PCI DSS)                     │
│ 6. Cross-Site Request Forgery (CSRF) Protection           │
│ 7. Directory Traversal Prevention                          │
└─────────────────────────────────────────────────────────────┘
```

### Session Security Implementation

```php
// Session Protection Mechanism
class SessionProtection {
    public static function validateAccess($requiredState) {
        if (!self::hasValidSession()) {
            self::redirectToStart();
        }

        if (!self::canAccessState($requiredState)) {
            self::redirectToProperPage();
        }

        if (self::isSessionExpired()) {
            self::clearSessionAndRedirect();
        }
    }
}
```

---

## 🚀 Performance Optimizations

### Frontend Performance

```javascript
// Performance Optimization Strategies
const performanceOptimizations = {
  imageOptimization: {
    format: "WebP with PNG fallback",
    compression: "Progressive JPEG",
    lazyLoading: "Intersection Observer API",
  },

  caching: {
    serviceWorker: "Cache-first for static assets",
    browserCache: "Aggressive caching with versioning",
    localStorage: "Session data persistence",
  },

  codeOptimization: {
    bundling: "Modular JavaScript loading",
    minification: "CSS/JS compression",
    criticalCSS: "Above-fold rendering",
  },
};
```

### Backend Performance

```php
// PHP Performance Considerations
$performanceFeatures = [
    'sessionOptimization' => 'File-based session storage',
    'imageProcessing' => 'Memory-efficient canvas operations',
    'fileManagement' => 'Automated cleanup of temporary files',
    'apiOptimization' => 'Minimal database queries, session-based data'
];
```

---

## 📊 Monitoring & Analytics

### Debug System Architecture

```
Debug & Monitoring Tools:
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   debug.php     │───▶│ Error Logging   │───▶│  Performance    │
│ (Main Debug)    │    │   System        │    │   Metrics       │
└─────────────────┘    └─────────────────┘    └─────────────────┘
         │                       │                       │
         ▼                       ▼                       ▼
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│debug_session.php│    │debug_logger.php │    │debug_monitor.php│
│(Session Monitor)│    │(Error Handler)  │    │(Performance)    │
└─────────────────┘    └─────────────────┘    └─────────────────┘
```

---

## 🔄 Data Flow Architecture

### Complete User Journey Data Flow

```mermaid
graph TD
    A[Index.php] --> B[Description.php]
    B --> C[SelectLayout.php]
    C --> D[CanvasLayout[1-6].php]
    D --> E[CustomizeLayout[1-6].php]
    E --> F[SelectPayment.php]
    F --> G[Payment-*.php]
    G --> H[YourPhotos.php]

    D --> I[API: save_photos.php]
    E --> J[API: save_final_photo.php]
    G --> K[API: charge_*.php]
    H --> L[API: complete_payment.php]
```

### Session State Transitions

```
Initial State → Layout Selected → Photo Session → Customization → Payment Pending → Payment Completed
     │                │               │               │                │                   │
     ▼                ▼               ▼               ▼                ▼                   ▼
 index.php    selectlayout.php  canvasLayout*.php customizeLayout*.php payment-*.php  yourphotos.php
```

---

## 🛠️ Development & Deployment

### File Organization Strategy

```
Code Organization Principles:
├── Separation of Concerns (HTML/CSS/JS)
├── Modular JavaScript (per-layout files)
├── Consistent Naming Convention
├── API Endpoint Segregation
├── Asset Optimization
└── Version Control with Backups
```

### Deployment Architecture

```
Production Environment:
┌─────────────────────────────────────────┐
│           Load Balancer/CDN             │
│  ┌─────────────────────────────────────┐ │
│  │        Web Server (Apache/Nginx)   │ │
│  │  ┌─────────────────────────────────┐│ │
│  │  │       PHP 8.0+ Runtime         ││ │
│  │  │  ┌─────────────────────────────┐││ │
│  │  │  │    GoFotobox Application   │││ │
│  │  │  └─────────────────────────────┘││ │
│  │  └─────────────────────────────────┘│ │
│  └─────────────────────────────────────┘ │
└─────────────────────────────────────────┘
```

---

## 📈 Scalability Considerations

### Horizontal Scaling Preparation

```php
// Future Scalability Features
$scalabilityFeatures = [
    'database' => 'Ready for MySQL/PostgreSQL integration',
    'storage' => 'S3/Cloud storage compatible',
    'caching' => 'Redis/Memcached ready',
    'loadBalancing' => 'Stateless session design',
    'cdn' => 'Static asset optimization',
    'monitoring' => 'Application metrics ready'
];
```

---

**🏗️ Architecture Documentation Complete**

This technical architecture document provides comprehensive insight into the GoFotobox application's technical foundation, ready for professional review and development team onboarding.

**© 2025 GoFotobox Technical Architecture. All rights reserved.**
