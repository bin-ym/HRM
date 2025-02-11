<?php 
include('./includes/db_connect.php'); 
session_start(); // Start session if you need to handle authentication

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];

    try {
        // Fetch the current password hash from the database
        $sql = "SELECT password FROM admins WHERE id = 1"; // Assuming there's only one admin
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$admin) {
            header("Location: admin_profile.php?message=Admin not found");
            exit;
        }

        // Verify current password
        if (!password_verify($current_password, $admin['password'])) {
            header("Location: admin_profile.php?message=Incorrect current password");
            exit;
        }

        // Prepare update query
        $update_query = "UPDATE admins SET username = :username, email = :email";
        
        // If new password is provided, update it
        if (!empty($new_password)) {
            $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_query .= ", password = :password";
        }

        $update_query .= " WHERE id = 1";
        $stmt = $pdo->prepare($update_query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        
        if (!empty($new_password)) {
            $stmt->bindParam(':password', $new_hashed_password);
        }

        // Execute the update
        $stmt->execute();
        
        // Redirect with success message
        header("Location: admin_profile.php?message=Profile updated successfully");
        exit;

    } catch (PDOException $e) {
        header("Location: admin_profile.php?message=Error: " . urlencode($e->getMessage()));
        exit;
    }
} else {
    header("Location: admin_profile.php");
    exit;
}
?>
