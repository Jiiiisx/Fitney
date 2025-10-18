<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

$response = [
    'success' => false,
    'daily_activities' => []
];

if ($user_id <= 0) {
    $response['message'] = 'User not logged in';
    echo json_encode($response);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT id, activity_name, duration_minutes, calories_burned, tanggal, created_at FROM daily_activities WHERE user_id = ? AND tanggal = CURDATE() ORDER BY created_at DESC");
    $stmt->execute([$user_id]);
    $daily_activities = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $response['success'] = true;
    $response['daily_activities'] = $daily_activities;

} catch (PDOException $e) {
    $response['message'] = 'Database error: ' . $e->getMessage();
}

echo json_encode($response);
