<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = 'Invalid request method.';
    header('Location: login.php');
    exit();
}

$email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
$password = trim($_POST['password']);

try {
    $stmt = $conn->prepare("SELECT id, username, password, role, is_temp_password FROM users WHERE email = ? AND status = 'Active'");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Debug: Log user data
    error_log("Login attempt: Email=$email, User=" . print_r($user, true));

    if (!$user) {
        $_SESSION['error'] = 'User not found or inactive. Please contact the administrator.';
        header('Location: login.php');
        exit();
    }

    if (!password_verify($password, $user['password'])) {
        $_SESSION['error'] = 'Invalid email or password.';
        header('Location: login.php');
        exit();
    }

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['last_login'] = date('Y-m-d H:i:s');

    if ($user['is_temp_password']) {
        header("Location: change_password.php");
        exit();
    }

    $update_stmt = $conn->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
    $update_stmt->execute([$user['id']]);

    session_regenerate_id(true);

    $valid_roles = ['Admin', 'Employee', 'Manager', 'HR Officer', 'HR Admin', 'Dean', 'Department Head', 'Finance Officer', 'Applicant'];
    if (in_array($user['role'], $valid_roles)) {
        header("Location: ../" . strtolower(str_replace(' ', '_', $user['role'])) . "/" . strtolower(str_replace(' ', '_', $user['role'])) . "_dashboard.php");
    } else {
        $_SESSION['error'] = 'Access Denied: Unknown role (' . $user['role'] . ').';
        header('Location: login.php');
    }
    exit();
} catch (PDOException $e) {
    error_log("Login Error: " . $e->getMessage());
    $_SESSION['error'] = 'Database Error: Unable to process login - ' . $e->getMessage();
    header('Location: login.php');
    exit();
}
?>