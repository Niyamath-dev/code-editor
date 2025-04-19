<?php
// Database connection
$host = 'localhost';
$dbname = 'hcjcode_db';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    if (empty($email)) {
        $error = "Email is required.";
    } else {
        // Check if email exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // For simplicity, generate a simple token (base64 encoded email)
            $token = base64_encode($email);
            // Instead of sending reset link, redirect directly to reset_password.php with email token
            echo "<script>
                alert('Email verified. Please reset your password now.');
                window.location.href = 'reset_password.php?token=" . urlencode($token) . "';
            </script>";
            exit();
        } else {
            $error = "Email not found.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Forgot Password</title>
  <link rel="stylesheet" href="bootstrap-5.3.3-dist/css/bootstrap.min.css" />
</head>
<body>
  <div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4 shadow-lg" style="width: 100%; max-width: 400px;">
      <h3 class="text-center mb-4">Forgot Password</h3>
      <?php if (!empty($error)) : ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
      <?php endif; ?>
      <form action="forgot_password.php" method="POST">
        <div class="mb-3">
          <label for="email" class="form-label">Enter your registered email</label>
          <input type="email" class="form-control" id="email" name="email" placeholder="Email" required />
        </div>
        <div class="d-grid gap-2">
          <button type="submit" class="btn btn-primary">Send Reset Link</button>
        </div>
        <p class="text-center mt-3"><a href="login.html">Back to Login</a></p>
      </form>
    </div>
  </div>
  <script src="bootstrap-5.3.3-dist/js/bootstrap.min.js"></script>
</body>
</html>
