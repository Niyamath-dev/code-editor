<?php
// Database connection
$host = 'localhost';
$dbname = 'hcjcode_db'; // Use your existing database name
$username = 'root'; // Default username for phpMyAdmin
$password = ''; // Default password for phpMyAdmin

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate input
    if (empty($name) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            // Email already exists
            echo "<script>
                alert('You already signed up. Redirecting to login page...');
                setTimeout(function() {
                    window.location.href = 'login.html';
                }, 500);
            </script>";
            exit();
        } else {
            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert user into the database
            $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);

            try {
                $stmt->execute();
                header("Location: login.html"); // Redirect to login page
                exit();
            } catch (PDOException $e) {
                $error = "Error occurred during signup.";
            }
        }
    }
}
?>
