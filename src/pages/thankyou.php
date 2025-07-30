<?php
// Include PWA helper
require_once '../includes/pwa-helper.php';

// Tidak menggunakan session timer di halaman thank you
// Halaman ini mengabaikan semua session timer
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
                    <img src="/src/assets/icons/logo-gofotobox-new-180.png" alt="GoFotobox Logo" style="width: 80px; height: 80px; border-radius: 12px; object-fit: cover;">
                </div>
                
                <h1 class="hero-title" style="text-align: center; margin-bottom: 1rem;">GoFotobox</h1>
                
                <div class="thank-you-message" style="text-align: center; margin-bottom: 2rem;">
                    <div style="font-size: 4rem; margin-bottom: 1rem;">✨</div>
                    <h2 class="thank-you-title" style="color: #E28585; font-size: 2rem; margin-bottom: 1rem;">Terima Kasih!</h2>
                    <p class="hero-subtitle" style="font-size: 1.1rem; line-height: 1.6;">
                        Foto Anda telah berhasil diproses dan dicetak.<br>
                        Silakan ambil hasil cetakan Anda.<br><br>
                        Terima kasih sudah menggunakan GoFotobox!
                    </p>
                </div>
                
                <div style="text-align: center;">
                    <button id="finishBtn" class="start-btn" style="
                        background: linear-gradient(135deg, #E28585, #FF6B9D);
                        color: white;
                        border: none;
                        border-radius: 25px;
                        padding: 15px 40px;
                        font-weight: 600;
                        font-size: 1.1rem;
                        cursor: pointer;
                        transition: all 0.3s ease;
                        min-width: 200px;
                    ">
                        Selesai
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .thank-you-content {
            text-align: center;
            padding: 2rem;
        }
        
        .start-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(226, 133, 133, 0.4);
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 0;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>

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

    <script>
        // Reset session and redirect to index
        async function resetSessionAndRedirect() {
            try {
                // Reset session on server
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

            // Redirect to index
            window.location.href = '/';
        }

        // Add event listener to finish button
        document.getElementById('finishBtn').addEventListener('click', resetSessionAndRedirect);
    </script>
    
    <?php PWAHelper::addPWAScript(); ?>
</body>

</html>
