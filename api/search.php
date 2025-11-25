<?php
require_once '../includes/config.php';

header('Content-Type: application/json');

$query = sanitize_input($_GET['q'] ?? '');

if (empty($query)) {
    echo json_encode([]);
    exit();
}

try {
    // Search for products that start with the query or contain it
    $search_start = $query . '%';
    $search_contain = '%' . $query . '%';
    
    $stmt = $pdo->prepare("SELECT p.*, c.name as category_name,
                          CASE 
                            WHEN LOWER(p.name) LIKE LOWER(?) THEN 1
                            WHEN LOWER(p.name) LIKE LOWER(?) THEN 2
                            WHEN LOWER(c.name) LIKE LOWER(?) THEN 3
                            ELSE 4
                          END as relevance
                          FROM products p 
                          LEFT JOIN categories c ON p.category_id = c.id 
                          WHERE LOWER(p.name) LIKE LOWER(?) 
                          OR LOWER(p.description) LIKE LOWER(?) 
                          OR LOWER(c.name) LIKE LOWER(?)
                          ORDER BY relevance, p.name 
                          LIMIT 10");
    $stmt->execute([$search_start, $search_contain, $search_start, $search_contain, $search_contain, $search_contain]);
    $results = $stmt->fetchAll();
    
    $output = [];
    foreach ($results as $product) {
        $output[] = [
            'id' => $product['id'],
            'name' => $product['name'],
            'price' => number_format($product['price'], 2),
            'category' => $product['category_name'],
            'category_id' => $product['category_id'],
            'image' => $product['image'],
            'stock' => $product['stock']
        ];
    }
    
    echo json_encode($output);
} catch (PDOException $e) {
    echo json_encode([]);
}
?>
