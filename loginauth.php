<?php
// loginauth.php

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
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate input
    if (empty($email) || empty($password)) {
        $error = "All fields are required.";
    } else {
        // Fetch user from the database
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Login successful
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name']; // Store user's name in session
            header("Location: index.php"); // Redirect to dashboard
            exit();
        } else {
            // Invalid login credentials
            $error = "Invalid email or password.";
            header("Location: login.html?error=$error"); // Redirect to login with error message
            exit();
        }
    }
}
?>