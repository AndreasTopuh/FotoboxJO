<?php

/**
 * Improved Email Sending with Better Validation
 * This is an example of how to improve the current send_email.php
 */

session_start();
require_once __DIR__ . '/../includes/email-validator.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($input['email']) || !isset($input['customized_image'])) {
        echo json_encode(['error' => 'Email dan gambar hasil customize diperlukan']);
        exit;
    }

    $email = $input['email'];
    $customizedImage = $input['customized_image'];

    // Validate email using our EmailValidator class
    $validationResult = EmailValidator::validateComprehensive($email);
    if (!$validationResult['valid']) {
        echo json_encode([
            'error' => 'Email tidak valid',
            'details' => $validationResult['error']
        ]);
        exit;
    }

    // Sanitize email
    $email = EmailValidator::sanitize($email);

    // Additional domain check (optional, can be slow)
    /*
    if (!EmailValidator::validateDomain($email)) {
        echo json_encode(['error' => 'Domain email tidak ditemukan']);
        exit;
    }
    */

    // Get original photos from session
    $originalPhotos = isset($_SESSION['captured_photos']) ? $_SESSION['captured_photos'] : [];

    // Get layout info
    $selectedLayout = isset($_SESSION['selected_layout']) ? $_SESSION['selected_layout'] : 'canvas';

    try {
        // Create PHPMailer instance
        $mail = new PHPMailer(true);

        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; // Set your SMTP server
        $mail->SMTPAuth   = true;
        $mail->Username   = 'your-email@gmail.com'; // Your email
        $mail->Password   = 'your-app-password'; // Your app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('noreply@gofotobox.com', 'GoFotobox');
        $mail->addAddress($email);
        $mail->addReplyTo('noreply@gofotobox.com', 'GoFotobox');

        // Content
        $mail->isHTML(true);
        $mail->Subject = "GoFotobox - Foto Hasil Photobooth Anda";

        $photosCount = count($originalPhotos);
        $layoutText = getLayoutDescription($selectedLayout);

        $mail->Body = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px; }
                .container { max-width: 600px; margin: 0 auto; background-color: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
                .header { text-align: center; color: #333; margin-bottom: 20px; }
                .content { color: #666; line-height: 1.6; }
                .footer { margin-top: 30px; text-align: center; color: #999; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>GoFotobox</h1>
                    <h2>Foto Hasil Photobooth Anda</h2>
                </div>
                <div class='content'>
                    <p>Terima kasih telah menggunakan GoFotobox!</p>
                    <p>Layout yang dipilih: <strong>{$layoutText}</strong></p>
                    <p>Jumlah foto individual: <strong>{$photosCount} foto</strong></p>
                    <p>Terlampir adalah foto-foto hasil photobooth Anda:</p>
                    <ul>
                        <li>{$photosCount} foto individual yang diambil</li>
                        <li>1 foto hasil customize/edit</li>
                    </ul>
                    <p>Sampai jumpa lagi di GoFotobox!</p>
                </div>
                <div class='footer'>
                    <p>© 2025 GoFotobox. Capture the moment, style your photo.</p>
                </div>
            </div>
        </body>
        </html>
        ";

        // Add customized image attachment
        $customizedImageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $customizedImage));
        $mail->addStringAttachment($customizedImageData, 'gofotobox-customized.png', 'base64', 'image/png');

        // Add original photos as attachments
        foreach ($originalPhotos as $index => $photoData) {
            $photoImageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $photoData));
            $mail->addStringAttachment($photoImageData, 'foto-' . ($index + 1) . '.png', 'base64', 'image/png');
        }

        // Send email
        $mail->send();

        echo json_encode([
            'success' => true,
            'message' => 'Email berhasil dikirim ke ' . $email,
            'is_indonesian_domain' => isset($validationResult['is_indonesian']) ? $validationResult['is_indonesian'] : false
        ]);
    } catch (Exception $e) {
        error_log('Email sending failed: ' . $e->getMessage());
        echo json_encode(['error' => 'Gagal mengirim email: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Metode request tidak valid']);
}

function getLayoutDescription($layout)
{
    switch ($layout) {
        case 'layout1':
            return 'Layout 1 - Photo Strip (2 Photos)';
        case 'layout2':
            return 'Layout 2 - Photo Grid (4 Photos)';
        case 'layout3':
            return 'Layout 3 - Photo Grid (6 Photos)';
        case 'layout4':
            return 'Layout 4 - Photo Grid (8 Photos)';
        case 'layout5':
            return 'Layout 5 - Photo Grid (6 Photos)';
        case 'layout6':
            return 'Layout 6 - Photo Grid (4 Photos)';
        case 'canvas':
            return 'Canvas - Original Frame (1 Photo)';
        case 'canvas2':
            return 'Canvas 2 - Original Frame (2 Photos)';
        case 'canvas4':
            return 'Canvas 4 - Original Frame (4 Photos)';
        case 'canvas6':
            return 'Canvas 6 - Original Frame (6 Photos)';
        default:
            return 'Canvas - Original Frame';
    }
}
