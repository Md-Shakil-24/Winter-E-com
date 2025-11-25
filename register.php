<?php
require_once 'includes/config.php';

// Redirect if already logged in
if (is_logged_in()) {
    header('Location: ' . (is_admin() ? 'admin/dashboard.php' : 'index.php'));
    exit();
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize_input($_POST['username'] ?? '');
    $email = sanitize_input($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validation
    if (empty($username)) {
        $errors[] = 'Username is required';
    } elseif (strlen($username) < 3) {
        $errors[] = 'Username must be at least 3 characters';
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $errors[] = 'Username can only contain letters, numbers, and underscores';
    }
    
    if (empty($email)) {
        $errors[] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format';
    }
    
    if (empty($password)) {
        $errors[] = 'Password is required';
    } elseif (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters';
    }
    
    if ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match';
    }
    
    if (empty($errors)) {
        try {
            // Check if username or email already exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);
            
            if ($stmt->fetch()) {
                $errors[] = 'Username or email already exists';
            } else {
                // Hash password and insert user
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'user')");
                $stmt->execute([$username, $email, $hashed_password]);
                
                set_message('Registration successful! Please login.', 'success');
                header('Location: login.php');
                exit();
            }
        } catch (PDOException $e) {
            $errors[] = 'Registration failed. Please try again.';
        }
    }
}

$page_title = 'Register';
require_once 'includes/header.php';
?>

<div class="auth-wrapper">
    <div class="auth-container">
        <div class="auth-card register-card">
            <div class="auth-header">
                <div class="auth-icon">🎉</div>
                <h1>Create Account</h1>
                <p>Join winter-E-com and start shopping</p>
            </div>
            
            <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <div><i class="fas fa-exclamation-circle"></i> <?php echo escape_output($error); ?></div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            
            <form method="POST" action="register.php" class="auth-form" id="registerForm">
                <div class="form-group">
                    <label for="username">
                        <i class="fas fa-user"></i> Username
                    </label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        placeholder="Choose a username (3+ characters)"
                        value="<?php echo isset($username) ? escape_output($username) : ''; ?>"
                        required
                    />
                    <small>Letters, numbers, and underscores only</small>
                </div>
                
                <div class="form-group">
                    <label for="email">
                        <i class="fas fa-envelope"></i> Email Address
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        placeholder="your@email.com"
                        value="<?php echo isset($email) ? escape_output($email) : ''; ?>"
                        required
                    />
                </div>
                
                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-lock"></i> Password
                    </label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="Create a strong password (min. 6 characters)"
                        required
                    />
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">
                        <i class="fas fa-lock"></i> Confirm Password
                    </label>
                    <input 
                        type="password" 
                        id="confirm_password" 
                        name="confirm_password" 
                        placeholder="Confirm your password"
                        required
                    />
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-user-plus"></i> Create Account
                </button>
            </form>
            
            <div class="auth-divider">OR</div>
            
            <div class="auth-footer">
                <p>Already have an account? <a href="login.php">Sign in instead</a></p>
            </div>
        </div>
        
        <div class="auth-side">
            <div class="auth-side-content">
                <h2>Why Join Us?</h2>
                <div class="benefits-list">
                    <div class="benefit">
                        <i class="fas fa-shopping-bag"></i>
                        <p><strong>Exclusive Deals</strong><br>Access special offers only for members</p>
                    </div>
                    <div class="benefit">
                        <i class="fas fa-shipping-fast"></i>
                        <p><strong>Fast Shipping</strong><br>Get your orders delivered quickly</p>
                    </div>
                    <div class="benefit">
                        <i class="fas fa-heart"></i>
                        <p><strong>Wishlist</strong><br>Save favorite items for later</p>
                    </div>
                    <div class="benefit">
                        <i class="fas fa-history"></i>
                        <p><strong>Order History</strong><br>Track all your purchases</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
