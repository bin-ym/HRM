<?php
// Include configuration and database connection
include('./includes/config.php');
include('./includes/db_connect.php');

// Fetch job ID from the URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Delete job from the database
if ($id > 0) {
    $sql = "DELETE FROM jobs WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
}

// Redirect to manage_jobs.php
header('Location: manage_jobs.php');
exit;
?>