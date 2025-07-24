<?php
session_start();

// Reset previous sessions dan buat thank you session 1 menit
session_unset();
session_destroy();
session_start();

// Set session thank you dengan waktu expired 1 menit
$_SESSION['thankyou_start_time'] = time();
$_SESSION['thankyou_expired_time'] = time() + (1 * 60); // 1 menit
$_SESSION['session_type'] = 'thankyou';

// Hitung waktu tersisa
$timeLeft = $_SESSION['thankyou_expired_time'] - time();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>GoFotobox - Terima Kasih</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="home-styles.css" />
</head>

<body>
    <div class="gradientBgCanvas"></div>

    <!-- Timer Session -->
    <div id="session-timer" style="position: fixed; top: 20px; right: 20px; background: rgba(255, 68, 68, 0.9); color: white; padding: 10px 15px; border-radius: 8px; font-weight: bold; z-index: 1000;">
        <div>Waktu Tersisa: <span id="timer"><?php echo sprintf('%02d:%02d', floor($timeLeft / 60), $timeLeft % 60); ?></span></div>
    </div>

    <div class="container">
        <div class="glass-card">
            <div class="thank-you-content" style="text-align: center; padding: 2rem;">
                <h1 class="hero-title" style="font-size: 3rem; margin-bottom: 1rem; color: #007bff;">GoFotobox</h1>
                
                <div class="thank-you-message" style="margin: 2rem 0;">
                    <h2 style="font-size: 2rem; color: #28a745; margin-bottom: 1rem;">✨ Terima Kasih! ✨</h2>
                    <p class="hero-subtitle" style="font-size: 1.2rem; line-height: 1.6;">
                        Terima kasih sudah menggunakan GoFotobox.<br>
                        Sampai jumpa lagi!
                    </p>
                </div>

                <div class="celebration-icons" style="font-size: 2rem; margin: 2rem 0;">
                    📸 🎉 💖 🌟 📷
                </div>

                <button id="finishBtn" class="start-btn" style="font-size: 1.5rem; padding: 1rem 2rem; margin-top: 2rem;">
                    Selesai
                </button>
            </div>
        </div>
    </div>

    <script>
        // Session timer
        let timeLeft = <?php echo $timeLeft; ?>;
        let timerInterval;

        function startSessionTimer() {
            timerInterval = setInterval(() => {
                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;
                
                document.getElementById('timer').textContent = 
                    `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                
                if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                    // Auto redirect when time is up
                    finishSession();
                }
                
                timeLeft--;
            }, 1000);
        }

        function finishSession() {
            // Reset session and redirect to index
            fetch('../api-fetch/reset_session.php', {
                method: 'POST'
            })
            .then(() => {
                window.location.href = '../../index.html';
            })
            .catch(() => {
                // Fallback redirect even if reset fails
                window.location.href = '../../index.html';
            });
        }

        // Event listener for finish button
        document.getElementById('finishBtn').addEventListener('click', finishSession);

        // Start timer when page loads
        window.addEventListener('load', function() {
            startSessionTimer();
        });
    </script>

    <style>
        .thank-you-content {
            animation: fadeInUp 1s ease-out;
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

        .celebration-icons {
            animation: bounce 2s infinite;
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

        .start-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 123, 255, 0.3);
        }
    </style>
</body>

</html>
