<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

$response = [
    'success' => false,
    'message' => ''
];

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

if ($user_id <= 0) {
    $response['message'] = 'User not logged in';
    echo json_encode($response);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

$schedule_name = $input['schedule_name'] ?? '';
$start_time = $input['start_time'] ?? '';
$end_time = $input['end_time'] ?? '';
$description = $input['description'] ?? '';
$schedule_date = $input['schedule_date'] ?? date('Y-m-d');

if (empty($schedule_name) || empty($start_time) || empty($end_time)) {
    $response['message'] = 'Schedule name, start time, and end time are required.';
    echo json_encode($response);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO daily_schedules (user_id, schedule_name, start_time, end_time, description, tanggal) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $schedule_name, $start_time, $end_time, $description, $schedule_date]);

    $response['success'] = true;
    $response['message'] = 'Schedule saved successfully!';

} catch (PDOException $e) {
    $response['message'] = 'Database error: ' . $e->getMessage();
}

echo json_encode($response);
