<?php
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['email']) || !isset($input['customized_image'])) {
        echo json_encode(['error' => 'Email and customized image are required']);
        exit;
    }
    
    $email = $input['email'];
    $customizedImage = $input['customized_image'];
    
    // Get original photos from session
    $originalPhotos = isset($_SESSION['captured_photos']) ? $_SESSION['captured_photos'] : [];
    
    // Get layout info to determine email content
    $selectedLayout = isset($_SESSION['selected_layout']) ? $_SESSION['selected_layout'] : 'canvas';
    
    try {
        // Prepare email content
        $subject = "GoFotobox - Foto Hasil Photobooth Anda";
        
        $photosCount = count($originalPhotos);
        $layoutText = getLayoutDescription($selectedLayout);
        
        $emailBody = "
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
        
        // Create email headers
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: multipart/mixed; boundary=\"boundary\"\r\n";
        $headers .= "From: noreply@gofotobox.com\r\n";
        
        // Email body with attachments
        $emailContent = "--boundary\r\n";
        $emailContent .= "Content-Type: text/html; charset=UTF-8\r\n\r\n";
        $emailContent .= $emailBody . "\r\n\r\n";
        
        // Attach customized image
        $customizedImageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $customizedImage));
        $emailContent .= "--boundary\r\n";
        $emailContent .= "Content-Type: image/png; name=\"gofotobox-customized.png\"\r\n";
        $emailContent .= "Content-Disposition: attachment; filename=\"gofotobox-customized.png\"\r\n";
        $emailContent .= "Content-Transfer-Encoding: base64\r\n\r\n";
        $emailContent .= base64_encode($customizedImageData) . "\r\n\r\n";
        
        // Attach original photos
        foreach ($originalPhotos as $index => $photoData) {
            $photoImageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $photoData));
            $emailContent .= "--boundary\r\n";
            $emailContent .= "Content-Type: image/png; name=\"foto-" . ($index + 1) . ".png\"\r\n";
            $emailContent .= "Content-Disposition: attachment; filename=\"foto-" . ($index + 1) . ".png\"\r\n";
            $emailContent .= "Content-Transfer-Encoding: base64\r\n\r\n";
            $emailContent .= base64_encode($photoImageData) . "\r\n\r\n";
        }
        
        $emailContent .= "--boundary--";
        
        // Send email
        $result = mail($email, $subject, $emailContent, $headers);
        
        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'Failed to send email']);
        }
        
    } catch (Exception $e) {
        echo json_encode(['error' => 'Email sending failed: ' . $e->getMessage()]);
    }
    
} else {
    echo json_encode(['error' => 'Invalid request method']);
}

function getLayoutDescription($layout) {
    switch ($layout) {
        case 'layout1': return 'Layout 1 - Photo Strip (2 Photos)';
        case 'layout2': return 'Layout 2 - Photo Grid (4 Photos)';
        case 'layout3': return 'Layout 3 - Photo Grid (6 Photos)';
        case 'layout4': return 'Layout 4 - Photo Grid (8 Photos)';
        case 'layout5': return 'Layout 5 - Photo Grid (6 Photos)';
        case 'layout6': return 'Layout 6 - Photo Grid (4 Photos)';
        case 'canvas': return 'Canvas - Original Frame (1 Photo)';
        case 'canvas2': return 'Canvas 2 - Original Frame (2 Photos)';
        case 'canvas4': return 'Canvas 4 - Original Frame (4 Photos)';
        case 'canvas6': return 'Canvas 6 - Original Frame (6 Photos)';
        default: return 'Canvas - Original Frame';
    }
}
?>
