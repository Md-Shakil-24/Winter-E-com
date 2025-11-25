<?php
require_once 'includes/config.php';
require_login();

$page_title = 'My Cart';
$user_id = get_user_id();

// Get cart items
$stmt = $pdo->prepare("SELECT c.*, p.name, p.price, p.image, p.stock 
                      FROM cart c 
                      LEFT JOIN products p ON c.product_id = p.id 
                      WHERE c.user_id = ?");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll();

// Calculate total
$total = 0;
foreach ($cart_items as $item) {
    $total += $item['price'] * $item['quantity'];
}

require_once 'includes/header.php';
?>

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-shopping-cart"></i> My Cart</h1>
    </div>
    
    <?php if (empty($cart_items)): ?>
        <div class="empty-state">
            <i class="fas fa-shopping-cart"></i>
            <h3>Your cart is empty</h3>
            <p>Add some products to your cart to get started</p>
            <a href="categories.php" class="btn btn-primary">Start Shopping</a>
        </div>
    <?php else: ?>
        <div class="cart-layout">
            <div class="cart-items">
                <?php foreach ($cart_items as $item): ?>
                <div class="cart-item">
                    <?php 
                    $image_path = 'uploads/' . $item['image'];
                    $image_src = (file_exists($image_path) && $item['image']) ? $image_path : 'uploads/default-product.jpg';
                    ?>
                    <img src="<?php echo $image_src; ?>" 
                         alt="<?php echo escape_output($item['name']); ?>"
                         onerror="this.src='uploads/default-product.jpg'">
                    <div class="cart-item-details">
                        <h3><?php echo escape_output($item['name']); ?></h3>
                        <p class="cart-item-price">$<?php echo number_format($item['price'], 2); ?> each</p>
                        <p class="cart-item-stock">Stock: <?php echo $item['stock']; ?> available</p>
                    </div>
                    <div class="cart-item-quantity">
                        <button class="qty-btn" onclick="updateQuantity(<?php echo $item['product_id']; ?>, -1)">-</button>
                        <input type="number" 
                               value="<?php echo $item['quantity']; ?>" 
                               min="1" 
                               max="<?php echo $item['stock']; ?>"
                               class="qty-input"
                               id="qty-<?php echo $item['product_id']; ?>"
                               onchange="updateQuantityInput(<?php echo $item['product_id']; ?>)">
                        <button class="qty-btn" onclick="updateQuantity(<?php echo $item['product_id']; ?>, 1)">+</button>
                    </div>
                    <div class="cart-item-total">
                        <p class="item-subtotal">$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></p>
                        <button class="btn btn-danger btn-sm remove-from-cart-btn" 
                                data-product-id="<?php echo $item['product_id']; ?>">
                            <i class="fas fa-trash"></i> Remove
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div class="cart-summary">
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
                <a href="checkout.php" class="btn btn-primary btn-block">
                    <i class="fas fa-lock"></i> Proceed to Checkout
                </a>
                <a href="categories.php" class="btn btn-secondary btn-block">
                    <i class="fas fa-arrow-left"></i> Continue Shopping
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
