<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
$today = date('Y-m-d');

$response = [
    'success' => false,
    'consumed' => 0,
    'burned' => 0
];

if ($user_id <= 0) {
    $response['message'] = 'User not logged in';
    echo json_encode($response);
    exit;
}

try {
    // Get total consumed calories for today
    $stmt_consumed = $pdo->prepare("SELECT SUM((m.kalori / m.porsi) * cf.quantity) as total_consumed FROM consumed_foods cf JOIN makanan m ON cf.food_id = m.id WHERE cf.user_id = ? AND cf.consumption_date = ?");
    $stmt_consumed->execute([$user_id, $today]);
    $consumed_result = $stmt_consumed->fetch(PDO::FETCH_ASSOC);
    $total_consumed_calories = $consumed_result['total_consumed'] ?? 0;

    // Get total burned calories for today
    $stmt_burned = $pdo->prepare("SELECT SUM(calories_burned) as total_burned FROM daily_activities WHERE user_id = ? AND tanggal = ?");
    $stmt_burned->execute([$user_id, $today]);
    $burned_result = $stmt_burned->fetch(PDO::FETCH_ASSOC);
    $total_burned_calories = $burned_result['total_burned'] ?? 0;

    $response['success'] = true;
    $response['consumed'] = round((float)$total_consumed_calories);
    $response['burned'] = round((float)$total_burned_calories);

} catch (PDOException $e) {
    $response['message'] = 'Database error: ' . $e->getMessage();
}

echo json_encode($response);
