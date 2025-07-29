<?php
// Include PWA helper
require_once '../includes/pwa-helper.php';

// Set timer untuk auto redirect (1 menit)
$timeLeft = 60; // 1 menit dalam detik
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

    <!-- Timer Session -->
    <div id="session-timer">
        <div>Waktu Tersisa: <span id="timer"><?php echo sprintf('%02d:%02d', floor($timeLeft / 60), $timeLeft % 60); ?></span></div>
    </div>

    <div class="container">
        <div class="glass-card">
            <div class="thank-you-content">
                <h1 class="hero-title">GoFotobox</h1>
                
                <div class="thank-you-message">
                    <h2 class="thank-you-title">✨ Terima Kasih! ✨</h2>
                    <p class="hero-subtitle">
                        Terima kasih sudah menggunakan GoBooth.<br>
                        Sampai jumpa lagi!
                    </p>
                </div>
                <button id="finishBtn" class="start-btn">
                    Selesai
                </button>
            </div>
        </div>
    </div>

    <script>
        // Session timer
        let timeLeft = <?php echo $timeLeft; ?>;

        function startSessionTimer() {
            const timerInterval = setInterval(() => {
                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;
                
                document.getElementById('timer').textContent = 
                    `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                
                if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                    finishSession();
                }
                
                timeLeft--;
            }, 1000);
        }

        function finishSession() {
            // Reset session and redirect to index
            fetch('../api-fetch/reset_session.php', { method: 'POST' })
                .finally(() => window.location.href = '/');
        }

        // Event listeners
        document.getElementById('finishBtn').onclick = finishSession;
        window.onload = startSessionTimer;
    </script>

    <style>
        /* Timer styles */
        #session-timer {
            position: fixed;
            top: 20px;
            right: 20px;
            background: rgba(255, 68, 68, 0.9);
            color: white;
            padding: 10px 15px;
            border-radius: 8px;
            font-weight: bold;
            z-index: 1000;
        }

        /* Thank you content styles */
        .thank-you-content {
            text-align: center;
            padding: 2rem;
            animation: fadeInUp 1s ease-out;
        }

        .hero-title {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #007bff;
        }

        .thank-you-message {
            margin: 2rem 0;
        }

        .thank-you-title {
            font-size: 2rem;
            color: #28a745;
            margin-bottom: 1rem;
        }

        .hero-subtitle {
            font-size: 1.2rem;
            line-height: 1.6;
        }

        .celebration-icons {
            font-size: 2rem;
            margin: 2rem 0;
            animation: bounce 2s infinite;
        }

        .start-btn {
            font-size: 1.5rem;
            padding: 1rem 2rem;
            margin-top: 2rem;
        }

        .start-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 123, 255, 0.3);
        }

        /* Animations */
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

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-10px);
            }
            60% {
                transform: translateY(-5px);
            }
        }
    </style>
    
    <?php PWAHelper::addPWAScript(); ?>
</body>

</html>
