<?php
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (isset($input['order_id'])) {
        $_SESSION['current_order_id'] = $input['order_id'];
        $_SESSION['payment_completed'] = true;
        
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Order ID not provided']);
    }
} else {
    echo json_encode(['error' => 'Invalid request method']);
}
?>
