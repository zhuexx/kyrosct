<?php
require_once __DIR__ . '/../includes/config.php';

header('Content-Type: application/json');

// Auth Check
if (!isset($_SERVER['HTTP_AUTHORIZATION']) || 
    $_SERVER['HTTP_AUTHORIZATION'] !== getenv('ADMIN_TOKEN')) {
    die(json_encode(['error' => 'Unauthorized']));
}

$input = json_decode(file_get_contents('php://input'), true);
$hwid = $input['hwid'] ?? null;
$expiry = $input['expiry'] ?? 30;

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, 
        DB_USER, 
        DB_PASS
    );
    
    // Generate key (format: TINY-XXXX-XXXX)
    $key = 'TINY-' . strtoupper(substr(md5(uniqid()), 0, 4)) . 
           '-' . strtoupper(substr(md5(uniqid()), 4, 4));

    $stmt = $pdo->prepare("INSERT INTO `keys` 
        (key_value, hwid_lock, expires_at) 
        VALUES (?, ?, DATE_ADD(NOW(), INTERVAL ? DAY))");
    $stmt->execute([$key, $hwid, $expiry]);
    
    echo json_encode([
        'key' => $key,
        'expires' => date('Y-m-d', strtotime("+$expiry days"))
    ]);
} catch(PDOException $e) {
    echo json_encode(['error' => 'Database error']);
}
?>