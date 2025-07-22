<?php
// Configuration file for FotoboxJO
// This file contains sensitive configuration that should not be committed to version control

return [
    // Midtrans Configuration
    'midtrans' => [
        'server_key' => 'SB-Mid-server-YOUR_SERVER_KEY_HERE', // Replace with your actual server key
        'client_key' => 'SB-Mid-client-YOUR_CLIENT_KEY_HERE', // Replace with your actual client key
        'is_production' => false, // Set to true for production environment
        'is_sanitized' => true,
        'is_3ds' => true,
    ],

    // Application Configuration
    'app' => [
        'name' => 'FotoboxJO',
        'debug' => true, // Set to false in production
        'base_url' => 'http://localhost/FotoboxJO', // Update for your domain
    ],

    // Payment Configuration
    'payment' => [
        'currency' => 'IDR',
        'default_amount' => 10000, // 10,000 IDR
        'session_timeout' => 3600, // 1 hour in seconds
    ]
];
