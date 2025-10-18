<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

if ($user_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$activity_name = isset($data['activity_name']) ? trim($data['activity_name']) : '';
$duration_minutes = isset($data['duration_minutes']) ? intval($data['duration_minutes']) : 0;
$calories_burned = isset($data['calories_burned']) ? floatval($data['calories_burned']) : 0;
$tanggal = isset($data['tanggal']) ? $data['tanggal'] : '';

if ($activity_name === '' || $duration_minutes <= 0 || $calories_burned <= 0 || $tanggal === '') {
    echo json_encode(['success' => false, 'message' => 'Invalid input data']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO daily_activities (user_id, activity_name, duration_minutes, calories_burned, tanggal, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->execute([$user_id, $activity_name, $duration_minutes, $calories_burned, $tanggal]);

    echo json_encode(['success' => true, 'message' => 'Activity added successfully']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
