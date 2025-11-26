<?php
require_once '../includes/config.php';
require_login();
require_admin();

$page_title = 'Manage Orders';

// Get all orders
$stmt = $pdo->query("SELECT o.*, u.username, u.email 
                    FROM orders o 
                    LEFT JOIN users u ON o.user_id = u.id 
                    ORDER BY o.date DESC");
$orders = $stmt->fetchAll();

require_once '../includes/header.php';
?>

<div class="container" style="margin-top:70px">
    <div class="admin-layout">
        <?php include 'sidebar.php'; ?>
        
        <div class="admin-content">
            <div class="page-header">
                <h1><i class="fas fa-shopping-bag"></i> Manage Orders</h1>
            </div>
            
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Email</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>#<?php echo $order['id']; ?></td>
                            <td><?php echo escape_output($order['username']); ?></td>
                            <td><?php echo escape_output($order['email']); ?></td>
                            <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                            <td>
                                <span class="badge badge-<?php echo $order['status']; ?>">
                                    <?php echo ucfirst($order['status']); ?>
                                </span>
                            </td>
                            <td><?php echo date('M d, Y H:i', strtotime($order['date'])); ?></td>
                            <td class="actions">
                                <a href="order_details.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
