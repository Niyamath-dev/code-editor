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
        $name = sanitizeInput($_POST['name'] ?? '');
        $email = sanitizeInput($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $terms = isset($_POST['terms']) ? true : false;

        // Validate input
        if (empty($name) || empty($email) || empty($password) || empty($confirmPassword)) {
            $error = 'All fields are required.';
        } elseif (!$terms) {
            $error = 'You must accept the terms and conditions.';
        } elseif (!validateEmail($email)) {
            $error = 'Please enter a valid email address.';
        } elseif (strlen($password) < 8) {
            $error = 'Password must be at least 8 characters long.';
        } elseif ($password !== $confirmPassword) {
            $error = 'Passwords do not match.';
        } else {
            try {
                $db = Database::getInstance()->getConnection();
                
                // Check if email already exists
                $stmt = $db->prepare("SELECT id FROM users WHERE email = :email");
                $stmt->bindParam(':email', $email);
                $stmt->execute();
                
                if ($stmt->fetch()) {
                    $error = 'An account with this email already exists.';
                } else {
                    // Hash password and create user
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    
                    $stmt = $db->prepare("INSERT INTO users (name, email, password, created_at) VALUES (:name, :email, :password, NOW())");
                    $stmt->bindParam(':name', $name);
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':password', $hashedPassword);
                    
                    if ($stmt->execute()) {
                        redirectWithMessage('login.php', 'Account created successfully! Please sign in.', 'success');
                    } else {
                        $error = 'Failed to create account. Please try again.';
                    }
                }
            } catch (Exception $e) {
                error_log("Signup error: " . $e->getMessage());
                $error = 'An error occurred: ' . $e->getMessage();
            }
        }
    }
}

$page_title = 'Sign Up';
include __DIR__ . '/../includes/header.php';
?>

<div class="auth-container">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6 col-lg-5">
                <div class="auth-card">
                    <div class="auth-header">
                        <div class="auth-logo">
                            <i class="bi bi-person-plus"></i>
                        </div>
                        <h2 class="auth-title">Create Account</h2>
                        <p class="auth-subtitle">Join our coding community today</p>
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
                            <label for="name" class="form-label">Full Name</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-person"></i>
                                </span>
                                <input type="text" class="form-control" id="name" name="name" 
                                       placeholder="Enter your full name" value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
                            </div>
                        </div>

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

                        <div class="form-group">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input type="password" class="form-control" id="password" name="password" 
                                       placeholder="Create a strong password" required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <small class="form-text text-muted">Password must be at least 8 characters long</small>
                        </div>

                        <div class="form-group">
                            <label for="confirm_password" class="form-label">Confirm Password</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-lock-fill"></i>
                                </span>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                                       placeholder="Confirm your password" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                                <label class="form-check-label" for="terms">
                                    I agree to the <a href="#" class="link-primary">Terms of Service</a> and <a href="#" class="link-primary">Privacy Policy</a>
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-auth">
                            <i class="bi bi-person-plus"></i>
                            Create Account
                        </button>
                    </form>

                    <div class="auth-footer">
                        <p class="auth-link">
                            Already have an account? 
                            <a href="login.php" class="link-primary">Sign in here</a>
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
    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function() {
        const password = document.getElementById('password');
        const icon = this.querySelector('i');
        
        if (password.type === 'password') {
            password.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            password.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    });
    
    // Password confirmation validation
    document.getElementById('confirm_password').addEventListener('input', function() {
        const password = document.getElementById('password').value;
        const confirmPassword = this.value;
        
        if (password !== confirmPassword) {
            this.setCustomValidity('Passwords do not match');
        } else {
            this.setCustomValidity('');
        }
    });
    
    // Form validation
    document.querySelector('.auth-form').addEventListener('submit', function(e) {
        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        const terms = document.getElementById('terms').checked;
        
        if (!name || !email || !password || !confirmPassword) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Please fill in all required fields.'
            });
            return;
        }
        
        if (password !== confirmPassword) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Password Mismatch',
                text: 'Passwords do not match. Please check and try again.'
            });
            return;
        }
        
        if (!terms) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Terms Required',
                text: 'Please accept the terms and conditions to continue.'
            });
            return;
        }
    });
";

include __DIR__ . '/../includes/footer.php';
?>
