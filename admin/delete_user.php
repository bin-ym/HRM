<?php
include('./includes/db_connect.php');

// Get user ID from URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Delete user from database
if ($id) {
    $sql = "DELETE FROM users WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id' => $id]);
}

// Redirect to manage_users.php
header('Location: manage_users.php');
exit;
?>
