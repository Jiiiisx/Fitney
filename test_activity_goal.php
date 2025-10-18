<?php
session_start();
$_SESSION['user_id'] = 2; // Set user ID to 2

require_once 'config.php';

// Test the get_daily_activities_updated.php functionality
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
$today = date('Y-m-d');

$response = [
    'success' => false,
    'daily_activities' => [],
    'total_burned_calories' => 0
];

if ($user_id <= 0) {
    $response['message'] = 'User not logged in';
    echo json_encode($response, JSON_PRETTY_PRINT);
    exit;
}

try {
    // Get daily activities for today
    $stmt = $pdo->prepare("SELECT id, activity_name, duration_minutes, calories_burned, tanggal, created_at FROM daily_activities WHERE user_id = ? AND tanggal = ? ORDER BY created_at DESC");

    // Get total active minutes for the week
    $stmt_week = $pdo->prepare("SELECT SUM(duration_minutes) as total_minutes FROM daily_activities WHERE user_id = ? AND tanggal >= DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY)");
    $stmt_week->execute([$user_id]);
    $week_data = $stmt_week->fetch(PDO::FETCH_ASSOC);

    $total_active_minutes = $week_data['total_minutes'] ?? 0;

    echo "Total Active Minutes for the Week: " . $total_active_minutes . "\n";

    // Calculate activity goal percentage
    $activity_goal_percentage = ($total_active_minutes / 300) * 100;
    echo "Activity Goal Percentage: " . round($activity_goal_percentage, 2) . "%\n";

    $response['activity_goal_percentage'] = round($activity_goal_percentage, 2);
    $stmt->execute([$user_id, $today]);
    $daily_activities = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $total_burned_calories = 0;
    foreach ($daily_activities as $activity) {
        $total_burned_calories += floatval($activity['calories_burned']);
    }

    $response['success'] = true;
    $response['daily_activities'] = $daily_activities;
    $response['total_burned_calories'] = $total_burned_calories;

} catch (PDOException $e) {
    $response['message'] = 'Database error: ' . $e->getMessage();
}

echo "API Response:\n";
