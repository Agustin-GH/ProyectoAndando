<?php
declare(strict_types=1);

// Game API endpoint - handles all game-related requests
require_once __DIR__ . '/app/config/app.php';
require_once APP_PATH . '/Controllers/JuegoControlador.php';

// Enable error reporting for debugging (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', '0');

// Set headers
header('Content-Type: application/json; charset=utf-8');

// CORS headers (adjust for production)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Accept');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Start session
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params([
        'httponly' => true,
        'secure'   => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'samesite' => 'Lax',
        'path'     => '/',
    ]);
    session_start();
}

try {
    // Check if action parameter exists
    if (!isset($_REQUEST['action'])) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'code' => 400,
            'message' => 'Missing action parameter'
        ]);
        exit;
    }

    // Handle the game request
    JuegoControlador::handleRequest();
} catch (Throwable $e) {
    error_log('Game API Error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'code' => 500,
        'message' => 'Internal server error'
    ]);
}
