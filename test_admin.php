<?php
// Test admin login
require_once 'includes/config.php';

$email = 'admin@winter-e-com.com';
$password = '12345678';

try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        echo "<h2>✅ User Found!</h2>";
        echo "<p><strong>Email:</strong> " . htmlspecialchars($user['email']) . "</p>";
        echo "<p><strong>ID:</strong> " . htmlspecialchars($user['id']) . "</p>";
        echo "<p><strong>Password Hash:</strong> " . htmlspecialchars($user['password']) . "</p>";
        
        // Test password
        if (password_verify($password, $user['password'])) {
            echo "<h3 style='color:green;'>✅ Password Verified!</h3>";
        } else {
            echo "<h3 style='color:red;'>❌ Password Verification Failed!</h3>";
            echo "<p>Password entered: <code>12345678</code></p>";
            echo "<p>Testing password hash generation...</p>";
            $test_hash = password_hash('12345678', PASSWORD_DEFAULT);
            echo "<p>Generated hash: <code>" . htmlspecialchars($test_hash) . "</code></p>";
        }
    } else {
        echo "<h2>❌ User Not Found!</h2>";
        echo "<p>Email: " . htmlspecialchars($email) . " does not exist in database.</p>";
        
        // List all users
        $users = $pdo->query("SELECT id, email, role FROM users")->fetchAll();
        echo "<h3>All users in database:</h3>";
        if ($users) {
            echo "<ul>";
            foreach ($users as $u) {
                echo "<li>ID: {$u['id']}, Email: {$u['email']}, Role: {$u['role']}</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No users found in database.</p>";
        }
    }
} catch (PDOException $e) {
    echo "<h2>❌ Database Error</h2>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
