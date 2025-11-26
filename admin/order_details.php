<?php
require_once '../includes/config.php';
require_login();
require_admin();

$page_title = 'Order Details';

$order_id = (int)($_GET['id'] ?? 0);

if ($order_id <= 0) {
    header('Location: orders.php');
    exit();
}

// Get order details
$stmt = $pdo->prepare("SELECT o.*, u.username, u.email FROM orders o 
                      LEFT JOIN users u ON o.user_id = u.id 
                      WHERE o.id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch();

if (!$order) {
    set_message('Order not found', 'error');
    header('Location: orders.php');
    exit();
}

// Get order items
$stmt = $pdo->prepare("SELECT oi.*, p.name, p.image FROM order_items oi 
                      LEFT JOIN products p ON oi.product_id = p.id 
                      WHERE oi.order_id = ?");
$stmt->execute([$order_id]);
$order_items = $stmt->fetchAll();

require_once '../includes/header.php';
?>

<div class="container" style = "margin-top:70px">
    <div class="admin-layout">
        <?php include 'sidebar.php'; ?>
        
        <div class="admin-content">
            <div class="page-header">
                <h1><i class="fas fa-shopping-bag"></i> Order #<?php echo $order_id; ?></h1>
                <a href="orders.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Orders
                </a>
            </div>
            
            <div class="order-details">
                <div class="order-info-card">
                    <h3>Order Information</h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <strong>Order ID:</strong>
                            <span>#<?php echo $order['id']; ?></span>
                        </div>
                        <div class="info-item">
                            <strong>Customer:</strong>
                            <span><?php echo escape_output($order['username']); ?></span>
                        </div>
                        <div class="info-item">
                            <strong>Email:</strong>
                            <span><?php echo escape_output($order['email']); ?></span>
                        </div>
                        <div class="info-item">
                            <strong>Status:</strong>
                            <span class="badge badge-<?php echo $order['status']; ?>">
                                <?php echo ucfirst($order['status']); ?>
                            </span>
                        </div>
                        <div class="info-item">
                            <strong>Date:</strong>
                            <span><?php echo date('M d, Y H:i:s', strtotime($order['date'])); ?></span>
                        </div>
                        <div class="info-item">
                            <strong>Total Amount:</strong>
                            <span class="total-amount">$<?php echo number_format($order['total_amount'], 2); ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="order-items-card">
                    <h3>Order Items</h3>
                    <div class="table-responsive">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Image</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($order_items as $item): ?>
                                <tr>
                                    <td><?php echo escape_output($item['name']); ?></td>
                                    <td>
                                        <?php 
                                        $image_path = '../uploads/' . $item['image'];
                                        $image_src = (file_exists($image_path) && $item['image']) ? $image_path : '../uploads/default-product.jpg';
                                        ?>
                                        <img src="<?php echo $image_src; ?>" 
                                             alt="Product" 
                                             class="product-thumb"
                                             onerror="this.src='../uploads/default-product.jpg'">
                                    </td>
                                    <td>$<?php echo number_format($item['price'], 2); ?></td>
                                    <td><?php echo $item['quantity']; ?></td>
                                    <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <tr class="total-row">
                                    <td colspan="4" class="text-right"><strong>Total:</strong></td>
                                    <td><strong>$<?php echo number_format($order['total_amount'], 2); ?></strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
