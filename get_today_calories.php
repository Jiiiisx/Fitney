<?php
// Check if this file is being included directly (not called as API)
if (!isset($direct_call)) {
    session_start();
    require_once 'config.php';
    
    header('Content-Type: application/json');
    
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
    $today = date('Y-m-d');
    
    $response = [
        'success' => false,
        'total_calories' => 0,
        'consumed_calories' => 0,
        'burned_calories' => 0,
        'net_calories' => 0
    ];
    
    if ($user_id <= 0) {
        $response['message'] = 'User not logged in';
        echo json_encode($response);
        exit;
    }
} else {
    // Called directly from dashboard, use provided user_id
    $user_id = $direct_call['user_id'];
    $today = date('Y-m-d');
    
    $response = [
        'success' => false,
        'total_calories' => 0,
        'consumed_calories' => 0,
        'burned_calories' => 0,
        'net_calories' => 0
    ];
}

try {
    // Get total consumed calories for today
    $stmt_consumed = $pdo->prepare("SELECT SUM((m.kalori / m.porsi) * cf.quantity) as total_consumed 
                                   FROM consumed_foods cf 
                                   JOIN makanan m ON cf.food_id = m.id 
                                   WHERE cf.user_id = ? AND cf.consumption_date = ?");
    $stmt_consumed->execute([$user_id, $today]);
    $consumed_result = $stmt_consumed->fetch(PDO::FETCH_ASSOC);
    $consumed_calories = $consumed_result['total_consumed'] ?? 0;

    // Get total burned calories for today
    $stmt_burned = $pdo->prepare("SELECT SUM(calories_burned) as total_burned 
                                 FROM daily_activities 
                                 WHERE user_id = ? AND tanggal = ?");
    $stmt_burned->execute([$user_id, $today]);
    $burned_result = $stmt_burned->fetch(PDO::FETCH_ASSOC);
    $burned_calories = $burned_result['total_burned'] ?? 0;

    // Calculate net calories
    $net_calories = $consumed_calories - $burned_calories;
    
    // Also check if there's any data in daily_calories table (for backward compatibility)
    $stmt_daily = $pdo->prepare("SELECT calorie FROM daily_calories WHERE user_id = ? AND tanggal = ?");
    $stmt_daily->execute([$user_id, $today]);
    $daily_calories = $stmt_daily->fetchColumn();
    
    // Use the calculated calories if available, otherwise use daily_calories table
    $total_calories = ($consumed_calories > 0 || $burned_calories > 0) ? $net_calories : ($daily_calories ?: 0);

    $response['success'] = true;
    $response['total_calories'] = round((float)$total_calories);
    $response['consumed_calories'] = round((float)$consumed_calories);
    $response['burned_calories'] = round((float)$burned_calories);
    $response['net_calories'] = round((float)$net_calories);

} catch (PDOException $e) {
    $response['message'] = 'Database error: ' . $e->getMessage();
}

echo json_encode($response);
?>
