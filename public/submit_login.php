<?php
session_start();
require_once '../config/database.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize user input
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);

    // Input validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'Invalid email format. Please try again.';
        header('Location: ../pages/login.php');
        exit();
    }

    if (empty($password)) {
        $_SESSION['error'] = 'Password cannot be empty.';
        header('Location: ../pages/login.php');
        exit();
    }

    // Query the database for user credentials
    $query = "SELECT id, username, password FROM users WHERE email = ?";
    $stmt = $db->prepare($query);

    if ($stmt) {
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Verify the password hash
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];

                // Redirect to the dashboard or homepage
                header('Location: ../public/index.php');
                exit();
            } else {
                $_SESSION['error'] = 'Incorrect password. Please try again.';
                header('Location: ../pages/login.php');
                exit();
            }
        } else {
            $_SESSION['error'] = 'No account found with the provided email.';
            header('Location: ../pages/login.php');
            exit();
        }
    } else {
        // Log the error or handle it gracefully
        $_SESSION['error'] = 'An error occurred. Please try again later.';
        header('Location: ../pages/login.php');
        exit();
    }
} else {
    // Redirect to the login page if the script is accessed without POST data
    header('Location: ../pages/login.php');
    exit();
}
?>
