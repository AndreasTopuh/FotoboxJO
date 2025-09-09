<?php
// Set timezone to Makassar (WITA)
date_default_timezone_set('Asia/Makassar');

session_start();

$token = $_GET['token'] ?? '';

// NEW: File-based token validation (more reliable)
function validateTokenFromFile($token)
{
    $photoDir = '/tmp/photobooth-photos';
    $metaFile = "{$photoDir}/{$token}.meta";
    $imageFile = "{$photoDir}/{$token}.png";

    // Check if metadata file exists
    if (!file_exists($metaFile)) {
        return ['valid' => false, 'reason' => 'Token metadata not found'];
    }

    // Read metadata
    $metaContent = file_get_contents($metaFile);
    $metadata = json_decode($metaContent, true);

    if (!$metadata) {
        return ['valid' => false, 'reason' => 'Invalid metadata format'];
    }

    // Check if expired
    if (time() > $metadata['expire']) {
        return ['valid' => false, 'reason' => 'Token expired', 'metadata' => $metadata];
    }

    // Check if image file exists
    if (!file_exists($imageFile)) {
        return ['valid' => false, 'reason' => 'Image file not found'];
    }

    return ['valid' => true, 'metadata' => $metadata, 'filename' => $imageFile];
}

// Check token validity
$validation = validateTokenFromFile($token);
$expired_or_invalid = !$validation['valid'];

if (!$expired_or_invalid) {
    $filename = $validation['filename'];
    $metadata = $validation['metadata'];
}

// OLD: Session-based fallback (for backward compatibility)
if ($expired_or_invalid && isset($_SESSION['photo_tokens'][$token])) {
    $photoData = $_SESSION['photo_tokens'][$token];
    if (time() <= $photoData['expire'] && file_exists($photoData['filename'])) {
        $expired_or_invalid = false;
        $filename = $photoData['filename'];
        $metadata = $photoData;
    }
}

// Clean up expired tokens (both file and session based)
function cleanupExpiredTokens()
{
    $photoDir = '/tmp/photobooth-photos';

    if (is_dir($photoDir)) {
        $files = glob("{$photoDir}/*.meta");
        foreach ($files as $metaFile) {
            $metaContent = file_get_contents($metaFile);
            $metadata = json_decode($metaContent, true);

            if ($metadata && time() > $metadata['expire']) {
                $token = $metadata['token'];
                $imageFile = "{$photoDir}/{$token}.png";

                // Delete expired files
                @unlink($metaFile);
                @unlink($imageFile);
            }
        }
    }

    // Clean session tokens too
    if (isset($_SESSION['photo_tokens'])) {
        foreach ($_SESSION['photo_tokens'] as $key => $data) {
            if (time() > $data['expire']) {
                if (file_exists($data['filename'])) {
                    @unlink($data['filename']);
                }
                unset($_SESSION['photo_tokens'][$key]);
            }
        }
    }
}

