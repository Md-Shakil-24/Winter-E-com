<?php
require_once 'includes/config.php';

$page_title = 'Categories';

// Get all categories with product count
$categories = $pdo->query("SELECT c.*, COUNT(p.id) as product_count FROM categories c 
                          LEFT JOIN products p ON c.id = p.category_id 
                          GROUP BY c.id 
                          ORDER BY c.name")->fetchAll();

require_once 'includes/header.php';
?>

<div class="container">
    <section style="margin: 3rem 0;">
        <div style="text-align: center; margin-bottom: 3rem;">
            <h1 style="font-size: 2.5rem; margin-bottom: 1rem;">
                <i class="fas fa-th-large"></i> Shop by Category
            </h1>
            <p style="font-size: 1.125rem; color: var(--text-secondary);">
                Browse our exclusive collection of winter accessories
            </p>
        </div>
        
        <div class="grid grid-3">
            <?php foreach ($categories as $category): ?>
            <a href="category.php?id=<?php echo $category['id']; ?>" class="card" style="text-decoration: none; color: inherit; display: flex; flex-direction: column; gap: 1rem; padding: 2rem;">
                <div style="font-size: 2.5rem; text-align: center;">
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
                
                <div>
                    <h3 style="margin-bottom: 0.5rem;"><?php echo escape_output($category['name']); ?></h3>
                    <p style="color: var(--text-secondary); font-size: 0.875rem; line-height: 1.4; margin-bottom: 1rem;">
                        <?php echo escape_output($category['description']); ?>
                    </p>
                </div>
                
                <div style="margin-top: auto; padding-top: 1rem; border-top: 1px solid var(--border-color);">
                    <span style="color: var(--accent-primary); font-weight: 600;">
                        <i class="fas fa-box"></i> <?php echo $category['product_count']; ?> products
                    </span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </section>
</div>

<?php require_once 'includes/footer.php'; ?>
