<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
$today = date('Y-m-d');

$response = [
    'success' => false,
    'consumed_foods' => [],
    'total_consumed_calories' => 0
];

if ($user_id <= 0) {
    $response['message'] = 'User not logged in';
    echo json_encode($response);
    exit;
}

try {
$stmt = $pdo->prepare("SELECT cf.id, cf.food_id, cf.quantity, cf.consumption_date, cf.created_at, m.nama_makanan, m.kalori, m.porsi, m.kategori, m.foto_makanan FROM consumed_foods cf JOIN makanan m ON cf.food_id = m.id WHERE cf.user_id = ? AND cf.consumption_date = ? ORDER BY cf.created_at DESC");
    $stmt->execute([$user_id, $today]);
    $consumed_foods = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $total_consumed_calories = 0;
    foreach ($consumed_foods as $food) {
        if ($food['porsi'] > 0) {
            $total_consumed_calories += ($food['kalori'] / $food['porsi']) * $food['quantity'];
        } else {
            error_log("Warning: Food ID " . $food['id'] . " has zero serving size. Calories not added to total.");
        }
    }

    $response['success'] = true;
    $response['consumed_foods'] = $consumed_foods;
    $response['total_consumed_calories'] = $total_consumed_calories;

} catch (PDOException $e) {
    $response['message'] = 'Database error: ' . $e->getMessage();
}

echo json_encode($response);
