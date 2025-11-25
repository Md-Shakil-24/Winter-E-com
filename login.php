<?php
require_once 'includes/config.php';

// Redirect if already logged in
if (is_logged_in()) {
    header('Location: ' . (is_admin() ? 'admin/dashboard.php' : 'index.php'));
    exit();
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize_input($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Validation
    if (empty($email)) {
        $errors[] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format';
    }
    
    if (empty($password)) {
        $errors[] = 'Password is required';
    }
    
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("SELECT id, username, password, role FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                
                set_message('Login successful! Welcome back, ' . $user['username'], 'success');
                
                if ($user['role'] === 'admin') {
                    header('Location: admin/dashboard.php');
                } else {
                    header('Location: index.php');
                }
                exit();
            } else {
                $errors[] = 'Invalid email or password';
            }
        } catch (PDOException $e) {
            $errors[] = 'Login failed. Please try again.';
        }
    }
}

$page_title = 'Login';
require_once 'includes/header.php';
?>

<div class="auth-wrapper">
    <div class="auth-container">
        <div class="auth-card login-card">
            <div class="auth-header">
                <div class="auth-icon">❄️</div>
                <h1>Welcome Back</h1>
                <p>Sign in to your winter-E-com account</p>
            </div>
            
            <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <div><i class="fas fa-exclamation-circle"></i> <?php echo escape_output($error); ?></div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            
            <form method="POST" action="login.php" class="auth-form">
                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> Email Address</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        placeholder="you@example.com"
                        required
                        value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                    />
                </div>
                
                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="Enter your password"
                        required
                    />
                </div>

                <div class="auth-options">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember" />
                        <span>Remember me</span>
                    </label>
                    <a href="#" class="forgot-link">Forgot password?</a>
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-sign-in-alt"></i> Sign In
                </button>
            </form>
            
            <div class="auth-divider">OR</div>
            
            <div class="auth-footer">
                <p>Don't have an account? <a href="register.php">Create one now</a></p>
            </div>

            <div class="demo-box">
                <h4><i class="fas fa-info-circle"></i> Demo Credentials</h4>
                <p>📧 Email: <strong>admin@winter-e-com.com</strong></p>
                <p>🔐 Password: <strong>12345678</strong></p>
            </div>
        </div>
        
        <div class="auth-side">
            <div class="auth-side-content">
                <h2>winter-E-com</h2>
                <p class="feature-list">
                    <span><i class="fas fa-check-circle"></i> Premium Winter Accessories</span>
                    <span><i class="fas fa-check-circle"></i> Fast & Free Shipping</span>
                    <span><i class="fas fa-check-circle"></i> 100% Secure</span>
                    <span><i class="fas fa-check-circle"></i> 24/7 Support</span>
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
