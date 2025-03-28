<?php
// Enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load environment variables
require_once __DIR__ . '/../../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

// Load game configuration
$CONFIG = require_once __DIR__ . '/../config.php';

// Set default timezone
date_default_timezone_set('UTC');

// Ensure all responses are JSON
header('Content-Type: application/json');

// Function to handle fatal errors
function fatalErrorHandler() {
    $error = error_get_last();
    if ($error !== null && $error['type'] === E_ERROR) {
        echo json_encode([
            'success' => false,
            'message' => 'A fatal error occurred: ' . $error['message']
        ]);
    }
}
register_shutdown_function('fatalErrorHandler');

// Function to handle exceptions
function exceptionHandler($exception) {
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred: ' . $exception->getMessage()
    ]);
}
set_exception_handler('exceptionHandler'); 