<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
if ($user_id <= 0) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$is_custom_food = isset($input['is_custom_food']) && $input['is_custom_food'];
$quantity = isset($input['quantity']) ? floatval($input['quantity']) : 0;
$consumption_date = isset($input['consumption_date']) ? $input['consumption_date'] : date('Y-m-d');

if ($quantity <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Quantity must be positive.']);
    exit;
}

try {
    $pdo->beginTransaction();

    if ($is_custom_food) {
        // Custom food: insert into makanan table first
        $food_name = isset($input['food_name']) ? trim($input['food_name']) : '';
        $calories = isset($input['calories']) ? floatval($input['calories']) : 0;
        $portion = isset($input['portion']) ? trim($input['portion']) : '';
        $category = isset($input['category']) ? trim($input['category']) : '';

        if (empty($food_name) || $calories <= 0 || empty($portion) || empty($category)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid custom food data. All fields are required.']);
            exit;
        }

        $stmt = $pdo->prepare("INSERT INTO makanan (nama_makanan, kalori, porsi, kategori, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$food_name, $calories, $portion, $category]);
        $food_id = $pdo->lastInsertId();
    } else {
        // Existing food
        $food_id = isset($input['food_id']) ? intval($input['food_id']) : 0;
        if ($food_id <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid food data. Food ID must be positive.']);
            exit;
        }
    }

    // Now, save to consumed_foods
    $stmt = $pdo->prepare("SELECT id FROM consumed_foods WHERE user_id = ? AND food_id = ? AND consumption_date = ?");
    $stmt->execute([$user_id, $food_id, $consumption_date]);

    if ($stmt->rowCount() > 0) {
        // Update existing entry
        $stmt = $pdo->prepare("UPDATE consumed_foods SET quantity = quantity + ?, created_at = NOW() WHERE user_id = ? AND food_id = ? AND consumption_date = ?");
        $stmt->execute([$quantity, $user_id, $food_id, $consumption_date]);
    } else {
        // Insert new entry
        $stmt = $pdo->prepare("INSERT INTO consumed_foods (user_id, food_id, quantity, consumption_date) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_id, $food_id, $quantity, $consumption_date]);
    }

    $pdo->commit();
    echo json_encode(['success' => true, 'message' => 'Food consumed successfully']);

} catch (PDOException $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
