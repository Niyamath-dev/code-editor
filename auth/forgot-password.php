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
$showPasswordReset = false;
$userEmail = '';
$userId = null;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
        $error = 'Invalid request. Please try again.';
    } else {
        // Check if this is email verification or password reset
        if (isset($_POST['action']) && $_POST['action'] === 'reset_password') {
            // Handle password reset
            $userId = sanitizeInput($_POST['user_id'] ?? '');
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            // Validate passwords
            if (empty($newPassword)) {
                $error = 'New password is required.';
            } elseif (strlen($newPassword) < 6) {
                $error = 'Password must be at least 6 characters long.';
            } elseif ($newPassword !== $confirmPassword) {
                $error = 'Passwords do not match.';
            } else {
                try {
                    $db = Database::getInstance()->getConnection();
                    
                    // Update password
                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                    $stmt = $db->prepare("UPDATE users SET password = :password, reset_token = NULL, reset_token_expires = NULL WHERE id = :id");
                    $stmt->bindParam(':password', $hashedPassword);
                    $stmt->bindParam(':id', $userId);
                    
                    if ($stmt->execute()) {
                        $success = 'Your password has been successfully reset. You can now login with your new password.';
                        $showPasswordReset = false;
                    } else {
                        $error = 'An error occurred while resetting your password. Please try again.';
                    }
                } catch (Exception $e) {
                    error_log("Password reset error: " . $e->getMessage());
                    $error = 'An error occurred. Please try again later.';
                }
            }
        } else {
            // Handle email verification
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
                        // Email exists, show password reset form
                        $showPasswordReset = true;
                        $userEmail = $email;
                        $userId = $user['id'];
                        $success = 'Email found! Please enter your new password below.';
                    } else {
                        $error = 'No account found with that email address.';
                    }
                } catch (Exception $e) {
                    error_log("Email verification error: " . $e->getMessage());
                    $error = 'An error occurred. Please try again later.';
                }
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
                        <h2 class="auth-title">
                            <?php echo $showPasswordReset ? 'Reset Password' : 'Forgot Password?'; ?>
                        </h2>
                        <p class="auth-subtitle">
                            <?php echo $showPasswordReset ? 'Enter your new password below' : 'Enter your email address to reset your password'; ?>
                        </p>
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

                    <?php if (!$showPasswordReset && !$success): ?>
                        <!-- Email verification form -->
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
                                <i class="bi bi-search"></i>
                                Verify Email
                            </button>
                        </form>
                    <?php elseif ($showPasswordReset): ?>
                        <!-- Password reset form -->
                        <form method="POST" class="auth-form" novalidate>
                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                            <input type="hidden" name="action" value="reset_password">
                            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($userId); ?>">
                            
                            <div class="form-group">
                                <label class="form-label">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-envelope"></i>
                                    </span>
                                    <input type="email" class="form-control" value="<?php echo htmlspecialchars($userEmail); ?>" readonly>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="new_password" class="form-label">New Password</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-lock"></i>
                                    </span>
                                    <input type="password" class="form-control" id="new_password" name="new_password" 
                                           placeholder="Enter new password" required minlength="6">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('new_password')">
                                        <i class="bi bi-eye" id="new_password_icon"></i>
                                    </button>
                                </div>
                                <small class="form-text text-muted">Password must be at least 6 characters long</small>
                            </div>

                            <div class="form-group">
                                <label for="confirm_password" class="form-label">Confirm New Password</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-lock-fill"></i>
                                    </span>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                                           placeholder="Confirm new password" required minlength="6">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('confirm_password')">
                                        <i class="bi bi-eye" id="confirm_password_icon"></i>
                                    </button>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-auth">
                                <i class="bi bi-check-circle"></i>
                                Reset Password
                            </button>
                            
                            <button type="button" class="btn btn-secondary btn-auth mt-2" onclick="window.location.href = 'forgot-password.php'">
                                <i class="bi bi-arrow-left"></i>
                                Back to Email Verification
                            </button>
                        </form>
                    <?php endif; ?>

                    <?php if (!$showPasswordReset): ?>
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
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$additional_css = ['../assets/css/auth.css'];
$inline_js = "
    // Toggle password visibility
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = document.getElementById(fieldId + '_icon');
        
        if (field.type === 'password') {
            field.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            field.type = 'password';
            icon.className = 'bi bi-eye';
        }
    }

    // Form validation
    document.querySelector('.auth-form').addEventListener('submit', function(e) {
        const action = document.querySelector('input[name=\"action\"]');
        
        if (action && action.value === 'reset_password') {
            // Validate password reset form
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (!newPassword) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Please enter a new password.'
                });
                return;
            }
            
            if (newPassword.length < 6) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Password must be at least 6 characters long.'
                });
                return;
            }
            
            if (newPassword !== confirmPassword) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Passwords do not match.'
                });
                return;
            }
        } else {
            // Validate email form
            const email = document.getElementById('email').value.trim();
            
            if (!email) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Please enter your email address.'
                });
            }
        }
    });
";

include __DIR__ . '/../includes/footer.php';
?>
