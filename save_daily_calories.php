<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

// Cek apakah request adalah POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Ambil data dari POST
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['total_calories']) || !is_numeric($input['total_calories'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid calories data']);
    exit;
}

$total_calories = floatval($input['total_calories']);
$today = date('Y-m-d');
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

// Debug log for user_id
error_log("save_daily_calories.php - user_id from session: " . $user_id);

if ($user_id <= 0) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

try {
    // Cek apakah sudah ada data untuk hari ini dan user ini
    $stmt = $pdo->prepare("SELECT id FROM daily_calories WHERE tanggal = ? AND user_id = ?");
    $stmt->execute([$today, $user_id]);
    
    if ($stmt->rowCount() > 0) {
        if ($total_calories <= 0) {
            // Delete data if total calories is 0 or less
            $stmt = $pdo->prepare("DELETE FROM daily_calories WHERE tanggal = ? AND user_id = ?");
            $stmt->execute([$today, $user_id]);
        } else {
            // Update data yang sudah ada
            $stmt = $pdo->prepare("UPDATE daily_calories SET calorie = ?, updated_at = NOW() WHERE tanggal = ? AND user_id = ?");
            $stmt->execute([$total_calories, $today, $user_id]);
        }
    } else {
        if ($total_calories > 0) {
            // Insert data baru hanya jika total kalori lebih dari 0
            $stmt = $pdo->prepare("INSERT INTO daily_calories (calorie, tanggal, user_id) VALUES (?, ?, ?)");
            $stmt->execute([$total_calories, $today, $user_id]);
        }
    }
    
    echo json_encode(['success' => true, 'message' => 'Kalori harian berhasil disimpan']);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
