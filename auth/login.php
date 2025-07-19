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
    <div class="container-fluid">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-11 col-sm-9 col-md-7 col-lg-5 col-xl-4">
                <div class="card shadow-lg border-0 auth-card">
                    <!-- Card Header -->
                    <div class="card-header bg-transparent border-0 text-center py-4">
                        <div class="auth-logo mx-auto mb-3">
                            <i class="bi bi-code-slash"></i>
                        </div>
                        <h2 class="card-title h3 fw-bold text-primary mb-2">Welcome Back</h2>
                        <p class="text-muted mb-0">Sign in to your account to continue coding</p>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body px-4 px-sm-5 pb-5">
                        <?php if ($error): ?>
                            <div class="alert alert-danger d-flex align-items-center" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <div><?php echo htmlspecialchars($error); ?></div>
                            </div>
                        <?php endif; ?>

                        <form method="POST" class="needs-validation" novalidate>
                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                            
                            <!-- Email Field -->
                            <div class="mb-3">
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
                            </div>

                            <!-- Password Field -->
                            <div class="mb-3">
                                <label for="password" class="form-label fw-medium">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-lock text-muted"></i>
                                    </span>
                                    <input type="password" 
                                           class="form-control border-start-0 border-end-0 ps-0" 
                                           id="password" 
                                           name="password" 
                                           placeholder="Enter your password" 
                                           required>
                                    <button class="btn btn-outline-light border-start-0" 
                                            type="button" 
                                            id="togglePassword"
                                            title="Toggle password visibility">
                                        <i class="bi bi-eye text-muted"></i>
                                    </button>
                                    <div class="invalid-feedback">
                                        Please provide your password.
                                    </div>
                                </div>
                            </div>

                            <!-- Remember Me -->
                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                    <label class="form-check-label text-muted" for="remember">
                                        Remember me for 30 days
                                    </label>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid mb-4">
                                <button type="submit" class="btn btn-primary btn-lg fw-medium">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>
                                    Sign In
                                </button>
                            </div>

                            <!-- Links -->
                            <div class="text-center">
                                <div class="mb-2">
                                    <span class="text-muted">Don't have an account?</span>
                                    <a href="signup.php" class="text-decoration-none fw-medium ms-1">Create one here</a>
                                </div>
                                <div>
                                    <a href="forgot-password.php" class="text-muted text-decoration-none">
                                        <i class="bi bi-question-circle me-1"></i>Forgot your password?
                                    </a>
                                </div>
                            </div>
                        </form>
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
    
    // Bootstrap form validation
    document.querySelector('.needs-validation').addEventListener('submit', function(e) {
        const form = this;
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value;
        
        if (!form.checkValidity() || !email || !password) {
            e.preventDefault();
            e.stopPropagation();
            
            if (!email || !password) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Please fill in all required fields.'
                });
            }
        }
        
        form.classList.add('was-validated');
    });
";

include __DIR__ . '/../includes/footer.php';
?>
