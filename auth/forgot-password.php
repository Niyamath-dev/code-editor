<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';

// Redirect if already logged in
if (isLoggedIn()) {
    header('Location: ../index.php');
    exit();
}

$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
        $error = 'Invalid request. Please try again.';
    } else {
        $email = sanitizeInput($_POST['email'] ?? '');

        // Validate input
        if (empty($email)) {
            $error = 'Email address is required.';
        } elseif (!validateEmail($email)) {
            $error = 'Please enter a valid email address.';
        } else {
            try {
                $db = Database::getInstance()->getConnection();
                
                // Check if email exists
                $stmt = $db->prepare("SELECT id, name FROM users WHERE email = :email");
                $stmt->bindParam(':email', $email);
                $stmt->execute();
                $user = $stmt->fetch();

                if ($user) {
                    // Generate reset token
                    $resetToken = bin2hex(random_bytes(32));
                    $expiresAt = date('Y-m-d H:i:s', time() + 3600); // 1 hour from now
                    
                    // Update user with reset token
                    $stmt = $db->prepare("UPDATE users SET reset_token = :token, reset_token_expires = :expires WHERE email = :email");
                    $stmt->bindParam(':token', $resetToken);
                    $stmt->bindParam(':expires', $expiresAt);
                    $stmt->bindParam(':email', $email);
                    
                    if ($stmt->execute()) {
                        // In a real application, you would send an email here
                        // For demo purposes, we'll just show a success message
                        $success = 'If an account with that email exists, we have sent password reset instructions to your email address.';
                        
                        // Log the reset token for demo purposes (remove in production)
                        error_log("Password reset token for $email: $resetToken");
                    } else {
                        $error = 'An error occurred. Please try again later.';
                    }
                } else {
                    // Don't reveal if email exists or not for security
                    $success = 'If an account with that email exists, we have sent password reset instructions to your email address.';
                }
            } catch (Exception $e) {
                error_log("Forgot password error: " . $e->getMessage());
                $error = 'An error occurred. Please try again later.';
            }
        }
    }
}

$page_title = 'Forgot Password';
include __DIR__ . '/../includes/header.php';
?>

<div class="auth-container">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6 col-lg-5">
                <div class="auth-card">
                    <div class="auth-header">
                        <div class="auth-logo">
                            <i class="bi bi-key"></i>
                        </div>
                        <h2 class="auth-title">Forgot Password?</h2>
                        <p class="auth-subtitle">Enter your email address and we'll send you instructions to reset your password</p>
                    </div>

                    <?php if ($error): ?>
                        <div class="alert alert-danger" role="alert">
                            <i class="bi bi-exclamation-triangle"></i>
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                        <div class="alert alert-success" role="alert">
                            <i class="bi bi-check-circle"></i>
                            <?php echo htmlspecialchars($success); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" class="auth-form" novalidate>
                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                        
                        <div class="form-group">
                            <label for="email" class="form-label">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input type="email" class="form-control" id="email" name="email" 
                                       placeholder="Enter your email address" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-auth">
                            <i class="bi bi-send"></i>
                            Send Reset Instructions
                        </button>
                    </form>

                    <div class="auth-footer">
                        <p class="auth-link">
                            Remember your password? 
                            <a href="login.php" class="link-primary">Sign in here</a>
                        </p>
                        <p class="auth-link">
                            Don't have an account? 
                            <a href="signup.php" class="link-primary">Create one here</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$additional_css = ['../assets/css/auth.css'];
$inline_js = "
    // Form validation
    document.querySelector('.auth-form').addEventListener('submit', function(e) {
        const email = document.getElementById('email').value.trim();
        
        if (!email) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Please enter your email address.'
            });
        }
    });
";

include __DIR__ . '/../includes/footer.php';
?>
