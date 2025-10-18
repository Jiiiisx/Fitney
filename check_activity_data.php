<?php
require_once 'config.php';

// Check if there are any activities for user ID 2
try {
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM daily_activities WHERE user_id = 2");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "Number of activities for user ID 2: " . $result['count'] . "\n";
    
    if ($result['count'] > 0) {
        $stmt = $pdo->prepare("SELECT activity_name, duration_minutes, calories_burned, tanggal FROM daily_activities WHERE user_id = 2 ORDER BY tanggal DESC LIMIT 5");
        $stmt->execute();
        $activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "Recent activities:\n";
        print_r($activities);
    } else {
        echo "No activities found for user ID 2. The activity goal percentage will be 0%.\n";
    }
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}
?>
