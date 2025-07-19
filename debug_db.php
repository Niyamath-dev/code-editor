<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Database Debug Script</h2>";

try {
    // Test basic MySQL connection
    echo "<h3>1. Testing MySQL Connection</h3>";
    $pdo = new PDO('mysql:host=localhost;charset=utf8mb4', 'root', '');
    echo "✅ MySQL connection successful<br>";
    
    // Check if database exists
    echo "<h3>2. Checking Database</h3>";
    $stmt = $pdo->query("SHOW DATABASES LIKE 'hcjcode_db'");
    $db = $stmt->fetch();
    if ($db) {
        echo "✅ Database 'hcjcode_db' exists<br>";
    } else {
        echo "❌ Database 'hcjcode_db' does not exist. Creating...<br>";
        $pdo->exec("CREATE DATABASE hcjcode_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        echo "✅ Database 'hcjcode_db' created<br>";
    }
    
    // Connect to the database
    echo "<h3>3. Connecting to Database</h3>";
    $pdo = new PDO('mysql:host=localhost;dbname=hcjcode_db;charset=utf8mb4', 'root', '');
    echo "✅ Connected to hcjcode_db<br>";
    
    // Check if users table exists
    echo "<h3>4. Checking Users Table</h3>";
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    $table = $stmt->fetch();
    if ($table) {
        echo "✅ Users table exists<br>";
    } else {
        echo "❌ Users table does not exist. Creating...<br>";
        $sql = "CREATE TABLE users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            email_verified BOOLEAN DEFAULT FALSE,
            reset_token VARCHAR(255) NULL,
            reset_token_expires DATETIME NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        $pdo->exec($sql);
        echo "✅ Users table created<br>";
    }
    
    // Check users count
    echo "<h3>5. Checking Users</h3>";
    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
    $count = $stmt->fetchColumn();
    echo "📊 Number of users: $count<br>";
    
    if ($count == 0) {
        echo "Creating test user...<br>";
        $hashedPassword = password_hash('password123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, email_verified) VALUES (?, ?, ?, ?)");
        $stmt->execute(['Test User', 'test@example.com', $hashedPassword, true]);
        echo "✅ Test user created: test@example.com / password123<br>";
    }
    
    // List all users
    echo "<h3>6. Current Users</h3>";
    $stmt = $pdo->query("SELECT id, name, email, email_verified FROM users");
    $users = $stmt->fetchAll();
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Verified</th></tr>";
    foreach ($users as $user) {
        echo "<tr>";
        echo "<td>" . $user['id'] . "</td>";
        echo "<td>" . $user['name'] . "</td>";
        echo "<td>" . $user['email'] . "</td>";
        echo "<td>" . ($user['email_verified'] ? 'Yes' : 'No') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<h3>✅ Database setup complete!</h3>";
    echo "<p><a href='auth/forgot-password.php'>Test Forgot Password</a></p>";
    echo "<p><a href='auth/login.php'>Test Login</a></p>";
    
} catch (Exception $e) {
    echo "<h3>❌ Error: " . $e->getMessage() . "</h3>";
    echo "<p>Stack trace:</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>
