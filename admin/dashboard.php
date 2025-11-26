<?php
require_once '../includes/config.php';
require_login();
require_admin();

$page_title = 'Admin Dashboard';

// Get statistics
$total_users = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'user'")->fetchColumn();
$total_products = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$total_orders = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$total_revenue = $pdo->query("SELECT SUM(total_amount) FROM orders WHERE status = 'completed'")->fetchColumn();

require_once '../includes/header.php';
?>

<div class="container" style="margin-top:70px">
    <div class="admin-layout">
        <?php include 'sidebar.php'; ?>
        
        <div class="admin-content">
            <div class="page-header">
                <h1><i class="fas fa-tachometer-alt"></i> Dashboard</h1>
                <p>Welcome back, <?php echo escape_output($_SESSION['username']); ?>!</p>
            </div>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon" style="background: #e690f5ff;">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-details">
                        <h3><?php echo $total_users; ?></h3>
                        <p>Total Users</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon" style="background: #749af1ff;">
                        <i class="fas fa-box"></i>
                    </div>
                    <div class="stat-details">
                        <h3><?php echo $total_products; ?></h3>
                        <p>Total Products</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon" style="background: #f23939ff;">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <div class="stat-details">
                        <h3><?php echo $total_orders; ?></h3>
                        <p>Total Orders</p>
                    </div>
                </div>
                
                <!-- <div class="stat-card">
                    <div class="stat-icon" style="background: #e74c3c;">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="stat-details">
                        <h3>$<?php echo number_format($total_revenue ?? 0, 2); ?></h3>
                        <p>Total Revenue</p>
                    </div>
                </div>
            </div>  -->
            
            <div class="dashboard-sections">
                <div class="dashboard-section">
                    <h2><i class="fas fa-box"></i> Recent Products</h2>
                    <?php
                    $stmt = $pdo->query("SELECT p.*, c.name as category_name FROM products p 
                                        LEFT JOIN categories c ON p.category_id = c.id 
                                        ORDER BY p.created_at DESC LIMIT 5");
                    $recent_products = $stmt->fetchAll();
                    ?>
                    <div class="table-responsive">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_products as $product): ?>
                                <tr>
                                    <td><?php echo escape_output($product['name']); ?></td>
                                    <td><?php echo escape_output($product['category_name']); ?></td>
                                    <td>$<?php echo number_format($product['price'], 2); ?></td>
                                    <td><?php echo $product['stock']; ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="dashboard-section">
                    <h2><i class="fas fa-shopping-bag"></i> Recent Orders</h2>
                    <?php
                    $stmt = $pdo->query("SELECT o.*, u.username FROM orders o 
                                        LEFT JOIN users u ON o.user_id = u.id 
                                        ORDER BY o.date DESC LIMIT 5");
                    $recent_orders = $stmt->fetchAll();
                    ?>
                    <div class="table-responsive">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_orders as $order): ?>
                                <tr>
                                    <td>#<?php echo $order['id']; ?></td>
                                    <td><?php echo escape_output($order['username']); ?></td>
                                    <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                                    <td><span class="badge badge-<?php echo $order['status']; ?>"><?php echo ucfirst($order['status']); ?></span></td>
                                    <td><?php echo date('M d, Y', strtotime($order['date'])); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
