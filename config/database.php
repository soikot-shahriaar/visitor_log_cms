<?php
/**
 * Database Configuration
 * Visitor Log System CMS
 */

// Database configuration constants
define('DB_HOST', 'localhost');
define('DB_NAME', 'visitor_log_system');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Application configuration
define('APP_NAME', 'Visitor Log System');
define('APP_VERSION', '1.0.0');
// Set APP_URL without port number (e.g., http://localhost/visitor_log_cms or http://localhost if in web root)
define('APP_URL', 'http://localhost/visitor_log_cms');

// Security configuration
define('SESSION_TIMEOUT', 3600); // 1 hour in seconds
define('CSRF_TOKEN_NAME', 'csrf_token');
define('PASSWORD_MIN_LENGTH', 6);

// File upload configuration
define('UPLOAD_PATH', __DIR__ . '/../uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'gif']);

// Pagination configuration
define('RECORDS_PER_PAGE', 20);

// Date and time configuration
define('DATE_FORMAT', 'Y-m-d');
define('TIME_FORMAT', 'H:i:s');
define('DATETIME_FORMAT', 'Y-m-d H:i:s');
define('DISPLAY_DATE_FORMAT', 'd/m/Y');
define('DISPLAY_TIME_FORMAT', 'H:i');
define('DISPLAY_DATETIME_FORMAT', 'd/m/Y H:i');

// Error reporting (set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set timezone
date_default_timezone_set('UTC');
?>

