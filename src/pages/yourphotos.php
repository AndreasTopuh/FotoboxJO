<?php
// Set timezone to Makassar (WITA)
date_default_timezone_set('Asia/Makassar');

session_start();

$token = $_GET['token'] ?? '';
$photoData = $_SESSION['photo_tokens'][$token] ?? null;

// Check if token is valid and not expired
if (!$token || !$photoData || time() > $photoData['expire']) {
    $expired_or_invalid = true;
} else {
    $expired_or_invalid = false;
    $filename = $photoData['filename'];
    
    // Check if file actually exists
    if (!file_exists($filename)) {
        $expired_or_invalid = true;
    }
}

// Clean up expired tokens
if (isset($_SESSION['photo_tokens'])) {
    foreach ($_SESSION['photo_tokens'] as $key => $data) {
        if (time() > $data['expire']) {
            // Delete expired file
            if (file_exists($data['filename'])) {
                unlink($data['filename']);
            }
            unset($_SESSION['photo_tokens'][$key]);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Photo - Photobooth</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Syne:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            padding: 3rem;
            text-align: center;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .error-container {
            color: #e74c3c;
        }

        .success-container {
            color: #27ae60;
        }

        h1 {
            font-family: 'Syne', sans-serif;
            font-size: 2rem;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .photo-container {
            margin: 2rem 0;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .photo-container img {
            width: 100%;
            height: auto;
            display: block;
        }

        .download-btn {
            display: inline-block;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            text-decoration: none;
            padding: 15px 30px;
            border-radius: 50px;
            font-weight: bold;
            font-size: 1.1rem;
            margin-top: 1.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        }

        .download-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
        }

        .info-text {
            margin-top: 1rem;
            color: #666;
            font-size: 0.9rem;
        }

        .timer-info {
            background: rgba(255, 193, 7, 0.1);
            border: 1px solid #ffc107;
            border-radius: 10px;
            padding: 15px;
            margin-top: 1.5rem;
            color: #856404;
        }

        .error-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        .success-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        @media (max-width: 768px) {
            .container {
                padding: 2rem 1.5rem;
            }
            
            h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($expired_or_invalid): ?>
            <div class="error-container">
                <div class="error-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h1>Link Expired atau Tidak Valid</h1>
                <p>Maaf, link foto ini sudah tidak berlaku atau tidak ditemukan.</p>
                <p class="info-text">Link foto hanya berlaku selama 30 menit untuk menjaga privasi Anda.</p>
            </div>
        <?php else: ?>
            <div class="success-container">
                <div class="success-icon">
                    <i class="fas fa-camera"></i>
                </div>
                <h1>Foto Anda Siap!</h1>
                <p>Berikut adalah foto hasil customize Anda:</p>
                
                <div class="photo-container">
                    <img src="<?php echo htmlspecialchars(str_replace('/tmp/photobooth-photos', '/src/user-photos-tmp', $filename)); ?>" alt="Your Custom Photo">
                </div>
                
                <a href="<?php echo htmlspecialchars(str_replace('/tmp/photobooth-photos', '/src/user-photos-tmp', $filename)); ?>" download="photobooth-custom.png" class="download-btn">
                    <i class="fas fa-download"></i> Download Foto
                </a>
                
                <div class="timer-info">
                    <i class="fas fa-clock"></i>
                    <strong>Penting:</strong> Link ini akan expired dalam 30 menit untuk menjaga privasi Anda.
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
