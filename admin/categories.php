<?php
require_once '../includes/config.php';
require_login();
require_admin();

$page_title = 'Manage Categories';

// Handle category deletion
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    try {
        $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        set_message('Category deleted successfully', 'success');
    } catch (PDOException $e) {
        set_message('Cannot delete category with existing products', 'error');
    }
    header('Location: categories.php');
    exit();
}

// Handle add/edit
$errors = [];
$edit_mode = false;
$edit_category = null;

if (isset($_GET['edit'])) {
    $edit_mode = true;
    $id = (int)$_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([$id]);
    $edit_category = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize_input($_POST['name'] ?? '');
    $description = sanitize_input($_POST['description'] ?? '');
    
    if (empty($name)) {
        $errors[] = 'Category name is required';
    }
    
    if (empty($errors)) {
        try {
            if (isset($_POST['category_id']) && $_POST['category_id'] > 0) {
                // Update
                $id = (int)$_POST['category_id'];
                $stmt = $pdo->prepare("UPDATE categories SET name = ?, description = ? WHERE id = ?");
                $stmt->execute([$name, $description, $id]);
                set_message('Category updated successfully', 'success');
            } else {
                // Insert
                $stmt = $pdo->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
                $stmt->execute([$name, $description]);
                set_message('Category added successfully', 'success');
            }
            header('Location: categories.php');
            exit();
        } catch (PDOException $e) {
            $errors[] = 'Failed to save category';
        }
    }
}

// Get all categories
$categories = $pdo->query("SELECT c.*, COUNT(p.id) as product_count FROM categories c 
                          LEFT JOIN products p ON c.id = p.category_id 
                          GROUP BY c.id 
                          ORDER BY c.name")->fetchAll();

require_once '../includes/header.php';
?>

<div class="container">
    <div class="admin-layout">
        <?php include 'sidebar.php'; ?>
        
        <div class="admin-content">
            <div class="page-header">
                <h1><i class="fas fa-th-large"></i> Manage Categories</h1>
            </div>
            
            <div class="category-management">
                <div class="form-card">
                    <h2><?php echo $edit_mode ? 'Edit Category' : 'Add New Category'; ?></h2>
                    
                    <?php if (!empty($errors)): ?>
                    <div class="alert alert-error">
                        <?php foreach ($errors as $error): ?>
                            <p><?php echo escape_output($error); ?></p>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="categories.php<?php echo $edit_mode ? '?edit=' . $edit_category['id'] : ''; ?>">
                        <?php if ($edit_mode): ?>
                        <input type="hidden" name="category_id" value="<?php echo $edit_category['id']; ?>">
                        <?php endif; ?>
                        
                        <div class="form-group">
                            <label for="name">Category Name *</label>
                            <input type="text" id="name" name="name" 
                                   value="<?php echo $edit_mode ? escape_output($edit_category['name']) : ''; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" rows="3"><?php echo $edit_mode ? escape_output($edit_category['description']) : ''; ?></textarea>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> <?php echo $edit_mode ? 'Update' : 'Add'; ?> Category
                            </button>
                            <?php if ($edit_mode): ?>
                            <a href="categories.php" class="btn btn-secondary">Cancel</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
                
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Products</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categories as $category): ?>
                            <tr>
                                <td><?php echo $category['id']; ?></td>
                                <td><?php echo escape_output($category['name']); ?></td>
                                <td><?php echo escape_output($category['description']); ?></td>
                                <td><span class="badge badge-info"><?php echo $category['product_count']; ?></span></td>
                                <td class="actions">
                                    <a href="categories.php?edit=<?php echo $category['id']; ?>" class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="categories.php?delete=<?php echo $category['id']; ?>" 
                                       class="btn btn-sm btn-danger delete-btn"
                                       onclick="return confirm('Are you sure? This will delete all products in this category!');">
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
</div>

<?php require_once '../includes/footer.php'; ?>
