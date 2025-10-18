<?php
require_once 'config.php';

// Initialize variables
$fullname = $email = $username = $password = '';
$role = 'pelanggan';
$errors = [];

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate inputs
    $fullname = trim($_POST['inputfullname']);
    $email = trim($_POST['inputemail']);
    $username = trim($_POST['inputusername']);
    $password = $_POST['inputpassword'];
    $passwordConfirm = $_POST['inputpasswordConfirm'];

    // Validation
    if (empty($fullname)) {
        $errors[] = "Full name is required.";
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    
    if (strlen($username) < 3) {
        $errors[] = "Username must be at least 3 characters.";
    }
    
    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }
    
    if ($password !== $passwordConfirm) {
        $errors[] = "Passwords do not match.";
    }


    // Check if username or email already exists
    if (empty($errors)) {
        try {
            // Check existing user
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);
            $count = $stmt->fetchColumn();
            
            if ($count > 0) {
                $errors[] = "Username or email already exists.";
            } else {
                // Hash password
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                
                // Insert new user
                $stmt = $pdo->prepare("INSERT INTO users (fullname, email, username, password, role, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
                $stmt->execute([$fullname, $email, $username, $hashedPassword, $role]);
                
                // Fetch the inserted user ID
                $user_id = $pdo->lastInsertId();

                // Start session and set session variables for auto-login
                session_start();
                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;

                // Redirect to dashboard or Food.php after signup and auto-login
                header("Location: dashboard-pelanggan.php");
                exit();
            }
        } catch(PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
}

// If there are errors, redirect back to signup page
if (!empty($errors)) {
    session_start();
    $_SESSION['errors'] = $errors;
    $_SESSION['form_data'] = $_POST;
    header("Location: signup.php");
    exit();
}
?>
