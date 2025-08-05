<?php
// Simple API test to check if URL path is correct
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$input = file_get_contents('php://input');
$data = json_decode($input, true);

echo json_encode([
    'success' => true,
    'message' => 'API is reachable',
    'method' => $method,
    'raw_input' => $input,
    'parsed_data' => $data,
    'timestamp' => date('Y-m-d H:i:s'),
    'script_path' => __FILE__
]);
