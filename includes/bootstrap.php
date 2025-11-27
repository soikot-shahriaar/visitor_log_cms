<?php
/**
 * Bootstrap File
 * Application initialization and common includes
 * Visitor Log System CMS
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include configuration
require_once __DIR__ . '/../config/database.php';

// Include classes
require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/Admin.php';
require_once __DIR__ . '/Visitor.php';
require_once __DIR__ . '/Utils.php';

// Initialize global objects
$db = new Database();
$admin = new Admin();
$visitor = new Visitor();

/**
 * Check if admin is logged in
 */
function requireLogin() {
    global $admin;
    if (!$admin->isLoggedIn()) {
        Utils::setFlashMessage('error', 'Please log in to access this page');
        Utils::redirect('login.php');
    }
}

/**
 * Get current admin
 */
function getCurrentAdmin() {
    global $admin;
    return $admin->getCurrentAdmin();
}

/**
 * Check CSRF token
 */
function checkCSRFToken($token) {
    global $admin;
    if (!$admin->verifyCSRFToken($token)) {
        Utils::setFlashMessage('error', 'Invalid security token. Please try again.');
        return false;
    }
    return true;
}

/**
 * Generate CSRF token
 */
function getCSRFToken() {
    global $admin;
    return $admin->generateCSRFToken();
}

/**
 * Handle form submission
 */
function handleFormSubmission($requiredFields = []) {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return false;
    }
    
    // Check CSRF token
    if (!isset($_POST['csrf_token']) || !checkCSRFToken($_POST['csrf_token'])) {
        return false;
    }
    
    // Check required fields
    foreach ($requiredFields as $field) {
        if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
            Utils::setFlashMessage('error', "Field '$field' is required");
            return false;
        }
    }
    
    return true;
}

/**
 * Get sanitized POST data
 */
function getPostData($fields = null) {
    if ($fields === null) {
        return Utils::sanitizeInput($_POST);
    }
    
    $data = [];
    foreach ($fields as $field) {
        $data[$field] = isset($_POST[$field]) ? Utils::sanitizeInput($_POST[$field]) : null;
    }
    return $data;
}

/**
 * Get sanitized GET data
 */
function getGetData($fields = null) {
    if ($fields === null) {
        return Utils::sanitizeInput($_GET);
    }
    
    $data = [];
    foreach ($fields as $field) {
        $data[$field] = isset($_GET[$field]) ? Utils::sanitizeInput($_GET[$field]) : null;
    }
    return $data;
}

/**
 * Include template with variables
 */
function includeTemplate($templateName, $variables = []) {
    extract($variables);
    $templatePath = __DIR__ . '/../templates/' . $templateName . '.php';
    if (file_exists($templatePath)) {
        include $templatePath;
    } else {
        echo "Template not found: $templateName";
    }
}

/**
 * Get base URL
 */
function getBaseUrl() {
    // Use APP_URL constant if defined, otherwise construct from server variables
    if (defined('APP_URL') && !empty(APP_URL)) {
        $baseUrl = rtrim(APP_URL, '/');
        $path = dirname($_SERVER['SCRIPT_NAME']);
        // Remove leading slash from path if baseUrl already ends with project path
        if ($path !== '/' && $path !== '.') {
            return $baseUrl . $path;
        }
        return $baseUrl;
    }
    
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    
    // Remove port 8080 or any port from host
    if (strpos($host, ':8080') !== false) {
        $host = str_replace(':8080', '', $host);
    } elseif (strpos($host, ':') !== false) {
        // Remove any other port number
        $host = explode(':', $host)[0];
    }
    
    $path = dirname($_SERVER['SCRIPT_NAME']);
    return $protocol . '://' . $host . $path;
}

/**
 * Get current page name
 */
function getCurrentPage() {
    return basename($_SERVER['PHP_SELF'], '.php');
}

/**
 * Check if current page is active
 */
function isActivePage($pageName) {
    return getCurrentPage() === $pageName ? 'active' : '';
}

/**
 * Format status badge
 */
function getStatusBadge($status) {
    $badges = [
        'checked_in' => '<span class="badge bg-success">Checked In</span>',
        'checked_out' => '<span class="badge bg-secondary">Checked Out</span>',
        'cancelled' => '<span class="badge bg-danger">Cancelled</span>'
    ];
    return $badges[$status] ?? '<span class="badge bg-light text-dark">' . ucfirst($status) . '</span>';
}

/**
 * Truncate text
 */
function truncateText($text, $length = 50) {
    if (strlen($text) <= $length) {
        return $text;
    }
    return substr($text, 0, $length) . '...';
}

/**
 * Get visitor status options
 */
function getVisitorStatusOptions() {
    return [
        'checked_in' => 'Checked In',
        'checked_out' => 'Checked Out',
        'cancelled' => 'Cancelled'
    ];
}

/**
 * Get ID document type options
 */
function getIdDocumentTypes() {
    return [
        'national_id' => 'National ID',
        'passport' => 'Passport',
        'driver_license' => 'Driver License',
        'other' => 'Other'
    ];
}

// Set error handler
set_error_handler(function($severity, $message, $file, $line) {
    Utils::logError("Error: $message", "$file:$line");
});

// Set exception handler
set_exception_handler(function($exception) {
    Utils::logError("Uncaught exception: " . $exception->getMessage(), $exception->getFile() . ':' . $exception->getLine());
    if (!headers_sent()) {
        http_response_code(500);
        echo "An error occurred. Please try again later.";
    }
});
?>

