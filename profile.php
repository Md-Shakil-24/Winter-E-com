<?php
require_once 'includes/config.php';
require_login();

$page_title = 'My Profile';
$user_id = get_user_id();

// Get user details
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Get user orders
$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY date DESC");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll();

$errors = [];

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $username = sanitize_input($_POST['username'] ?? '');
    $email = sanitize_input($_POST['email'] ?? '');
    
    if (empty($username)) {
        $errors[] = 'Username is required';
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Valid email is required';
    }
    
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
            $stmt->execute([$username, $email, $user_id]);
            set_message('Profile updated successfully', 'success');
            header('Location: profile.php');
            exit();
        } catch (PDOException $e) {
            $errors[] = 'Failed to update profile';
        }
    }
}

// Handle password change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    if (!password_verify($current_password, $user['password'])) {
        $errors[] = 'Current password is incorrect';
    }
    if (strlen($new_password) < 6) {
        $errors[] = 'New password must be at least 6 characters';
    }
    if ($new_password !== $confirm_password) {
        $errors[] = 'Passwords do not match';
    }
    
    if (empty($errors)) {
        try {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->execute([$hashed_password, $user_id]);
            set_message('Password changed successfully', 'success');
            header('Location: profile.php');
            exit();
        } catch (PDOException $e) {
            $errors[] = 'Failed to change password';
        }
    }
}

require_once 'includes/header.php';
?>

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-user"></i> My Profile</h1>
    </div>
    
    <?php if (!empty($errors)): ?>
    <div class="alert alert-error">
        <?php foreach ($errors as $error): ?>
            <p><?php echo escape_output($error); ?></p>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
    
    <div class="profile-layout">
        <div class="profile-sidebar">
            <div class="profile-card">
                <div class="profile-avatar">
                    <i class="fas fa-user-circle"></i>
                </div>
                <h3><?php echo escape_output($user['username']); ?></h3>
                <p><?php echo escape_output($user['email']); ?></p>
                <span class="badge badge-info"><?php echo ucfirst($user['role']); ?></span>
                <p class="profile-since">Member since <?php echo date('M Y', strtotime($user['created_at'])); ?></p>
            </div>
        </div>
        
        <div class="profile-content">
            <div class="profile-section">
                <h2>Edit Profile</h2>
                <form method="POST" action="profile.php" class="profile-form">
                    <input type="hidden" name="update_profile" value="1">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" 
                               value="<?php echo escape_output($user['username']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" 
                               value="<?php echo escape_output($user['email']); ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Profile
                    </button>
                </form>
            </div>
            
            <div class="profile-section">
                <h2>Change Password</h2>
                <form method="POST" action="profile.php" class="profile-form">
                    <input type="hidden" name="change_password" value="1">
                    <div class="form-group">
                        <label for="current_password">Current Password</label>
                        <input type="password" id="current_password" name="current_password" required>
                    </div>
                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" id="new_password" name="new_password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm New Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-key"></i> Change Password
                    </button>
                </form>
            </div>
            
            <div class="profile-section">
                <h2>My Orders</h2>
                <?php if (empty($orders)): ?>
                    <p class="no-orders">You haven't placed any orders yet.</p>
                    <a href="categories.php" class="btn btn-primary">Start Shopping</a>
                <?php else: ?>
                    <div class="orders-list">
                        <?php foreach ($orders as $order): ?>
                        <div class="order-item">
                            <div class="order-header">
                                <h4>Order #<?php echo $order['id']; ?></h4>
                                <span class="badge badge-<?php echo $order['status']; ?>">
                                    <?php echo ucfirst($order['status']); ?>
                                </span>
                            </div>
                            <p>Date: <?php echo date('M d, Y H:i', strtotime($order['date'])); ?></p>
                            <p class="order-total">Total: $<?php echo number_format($order['total_amount'], 2); ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
