<?php
// Set proper session configuration for PWA
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 0); // Set to 1 for HTTPS
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.use_strict_mode', 0);

session_start();

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$response = [
    'session_id' => session_id(),
    'session_data' => $_SESSION,
    'cookie_params' => session_get_cookie_params(),
    'current_time' => time(),
    'current_datetime' => date('Y-m-d H:i:s'),
    'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown',
    'is_pwa' => isset($_SERVER['HTTP_X_REQUESTED_WITH']) || 
               (isset($_SERVER['HTTP_SEC_FETCH_MODE']) && $_SERVER['HTTP_SEC_FETCH_MODE'] === 'navigate'),
    'request_headers' => getallheaders(),
    'server_info' => [
        'php_version' => phpversion(),
        'session_module_name' => session_module_name(),
        'session_save_path' => session_save_path()
    ]
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if ($input) {
        foreach ($input as $key => $value) {
            $_SESSION[$key] = $value;
        }
        $response['action'] = 'session_updated';
        $response['input_received'] = $input;
    }
}

echo json_encode($response, JSON_PRETTY_PRINT);
?>
