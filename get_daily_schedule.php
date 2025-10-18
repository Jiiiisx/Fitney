<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

$response = [
    'success' => false,
    'schedules' => []
];

if ($user_id <= 0) {
    $response['message'] = 'User not logged in';
    echo json_encode($response);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT schedule_name, start_time, end_time, description FROM daily_schedules WHERE user_id = ? AND tanggal = ? ORDER BY start_time ASC");
    $stmt->execute([$user_id, $date]);
    $schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $response['success'] = true;
    $response['schedules'] = $schedules;

} catch (PDOException $e) {
    $response['message'] = 'Database error: ' . $e->getMessage();
}

echo json_encode($response);
