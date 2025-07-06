<?php
require_once __DIR__ . '/../config/app.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

// Handle logout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (isset($_POST['csrf_token']) && verifyCSRFToken($_POST['csrf_token'])) {
        // Clear all session variables
        $_SESSION = array();
        
        // Destroy the session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        // Destroy the session
        session_destroy();
        
        // Redirect to login with success message
        session_start();
        redirectWithMessage('login.php', 'You have been successfully logged out.', 'success');
    } else {
        redirectWithMessage('../index.php', 'Invalid logout request.', 'error');
    }
} else {
    // If not POST request, redirect back
    header('Location: ../index.php');
    exit();
}
?>
