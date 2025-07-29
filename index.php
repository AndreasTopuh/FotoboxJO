<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>GoFotobox</title>

    <!-- PWA Manifest -->
    <link rel="manifest" href="/manifest.json">

    <!-- Theme colors -->
    <meta name="theme-color" content="#E28585">
    <meta name="background-color" content="#FFE4EA">

    <!-- PWA meta tags -->
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-status-bar-style" content="default">
    <meta name="mobile-web-app-title" content="GoFotobox">

    <!-- Apple specific -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="GoFotobox">
    <link rel="apple-touch-icon" href="/src/assets/icons/logo-gofotobox-new-180.png">

    <!-- Windows specific -->
    <meta name="msapplication-TileColor" content="#E28585">
    <meta name="msapplication-TileImage" content="/src/assets/icons/logo-gofotobox-new-192.png">

    <!-- SEO -->
    <meta name="description" content="Capture the moment, style your photo, and print it instantly with GoFotobox">
    <meta name="keywords" content="photobooth, photo, print, instant, camera">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="./src/pages/home-styles.css">
    <style>
        .container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 2rem;
        }

        .main-content {
            text-align: center;
            max-width: 600px;
            width: 100%;
        }

        .title-section {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        .logo {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            object-fit: cover;
        }

        .hero-title {
            color: #333;
            margin: 0;
            font-weight: 700;
            font-size: 3.5rem;
        }

        .hero-subtitle {
            color: #666;
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .start-btn {
            background: #FFFFFF;
            color: #333;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            margin-top: 3rem;
            margin-bottom: 0.9rem;
            min-width: 430px;
        }

        .start-btn:hover {
            background: #e9e9e9e9;
            transform: translateY(-2px);
        }

        .instruction-text {
            color: #666;
            font-size: 0.7rem;
            margin: 0;
        }

        @media (max-width: 768px) {
            .title-section {
                flex-direction: column;
                gap: 10px;
            }
            
            .hero-title {
                font-size: 2.5rem;
            }
            
            .logo {
                width: 50px;
                height: 50px;
            }
        }
    </style>
</head>

<body>
    <div class="gradientBgCanvas"></div>

    <div class="container">
        <div class="main-content">
            <div class="title-section">
                <img src="/src/assets/icons/logo-gofotobox-new-180.png" alt="GoFotobox Logo" class="logo">
                <h1 class="hero-title">GoFotobox</h1>
            </div>
            <p class="hero-subtitle">Capture the moment, style your photo, and print it instantly.</p>
            <a href="./src/pages/selectpayment.php">
                <button class="start-btn">Mulai</button>
            </a>
            <p class="instruction-text">Tekan untuk memulai sesi foto</p>
        </div>
    </div>
    <!-- PWA Installation Script -->
    <script>
        // Register service worker
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js')
                .then(reg => console.log('SW registered'))
                .catch(err => console.log('SW registration failed:', err));
        }

        // PWA install functionality
        let deferredPrompt;

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            showInstallButton();
        });

        window.addEventListener('appinstalled', () => {
            console.log('GoFotobox installed');
            hideInstallButton();
        });

        function showInstallButton() {
            if (document.getElementById('installBtn')) return;

            const installBtn = document.createElement('button');
            installBtn.id = 'installBtn';
            installBtn.innerHTML = '📱 Install App';
            installBtn.style.cssText = `
                position: fixed; top: 20px; right: 20px;
                background: linear-gradient(135deg, #E28585, #FF6B9D);
                color: white; border: none; padding: 10px 20px;
                border-radius: 25px; font-weight: 600; cursor: pointer;
                z-index: 1000; box-shadow: 0 4px 15px rgba(226, 133, 133, 0.3);
                transition: all 0.3s ease;
            `;
            installBtn.onclick = installApp;
            document.body.appendChild(installBtn);
        }

        function hideInstallButton() {
            const installBtn = document.getElementById('installBtn');
            if (installBtn) installBtn.remove();
        }

        function installApp() {
            if (!deferredPrompt) return;
            deferredPrompt.prompt();
            deferredPrompt.userChoice.then(() => deferredPrompt = null);
        }

        // Online/offline status
        function updateOnlineStatus() {
            console.log('App is', navigator.onLine ? 'online' : 'offline');
        }

        window.addEventListener('online', updateOnlineStatus);
        window.addEventListener('offline', updateOnlineStatus);

        // Initialize
        updateOnlineStatus();
        if (window.matchMedia('(display-mode: standalone)').matches) {
            console.log('App running in standalone mode');
        }
    </script>
</body>

</html>