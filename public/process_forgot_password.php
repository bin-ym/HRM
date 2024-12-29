<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die('Invalid email address.');
    }

    // Example logic to check if email exists in the database
    // (Replace this with actual database queries)
    $db_email = 'test@example.com'; // Example email from database

    if ($email === $db_email) {
        // Generate a reset token
        $reset_token = bin2hex(random_bytes(16));
        
        // Save the token in the database with an expiration time (e.g., 1 hour)
        // Example: save_token_in_database($email, $reset_token, $expiration_time);

        // Send reset link to user's email
        $reset_link = "http://yourdomain.com/reset_password.php?token=$reset_token";
        mail($email, 'Password Reset Instructions', "Click the following link to reset your password: $reset_link");

        echo 'Password reset instructions have been sent to your email.';
    } else {
        echo 'Email address not found.';
    }
} else {
    header('Location: forgot_password.php');
    exit();
}
