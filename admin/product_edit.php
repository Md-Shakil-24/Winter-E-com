<?php
require_once '../includes/config.php';
require_login();
require_admin();

$page_title = 'Edit Product';
$errors = [];

// Get product ID
$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    header('Location: products.php');
    exit();
}

// Get product details
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    set_message('Product not found', 'error');
    header('Location: products.php');
    exit();
}

// Get categories
$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize_input($_POST['name'] ?? '');
    $category_id = (int)($_POST['category_id'] ?? 0);
    $price = floatval($_POST['price'] ?? 0);
    $description = sanitize_input($_POST['description'] ?? '');
    $stock = (int)($_POST['stock'] ?? 0);
    $image = $product['image'];
    
    // Validation
    if (empty($name)) {
        $errors[] = 'Product name is required';
    }
    if ($category_id <= 0) {
        $errors[] = 'Please select a category';
    }
    if ($price <= 0) {
        $errors[] = 'Price must be greater than 0';
    }
    if ($stock < 0) {
        $errors[] = 'Stock cannot be negative';
    }
    
    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['image']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            $new_filename = uniqid() . '.' . $ext;
            $upload_path = '../uploads/' . $new_filename;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                // Delete old image if not default
                if ($product['image'] !== 'default-product.jpg' && file_exists('../uploads/' . $product['image'])) {
                    unlink('../uploads/' . $product['image']);
                }
                $image = $new_filename;
            }
        } else {
            $errors[] = 'Invalid image format. Allowed: jpg, jpeg, png, gif';
        }
    }
    
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("UPDATE products SET category_id = ?, name = ?, price = ?, description = ?, stock = ?, image = ? WHERE id = ?");
            $stmt->execute([$category_id, $name, $price, $description, $stock, $image, $id]);
            
            set_message('Product updated successfully', 'success');
            header('Location: products.php');
            exit();
        } catch (PDOException $e) {
            $errors[] = 'Failed to update product';
        }
    }
}

require_once '../includes/header.php';
?>

<div class="container">
    <div class="admin-layout">
        <?php include 'sidebar.php'; ?>
        
        <div class="admin-content">
            <div class="page-header">
                <h1><i class="fas fa-edit"></i> Edit Product</h1>
                <a href="products.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Products
                </a>
            </div>
            
            <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo escape_output($error); ?></p>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            
            <div class="form-card">
                <form method="POST" action="product_edit.php?id=<?php echo $id; ?>" enctype="multipart/form-data" id="productForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Product Name *</label>
                            <input type="text" id="name" name="name" 
                                   value="<?php echo escape_output($product['name']); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="category_id">Category *</label>
                            <select id="category_id" name="category_id" required>
                                <option value="">Select Category</option>
                                <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>" 
                                        <?php echo $product['category_id'] == $category['id'] ? 'selected' : ''; ?>>
                                    <?php echo escape_output($category['name']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="price">Price ($) *</label>
                            <input type="number" id="price" name="price" step="0.01" min="0.01"
                                   value="<?php echo $product['price']; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="stock">Stock Quantity *</label>
                            <input type="number" id="stock" name="stock" min="0"
                                   value="<?php echo $product['stock']; ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="4"><?php echo escape_output($product['description']); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="image">Product Image</label>
                        <div class="current-image">
                            <?php 
                            $image_path = '../uploads/' . $product['image'];
                            $image_src = (file_exists($image_path) && $product['image']) ? $image_path : '../uploads/default-product.jpg';
                            ?>
                            <img src="<?php echo $image_src; ?>" 
                                 alt="Current product image" 
                                 style="max-width: 150px;"
                                 onerror="this.src='../uploads/default-product.jpg'">
                        </div>
                        <input type="file" id="image" name="image" accept="image/*">
                        <small>Leave empty to keep current image. Allowed formats: JPG, JPEG, PNG, GIF</small>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Product
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
