<?php
// Test Database Connection and Admin User

echo "<h1>GroceryGo Database Test</h1>";

// Database credentials
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'grocerygo';

echo "<h2>1. Testing Database Connection...</h2>";

try {
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ MySQL Connection: <strong style='color: green;'>SUCCESS</strong><br>";
    
    // Check if database exists
    echo "<h2>2. Checking if 'grocerygo' database exists...</h2>";
    $stmt = $pdo->query("SHOW DATABASES LIKE 'grocerygo'");
    $dbExists = $stmt->fetch();
    
    if ($dbExists) {
        echo "✅ Database 'grocerygo': <strong style='color: green;'>EXISTS</strong><br>";
        
        // Connect to grocerygo database
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Check if users table exists
        echo "<h2>3. Checking if 'users' table exists...</h2>";
        $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
        $tableExists = $stmt->fetch();
        
        if ($tableExists) {
            echo "✅ Table 'users': <strong style='color: green;'>EXISTS</strong><br>";
            
            // Check for admin user
            echo "<h2>4. Checking for admin user...</h2>";
            $stmt = $pdo->query("SELECT id, username, email, role FROM users WHERE email = 'admin@grocerygo.com'");
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($admin) {
                echo "✅ Admin user: <strong style='color: green;'>FOUND</strong><br>";
                echo "<pre>";
                echo "ID: " . $admin['id'] . "\n";
                echo "Username: " . $admin['username'] . "\n";
                echo "Email: " . $admin['email'] . "\n";
                echo "Role: " . $admin['role'] . "\n";
                echo "</pre>";
                
                // Test password verification
                echo "<h2>5. Testing password verification...</h2>";
                $stmt = $pdo->query("SELECT password FROM users WHERE email = 'admin@grocerygo.com'");
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $storedHash = $result['password'];
                
                echo "Stored hash: <code>" . substr($storedHash, 0, 30) . "...</code><br>";
                
                $testPassword = 'admin123';
                if (password_verify($testPassword, $storedHash)) {
                    echo "✅ Password verification: <strong style='color: green;'>SUCCESS</strong><br>";
                    echo "Password 'admin123' is correct!<br>";
                } else {
                    echo "❌ Password verification: <strong style='color: red;'>FAILED</strong><br>";
                    echo "The stored password hash doesn't match 'admin123'<br>";
                    
                    // Create new hash
                    echo "<h3>Fix: New password hash for 'admin123':</h3>";
                    $newHash = password_hash('admin123', PASSWORD_DEFAULT);
                    echo "<code>$newHash</code><br>";
                    echo "<p>Run this SQL to fix:</p>";
                    echo "<pre>UPDATE users SET password = '$newHash' WHERE email = 'admin@grocerygo.com';</pre>";
                }
                
            } else {
                echo "❌ Admin user: <strong style='color: red;'>NOT FOUND</strong><br>";
                echo "<p>The admin user doesn't exist. Please import database.sql</p>";
                echo "<h3>To fix this:</h3>";
                echo "<ol>";
                echo "<li>Go to <a href='http://localhost/phpmyadmin'>phpMyAdmin</a></li>";
                echo "<li>Select 'grocerygo' database</li>";
                echo "<li>Click 'Import' tab</li>";
                echo "<li>Choose 'database.sql' file</li>";
                echo "<li>Click 'Go'</li>";
                echo "</ol>";
            }
            
        } else {
            echo "❌ Table 'users': <strong style='color: red;'>NOT FOUND</strong><br>";
            echo "<p>The database exists but tables are missing. Please import database.sql</p>";
        }
        
    } else {
        echo "❌ Database 'grocerygo': <strong style='color: red;'>NOT FOUND</strong><br>";
        echo "<h3>To fix this:</h3>";
        echo "<ol>";
        echo "<li>Go to <a href='http://localhost/phpmyadmin'>phpMyAdmin</a></li>";
        echo "<li>Click 'New' to create database</li>";
        echo "<li>Name it 'grocerygo'</li>";
        echo "<li>Click 'Create'</li>";
        echo "<li>Then import database.sql</li>";
        echo "</ol>";
    }
    
} catch (PDOException $e) {
    echo "❌ Error: <strong style='color: red;'>" . $e->getMessage() . "</strong><br>";
    echo "<h3>Common issues:</h3>";
    echo "<ul>";
    echo "<li>Make sure MySQL is running in XAMPP Control Panel</li>";
    echo "<li>Check if Apache and MySQL are started (green status)</li>";
    echo "<li>Default MySQL credentials: username='root', password='' (empty)</li>";
    echo "</ul>";
}

echo "<hr>";
echo "<h2>Quick Links:</h2>";
echo "<ul>";
echo "<li><a href='http://localhost/phpmyadmin'>phpMyAdmin</a></li>";
echo "<li><a href='login.php'>Login Page</a></li>";
echo "<li><a href='index.php'>Homepage</a></li>";
echo "</ul>";
?>
