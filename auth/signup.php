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
    <div class="container-fluid">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-11 col-sm-9 col-md-7 col-lg-6 col-xl-5">
                <div class="card shadow-lg border-0 auth-card">
                    <!-- Card Header -->
                    <div class="card-header bg-transparent border-0 text-center py-4">
                        <div class="auth-logo mx-auto mb-3">
                            <i class="bi bi-person-plus"></i>
                        </div>
                        <h2 class="card-title h3 fw-bold text-primary mb-2">Create Account</h2>
                        <p class="text-muted mb-0">Join our coding community today</p>
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

                        <form method="POST" class="needs-validation" novalidate>
                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                            
                            <!-- Full Name Field -->
                            <div class="mb-3">
                                <label for="name" class="form-label fw-medium">Full Name</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-person text-muted"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control border-start-0 ps-0" 
                                           id="name" 
                                           name="name" 
                                           placeholder="Enter your full name" 
                                           value="<?php echo htmlspecialchars($name ?? ''); ?>" 
                                           required>
                                    <div class="invalid-feedback">
                                        Please provide your full name.
                                    </div>
                                </div>
                            </div>

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
                                           placeholder="Create a strong password" 
                                           minlength="8"
                                           required>
                                    <button class="btn btn-outline-light border-start-0" 
                                            type="button" 
                                            id="togglePassword"
                                            title="Toggle password visibility">
                                        <i class="bi bi-eye text-muted"></i>
                                    </button>
                                    <div class="invalid-feedback">
                                        Password must be at least 8 characters long.
                                    </div>
                                </div>
                                <small class="form-text text-muted">
                                    <i class="bi bi-info-circle me-1"></i>Password must be at least 8 characters long
                                </small>
                            </div>

                            <!-- Confirm Password Field -->
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label fw-medium">Confirm Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-lock-fill text-muted"></i>
                                    </span>
                                    <input type="password" 
                                           class="form-control border-start-0 ps-0" 
                                           id="confirm_password" 
                                           name="confirm_password" 
                                           placeholder="Confirm your password" 
                                           required>
                                    <div class="invalid-feedback">
                                        Please confirm your password.
                                    </div>
                                </div>
                            </div>

                            <!-- Terms and Conditions -->
                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                                    <label class="form-check-label text-muted" for="terms">
                                        I agree to the <a href="#" class="text-decoration-none">Terms of Service</a> and <a href="#" class="text-decoration-none">Privacy Policy</a>
                                    </label>
                                    <div class="invalid-feedback">
                                        You must accept the terms and conditions.
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid mb-4">
                                <button type="submit" class="btn btn-primary btn-lg fw-medium">
                                    <i class="bi bi-person-plus me-2"></i>
                                    Create Account
                                </button>
                            </div>

                            <!-- Links -->
                            <div class="text-center">
                                <div>
                                    <span class="text-muted">Already have an account?</span>
                                    <a href="login.php" class="text-decoration-none fw-medium ms-1">Sign in here</a>
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
    
    // Bootstrap form validation
    document.querySelector('.needs-validation').addEventListener('submit', function(e) {
        const form = this;
        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        const terms = document.getElementById('terms').checked;
        
        if (!form.checkValidity() || !name || !email || !password || !confirmPassword || !terms) {
            e.preventDefault();
            e.stopPropagation();
            
            if (!name || !email || !password || !confirmPassword) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Please fill in all required fields.'
                });
                return;
            }
            
            if (password !== confirmPassword) {
                Swal.fire({
                    icon: 'error',
                    title: 'Password Mismatch',
                    text: 'Passwords do not match. Please check and try again.'
                });
                return;
            }
            
            if (!terms) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Terms Required',
                    text: 'Please accept the terms and conditions to continue.'
                });
                return;
            }
        }
        
        form.classList.add('was-validated');
    });
";

include __DIR__ . '/../includes/footer.php';
?>
