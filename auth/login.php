<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';

// Redirect if already logged in
if (isLoggedIn()) {
    header('Location: ../index.php');
    exit();
}

$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
        $error = 'Invalid request. Please try again.';
    } else {
        $email = sanitizeInput($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validate input
        if (empty($email) || empty($password)) {
            $error = 'All fields are required.';
        } elseif (!validateEmail($email)) {
            $error = 'Please enter a valid email address.';
        } else {
            try {
                $db = Database::getInstance()->getConnection();
                
                // Fetch user from the database
                $stmt = $db->prepare("SELECT id, name, email, password FROM users WHERE email = :email");
                $stmt->bindParam(':email', $email);
                $stmt->execute();
                $user = $stmt->fetch();

                if ($user && password_verify($password, $user['password'])) {
                    // Login successful
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['login_time'] = time();
                    
                    // Regenerate session ID for security
                    session_regenerate_id(true);
                    
                    redirectWithMessage('../index.php', 'Welcome back, ' . $user['name'] . '!', 'success');
                } else {
                    $error = 'Invalid email or password.';
                }
            } catch (Exception $e) {
                error_log("Login error: " . $e->getMessage());
                $error = 'An error occurred. Please try again later.';
            }
        }
    }
}

$page_title = 'Login';
include __DIR__ . '/../includes/header.php';
?>

<div class="auth-container">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6 col-lg-5">
                <div class="auth-card">
                    <div class="auth-header">
                        <div class="auth-logo">
                            <i class="bi bi-code-slash"></i>
                        </div>
                        <h2 class="auth-title">Welcome Back</h2>
                        <p class="auth-subtitle">Sign in to your account to continue coding</p>
                    </div>

                    <?php if ($error): ?>
                        <div class="alert alert-danger" role="alert">
                            <i class="bi bi-exclamation-triangle"></i>
                            <?php echo htmlspecialchars($error); ?>
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
                                       placeholder="Enter your email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input type="password" class="form-control" id="password" name="password" 
                                       placeholder="Enter your password" required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                <label class="form-check-label" for="remember">
                                    Remember me
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-auth">
                            <i class="bi bi-box-arrow-in-right"></i>
                            Sign In
                        </button>
                    </form>

                    <div class="auth-footer">
                        <p class="auth-link">
                            Don't have an account? 
                            <a href="signup.php" class="link-primary">Create one here</a>
                        </p>
                        <p class="auth-link">
                            <a href="forgot-password.php" class="link-secondary">Forgot your password?</a>
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
    
    // Form validation
    document.querySelector('.auth-form').addEventListener('submit', function(e) {
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value;
        
        if (!email || !password) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Please fill in all required fields.'
            });
        }
    });
";

include __DIR__ . '/../includes/footer.php';
?>
