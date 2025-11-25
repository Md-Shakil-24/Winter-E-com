<?php
// Basic health check endpoint
header('Content-Type: application/json');

$status = [
    'status' => 'ok',
    'time' => date('c'),
];

// Optional: try DB connection if env vars are present
$dbHost = getenv('MYSQLHOST') ?: getenv('DB_HOST') ?: null;
if ($dbHost) {
    try {
        $user = getenv('MYSQLUSER') ?: getenv('DB_USER');
        $pass = getenv('MYSQLPASSWORD') ?: getenv('DB_PASS');
        $name = getenv('MYSQLDATABASE') ?: getenv('DB_NAME');
        $port = getenv('MYSQLPORT') ?: getenv('DB_PORT') ?: 3306;
        $dsn = "mysql:host={$dbHost};port={$port};dbname={$name};charset=utf8mb4";
        $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_TIMEOUT => 3]);
        $status['db'] = 'connected';
    } catch (Exception $e) {
        $status['db'] = 'error';
        $status['db_error'] = $e->getMessage();
    }
} else {
    $status['db'] = 'not-configured';
}

echo json_encode($status);
