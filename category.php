<?php
require_once 'includes/config.php';

$category_id = (int)($_GET['id'] ?? 0);

if ($category_id <= 0) {
    header('Location: categories.php');
    exit();
}

// Get category details
$stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
$stmt->execute([$category_id]);
$category = $stmt->fetch();

if (!$category) {
    header('Location: categories.php');
    exit();
}

$page_title = $category['name'];

// Get products in this category
$stmt = $pdo->prepare("SELECT * FROM products WHERE category_id = ? ORDER BY name");
$stmt->execute([$category_id]);
$products = $stmt->fetchAll();

// Get all categories for sidebar
$all_categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();

require_once 'includes/header.php';
?>

<section class="shop-section">
    <div class="container">
        <div class="shop-layout">
            <!-- Left Sidebar - Categories -->
            <aside class="shop-sidebar">
                <div class="sidebar-widget">
                    <h3><i class="fas fa-th-large"></i> Categories</h3>
                    <ul class="category-list">
                        <li><a href="index.php"><i class="fas fa-home"></i> All Products</a></li>
                        <?php foreach ($all_categories as $cat): ?>
                        <li>
                            <a href="category.php?id=<?php echo $cat['id']; ?>" 
                               class="<?php echo ($cat['id'] == $category_id) ? 'active' : ''; ?>">
                                <i class="fas fa-angle-right"></i> 
                                <?php echo escape_output($cat['name']); ?>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </aside>
            
            <!-- Right Content - Products -->
            <div class="shop-content">
                <div class="page-header">
                    <h1><?php echo escape_output($category['name']); ?></h1>
                    <p><?php echo escape_output($category['description']); ?></p>
                    <span class="product-count"><?php echo count($products); ?> products found</span>
                </div>
                
                <?php if (empty($products)): ?>
                    <div class="empty-state">
                        <i class="fas fa-box-open"></i>
                        <h3>No products available</h3>
                        <p>Check back later for new products in this category</p>
                    </div>
                <?php else: ?>
                    <div class="products-grid">
            <?php foreach ($products as $product): ?>
            <div class="product-card">
                <div class="product-image">
                    <?php 
                    $image_path = 'uploads/' . $product['image'];
                    $image_src = (file_exists($image_path) && $product['image']) ? $image_path : 'uploads/default-product.jpg';
                    ?>
                    <img src="<?php echo $image_src; ?>" 
                         alt="<?php echo escape_output($product['name']); ?>"
                         onerror="this.src='uploads/default-product.jpg'">
                    <?php if ($product['stock'] < 10): ?>
                        <span class="badge-low-stock">Low Stock</span>
                    <?php endif; ?>
                </div>
                <div class="product-info">
                    <span class="product-category"><?php echo escape_output($category['name']); ?></span>
                    <h3><?php echo escape_output($product['name']); ?></h3>
                    <p><?php echo escape_output($product['description']); ?></p>
                    <div class="product-footer">
                        <span class="product-price">$<?php echo number_format($product['price'], 2); ?></span>
                        <?php if (is_logged_in() && !is_admin()): ?>
                            <?php if ($product['stock'] > 0): ?>
                            <button class="btn btn-primary btn-sm add-to-cart-btn" 
                                    data-product-id="<?php echo $product['id']; ?>">
                                <i class="fas fa-cart-plus"></i> Add to Cart
                            </button>
                            <?php else: ?>
                            <button class="btn btn-secondary btn-sm" disabled>
                                Out of Stock
                            </button>
                            <?php endif; ?>
                        <?php else: ?>
                        <a href="login.php" class="btn btn-primary btn-sm">
                            <i class="fas fa-sign-in-alt"></i> Login to Buy
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
