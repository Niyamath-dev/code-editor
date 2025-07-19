<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Database Schema Fix Script</h2>";

try {
    // Connect to the database
    $pdo = new PDO('mysql:host=localhost;dbname=hcjcode_db;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connected to database<br>";
    
    // Check current table structure
    echo "<h3>Current Table Structure</h3>";
    $stmt = $pdo->query("DESCRIBE users");
    $columns = $stmt->fetchAll();
    $existingColumns = array_column($columns, 'Field');
    
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>" . $column['Field'] . "</td>";
        echo "<td>" . $column['Type'] . "</td>";
        echo "<td>" . $column['Null'] . "</td>";
        echo "<td>" . $column['Key'] . "</td>";
        echo "<td>" . $column['Default'] . "</td>";
        echo "</tr>";
    }
    echo "</table><br>";
    
    // Add missing columns
    $columnsToAdd = [
        'email_verified' => 'BOOLEAN DEFAULT FALSE',
        'reset_token' => 'VARCHAR(255) NULL',
        'reset_token_expires' => 'DATETIME NULL'
    ];
    
    echo "<h3>Adding Missing Columns</h3>";
    foreach ($columnsToAdd as $columnName => $columnDef) {
        if (!in_array($columnName, $existingColumns)) {
            $sql = "ALTER TABLE users ADD COLUMN $columnName $columnDef";
            $pdo->exec($sql);
            echo "✅ Added column: $columnName<br>";
        } else {
            echo "ℹ️ Column already exists: $columnName<br>";
        }
    }
    
    // Update existing users to have email_verified = TRUE
    echo "<h3>Updating Existing Users</h3>";
    $stmt = $pdo->exec("UPDATE users SET email_verified = TRUE WHERE email_verified IS NULL OR email_verified = FALSE");
    echo "✅ Updated $stmt users to have email_verified = TRUE<br>";
    
    // Insert admin user if not exists
    echo "<h3>Checking Admin User</h3>";
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $stmt->execute(['admin@hcjcode.com']);
    $adminExists = $stmt->fetchColumn();
    
    if ($adminExists == 0) {
        $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, email_verified) VALUES (?, ?, ?, ?)");
        $stmt->execute(['Admin User', 'admin@hcjcode.com', $hashedPassword, true]);
        echo "✅ Admin user created: admin@hcjcode.com / admin123<br>";
    } else {
        echo "ℹ️ Admin user already exists<br>";
    }
    
    // Show final table structure
    echo "<h3>Final Table Structure</h3>";
    $stmt = $pdo->query("DESCRIBE users");
    $columns = $stmt->fetchAll();
    
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>" . $column['Field'] . "</td>";
        echo "<td>" . $column['Type'] . "</td>";
        echo "<td>" . $column['Null'] . "</td>";
        echo "<td>" . $column['Key'] . "</td>";
        echo "<td>" . $column['Default'] . "</td>";
        echo "</tr>";
    }
    echo "</table><br>";
    
    // Show all users
    echo "<h3>All Users</h3>";
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
    echo "</table><br>";
    
    echo "<h3>✅ Database schema fixed successfully!</h3>";
    echo "<p><a href='auth/forgot-password.php'>Test Forgot Password</a></p>";
    echo "<p><a href='auth/login.php'>Test Login with admin@hcjcode.com / admin123</a></p>";
    
} catch (Exception $e) {
    echo "<h3>❌ Error: " . $e->getMessage() . "</h3>";
    echo "<p>Stack trace:</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>
