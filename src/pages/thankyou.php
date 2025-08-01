<?php
// Include PWA helper
require_once '../includes/pwa-helper.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php PWAHelper::addPWAHeaders(); ?>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>GoBooth - Terima Kasih</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="home-styles.css" />
</head>
<body>
    <div class="gradientBgCanvas"></div>
    <div class="container">
        <div class="glass-card">
            <div class="thank-you-content">
                <div style="text-align: center; margin-bottom: 2rem;">
                    <img src="/src/assets/icons/logo-gofotobox-new-180.png" alt="GoFotobox Logo" style="width: 90px; height: 90px; border-radius: 12px; object-fit: cover;">
                </div>
                <h1 class="hero-title">GoFotobox</h1>
                <div class="thank-you-message">
                    <div style="font-size: 4rem; margin-bottom: 1rem;">✨</div>
                    <h2 class="thank-you-title">Terima Kasih!</h2>
                    <p class="hero-subtitle">
                        Sampai jumpa di momen seru berikutnya!
                    </p>
                </div>
                <div style="text-align: center;">
                    <button id="finishBtn" class="start-btn">
                        Selesai
                    </button>
                </div>
            </div>
        </div>
    </div>
    <style>
        :root {
            --pink-primary: #E91E63;
            --pink-secondary: rgba(233, 30, 99, 0.1);
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            /* background: linear-gradient(90deg, #f598a8, #f6edb2); */
            min-height: 100vh;
            overflow-x: hidden;
        }

        .gradientBgCanvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, #f598a8, #f6edb2);
            z-index: -1;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem 1rem;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 25px;
            padding: 3rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--pink-secondary);
            width: 100%;
        }

        .thank-you-content {
            text-align: center;
            padding: 2rem;
            animation: fadeInUp 1s ease-out;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            color: var(--pink-primary);
            margin-bottom: 1.5rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .thank-you-message {
            margin: 2rem 0;
        }

        .thank-you-title {
            font-size: 2.2rem;
            font-weight: 600;
            color: var(--pink-primary);
            margin-bottom: 1rem;
        }

        .hero-subtitle {
            font-size: 1.3rem;
            font-weight: 400;
            color: #333;
            line-height: 1.6;
        }

        .start-btn {
            background: linear-gradient(135deg, var(--pink-primary), #FF6B9D);
            color: white;
            border: none;
            border-radius: 25px;
            padding: 18px 50px;
            font-weight: 600;
            font-size: 1.2rem;
            cursor: pointer;
            transition: all 0.3s ease;
            min-width: 220px;
        }

        .start-btn:hover {
            transform: translateY(-2px);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem 0.5rem;
            }

            .glass-card {
                padding: 2rem;
            }

            .hero-title {
                font-size: 2.8rem;
                margin-bottom: 1rem;
            }

            .thank-you-title {
                font-size: 1.8rem;
            }

            .hero-subtitle {
                font-size: 1.1rem;
            }

            .start-btn {
                padding: 15px 40px;
                font-size: 1.1rem;
                min-width: 200px;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 1rem 0.5rem;
            }

            .glass-card {
                padding: 1.5rem;
            }

            .hero-title {
                font-size: 2.2rem;
                margin-bottom: 0.8rem;
            }

            .thank-you-title {
                font-size: 1.6rem;
            }

            .hero-subtitle {
                font-size: 1rem;
            }

            .start-btn {
                padding: 12px 30px;
                font-size: 1rem;
                min-width: 180px;
            }
        }
    </style>
    <script>
        async function resetSessionAndRedirect() {
            try {
                await fetch('../api-fetch/reset_session.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ action: 'reset' })
                });
            } catch (error) {
                console.error('Error resetting session:', error);
            }
            window.location.href = '/';
        }

        document.getElementById('finishBtn').addEventListener('click', resetSessionAndRedirect);
    </script>
    <?php PWAHelper::addPWAScript(); ?>
</body>
</html>