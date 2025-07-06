<?php
/**
 * Application Configuration
 * Global application settings and constants
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Application constants
define('APP_NAME', 'HCJ Code Editor');
define('APP_VERSION', '2.0.0');
define('APP_URL', 'http://localhost/code-editor');

// Security settings
define('CSRF_TOKEN_NAME', 'csrf_token');
define('SESSION_TIMEOUT', 3600); // 1 hour

// File upload settings
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_FILE_TYPES', ['html', 'css', 'js', 'txt']);

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

/**
 * Generate CSRF Token
 */
function generateCSRFToken() {
    if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
    }
    return $_SESSION[CSRF_TOKEN_NAME];
}

/**
 * Verify CSRF Token
 */
function verifyCSRFToken($token) {
    return isset($_SESSION[CSRF_TOKEN_NAME]) && hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
}

/**
 * Sanitize input data
 */
function sanitizeInput($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

/**
 * Validate email format
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Redirect to login if not authenticated
 */
function requireAuth() {
    if (!isLoggedIn()) {
        header('Location: auth/login.php');
        exit();
    }
}

/**
 * Redirect with message
 */
function redirectWithMessage($url, $message, $type = 'error') {
    $_SESSION['flash_message'] = $message;
    $_SESSION['flash_type'] = $type;
    header("Location: $url");
    exit();
}

/**
 * Get and clear flash message
 */
function getFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        $type = $_SESSION['flash_type'] ?? 'info';
        unset($_SESSION['flash_message'], $_SESSION['flash_type']);
        return ['message' => $message, 'type' => $type];
    }
    return null;
}
