<?php
// Start the session at the beginning
session_start();

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'storedb');
define('DB_USER', 'root');
define('DB_PASS', '');

// Application Configuration
define('BASE_URL', 'http://localhost');
define('APP_NAME', 'IESMINS_RAW');

// Debug Configuration
define('DEBUG_MODE', true);
define('DEBUG_DB', true);
define('DEBUG_ROUTER', true);
define('DEBUG_VIEW', true);
define('DEBUG_AUTH', true);

// Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Debug Log File
define('DEBUG_LOG_FILE', __DIR__ . '/../../logs/debug.log');

// Create logs directory if it doesn't exist
if (!file_exists(__DIR__ . '/../../logs')) {
    mkdir(__DIR__ . '/../../logs', 0777, true);
}