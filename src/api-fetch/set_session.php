<?php
require_once '../includes/session-manager.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    // Handle start payment action
    if (isset($input['action']) && $input['action'] === 'start_payment') {
        $paymentMethod = $input['payment_method'] ?? 'unknown';

        // Start the 20-minute session timer
        $session = SessionManager::startPaymentSession();

        // Store payment method
        $_SESSION['selected_payment_method'] = $paymentMethod;

        echo json_encode([
            'success' => true,
            'message' => 'Payment session started',
            'session_state' => SessionManager::getSessionState(),
            'time_remaining' => SessionManager::getMainTimerRemaining(),
            'payment_method' => $paymentMethod,
            'session_start' => $_SESSION['main_timer_start']
        ]);
        exit();
    }

    // Handle developer session - skip payment with same 20 minute timer
    if (isset($input['action']) && $input['action'] === 'start_developer_session') {
        // Start the 20-minute session timer (same as payment)
        $session = SessionManager::startPaymentSession();

        // Automatically complete payment for developer
        SessionManager::completePayment('DEV_ACCESS_' . time());

        // Mark as developer session
        $_SESSION['is_developer_session'] = true;
        $_SESSION['selected_payment_method'] = 'developer';

        echo json_encode([
            'success' => true,
            'message' => 'Developer session started',
            'session_state' => SessionManager::getSessionState(),
            'time_remaining' => SessionManager::getMainTimerRemaining(),
            'payment_method' => 'developer',
            'is_developer' => true,
            'session_start' => $_SESSION['main_timer_start']
        ]);
        exit();
    }

    // Handle extend session action - untuk print process
    if (isset($input['action']) && $input['action'] === 'extend') {
        $extendMinutes = isset($input['extend_minutes']) ? (int)$input['extend_minutes'] : 5;
        $reason = $input['reason'] ?? 'manual_extend';
        
        // Validate extend minutes (1-10 minutes allowed)
        if ($extendMinutes < 1 || $extendMinutes > 10) {
            echo json_encode([
                'success' => false,
                'error' => 'Invalid extend duration. Must be 1-10 minutes.',
                'requested' => $extendMinutes
            ]);
            exit();
        }

        // Check if session is active (not expired)
        if (SessionManager::isMainTimerExpired()) {
            echo json_encode([
                'success' => false,
                'error' => 'Session has expired, cannot extend',
                'session_state' => SessionManager::getSessionState(),
                'time_remaining' => SessionManager::getMainTimerRemaining()
            ]);
            exit();
        }

        // Extend the session
        try {
            $currentStart = $_SESSION['main_timer_start'] ?? time();
            $extendSeconds = $extendMinutes * 60;
            
            // Extend by adding time to the start (effectively extending duration)
            $_SESSION['main_timer_start'] = $currentStart - $extendSeconds;
            
            // Log the extension
            error_log("📏 Session extended by {$extendMinutes} minutes. Reason: {$reason}");

            echo json_encode([
                'success' => true,
                'message' => "Session extended by {$extendMinutes} minutes",
                'extended_minutes' => $extendMinutes,
                'reason' => $reason,
                'new_time_remaining' => SessionManager::getMainTimerRemaining(),
                'session_state' => SessionManager::getSessionState(),
                'timestamp' => date('Y-m-d H:i:s')
            ]);
            exit();
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => 'Failed to extend session: ' . $e->getMessage(),
                'reason' => $reason
            ]);
            exit();
        }
    }

    // Handle cash session - same as developer but marked as cash
    if (isset($input['action']) && $input['action'] === 'start_cash_session') {
        // Start the 20-minute session timer (same as payment)
        $session = SessionManager::startPaymentSession();

        // Automatically complete payment for cash
        SessionManager::completePayment('CASH_PAYMENT_' . time());

        // Mark as cash session
        $_SESSION['is_cash_session'] = true;
        $_SESSION['selected_payment_method'] = 'cash';

        echo json_encode([
            'success' => true,
            'message' => 'Cash session started',
            'session_state' => SessionManager::getSessionState(),
            'time_remaining' => SessionManager::getMainTimerRemaining(),
            'payment_method' => 'cash',
            'is_cash' => true,
            'session_start' => $_SESSION['main_timer_start']
        ]);
        exit();
    }

    // Handle payment completion
    if (isset($input['action']) && $input['action'] === 'complete_payment') {
        $orderId = $input['order_id'] ?? null;

        if (SessionManager::completePayment($orderId)) {
            echo json_encode([
                'success' => true,
                'message' => 'Payment completed',
                'session_state' => SessionManager::getSessionState(),
                'time_remaining' => SessionManager::getMainTimerRemaining()
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to complete payment'
            ]);
        }
        exit();
    }

    // Handle layout selection
    if (isset($input['action']) && $input['action'] === 'select_layout') {
        $layoutId = $input['layout_id'] ?? null;

        if ($layoutId && SessionManager::selectLayout($layoutId)) {
            echo json_encode([
                'success' => true,
                'message' => 'Layout selected',
                'session_state' => SessionManager::getSessionState(),
                'time_remaining' => SessionManager::getMainTimerRemaining(),
                'selected_layout' => $layoutId
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to select layout'
            ]);
        }
        exit();
    }

    // Legacy support for backward compatibility
    $updated_fields = [];

    // Handle order ID setting
    if (isset($input['order_id'])) {
        $_SESSION['current_order_id'] = $input['order_id'];
        $_SESSION['payment_completed'] = true;
        $updated_fields[] = 'order_id';
    }

    // Handle payment completion
    if (isset($input['payment_completed'])) {
        $_SESSION['payment_completed'] = $input['payment_completed'];
        $updated_fields[] = 'payment_completed';
    }

    // Handle session type
    if (isset($input['session_type'])) {
        $_SESSION['session_type'] = $input['session_type'];
        $updated_fields[] = 'session_type';
    }

    // Handle manual session expires (for testing)
    if (isset($input['session_expires'])) {
        $_SESSION['session_expires'] = $input['session_expires'];
        $updated_fields[] = 'session_expires';
    }

    // Set session type to layout selection if payment completed
    if (isset($input['payment_completed']) && $input['payment_completed'] === true) {
        $_SESSION['session_type'] = 'layout_selection';
        if (!in_array('session_type', $updated_fields)) {
            $updated_fields[] = 'auto_session_type';
        }
    }

    echo json_encode([
        'success' => true,
        'session_updated' => true,
        'session_id' => session_id(),
        'updated_fields' => $updated_fields,
        'current_session' => $_SESSION,
        'timestamp' => date('Y-m-d H:i:s')
    ]);
} else {
    echo json_encode([
        'error' => 'Invalid request method',
        'method' => $_SERVER['REQUEST_METHOD'],
        'allowed' => 'POST'
    ]);
}
