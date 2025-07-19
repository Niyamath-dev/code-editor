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
                    
                    // Verify user exists and has valid reset token (more lenient check)
                    $checkStmt = $db->prepare("SELECT id, reset_token, reset_token_expires FROM users WHERE id = :id");
                    $checkStmt->bindParam(':id', $userId);
                    $checkStmt->execute();
                    $user = $checkStmt->fetch();
                    
                    if ($user && $user['reset_token'] !== null) {
                        // Check if token is still valid (within 24 hours)
                        $tokenExpires = strtotime($user['reset_token_expires']);
                        $currentTime = time();
                        
                        if ($tokenExpires > $currentTime) {
                        // Update password and clear reset token
                        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                        $stmt = $db->prepare("UPDATE users SET password = :password, reset_token = NULL, reset_token_expires = NULL WHERE id = :id");
                        $stmt->bindParam(':password', $hashedPassword);
                        $stmt->bindParam(':id', $userId);
                        
                            if ($stmt->execute()) {
                                redirectWithMessage('login.php', 'Your password has been successfully reset. You can now login with your new password.', 'success');
                            } else {
                                $error = 'An error occurred while resetting your password. Please try again.';
                            }
                        } else {
                            $error = 'Reset token has expired. Please start the password reset process again.';
                        }
                    } else {
                        $error = 'Invalid reset request. Please start the password reset process again.';
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
                        // Email exists, generate reset token and show password reset form
                        $resetToken = bin2hex(random_bytes(32));
                        $resetExpires = date('Y-m-d H:i:s', strtotime('+24 hours')); // Extended to 24 hours
                        
                        // Update user with reset token
                        $updateStmt = $db->prepare("UPDATE users SET reset_token = :token, reset_token_expires = :expires WHERE id = :id");
                        $updateStmt->bindParam(':token', $resetToken);
                        $updateStmt->bindParam(':expires', $resetExpires);
                        $updateStmt->bindParam(':id', $user['id']);
                        
                        if ($updateStmt->execute()) {
                            $showPasswordReset = true;
                            $userEmail = $email;
                            $userId = $user['id'];
                            $success = 'Email found! Please enter your new password below.';
                        } else {
                            $error = 'An error occurred while processing your request. Please try again.';
                        }
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
    <div class="container-fluid">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-11 col-sm-9 col-md-7 col-lg-5 col-xl-4">
                <div class="card shadow-lg border-0 auth-card">
                    <!-- Card Header -->
                    <div class="card-header bg-transparent border-0 text-center py-4">
                        <div class="auth-logo mx-auto mb-3">
                            <i class="bi bi-key"></i>
                        </div>
                        <h2 class="card-title h3 fw-bold text-primary mb-2">
                            <?php echo $showPasswordReset ? 'Reset Password' : 'Forgot Password?'; ?>
                        </h2>
                        <p class="text-muted mb-0">
                            <?php echo $showPasswordReset ? 'Enter your new password below' : 'Enter your email address to reset your password'; ?>
                        </p>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body px-4 px-sm-5 pb-5">
                        <?php if ($error): ?>
                            <div class="alert alert-danger d-flex align-items-center" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <div><?php echo htmlspecialchars($error); ?></div>
                            </div>
                        <?php endif; ?>

                        <?php if ($success): ?>
                            <div class="alert alert-success d-flex align-items-center" role="alert">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                <div><?php echo htmlspecialchars($success); ?></div>
                            </div>
                        <?php endif; ?>

                        <?php if (!$showPasswordReset && !$success): ?>
                            <!-- Email verification form -->
                            <form method="POST" class="needs-validation" novalidate>
                                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                
                                <!-- Email Field -->
                                <div class="mb-4">
                                    <label for="email" class="form-label fw-medium">Email Address</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="bi bi-envelope text-muted"></i>
                                        </span>
                                        <input type="email" 
                                               class="form-control border-start-0 ps-0" 
                                               id="email" 
                                               name="email" 
                                               placeholder="Enter your email address" 
                                               value="<?php echo htmlspecialchars($email ?? ''); ?>" 
                                               required>
                                        <div class="invalid-feedback">
                                            Please provide a valid email address.
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">
                                        <i class="bi bi-info-circle me-1"></i>We'll check if this email is registered with us
                                    </small>
                                </div>

                                <!-- Submit Button -->
                                <div class="d-grid mb-4">
                                    <button type="submit" class="btn btn-primary btn-lg fw-medium">
                                        <i class="bi bi-search me-2"></i>
                                        Verify Email
                                    </button>
                                </div>

                                <!-- Links -->
                                <div class="text-center">
                                    <div class="mb-2">
                                        <span class="text-muted">Remember your password?</span>
                                        <a href="login.php" class="text-decoration-none fw-medium ms-1">Sign in here</a>
                                    </div>
                                    <div>
                                        <span class="text-muted">Don't have an account?</span>
                                        <a href="signup.php" class="text-decoration-none fw-medium ms-1">Create one here</a>
                                    </div>
                                </div>
                            </form>
                        <?php elseif ($showPasswordReset): ?>
                            <!-- Password reset form -->
                            <form method="POST" class="needs-validation" novalidate>
                                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                <input type="hidden" name="action" value="reset_password">
                                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($userId); ?>">
                                
                                <!-- Email Display Field -->
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Email Address</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="bi bi-envelope text-muted"></i>
                                        </span>
                                        <input type="email" 
                                               class="form-control border-start-0 ps-0" 
                                               value="<?php echo htmlspecialchars($userEmail); ?>" 
                                               readonly>
                                    </div>
                                </div>

                                <!-- New Password Field -->
                                <div class="mb-3">
                                    <label for="new_password" class="form-label fw-medium">New Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="bi bi-lock text-muted"></i>
                                        </span>
                                        <input type="password" 
                                               class="form-control border-start-0 border-end-0 ps-0" 
                                               id="new_password" 
                                               name="new_password" 
                                               placeholder="Enter new password" 
                                               minlength="6"
                                               required>
                                        <button class="btn btn-outline-light border-start-0" 
                                                type="button" 
                                                onclick="togglePassword('new_password')"
                                                title="Toggle password visibility">
                                            <i class="bi bi-eye text-muted" id="new_password_icon"></i>
                                        </button>
                                        <div class="invalid-feedback">
                                            Password must be at least 6 characters long.
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">
                                        <i class="bi bi-info-circle me-1"></i>Password must be at least 6 characters long
                                    </small>
                                </div>

                                <!-- Confirm Password Field -->
                                <div class="mb-4">
                                    <label for="confirm_password" class="form-label fw-medium">Confirm New Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="bi bi-lock-fill text-muted"></i>
                                        </span>
                                        <input type="password" 
                                               class="form-control border-start-0 border-end-0 ps-0" 
                                               id="confirm_password" 
                                               name="confirm_password" 
                                               placeholder="Confirm new password" 
                                               minlength="6"
                                               required>
                                        <button class="btn btn-outline-light border-start-0" 
                                                type="button" 
                                                onclick="togglePassword('confirm_password')"
                                                title="Toggle password visibility">
                                            <i class="bi bi-eye text-muted" id="confirm_password_icon"></i>
                                        </button>
                                        <div class="invalid-feedback">
                                            Please confirm your new password.
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Buttons -->
                                <div class="d-grid mb-2">
                                    <button type="submit" class="btn btn-primary btn-lg fw-medium">
                                        <i class="bi bi-check-circle me-2"></i>
                                        Reset Password
                                    </button>
                                </div>
                                
                                <div class="d-grid">
                                    <button type="button" 
                                            class="btn btn-outline-secondary" 
                                            onclick="window.location.href = 'forgot-password.php'">
                                        <i class="bi bi-arrow-left me-2"></i>
                                        Back to Email Verification
                                    </button>
                                </div>
                            </form>
                        <?php endif; ?>
                    </div>
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

    // Bootstrap form validation
    document.querySelector('.needs-validation').addEventListener('submit', function(e) {
        const form = this;
        const action = document.querySelector('input[name=\"action\"]');
        
        if (action && action.value === 'reset_password') {
            // Validate password reset form
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (!form.checkValidity() || !newPassword || newPassword.length < 6 || newPassword !== confirmPassword) {
                e.preventDefault();
                e.stopPropagation();
                
                if (!newPassword) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: 'Please enter a new password.'
                    });
                    return;
                }
                
                if (newPassword.length < 6) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: 'Password must be at least 6 characters long.'
                    });
                    return;
                }
                
                if (newPassword !== confirmPassword) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: 'Passwords do not match.'
                    });
                    return;
                }
            }
        } else {
            // Validate email form
            const email = document.getElementById('email').value.trim();
            
            if (!form.checkValidity() || !email) {
                e.preventDefault();
                e.stopPropagation();
                
                if (!email) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: 'Please enter your email address.'
                    });
                }
            }
        }
        
        form.classList.add('was-validated');
    });
";

include __DIR__ . '/../includes/footer.php';
?>
