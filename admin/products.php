<?php
require_once '../includes/config.php';
require_login();
require_admin();

$page_title = 'Manage Products';

// Handle product deletion
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    try {
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$id]);
        set_message('Product deleted successfully', 'success');
    } catch (PDOException $e) {
        set_message('Failed to delete product', 'error');
    }
    header('Location: products.php');
    exit();
}

// Get all products
$stmt = $pdo->query("SELECT p.*, c.name as category_name FROM products p 
                     LEFT JOIN categories c ON p.category_id = c.id 
                     ORDER BY p.created_at DESC");
$products = $stmt->fetchAll();

require_once '../includes/header.php';
?>

<div class="container" style="margin-top:70px">
    <div class="admin-layout">
        <?php include 'sidebar.php'; ?>
        
        <div class="admin-content">
            <div class="page-header">
                <h1><i class="fas fa-box"></i> Manage Products</h1>
                <a href="product_add.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Product
                </a>
            </div>
            
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo $product['id']; ?></td>
                            <td>
                                <?php 
                                $image_path = '../uploads/' . $product['image'];
                                $image_src = (file_exists($image_path) && $product['image']) ? $image_path : '../uploads/default-product.jpg';
                                ?>
                                <img src="<?php echo $image_src; ?>" 
                                     alt="Product" 
                                     class="product-thumb"
                                     onerror="this.src='../uploads/default-product.jpg'">
                            </td>
                            <td><?php echo escape_output($product['name']); ?></td>
                            <td><?php echo escape_output($product['category_name']); ?></td>
                            <td>$<?php echo number_format($product['price'], 2); ?></td>
                            <td>
                                <span class="badge <?php echo $product['stock'] < 10 ? 'badge-error' : 'badge-success'; ?>">
                                    <?php echo $product['stock']; ?>
                                </span>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($product['created_at'])); ?></td>
                            <td class="actions">
                                <a href="product_edit.php?id=<?php echo $product['id']; ?>" class="btn btn-sm btn-info">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="products.php?delete=<?php echo $product['id']; ?>" 
                                   class="btn btn-sm btn-danger delete-btn"
                                   onclick="return confirm('Are you sure you want to delete this product?');">
                                    <i class="fas fa-trash"></i>
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
