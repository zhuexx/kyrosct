<?php
require_once __DIR__ . '/../includes/config.php';

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$key = $input['key'] ?? '';
$hwid = $input['hwid'] ?? '';

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, 
        DB_USER, 
        DB_PASS
    );
    
    $stmt = $pdo->prepare("SELECT id FROM `keys` 
        WHERE key_value = ? 
        AND (hwid_lock IS NULL OR hwid_lock = ?) 
        AND (expires_at > NOW() OR expires_at IS NULL)");
    $stmt->execute([$key, $hwid]);
    
    if ($stmt->rowCount() > 0) {
        $pdo->prepare("UPDATE `keys` SET 
            is_used = 1, 
            used_at = NOW(), 
            used_by = ? 
            WHERE key_value = ?")
           ->execute([$hwid, $key]);
        
        echo json_encode(['valid' => true]);
    } else {
        echo json_encode(['valid' => false]);
    }
} catch(PDOException $e) {
    echo json_encode(['valid' => false]);
}
?>
