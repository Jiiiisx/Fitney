<?php
session_start();

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $error_message = "Please fill in both email and password.";
    } else {
        $stmt = $pdo->prepare("SELECT id, email, password, role, username FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Set the session variables
            $_SESSION['email'] = $email;
            $_SESSION['role'] = $user['role'];
            $_SESSION['username'] = $user['username'] ?? explode('@', $email)[0];
            $_SESSION['user_id'] = $user['id'];  // Add user_id to session

            // Debugging: Log session variables
            error_log("User logged in: " . print_r($_SESSION, true));
            if ($user['role'] === 'admin') {
                header('Location: Dashboard-admin.php');
            } else {
                header('Location: Dashboard-pelanggan.php');
            }
            exit();
        } else {
            $error_message = "Invalid email or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Sign In</title>
    <link rel="stylesheet" href="css/Login.css" />
    <link rel="stylesheet" href="css/view-transition.css" />
    <meta name="view-transition" content="same-origin">
</head>
<body>
    <div class="container">
        <div class="right-panel">
            <div class="Title">
                <h1>Login</h1>
                <a href="index.php" class="btn black-btn view-transition">→</a>
            </div>
            <?php if (isset($_GET['signup']) && $_GET['signup'] === 'success'): ?>
                <div class="alert alert-success" role="alert" style="color: green; margin-bottom: 15px; padding: 10px; background-color: #e6ffed; border-radius: 5px;">
                    Signup berhasil! Silakan login dengan akun Anda.
                </div>
            <?php endif; ?>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <?php if (isset($error_message)): ?>
                <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
                </div>
                <?php endif; ?>
                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" placeholder="Email" required />
                </div>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="Password" required />
                </div>
                <button type="submit" class="create-btn">Masuk</button>
            </form>
            <p class="login-text">
                <strong>Belum memiliki akun?</strong> <a href="signup.php" class="view-transition">Sign up</a>
            </p>
        </div>
    </div>
    <script src="js/view-transition.js"></script>
</body>
</html>
