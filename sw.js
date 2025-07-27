const CACHE_NAME = 'gofotobox-v11';
const IS_DEVELOPMENT = self.location.hostname === 'localhost' || self.location.hostname.includes('dev');
const urlsToCache = [
  'https://gofotobox.online/',
  'https://gofotobox.online/index.html',
  'https://gofotobox.online/offline.html',
  'https://gofotobox.online/src/pages/home-styles.css',
  'https://gofotobox.online/src/pages/selectpayment.php',
  'https://gofotobox.online/src/pages/selectlayout.php',
  'https://gofotobox.online/src/pages/payment-bank.php',
  'https://gofotobox.online/src/pages/payment-qris.php',
  // 'https://gofotobox.online/src/pages/canvas.php',
  'https://gofotobox.online/src/pages/canvasLayout1.php',
  'https://gofotobox.online/src/pages/canvasLayout2.php',
  'https://gofotobox.online/src/pages/canvasLayout3.php',
  'https://gofotobox.online/src/pages/canvasLayout4.php',
  'https://gofotobox.online/src/pages/canvasLayout5.php',
  'https://gofotobox.online/src/pages/canvasLayout6.php',
  // 'https://gofotobox.online/src/pages/customize.php',
  'https://gofotobox.online/src/pages/customizeLayout1.php',
  'https://gofotobox.online/src/pages/customizeLayout2.php',
  'https://gofotobox.online/src/pages/customizeLayout3.php',
  'https://gofotobox.online/src/pages/customizeLayout4.php',
  'https://gofotobox.online/src/pages/customizeLayout5.php',
  'https://gofotobox.online/src/pages/customizeLayout6.php',
  'https://gofotobox.online/src/pages/thankyou.php',
  'https://gofotobox.online/src/assets/icons/logo-gofotobox-new-192.png',
  'https://gofotobox.online/src/assets/icons/logo-gofotobox-new-512.png',
  'https://gofotobox.online/src/assets/icons/logo-gofotobox-new-180.png',
  'https://gofotobox.online/src/assets/bca.png',
  'https://gofotobox.online/src/assets/qris.png',
  'https://gofotobox.online/manifest.json',
  'https://gofotobox.online/styles.css'
];

// Install event - cache resources
self.addEventListener('install', (event) => {
  console.log('[ServiceWorker] Install');
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then((cache) => {
        console.log('[ServiceWorker] Caching app shell');
        return cache.addAll(urlsToCache);
      })
      .then(() => {
        return self.skipWaiting();
      })
  );
});

// Activate event - clean up old caches
self.addEventListener('activate', (event) => {
  console.log('[ServiceWorker] Activate');
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cacheName) => {
          if (cacheName !== CACHE_NAME) {
            console.log('[ServiceWorker] Removing old cache', cacheName);
            return caches.delete(cacheName);
          }
        })
      );
    }).then(() => {
      return self.clients.claim();
    })
  );
});

// Fetch event - serve cached content when offline
self.addEventListener('fetch', (event) => {
  // Skip cross-origin requests and payment-related requests
  if (!event.request.url.startsWith(self.location.origin)) {
    return;
  }

  // Don't cache payment-related requests and PHP sessions
  const paymentUrls = [
    'midtrans.com',
    'charge_bank.php',
    'charge_qris.php',
    'check_status.php',
    'payment_notification.php',
    'api.midtrans.com',
    'PHPSESSID'
  ];
  
  // Always bypass cache for API endpoints, non-GET requests, and development canvas files
  const shouldSkipCache = [
    '/src/api-fetch/',
    event.request.method !== 'GET',
    IS_DEVELOPMENT && event.request.url.includes('canvas'),
    IS_DEVELOPMENT && event.request.url.includes('customize'),
    ...paymentUrls.map(url => event.request.url.includes(url))
  ].some(condition => condition);
  
  if (shouldSkipCache) {
    console.log('[ServiceWorker] Bypassing cache for:', event.request.url);
    event.respondWith(fetch(event.request));
    return;
  }

  event.respondWith(
    caches.match(event.request)
      .then((response) => {
        // Return cached version or fetch from network
        if (response) {
          console.log('[ServiceWorker] Found in cache:', event.request.url);
          return response;
        }
        
        console.log('[ServiceWorker] Fetching from network:', event.request.url);
        return fetch(event.request).then((response) => {
          // Don't cache if not a valid response
          if (!response || response.status !== 200 || response.type !== 'basic') {
            return response;
          }

          // Clone the response
          const responseToCache = response.clone();

          caches.open(CACHE_NAME)
            .then((cache) => {
              cache.put(event.request, responseToCache);
            });

          return response;
        });
      }
    )
  );
});

// Handle background sync for failed requests
self.addEventListener('sync', (event) => {
  if (event.tag === 'background-sync') {
    console.log('[ServiceWorker] Background sync');
    event.waitUntil(doBackgroundSync());
  }
});

function doBackgroundSync() {
  // Handle any failed requests that need to be retried
  return Promise.resolve();
}

// Handle push notifications (optional for future use)
self.addEventListener('push', (event) => {
  console.log('[ServiceWorker] Push received');
  
  const options = {
    body: event.data ? event.data.text() : 'New notification from GoFotobox',
    icon: '/src/assets/icons/photobooth-new-logo.png',
    badge: '/src/assets/icons/photobooth-new-logo.png',
    vibrate: [100, 50, 100],
    data: {
      dateOfArrival: Date.now(),
      primaryKey: 1
    }
  };

  event.waitUntil(
    self.registration.showNotification('GoFotobox', options)
  );
});
