<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);

    try {
        // Prepare SQL to fetch user by email and active status
        $stmt = $conn->prepare("SELECT id, username, role, password FROM users WHERE email = ? AND status = 'Active'");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Debugging: Print user data
        echo "User found: " . print_r($user, true) . "<br>";
        echo "Input password: " . $password . "<br>";
        echo "Hashed password from DB: " . $user['password'] . "<br>";

        // If user is found
        if ($user) {
            // Validate password if user exists
            if (password_verify($password, $user['password'])) {
                // Set session variables upon successful login
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                // Regenerate session ID to prevent session fixation
                session_regenerate_id(true);
                // Redirect based on role
                if ($user['role'] === 'Admin') {
                    header('Location: ../admin/admin_dashboard.php');
                } elseif ($user['role'] === 'Employee') {
                    header('Location: ../employee/employee_dashboard.php');
                } elseif ($user['role'] === 'Manager') {
                    header('Location: ../manager/manager_dashboard.php');
                } else {
                    $_SESSION['error'] = 'Access Denied: Role mismatch';
                    header('Location: ../public/login.php');
                }
                exit();
            } else {
                // Password does not match
                $_SESSION['error'] = 'Invalid Credentials - Password does not match';
                header('Location: ../public/login.php');
                exit();
            }
        } else {
            // User not found or not active
            $_SESSION['error'] = 'User not found or not active';
            header('Location: ../public/login.php');
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Database Error: ' . $e->getMessage();
        header('Location: ../public/login.php');
        exit();
    }
}
?>