// Enhanced Force Service Worker Update Script for Production
// Tambahkan script ini ke index.html production

(function() {
    'use strict';
    
    console.log('[PWA] Enhanced PWA Update System v6-modern');
    
    // Force update service worker
    if ('serviceWorker' in navigator) {
        console.log('[PWA] Checking for service worker updates...');
        
        // Clear old caches first
        if ('caches' in window) {
            caches.keys().then(cacheNames => {
                cacheNames.forEach(cacheName => {
                    if (cacheName.includes('gofotobox-v') && !cacheName.includes('v6-modern')) {
                        caches.delete(cacheName);
                        console.log('[PWA] Deleted old cache:', cacheName);
                    }
                });
            });
        }
        
        // Get existing registrations and update them
        navigator.serviceWorker.getRegistrations().then(function(registrations) {
            for(let registration of registrations) {
                console.log('[PWA] Updating service worker registration');
                registration.update();
            }
        });
        
        // Register new service worker
        navigator.serviceWorker.register('/sw.js')
            .then(function(registration) {
                console.log('[PWA] Service Worker registered successfully:', registration.scope);
                
                // Check for updates immediately
                registration.addEventListener('updatefound', function() {
                    console.log('[PWA] New service worker version found!');
                    const newWorker = registration.installing;
                    
                    newWorker.addEventListener('statechange', function() {
                        if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                            console.log('[PWA] New service worker installed, will refresh on next visit');
                            
                            // Show update notification to user
                            if (document.getElementById('pwa-update-banner')) {
                                document.getElementById('pwa-update-banner').style.display = 'block';
                            }
                        }
                    });
                });
                
                // Auto-update check every 30 seconds
                setInterval(() => {
                    registration.update();
                }, 30000);
                
            })
            .catch(function(error) {
                console.log('[PWA] Service Worker registration failed:', error);
            });
            
        // Listen for controlling service worker change
        navigator.serviceWorker.addEventListener('controllerchange', function() {
            console.log('[PWA] Service worker controller changed, reloading...');
            window.location.reload();
        });
    }
    
    // Check if running in PWA mode
    const isStandalone = window.matchMedia('(display-mode: standalone)').matches || 
                        window.navigator.standalone === true;
    
    if (isStandalone) {
        console.log('[PWA] Running in standalone mode');
        document.body.classList.add('pwa-mode');
    } else {
        console.log('[PWA] Running in browser mode');
    }
    
    // Performance monitoring
    window.addEventListener('load', function() {
        console.log('[PWA] Page loaded in:', performance.now().toFixed(2), 'ms');
    });
    
})();
