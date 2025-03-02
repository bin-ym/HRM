<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = 'Invalid request method.';
    header('Location: ../public/signup.php');
    exit();
}

// Input validation
$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$password = trim($_POST['password']);
$confirm_password = trim($_POST['confirm_password']);
$role = 'Applicant';

// Validation checks
if (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username)) {
    $_SESSION['error'] = 'Username must be 3-20 characters and can only contain letters, numbers, and underscores.';
    header('Location: ../public/signup.php');
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'Invalid email format.';
    header('Location: ../public/signup.php');
    exit();
}

if (!preg_match('/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/', $password)) {
    $_SESSION['error'] = 'Password must be at least 8 characters and contain at least one uppercase letter, one lowercase letter, and one number.';
    header('Location: ../public/signup.php');
    exit();
}

if ($password !== $confirm_password) {
    $_SESSION['error'] = 'Passwords do not match.';
    header('Location: ../public/signup.php');
    exit();
}

try {
    // Verify database connection
    if (!$conn) {
        throw new Exception('Database connection failed');
    }

    // Check if username or email already exists
    $check_stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ?");
    if (!$check_stmt) {
        throw new Exception('Failed to prepare check statement: ' . $conn->errorInfo()[2]);
    }
    $check_stmt->execute([$username, $email]);
    if ($check_stmt->fetchColumn() > 0) {
        $_SESSION['error'] = 'Username or email already exists.';
        header('Location: ../public/signup.php');
        exit();
    }

    // Insert applicant into database
    $password_hash = password_hash($password, PASSWORD_ARGON2ID);
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role, status, created_at) 
                           VALUES (?, ?, ?, 'Applicant', 'Pending', NOW())");
    if (!$stmt) {
        throw new Exception('Failed to prepare insert statement: ' . $conn->errorInfo()[2]);
    }
    
    $result = $stmt->execute([$username, $email, $password_hash]);
    if (!$result) {
        throw new Exception('Failed to execute insert: ' . $stmt->errorInfo()[2]);
    }

    $_SESSION['success'] = 'Applicant account created successfully! Please wait for approval.';
    header('Location: ../public/login.php');
    exit();
} catch (Exception $e) {
    // Log detailed error
    error_log("Signup Error: " . $e->getMessage() . " | Line: " . $e->getLine());
    
    // Check specific error types
    if ($e instanceof PDOException) {
        if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
            $_SESSION['error'] = 'Username or email already exists.';
        } elseif (strpos($e->getMessage(), 'Connection') !== false) {
            $_SESSION['error'] = 'Database connection error. Please try again later.';
        } else {
            $_SESSION['error'] = 'Registration failed: ' . $e->getMessage();
        }
    } else {
        $_SESSION['error'] = 'Registration failed: ' . $e->getMessage();
    }
    header('Location: ../public/signup.php');
    exit();
} finally {
    $conn = null;
}
?>