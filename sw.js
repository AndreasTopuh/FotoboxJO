const CACHE_NAME = 'gofotobox-v3.0.23';
const IS_DEVELOPMENT = self.location.hostname === 'localhost' || self.location.hostname.includes('dev');

// Essential files that should be cached
const urlsToCache = [
  '/',
  '/index.php',
  '/offline.html',
  '/manifest.json',
  '/static/css/main.css',
  '/static/css/canvas.css',
  '/static/css/customize.css',
  '/static/css/payment.css',
  '/static/css/admin.css',
  '/static/css/responsive.css',
  '/static/css/index.css',
  '/src/assets/icons/logo-gofotobox-new-192.png',
  '/src/assets/icons/logo-gofotobox-new-512.png',
  '/src/assets/icons/logo-gofotobox-new-180.png',
  '/src/assets/bca.png',
  '/src/assets/qris.png'
];

// Additional pages to cache (these will be cached dynamically)
const pagesToCache = [
  '/src/pages/selectpayment.php',
  '/src/pages/selectlayout.php',
  '/src/pages/payment-bank.php',
  '/src/pages/payment-qris.php',
  '/src/pages/thankyou.php',
  '/src/pages/admin.php',
  '/src/pages/admin-login.php'
];

// Install event - cache resources with better error handling
self.addEventListener('install', (event) => {
  console.log('[ServiceWorker] Install');
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then((cache) => {
        console.log('[ServiceWorker] Caching app shell');
        
        // Cache files one by one with error handling
        return Promise.allSettled(
          urlsToCache.map(url => {
            return cache.add(url).catch(error => {
              console.warn('[ServiceWorker] Failed to cache:', url, error);
              return null; // Continue with other files
            });
          })
        );
      })
      .then((results) => {
        const failed = results.filter(result => result.status === 'rejected');
        if (failed.length > 0) {
          console.warn('[ServiceWorker] Some files failed to cache:', failed.length);
        } else {
          console.log('[ServiceWorker] All essential files cached successfully');
        }
        return self.skipWaiting();
      })
      .catch(error => {
        console.error('[ServiceWorker] Install failed:', error);
        return self.skipWaiting(); // Still activate even if some caching fails
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
    event.respondWith(fetch(event.request).catch(() => {
      // Return offline page for navigation requests
      if (event.request.mode === 'navigate') {
        return caches.match('/offline.html');
      }
      throw error;
    }));
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

          // Cache the response
          caches.open(CACHE_NAME)
            .then((cache) => {
              cache.put(event.request, responseToCache).catch(error => {
                console.warn('[ServiceWorker] Failed to cache response:', error);
              });
            });

          return response;
        });
      })
      .catch(() => {
        // If network fails and no cache, return offline page for navigation
        if (event.request.mode === 'navigate') {
          return caches.match('/offline.html');
        }
        throw error;
      })
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
