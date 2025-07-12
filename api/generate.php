<?php
require_once('../includes/config.php');

header('Content-Type: application/json');

// Verify token
if (!isset($_SERVER['HTTP_AUTHORIZATION']) || $_SERVER['HTTP_AUTHORIZATION'] !== ADMIN_TOKEN) {
    die(json_encode(['error' => 'Unauthorized']));
}

// Connect to DB
try {
    $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Generate key (format: TINY-XXXX-XXXX)
    $key = 'TINY-' . strtoupper(substr(md5(uniqid()), 0, 4)) . '-' . strtoupper(substr(md5(uniqid()), 4, 4));
    $hwid = $_POST['hwid'] ?? null;
    $expiry = $_POST['expiry'] ?? 30;

    $stmt = $pdo->prepare("INSERT INTO keys (key_value, hwid_lock, expires_at) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL ? DAY))");
    $stmt->execute([$key, $hwid, $expiry]);
    
    echo json_encode([
        'success' => true,
        'key' => $key,
        'expires' => date('Y-m-d', strtotime("+$expiry days"))
    ]);
    
} catch(PDOException $e) {
    echo json_encode(['error' => 'Database error']);
}
?>
