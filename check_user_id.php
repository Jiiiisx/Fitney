    <?php
session_start();
require_once 'config.php';

header('Content-Type: text/html');

echo "<h2>Informasi User ID</h2>";
echo "<p>User ID dari session: " . (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'Tidak ada') . "</p>";

// Jika ingin detail lebih lengkap
if (isset($_SESSION['user_id'])) {
    try {
        $stmt = $pdo->prepare("SELECT id, username, email FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo "<h3>Detail User:</h3>";
        echo "<p>ID: " . $user['id'] . "</p>";
        echo "<p>Username: " . $user['username'] . "</p>";
        echo "<p>Email: " . $user['email'] . "</p>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "<p>Anda belum login. Silakan login terlebih dahulu.</p>";
}
?>
