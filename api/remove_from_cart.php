<?php
require_once '../includes/config.php';

header('Content-Type: application/json');

if (!is_logged_in()) {
    echo json_encode(['success' => false, 'message' => 'Please login']);
    exit();
}

$user_id = get_user_id();
$product_id = (int)($_POST['product_id'] ?? 0);

if ($product_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid product']);
    exit();
}

try {
    $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $product_id]);
    
    $cart_count = get_cart_count($user_id);
    echo json_encode(['success' => true, 'message' => 'Removed from cart', 'cart_count' => $cart_count]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Failed to remove from cart']);
}
?>
