<?php
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['image_data'])) {
        echo json_encode(['error' => 'Image data is required']);
        exit;
    }
    
    $imageData = $input['image_data'];
    
    try {
        // Decode base64 image
        $imageContent = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imageData));
        
        if ($imageContent === false) {
            throw new Exception('Invalid image data');
        }
        
        // Create temporary file
        $tempFile = tempnam(sys_get_temp_dir(), 'gofotobox_print_') . '.png';
        
        if (file_put_contents($tempFile, $imageContent) === false) {
            throw new Exception('Failed to create temporary file');
        }
        
        // Print command for EcoTank L8050 (adjust as needed)
        // This assumes CUPS is installed and printer is configured
        $printerName = 'L8050'; // Change this to your actual printer name
        
        // Use lpr command to print (Linux/Unix systems)
        // For EcoTank L8050, we'll use 4R paper size (10x15cm) which is common for photo printing
        $printCommand = "lpr -P {$printerName} -o media=4x6 -o ColorModel=RGB -o print-quality=5 " . escapeshellarg($tempFile);
        
        // Alternative command if you have specific driver settings:
        // $printCommand = "lp -d {$printerName} -o PageSize=4x6 -o ColorModel=RGB -o Resolution=300x300dpi " . escapeshellarg($tempFile);
        
        // Execute print command
        $output = [];
        $returnCode = 0;
        exec($printCommand . ' 2>&1', $output, $returnCode);
        
        // Clean up temporary file
        unlink($tempFile);
        
        if ($returnCode === 0) {
            echo json_encode([
                'success' => true, 
                'message' => 'Photo sent to printer successfully'
            ]);
        } else {
            throw new Exception('Print command failed: ' . implode(' ', $output));
        }
        
    } catch (Exception $e) {
        // Clean up temp file if it exists
        if (isset($tempFile) && file_exists($tempFile)) {
            unlink($tempFile);
        }
        
        echo json_encode([
            'error' => 'Print failed: ' . $e->getMessage()
        ]);
    }
    
} else {
    echo json_encode(['error' => 'Invalid request method']);
}
?>