cleanupExpiredTokens();
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
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 40px;
            text-align: center;
            max-width: 500px;
            width: 100%;
        }

        .icon {
            font-size: 4rem;
            margin-bottom: 20px;
        }

        .success-icon {
            color: #4CAF50;
        }

        .error-icon {
            color: #f44336;
        }

        h1 {
            font-family: 'Syne', sans-serif;
            font-size: 2rem;
            margin-bottom: 15px;
            color: #333;
        }

        .success-title {
            color: #4CAF50;
        }

        .error-title {
            color: #f44336;
        }

        .message {
            font-size: 1.1rem;
            color: #666;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .photo-container {
            margin: 30px 0;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .photo-container img {
            width: 100%;
            height: auto;
            display: block;
        }

        .download-btn {
            background: linear-gradient(45deg, #4CAF50, #45a049);
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 25px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
        }

        .download-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.4);
        }

        .back-btn {
            background: #666;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 20px;
            font-size: 1rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 20px;
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            background: #555;
            transform: translateY(-1px);
        }

        .expire-info {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 10px;
            padding: 15px;
            margin: 20px 0;
            color: #856404;
            font-size: 0.9rem;
        }

        .debug-info {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            text-align: left;
            font-family: monospace;
            font-size: 0.8rem;
            color: #6c757d;
        }

        /* NEW: Photo Gallery Styles */
        .photo-gallery {
            margin: 30px 0;
        }

        .photo-section {
            margin-bottom: 40px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 15px;
        }

        .photo-section h3 {
            font-family: 'Syne', sans-serif;
            color: #333;
            margin-bottom: 15px;
            font-size: 1.3rem;
        }

        .final-photo img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            margin-bottom: 15px;
        }

        .raw-photos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .raw-photo-item {
            text-align: center;
        }

        .raw-photo-item img {
            width: 100%;
            aspect-ratio: 3/4;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 8px;
        }

        .photo-download-btn {
            background: #4CAF50;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 20px;
            font-size: 0.85rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all 0.3s ease;
            margin: 5px;
        }

        .photo-download-btn:hover {
            background: #45a049;
            transform: translateY(-1px);
        }

        .photo-download-btn.final {
            background: #2196F3;
            font-size: 1rem;
            padding: 12px 24px;
        }

        .photo-download-btn.final:hover {
            background: #1976D2;
        }

        .download-all-section {
            margin-top: 30px;
            padding: 20px;
            background: #e3f2fd;
            border-radius: 15px;
        }

        .qr-section {
            margin: 30px 0;
            padding: 20px;
            background: #f3e5f5;
            border-radius: 15px;
            text-align: center;
        }

        .qr-code {
            max-width: 200px;
            height: auto;
            margin: 15px 0;
            border-radius: 10px;
        }

        @media (max-width: 600px) {
            .container {
                padding: 30px 20px;
            }

            h1 {
                font-size: 1.5rem;
            }

            .message {
                font-size: 1rem;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <?php if ($expired_or_invalid): ?>
            <div class="icon error-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h1 class="error-title">Link Expired atau Tidak Valid</h1>
            <p class="message">
                Maaf, link foto ini sudah tidak berlaku atau tidak ditemukan.<br>
                Link foto hanya berlaku selama 30 menit untuk menjaga privasi Anda.
            </p>

            <?php if (isset($validation) && $validation['reason']): ?>
                <div class="debug-info">
                    <strong>Debug Info:</strong><br>
                    Reason: <?php echo htmlspecialchars($validation['reason']); ?><br>
                    Token: <?php echo htmlspecialchars($token); ?><br>
                    Current Time: <?php echo date('Y-m-d H:i:s'); ?><br>
                    Timezone: <?php echo date_default_timezone_get(); ?><br>

                    <?php if (isset($validation['metadata'])): ?>
                        <br><strong>Token Metadata:</strong><br>
                        Created: <?php echo date('Y-m-d H:i:s', $validation['metadata']['created']); ?><br>
                        Expires: <?php echo date('Y-m-d H:i:s', $validation['metadata']['expire']); ?><br>
                        Timezone: <?php echo $validation['metadata']['timezone'] ?? 'Unknown'; ?><br>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <div class="icon success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h1 class="success-title">Foto Anda Siap!</h1>
            <p class="message">
                Foto hasil photobooth Anda berhasil disimpan. Pilih foto yang ingin didownload.
            </p>

            <div class="photo-gallery">
                <!-- Final Photo Section -->
                <div class="photo-section final-photo">
                    <h3><i class="fas fa-star"></i> Foto Final (Hasil Gabungan)</h3>
                    <img src="data:image/png;base64,<?php echo base64_encode(file_get_contents($filename)); ?>" alt="Final Photo">
                    <br>
                    <a href="/src/api-fetch/get_photo.php?token=<?php echo htmlspecialchars($token); ?>&type=final"
                        download="photobooth-final-<?php echo date('Y-m-d-H-i-s'); ?>.png"
                        class="photo-download-btn final">
                        <i class="fas fa-download"></i>
                        Download Foto Final
                    </a>
                </div>

                <?php if (!empty($metadata['raw_photos'])): ?>
                    <!-- Raw Photos Section -->
                    <div class="photo-section raw-photos">
                        <h3><i class="fas fa-images"></i> Foto Mentahan (<?php echo count($metadata['raw_photos']); ?> foto)</h3>
                        <div class="raw-photos-grid">
                            <?php foreach ($metadata['raw_photos'] as $rawPhoto): ?>
                                <?php if (file_exists($rawPhoto['filename'])): ?>
                                    <div class="raw-photo-item">
                                        <img src="data:image/png;base64,<?php echo base64_encode(file_get_contents($rawPhoto['filename'])); ?>"
                                            alt="Raw Photo <?php echo $rawPhoto['index'] + 1; ?>">
                                        <br>
                                        <a href="/src/api-fetch/get_photo.php?token=<?php echo htmlspecialchars($token); ?>&type=raw&index=<?php echo $rawPhoto['index']; ?>"
                                            download="photobooth-raw-<?php echo $rawPhoto['index'] + 1; ?>-<?php echo date('Y-m-d-H-i-s'); ?>.png"
                                            class="photo-download-btn">
                                            <i class="fas fa-download"></i>
                                            Foto <?php echo $rawPhoto['index'] + 1; ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- QR Code Section -->
                <?php if (!empty($metadata['qr_code']) && file_exists($metadata['qr_code']['filename'])): ?>
                    <div class="qr-section">
                        <h3><i class="fas fa-qrcode"></i> QR Code untuk Link Ini</h3>
                        <p>Scan QR code ini untuk membuka link foto di perangkat lain</p>
                        <br>
                        <img src="data:image/png;base64,<?php echo base64_encode(file_get_contents($metadata['qr_code']['filename'])); ?>"
                            alt="QR Code" class="qr-code">
                        <br>
                        <small style="color: #666; font-size: 0.8rem;">
                            Link: <?php echo htmlspecialchars($metadata['photo_url']); ?>
                        </small>
                    </div>
                <?php endif; ?>

                <!-- Download All Section -->
                <div class="download-all-section">
                    <h3><i class="fas fa-cloud-download-alt"></i> Download Semua</h3>
                    <p>Ingin download semua foto sekaligus? Klik tombol di bawah ini:</p>
                    <br>
                    <button onclick="downloadAllPhotos()" class="photo-download-btn final">
                        <i class="fas fa-download"></i>
                        Download Semua Foto
                    </button>
                </div>
            </div>

            <div class="expire-info">
                <i class="fas fa-clock"></i>
                Link ini akan expired pada: <strong><?php echo date('Y-m-d H:i:s', $metadata['expire']); ?></strong> (Waktu Makassar)
                <br><small>Layout: <?php echo ucfirst($metadata['layout_type'] ?? 'Unknown'); ?></small>
            </div>
        <?php endif; ?>

        <!-- <a href="/" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Kembali ke Photobooth
        </a> -->
    </div>

    <script>
        function downloadAllPhotos() {
            // Get all download links (both data URI and API endpoints)
            const downloadLinks = document.querySelectorAll('.photo-download-btn[href]');

            if (downloadLinks.length === 0) {
                alert('Tidak ada foto yang tersedia untuk didownload.');
                return;
            }

            // Show loading message
            const button = event.target;
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Downloading...';
            button.disabled = true;

            // Download each photo with delay to prevent browser blocking
            let downloadCount = 0;
            const totalPhotos = downloadLinks.length;

            downloadLinks.forEach((link, index) => {
                setTimeout(() => {
                    // Create temporary link and click it
                    const tempLink = document.createElement('a');
                    tempLink.href = link.href;
                    tempLink.download = link.download || `photo-${index + 1}.png`;
                    tempLink.style.display = 'none';
                    document.body.appendChild(tempLink);
                    tempLink.click();
                    document.body.removeChild(tempLink);

                    downloadCount++;

                    // Update button text
                    button.innerHTML = `<i class="fas fa-download"></i> Downloading ${downloadCount}/${totalPhotos}...`;

                    // Reset button when all downloads are triggered
                    if (downloadCount === totalPhotos) {
                        setTimeout(() => {
                            button.innerHTML = originalText;
                            button.disabled = false;
                            alert(`${totalPhotos} foto berhasil didownload!`);
                        }, 1000);
                    }
                }, index * 500); // 500ms delay between downloads
            });
        }

        // Auto-refresh page when token is about to expire (5 minutes before)
        <?php if (!$expired_or_invalid && isset($metadata['expire'])): ?>
            const expireTime = <?php echo $metadata['expire']; ?> * 1000; // Convert to milliseconds
            const currentTime = <?php echo time(); ?> * 1000;
            const timeUntilExpire = expireTime - currentTime;
            const refreshTime = timeUntilExpire - (5 * 60 * 1000); // 5 minutes before expire

            if (refreshTime > 0) {
                setTimeout(() => {
                    if (confirm('Link akan expired dalam 5 menit. Refresh halaman untuk memperpanjang?')) {
                        location.reload();
                    }
                }, refreshTime);
            }
        <?php endif; ?>
    </script>
</body>

</html>