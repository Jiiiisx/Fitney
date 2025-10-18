<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] <= 0) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id']) || !is_numeric($data['id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid or missing consumed food ID']);
    exit;
}

$consumed_food_id = $data['id'];

try {
    $stmt = $pdo->prepare("DELETE FROM consumed_foods WHERE id = ? AND user_id = ?");
    $stmt->execute([$consumed_food_id, $_SESSION['user_id']]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Food item deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Food item not found or you do not have permission to delete it']);
    }
} catch (PDOException $e) {
    error_log("Error deleting consumed food: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>