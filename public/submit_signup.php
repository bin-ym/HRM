<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize user input
    $username = htmlspecialchars(trim($_POST['username']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Validate input
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die('Invalid email format.');
    }

    if ($password !== $confirmPassword) {
        die('Passwords do not match.');
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Save to database (example, replace with your database logic)
    // $db = new mysqli('localhost', 'username', 'password', 'database');
    // $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    // $stmt->bind_param('sss', $username, $email, $hashedPassword);
    // $stmt->execute();

    echo 'Sign-up successful. You can now <a href="login.php">login</a>.';
}
?>
