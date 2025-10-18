<?php
// Check if this file is being included directly (not called as API)
if (!isset($direct_call)) {
    session_start();
    require_once 'config.php';
    
    header('Content-Type: application/json');
    
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
} else {
    // Called directly from dashboard, use provided user_id
    $user_id = $direct_call['user_id'];
}

$response = [
    'success' => false,
    'day_streak' => 0,
    'message' => ''
];

if ($user_id <= 0) {
    $response['message'] = 'User not logged in';
    echo json_encode($response);
    exit;
}

try {
    // Get all dates where user has either calories or activities
    $sql = "
        SELECT DISTINCT tanggal 
        FROM (
            SELECT tanggal FROM daily_calories WHERE user_id = ? AND calorie > 0
            UNION 
            SELECT tanggal FROM daily_activities WHERE user_id = ? AND calories_burned > 0
        ) AS user_activity_dates 
        ORDER BY tanggal DESC
    ";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id, $user_id]);
    $dates = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    $day_streak = 0;
    
    if (!empty($dates)) {
        $today = new DateTime();
        $current_date = clone $today;
        
        // Check if today has activity
        $today_has_activity = in_array($today->format('Y-m-d'), $dates);
        
        if ($today_has_activity) {
            $day_streak = 1;
            $current_date->modify('-1 day');
            
            // Check consecutive days backwards
            while (true) {
                $previous_day = $current_date->format('Y-m-d');
                
                if (in_array($previous_day, $dates)) {
                    $day_streak++;
                    $current_date->modify('-1 day');
                } else {
                    break;
                }
                
                // Safety break to prevent infinite loop
                if ($day_streak > 365) {
                    break;
                }
            }
        } else {
            // If today doesn't have activity, check yesterday
            $yesterday = clone $today;
            $yesterday->modify('-1 day');
            $yesterday_str = $yesterday->format('Y-m-d');
            
            if (in_array($yesterday_str, $dates)) {
                $day_streak = 1;
                $current_date = clone $yesterday;
                $current_date->modify('-1 day');
                
                // Check consecutive days backwards from yesterday
                while (true) {
                    $previous_day = $current_date->format('Y-m-d');
                    
                    if (in_array($previous_day, $dates)) {
                        $day_streak++;
                        $current_date->modify('-1 day');
                    } else {
                        break;
                    }
                    
                    // Safety break to prevent infinite loop
                    if ($day_streak > 365) {
                        break;
                    }
                }
            }
        }
    }
    
    $response['success'] = true;
    $response['day_streak'] = $day_streak;
    $response['total_days'] = count($dates);

} catch (PDOException $e) {
    $response['message'] = 'Database error: ' . $e->getMessage();
}

echo json_encode($response);
?>
