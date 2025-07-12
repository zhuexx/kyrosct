<?php
require_once('../includes/config.php');

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$key = $data['key'] ?? '';
$hwid = $data['hwid'] ?? '';

try {
    $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    
    // Check key validity
    $stmt = $pdo->prepare("SELECT id FROM keys WHERE key_value = ? AND (hwid_lock IS NULL OR hwid_lock = ?) AND (expires_at > NOW() OR expires_at IS NULL)");
    $stmt->execute([$key, $hwid]);
    
    if ($stmt->rowCount() > 0) {
        // Mark as used
        $pdo->prepare("UPDATE keys SET is_used = 1, used_at = NOW(), used_by = ? WHERE key_value = ?")
            ->execute([$hwid, $key]);
        
        echo json_encode(['valid' => true]);
    } else {
        echo json_encode(['valid' => false]);
    }
    
} catch(PDOException $e) {
    echo json_encode(['valid' => false]);
}
?>
