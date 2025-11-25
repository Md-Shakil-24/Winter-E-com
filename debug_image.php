<?php
require_once 'includes/config.php';

echo "<h2>Latest Product with Image</h2>";

// Get the most recent product
$stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC LIMIT 1");
$product = $stmt->fetch();

echo "<h3>Product Details:</h3>";
echo "ID: " . $product['id'] . "<br>";
echo "Name: " . htmlspecialchars($product['name']) . "<br>";
echo "Image field in DB: <strong>" . htmlspecialchars($product['image']) . "</strong><br>";
echo "<br>";

echo "<h3>File Check:</h3>";
$file_path = 'uploads/' . $product['image'];
echo "Looking for: $file_path<br>";
echo "File exists: " . (file_exists($file_path) ? '✅ YES' : '❌ NO') . "<br>";

if (file_exists($file_path)) {
    echo "File size: " . filesize($file_path) . " bytes<br>";
    echo "Is readable: " . (is_readable($file_path) ? 'YES' : 'NO') . "<br>";
    echo "<br><h3>Image Preview:</h3>";
    echo "<img src='$file_path' style='max-width: 300px; border: 2px solid #ccc; padding: 5px;'><br>";
} else {
    echo "<br><strong style='color: red;'>Image file not found at: " . realpath('uploads') . "\\" . $product['image'] . "</strong><br>";
}

echo "<br><h3>All Files in Uploads Folder:</h3>";
$files = scandir('uploads');
echo "<ul>";
foreach ($files as $file) {
    if ($file != '.' && $file != '..') {
        echo "<li>" . $file . " (" . filesize('uploads/' . $file) . " bytes)</li>";
    }
}
echo "</ul>";

echo "<br><h3>Testing Image Display (3 methods):</h3>";
echo "<div style='display: flex; gap: 20px;'>";

echo "<div>";
echo "<h4>Method 1: Direct path</h4>";
echo "<img src='uploads/" . $product['image'] . "' style='max-width: 200px; border: 2px solid red;' onerror='this.alt=\"FAILED\"'>";
echo "</div>";

echo "<div>";
echo "<h4>Method 2: Full URL</h4>";
echo "<img src='http://localhost/Project/uploads/" . $product['image'] . "' style='max-width: 200px; border: 2px solid green;' onerror='this.alt=\"FAILED\"'>";
echo "</div>";

echo "<div>";
echo "<h4>Method 3: With check</h4>";
$image_src = file_exists($file_path) ? $file_path : 'uploads/default-product.jpg';
echo "<img src='$image_src' style='max-width: 200px; border: 2px solid blue;'>";
echo "</div>";

echo "</div>";
?>
