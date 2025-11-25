<?php
require_once 'includes/config.php';
require_login();

$page_title = 'Checkout';
$user_id = get_user_id();

// Get cart items
$stmt = $pdo->prepare("SELECT c.*, p.name, p.price, p.stock 
                      FROM cart c 
                      LEFT JOIN products p ON c.product_id = p.id 
                      WHERE c.user_id = ?");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll();

if (empty($cart_items)) {
    header('Location: cart.php');
    exit();
}

// Calculate total
$total = 0;
foreach ($cart_items as $item) {
    $total += $item['price'] * $item['quantity'];
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate stock availability
    $stock_error = false;
    foreach ($cart_items as $item) {
        if ($item['quantity'] > $item['stock']) {
            $errors[] = $item['name'] . ' has insufficient stock';
            $stock_error = true;
        }
    }
    
    if (!$stock_error) {
        try {
            $pdo->beginTransaction();
            
            // Create order
            $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount, status) VALUES (?, ?, 'pending')");
            $stmt->execute([$user_id, $total]);
            $order_id = $pdo->lastInsertId();
            
            // Create order items and update stock
            foreach ($cart_items as $item) {
                $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
                $stmt->execute([$order_id, $item['product_id'], $item['quantity'], $item['price']]);
                
                $stmt = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
                $stmt->execute([$item['quantity'], $item['product_id']]);
            }
            
            // Clear cart
            $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
            $stmt->execute([$user_id]);
            
            $pdo->commit();
            
            set_message('Order placed successfully! Order ID: #' . $order_id, 'success');
            header('Location: profile.php');
            exit();
        } catch (PDOException $e) {
            $pdo->rollBack();
            $errors[] = 'Failed to place order. Please try again.';
        }
    }
}

require_once 'includes/header.php';
?>

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-lock"></i> Checkout</h1>
    </div>
    
    <?php if (!empty($errors)): ?>
    <div class="alert alert-error">
        <?php foreach ($errors as $error): ?>
            <p><?php echo escape_output($error); ?></p>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
    
    <div class="checkout-layout">
        <div class="checkout-items">
            <h2>Order Items</h2>
            <?php foreach ($cart_items as $item): ?>
            <div class="checkout-item">
                <div>
                    <h4><?php echo escape_output($item['name']); ?></h4>
                    <p>Quantity: <?php echo $item['quantity']; ?> × $<?php echo number_format($item['price'], 2); ?></p>
                </div>
                <p class="item-total">$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="checkout-summary">
            <h2>Order Summary</h2>
            <div class="summary-row">
                <span>Subtotal:</span>
                <span>$<?php echo number_format($total, 2); ?></span>
            </div>
            <div class="summary-row">
                <span>Delivery:</span>
                <span>Free</span>
            </div>
            <div class="summary-divider"></div>
            <div class="summary-row summary-total">
                <span>Total:</span>
                <span>$<?php echo number_format($total, 2); ?></span>
            </div>
            
            <form method="POST" action="checkout.php">
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-check"></i> Place Order
                </button>
            </form>
            <a href="cart.php" class="btn btn-secondary btn-block">
                <i class="fas fa-arrow-left"></i> Back to Cart
            </a>
            
            <div class="checkout-note">
                <p><i class="fas fa-info-circle"></i> This is a demo checkout. No actual payment will be processed.</p>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
