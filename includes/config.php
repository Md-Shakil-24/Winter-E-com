<?php
// Database configuration - Support both local and cloud environments
# Normalize DB host so PHP uses TCP (127.0.0.1) instead of a Unix socket when appropriate
$envHost = getenv('MYSQLHOST');
if ($envHost === false || $envHost === '') {
    $envHost = '127.0.0.1';
} elseif (strtolower($envHost) === 'localhost') {
    // Force TCP to avoid PDO attempting a Unix socket (which produces 'No such file or directory')
    $envHost = '127.0.0.1';
}
define('DB_HOST', $envHost);
define('DB_USER', getenv('MYSQLUSER') ?: 'root');
define('DB_PASS', getenv('MYSQLPASSWORD') ?: '');
define('DB_NAME', getenv('MYSQLDATABASE') ?: 'winter-e-com');
define('DB_PORT', getenv('MYSQLPORT') ?: 3306);
// Optional path to CA certificate for hosts that require TLS (e.g., PlanetScale)
define('DB_SSL_CA', getenv('MYSQL_SSL_CA') ?: '');

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database connection using PDO
try {
    // Build DSN and PDO options. If DB_SSL_CA is provided and the file exists, enable SSL CA option.
    $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', DB_HOST, DB_PORT, DB_NAME);
    $pdoOptions = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ];
    if (!empty(DB_SSL_CA) && file_exists(DB_SSL_CA)) {
        $pdoOptions[PDO::MYSQL_ATTR_SSL_CA] = DB_SSL_CA;
    }
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $pdoOptions);
} catch (PDOException $e) {
    // Fail early with a helpful hint (do not disclose secrets)
    $hint = sprintf(' (host=%s port=%s)', DB_HOST, DB_PORT);
    die("Database connection failed: " . $e->getMessage() . $hint);
}

// Security functions
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

function escape_output($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

// Check if user is logged in
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Check if user is admin
function is_admin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Redirect if not logged in
function require_login() {
    if (!is_logged_in()) {
        header('Location: login.php');
        exit();
    }
}

// Redirect if not admin
function require_admin() {
    if (!is_admin()) {
        header('Location: index.php');
        exit();
    }
}

// Get current user ID
function get_user_id() {
    return $_SESSION['user_id'] ?? null;
}

// Get current user role
function get_user_role() {
    return $_SESSION['role'] ?? null;
}

// Set success message
function set_message($message, $type = 'success') {
    $_SESSION['message'] = $message;
    $_SESSION['message_type'] = $type;
}

// Get and clear message
function get_message() {
    if (isset($_SESSION['message'])) {
        $message = [
            'text' => $_SESSION['message'],
            'type' => $_SESSION['message_type']
        ];
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
        return $message;
    }
    return null;
}

// Get cart count
function get_cart_count($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT SUM(quantity) as count FROM cart WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $result = $stmt->fetch();
    return $result['count'] ?? 0;
}
?>
