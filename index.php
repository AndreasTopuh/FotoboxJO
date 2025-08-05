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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="./src/pages/home-styles.css">
    <style>
        :root {
            --pink: #ff7daf;
            /* Warna pink utama untuk gradien animasi */
            --text-pink: #f2005b;
            /* Warna pink sekunder untuk gradien animasi */
            --lighterGray: #dcdcdd;
            --background-pink: #ff6eb9ff;
            /* Warna pink baru untuk latar belakang body */
        }

        body {
            background-color: var(--background-pink);
            /* Mengganti latar belakang body ke pink baru */
            transition: background-color 0.9s ease-in-out;
        }

        .gradientBg {
            height: 60%;
            width: 60%;
            background: radial-gradient(circle, #f2005b, #ff7daf);
            border-radius: 45%;
            filter: blur(100px);
            animation: breathing 6s infinite ease-in-out;
            position: absolute;
            z-index: -1;
            transition: background-color 0.8s ease;
        }

        .gradientBgCanvas {
            height: 40%;
            width: 40%;
            background: radial-gradient(circle, #f2005b, #ff7daf);
            border-radius: 45%;
            filter: blur(100px);
            animation: breathingCanvas 6s infinite ease-in-out;
            position: absolute;
            z-index: -1;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .gradientBgCanvasHero {
            height: 100px;
            width: 100px;
            background: radial-gradient(circle, #f2005b, #ff7daf);
            border-radius: 50%;
            filter: blur(100px);
            animation: breathingCanvasHero 6s infinite ease-in-out;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1;
        }

        @keyframes breathing {
            0% {
                height: 60%;
                width: 60%;
                filter: blur(100px);
            }

            25% {
                height: 50%;
                width: 50%;
                filter: blur(30px);
            }

            50% {
                height: 40%;
                width: 40%;
                filter: blur(20px);
            }

            75% {
                height: 50%;
                width: 50%;
                filter: blur(30px);
            }

            100% {
                height: 60%;
                width: 60%;
                filter: blur(100px);
            }
        }

        @keyframes breathingCanvas {
            0% {
                height: 80%;
                width: 80%;
                filter: blur(100px);
            }

            25% {
                height: 60%;
                width: 60%;
                filter: blur(60px);
            }

            50% {
                height: 40%;
                width: 40%;
                filter: blur(50px);
            }

            75% {
                height: 60%;
                width: 60%;
                filter: blur(60px);
            }

            100% {
                height: 80%;
                width: 80%;
                filter: blur(100px);
            }
        }

        @keyframes breathingCanvasHero {
            0% {
                height: 50%;
                width: 50%;
                filter: blur(100px);
            }

            25% {
                height: 50%;
                width: 40%;
                filter: blur(60px);
            }

            50% {
                height: 30%;
                width: 30%;
                filter: blur(50px);
            }

            75% {
                height: 50%;
                width: 40%;
                filter: blur(60px);
            }

            100% {
                height: 50%;
                width: 50%;
                filter: blur(100px);
            }
        }

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
            color: white;
            margin: 0;
            font-weight: 700;
            font-size: 3.5rem;
        }

        .hero-subtitle {
            color: white;
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .start-btn-merged {
            background: #fff;
            border: 2px solid var(--pink-secondary);
            color: var(--pink-primary);
            padding: 12px 30px;
            border-radius: 999px;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 1.5rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            min-width: 430px;
            margin-top: 0.8rem;
            margin-bottom: 0.9rem;
            text-decoration: none;
        }

        .start-btn-merged:hover {
            background: var(--pink-primary);
            border: 2px solid #fff;
            color: #fff;
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 8px 20px rgba(233, 30, 99, 0.3);
        }

        .instruction-text {
            color: white;
            margin: 2rem 2rem auto;
            font-size: 0.7rem;
        }

        /* Exit Button Styles */
        .exit-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
            z-index: 1000;
            backdrop-filter: blur(10px);
        }

        .exit-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
            border-color: rgba(255, 255, 255, 0.5);
            transform: scale(1.1);
            backdrop-filter: blur(15px);
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

            .start-btn-merged {
                min-width: 300px;
                font-size: 1.3rem;
            }

            .exit-btn {
                width: 35px;
                height: 35px;
                font-size: 16px;
                top: 15px;
                right: 15px;
            }
        }
    </style>
</head>

<body>
    <div class="gradientBgCanvas blob"></div>

    <!-- Exit Button -->
    <!-- <button class="exit-btn" onclick="exitPWA()" title="Keluar dari Aplikasi">
        <i class="fas fa-times"></i>
    </button> -->

    <div class="container">
        <div class="main-content">
            <div class="title-section">
                <img src="/src/assets/icons/logo-gofotobox-new-180.png" alt="GoFotobox Logo" class="logo">
                <h1 class="hero-title">GoFotobox</h1>
            </div>
            <p class="hero-subtitle">Capture the moment, style your photo, and print it instantly.</p>
            <p class="instruction-text">Tekan untuk memulai sesi foto</p>
            <a href="./src/pages/description.php">
                <button class="start-btn-merged">Mulai</button>
            </a>
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

        // Exit PWA function
        function exitPWA() {
            if (confirm('Apakah Anda yakin ingin keluar dari aplikasi?')) {
                if (window.matchMedia('(display-mode: standalone)').matches ||
                    window.navigator.standalone === true) {
                    // Running as PWA - try to close
                    try {
                        window.close();
                    } catch (e) {
                        // If close fails, show message
                        alert('Untuk keluar dari aplikasi, gunakan tombol home atau task manager perangkat Anda.');
                    }
                } else {
                    // Running in browser - close tab
                    try {
                        window.close();
                    } catch (e) {
                        // If close fails, go to blank page
                        window.location.href = 'about:blank';
                    }
                }
            }
        }
    </script>
</body>

</html>