<?php
session_start();

header('Content-Type: application/json');

// Reset semua session
session_unset();
session_destroy();

echo json_encode(['success' => true]);
?>
