<?php
require_once 'includes/config.php';

echo "<h2>Image Check</h2>";

// Check if default image exists
$default_image = 'uploads/default-product.jpg';
echo "<h3>Default Image Status:</h3>";
if (file_exists($default_image)) {
    echo "✅ Default image EXISTS at: " . realpath($default_image) . "<br>";
    echo "File size: " . filesize($default_image) . " bytes<br>";
    echo "Is readable: " . (is_readable($default_image) ? 'Yes' : 'No') . "<br>";
    echo "<img src='$default_image' style='max-width: 200px; margin: 10px 0;'><br>";
} else {
    echo "❌ Default image NOT FOUND at: $default_image<br>";
}

// Check products in database
echo "<h3>Products in Database:</h3>";
$stmt = $pdo->query("SELECT id, name, image FROM products LIMIT 10");
$products = $stmt->fetchAll();

echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
echo "<tr><th>ID</th><th>Product Name</th><th>Image Path</th><th>File Exists?</th><th>Preview</th></tr>";

foreach ($products as $product) {
    $image_path = 'uploads/' . $product['image'];
    $exists = file_exists($image_path);
    
    echo "<tr>";
    echo "<td>" . $product['id'] . "</td>";
    echo "<td>" . htmlspecialchars($product['name']) . "</td>";
    echo "<td>" . htmlspecialchars($product['image']) . "</td>";
    echo "<td>" . ($exists ? '✅ Yes' : '❌ No') . "</td>";
    echo "<td>";
    if ($exists) {
        echo "<img src='$image_path' style='max-width: 100px;'>";
    } else {
        echo "<img src='$default_image' style='max-width: 100px;' title='Using default'>";
    }
    echo "</td>";
    echo "</tr>";
}

echo "</table>";

echo "<br><h3>Upload Directory Info:</h3>";
echo "Upload directory: " . realpath('uploads') . "<br>";
echo "Is writable: " . (is_writable('uploads') ? 'Yes' : 'No') . "<br>";
echo "Files in uploads:<br>";
$files = scandir('uploads');
echo "<ul>";
foreach ($files as $file) {
    if ($file != '.' && $file != '..') {
        echo "<li>$file</li>";
    }
}
echo "</ul>";
?>
