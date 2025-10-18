<?php
require_once 'config.php';

header('Content-Type: application/json');

try {
    $sql = "SELECT id, nama_makanan, porsi, kalori, kategori FROM makanan ORDER BY nama_makanan ASC";
    $stmt = $pdo->query($sql);
    $foods = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'foods' => $foods]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>