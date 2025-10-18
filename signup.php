<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Create Account</title>
    <link rel="stylesheet" href="css/signup.css" />
    <link rel="stylesheet" href="css/view-transition.css" />
    <meta name="view-transition" content="same-origin">
</head>
<body>
    <div class="container">
        <div class="right-panel">
            <div class="Title">
                <h1>Create Your Account</h1>
                <a href="index.php" class="btn black-btn">→</a>
            </div>

            <?php if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])): ?>
                <div class="alert alert-danger" role="alert">
                    <?php foreach ($_SESSION['errors'] as $error): ?>
                        <p><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
                <?php unset($_SESSION['errors']); ?>
            <?php endif; ?>
            <?php unset($_SESSION['form_data']); ?>
            
            <form method="POST" action="signup_process.php">
                <div class="name-fields">
                    <div class="input-group">
                        <i class="fas fa-user"></i>
                        <input type="text" name="inputfullname" placeholder="Full Name" 
                               value="<?php echo isset($_SESSION['form_data']['inputfullname']) ? htmlspecialchars($_SESSION['form_data']['inputfullname']) : ''; ?>" required />
                    </div>
                </div>
                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="inputemail" placeholder="Email" 
                           value="<?php echo isset($_SESSION['form_data']['inputemail']) ? htmlspecialchars($_SESSION['form_data']['inputemail']) : ''; ?>" required />
                </div>
                <div class="input-group">
                    <i class="fas fa-user-circle"></i>
                    <input type="text" name="inputusername" placeholder="Username" 
                           value="<?php echo isset($_SESSION['form_data']['inputusername']) ? htmlspecialchars($_SESSION['form_data']['inputusername']) : ''; ?>" required />
                </div>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="inputpassword" placeholder="Password" required />
                </div>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="inputpasswordConfirm" placeholder="Confirm Password" required />
                </div>               
                <button type="submit" class="create-btn">Buat Akun</button>
            </form>
            <p class="login-text">
                <strong>Sudah Memiliki Akun?</strong> <a href="login.php" class="view-transition">Log in</a>
            </p>
        </div>
    </div>
    <script src="js/view-transition.js"></script>
</body>
</html>
