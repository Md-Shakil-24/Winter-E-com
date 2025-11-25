<?php
require_once 'includes/config.php';

$page_title = 'Home';

// Get featured products (Seasonal Sale category)
$stmt = $pdo->query("SELECT p.*, c.name as category_name FROM products p 
                    LEFT JOIN categories c ON p.category_id = c.id 
                    WHERE c.name = 'Seasonal Sale' 
                    ORDER BY p.created_at DESC LIMIT 8");
$flash_sale_products = $stmt->fetchAll();

// Get all categories
$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();

// Get total products count
$total_products_result = $pdo->query("SELECT COUNT(*) as total FROM products");
$total_products_row = $total_products_result->fetch();
$total_products = $total_products_row['total'];

require_once 'includes/header.php';
?>

<section class="hero">
    <div id="heroSlider" class="hero-slider"></div>
</section>

<div class="container">
    <!-- Featured Products Section -->
    <section class="section">
        <h2 class="section-title">⭐ Featured Winter Collection</h2>
        <p class="section-subtitle">Handpicked products for the ultimate winter experience</p>
        
        <?php if (!empty($flash_sale_products)): ?>
        <div class="grid grid-4">
            <?php foreach ($flash_sale_products as $product): ?>
            <div class="product-card">
                <div class="product-image">
                    <?php 
                    $image_path = 'uploads/' . $product['image'];
                    $image_src = (file_exists($image_path) && $product['image']) ? $image_path : 'uploads/default-product.jpg';
                    ?>
                    <img src="<?php echo $image_src; ?>" 
                         alt="<?php echo escape_output($product['name']); ?>"
                         onerror="this.src='uploads/default-product.jpg'">
                </div>
                <div class="product-info">
                    <span class="product-category"><?php echo escape_output($product['category_name']); ?></span>
                    <h3 class="product-name"><?php echo escape_output($product['name']); ?></h3>
                    <p class="product-description"><?php echo escape_output(substr($product['description'], 0, 60)) . '...'; ?></p>
                    <div class="product-price">$<?php echo number_format($product['price'], 2); ?></div>
                    <div class="product-stock">Stock: <?php echo $product['stock']; ?> available</div>
                    
                    <?php if (is_logged_in() && !is_admin()): ?>
                    <button class="btn btn-primary" style="width: 100%; justify-content: center;"
                            onclick="addToCart(<?php echo $product['id']; ?>)">
                        <i class="fas fa-cart-plus"></i> Add to Cart
                    </button>
                    <?php elseif (!is_logged_in()): ?>
                    <a href="login.php" class="btn btn-secondary" style="width: 100%; text-align: center; justify-content: center;">
                        <i class="fas fa-sign-in-alt"></i> Login to Buy
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div style="text-align: center; padding: 4rem 2rem;">
            <i class="fas fa-box-open" style="font-size: 4rem; color: var(--text-tertiary); margin-bottom: 1rem;"></i>
            <h3>No products available</h3>
            <p>Check back later for new products</p>
        </div>
        <?php endif; ?>
    </section>

    <!-- Features Section -->
    <section class="section">
        <h2 class="section-title">✨ Why Choose winter-E-com?</h2>
        <p class="section-subtitle">We offer the best winter shopping experience</p>
        
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">🚚</div>
                <h3>Fast Shipping</h3>
                <p>Get your winter essentials delivered quickly to your doorstep with reliable shipping partners.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">💎</div>
                <h3>Premium Quality</h3>
                <p>All our products are carefully selected for superior quality and durability in cold weather.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">💰</div>
                <h3>Best Prices</h3>
                <p>Competitive pricing on all winter accessories without compromising on quality.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🛡️</div>
                <h3>Secure Shopping</h3>
                <p>Your personal and payment information is protected with the latest security technologies.</p>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="section">
        <h2 class="section-title">🛍️ Shop by Category</h2>
        <p class="section-subtitle">Browse our exclusive collection of winter accessories</p>
        
        <div class="grid grid-3">
            <?php foreach (array_slice($categories, 0, 6) as $category): ?>
            <a href="category.php?id=<?php echo $category['id']; ?>" class="card" style="text-align: center; padding: 2rem; text-decoration: none; color: inherit;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">
                    <?php
                    $icons = [
                        'Jackets & Coats' => '🧥',
                        'Scarves & Wraps' => '🧣',
                        'Gloves & Mittens' => '🧤',
                        'Winter Boots' => '👢',
                        'Hats & Beanies' => '🧢',
                        'Sweaters & Cardigans' => '🧶',
                        'Thermal & Base Layers' => '🏃',
                        'Winter Accessories' => '❄️',
                        'Snow Sports Gear' => '⛷️',
                        'Thermal Bags & Covers' => '🎒',
                        'Seasonal Sale' => '🎉'
                    ];
                    echo $icons[$category['name']] ?? '❄️';
                    ?>
                </div>
                <h3><?php echo escape_output($category['name']); ?></h3>
                <p style="color: var(--text-secondary); margin-top: 0.5rem;">
                    <?php echo escape_output(substr($category['description'], 0, 50)) . '...'; ?>
                </p>
            </a>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="section">
        <h2 class="section-title">⭐ Customer Reviews</h2>
        <p class="section-subtitle">See what our happy customers are saying</p>
        
        <div class="testimonials-grid">
            <div class="testimonial-card">
                <div class="testimonial-stars">⭐⭐⭐⭐⭐</div>
                <p class="testimonial-text">"Amazing quality jackets! Kept me warm all winter. Highly recommend winter-E-com!"</p>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">SK</div>
                    <div class="testimonial-info">
                        <h4>Sarah K.</h4>
                        <p>Verified Buyer</p>
                    </div>
                </div>
            </div>
            <div class="testimonial-card">
                <div class="testimonial-stars">⭐⭐⭐⭐⭐</div>
                <p class="testimonial-text">"Fast shipping and excellent customer service. The boots are exactly as described!"</p>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">JM</div>
                    <div class="testimonial-info">
                        <h4>James M.</h4>
                        <p>Verified Buyer</p>
                    </div>
                </div>
            </div>
            <div class="testimonial-card">
                <div class="testimonial-stars">⭐⭐⭐⭐⭐</div>
                <p class="testimonial-text">"Best winter accessories store online. Great prices and beautiful designs!"</p>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">EC</div>
                    <div class="testimonial-info">
                        <h4>Emily C.</h4>
                        <p>Verified Buyer</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="section">
        <div class="stats-section">
            <div class="stat-item">
                <h3>10K+</h3>
                <p>Happy Customers</p>
            </div>
            <div class="stat-item">
                <h3><?php echo $total_products; ?></h3>
                <p>Premium Products</p>
            </div>
            <div class="stat-item">
                <h3>100%</h3>
                <p>Satisfaction Rate</p>
            </div>
            <div class="stat-item">
                <h3>24/7</h3>
                <p>Customer Support</p>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="newsletter-section">
        <h2>❄️ Stay Updated with Winter Deals</h2>
        <p>Subscribe to our newsletter and get exclusive offers on winter accessories</p>
        <div class="newsletter-input-group">
            <input type="email" placeholder="Enter your email" required>
            <button><i class="fas fa-paper-plane"></i> Subscribe</button>
        </div>
    </section>
</div>

<?php require_once 'includes/footer.php'; ?>
