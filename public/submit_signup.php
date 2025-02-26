<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
    $role = trim($_POST['role']); // Ensure role is retrieved correctly

    // Define allowed roles
    $allowed_roles = ['Admin', 'Employee', 'Manager', 'HR Officer', 'HR Admin', 'Dean', 'Department Head', 'Finance Officer', 'Applicant'];

    // Check if role is valid
    if (!in_array($role, $allowed_roles)) {
        $_SESSION['error'] = 'Invalid role selected.';
        header('Location: ../public/signup.php');
        exit();
    }

    try {
        // Insert user into database
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role, status) VALUES (?, ?, ?, ?, 'Active')");
        $stmt->execute([$username, $email, $password, $role]);

        $_SESSION['success'] = 'Account created successfully!';
        header('Location: ../public/login.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Database Error: ' . $e->getMessage();
        header('Location: ../public/signup.php');
        exit();
    }
}
?>
