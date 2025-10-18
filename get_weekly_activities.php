<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

// Initialize response array
$response = [
    'labels' => [],
    'minutes' => []
];

try {
    // Get user ID from session
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
    
    if ($user_id > 0) {
        // Get last 7 days data
        $sql = "SELECT 
                    DATE_FORMAT(tanggal, '%a') as day_name,
                    tanggal,
                    SUM(duration_minutes) as total_minutes
                FROM daily_activities 
                WHERE user_id = ? 
                AND tanggal >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
                GROUP BY tanggal
                ORDER BY tanggal ASC";
        
        $stmt = $pdo->prepare($sql);
        if ($stmt) {
            $stmt->execute([$user_id]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']; // Start with Sunday for consistent week
            $activityData = array_fill_keys($days, 0);
            
            foreach ($result as $row) {
                $dayName = date('D', strtotime($row['tanggal']));
                $activityData[$dayName] = round(floatval($row['total_minutes']));
            }
            
            // Reorder data to start from current day and go back 7 days
            $orderedLabels = [];
            $orderedMinutes = [];
            $currentDayIndex = array_search(date('D'), $days);
            
            for ($i = 0; $i < 7; $i++) {
                $dayIndex = ($currentDayIndex - (6 - $i) + 7) % 7; // Calculate index for last 7 days
                $orderedLabels[] = $days[$dayIndex];
                $orderedMinutes[] = $activityData[$days[$dayIndex]];
            }

            $response['labels'] = $orderedLabels;
            $response['minutes'] = $orderedMinutes;
        }
    } else {
        // Return dummy data if no user logged in
        $response = [
            'labels' => ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            'minutes' => [0, 0, 0, 0, 0, 0, 0] // Return zeros if no user
        ];
    }
    
} catch (Exception $e) {
    error_log("Error in get_weekly_activities.php: " . $e->getMessage());
    // Return dummy data on error
    $response = [
        'labels' => ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
        'minutes' => [0, 0, 0, 0, 0, 0, 0] // Return zeros on error
    ];
}

// Return JSON response
echo json_encode($response);
?>
