<?php
require_once '../includes/config.php';
require_login();
require_admin();

$page_title = 'Manage Users';

// Get all users
$stmt = $pdo->query("SELECT u.*, 
                    (SELECT COUNT(*) FROM orders WHERE user_id = u.id) as order_count,
                    (SELECT SUM(total_amount) FROM orders WHERE user_id = u.id AND status = 'completed') as total_spent
                    FROM users u 
                    ORDER BY u.created_at DESC");
$users = $stmt->fetchAll();

require_once '../includes/header.php';
?>

<div class="container">
    <div class="admin-layout">
        <?php include 'sidebar.php'; ?>
        
        <div class="admin-content">
            <div class="page-header">
                <h1><i class="fas fa-users"></i> Manage Users</h1>
            </div>
            
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Orders</th>
                            <th>Total Spent</th>
                            <th>Registered</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo escape_output($user['username']); ?></td>
                            <td><?php echo escape_output($user['email']); ?></td>
                            <td>
                                <span class="badge badge-<?php echo $user['role'] === 'admin' ? 'error' : 'info'; ?>">
                                    <?php echo ucfirst($user['role']); ?>
                                </span>
                            </td>
                            <td><?php echo $user['order_count']; ?></td>
                            <td>$<?php echo number_format($user['total_spent'] ?? 0, 2); ?></td>
                            <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
